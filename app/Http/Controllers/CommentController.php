<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new comment
     */
    public function store(Request $request, $blogId)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        $blog = Blog::findOrFail($blogId);

        $comment = BlogComment::create([
            'id' => Str::random(12),
            'blog_id' => $blogId,
            'user_id' => auth()->user()->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => [
                    'id' => $comment->user->id,
                    'username' => $comment->user->username,
                ],
                'created_at' => $comment->created_at->format('M d, Y h:i A'),
                'parent_id' => $comment->parent_id,
            ],
        ]);
    }

    /**
     * Delete a comment (only own comments or admin)
     */
    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        
        // Check if user owns the comment or is admin
        if ($comment->user_id !== auth()->user()->id && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
