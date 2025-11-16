<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'content',
        'excerpt',
        'author_id',
        'featured_image',
        'status',
        'approval_status',
        'rejection_reason',
        'published_at',
        'approved_at',
        'approved_by',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the author of the blog post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the admin who approved this blog post
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all likes for this blog
     */
    public function likes()
    {
        return $this->hasMany(BlogLike::class, 'blog_id')->where('type', 'like');
    }

    /**
     * Get all dislikes for this blog
     */
    public function dislikes()
    {
        return $this->hasMany(BlogLike::class, 'blog_id')->where('type', 'dislike');
    }

    /**
     * Get all likes/dislikes for this blog
     */
    public function reactions()
    {
        return $this->hasMany(BlogLike::class, 'blog_id');
    }

    /**
     * Get all comments for this blog
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id')->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments including replies
     */
    public function allComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id')->orderBy('created_at', 'asc');
    }

    /**
     * Check if user has liked this blog
     */
    public function isLikedBy($userId)
    {
        return $this->reactions()->where('user_id', $userId)->where('type', 'like')->exists();
    }

    /**
     * Check if user has disliked this blog
     */
    public function isDislikedBy($userId)
    {
        return $this->reactions()->where('user_id', $userId)->where('type', 'dislike')->exists();
    }

    /**
     * Get user's reaction (like/dislike/null)
     */
    public function getUserReaction($userId)
    {
        $reaction = $this->reactions()->where('user_id', $userId)->first();
        return $reaction ? $reaction->type : null;
    }

    /**
     * Get published blog posts
     */
    public static function published()
    {
        return self::where('status', 'published')
            ->where('approval_status', 'approved')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get excerpt or generate from content
     */
    public function getExcerptOrGenerated()
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        
        // Generate excerpt from content (first 150 characters)
        $content = strip_tags($this->content);
        return strlen($content) > 150 
            ? substr($content, 0, 150) . '...' 
            : $content;
    }
}
