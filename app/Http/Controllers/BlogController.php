<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display public blog listing (home page)
     */
    public function index()
    {
        $blogs = Blog::published()->with('author')->paginate(6);
        
        return view('blog.index', compact('blogs'));
    }

    /**
     * Display single blog post
     */
    public function show($id)
    {
        $blog = Blog::where('id', $id)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['author', 'likes', 'dislikes', 'comments.user', 'comments.replies.user'])
            ->firstOrFail();
        
        // Increment view count
        $blog->incrementViews();
        
        // Get user's reaction if authenticated
        $userReaction = null;
        if (auth()->check()) {
            $userReaction = $blog->getUserReaction(auth()->user()->id);
        }
        
        return view('blog.show', compact('blog', 'userReaction'));
    }

    /**
     * Admin: Display all blog posts
     */
    public function adminIndex()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        
        $blogs = Blog::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show create form (public - anyone can post)
     */
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to create a blog post.');
        }
        
        return view('blog.create');
    }

    /**
     * Store new blog post (public - anyone can post)
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to create a blog post.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|string|max:255',
        ]);

        $blog = Blog::create([
            'id' => Str::random(12),
            'title' => $request->title,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'author_id' => auth()->user()->id,
            'status' => $request->status,
            'published_at' => $request->status === 'published' 
                ? ($request->published_at ?? now()) 
                : null,
            'featured_image' => $request->featured_image,
        ]);

        return redirect()->route('blog.show', $blog->id)
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Show edit form (public - users can edit their own posts)
     */
    public function edit($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to edit blog posts.');
        }
        
        $blog = Blog::findOrFail($id);
        
        // Check if user owns the post or is admin
        if ($blog->author_id !== auth()->user()->id && !auth()->user()->isAdmin()) {
            return redirect()->route('blog.show', $blog->id)->with('error', 'You can only edit your own posts.');
        }
        
        return view('blog.edit', compact('blog'));
    }

    /**
     * Update blog post (public - users can update their own posts)
     */
    public function update(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to update blog posts.');
        }
        
        $blog = Blog::findOrFail($id);
        
        // Check if user owns the post or is admin
        if ($blog->author_id !== auth()->user()->id && !auth()->user()->isAdmin()) {
            return redirect()->route('blog.show', $blog->id)->with('error', 'You can only update your own posts.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|string|max:255',
        ]);

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'status' => $request->status,
            'published_at' => $request->status === 'published' 
                ? ($request->published_at ?? ($blog->published_at ?? now())) 
                : null,
            'featured_image' => $request->featured_image,
        ]);

        // Redirect based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post updated successfully!');
        }
        
        return redirect()->route('blog.show', $blog->id)
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Delete blog post (public - users can delete their own posts)
     */
    public function destroy($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to delete blog posts.');
        }
        
        $blog = Blog::findOrFail($id);
        
        // Check if user owns the post or is admin
        if ($blog->author_id !== auth()->user()->id && !auth()->user()->isAdmin()) {
            return redirect()->route('blog.show', $blog->id)->with('error', 'You can only delete your own posts.');
        }
        
        $blog->delete();

        // Redirect based on user role
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.blogs.index')
                ->with('success', 'Blog post deleted successfully!');
        }
        
        return redirect()->route('home')
            ->with('success', 'Blog post deleted successfully!');
    }
}
