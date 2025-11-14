<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogLike;
use Illuminate\Support\Str;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle like/dislike on a blog post
     */
    public function toggle(Request $request, $blogId)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $blog = Blog::findOrFail($blogId);
        $userId = auth()->user()->id;
        $type = $request->type;

        // Check if user already has a reaction
        $existingReaction = BlogLike::where('blog_id', $blogId)
            ->where('user_id', $userId)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                // Remove reaction if clicking the same type
                $existingReaction->delete();
                $action = 'removed';
            } else {
                // Change reaction type
                $existingReaction->type = $type;
                $existingReaction->save();
                $action = 'changed';
            }
        } else {
            // Create new reaction
            BlogLike::create([
                'id' => Str::random(12),
                'blog_id' => $blogId,
                'user_id' => $userId,
                'type' => $type,
            ]);
            $action = 'added';
        }

        // Get updated counts
        $likesCount = $blog->likes()->count();
        $dislikesCount = $blog->dislikes()->count();
        $userReaction = $blog->getUserReaction($userId);

        return response()->json([
            'success' => true,
            'action' => $action,
            'likes' => $likesCount,
            'dislikes' => $dislikesCount,
            'user_reaction' => $userReaction,
        ]);
    }
}
