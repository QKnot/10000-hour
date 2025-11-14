@extends('layouts.main')

@section('title', $blog->title . ' - 10000 Hour Blog')

@section('content')
<style>
    .blog-post-header {
        margin-bottom: 2rem;
    }

    .blog-post-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .blog-post-meta {
        display: flex;
        align-items: center;
        gap: 2rem;
        color: #666;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .blog-post-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .blog-post-featured-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }

    .blog-post-content {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        line-height: 1.8;
        color: #333;
        font-size: 1.1rem;
    }

    .blog-post-content h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .blog-post-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #764ba2;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .blog-post-content p {
        margin-bottom: 1.5rem;
    }

    .blog-post-content ul, .blog-post-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .blog-post-content li {
        margin-bottom: 0.5rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: #764ba2;
        transform: translateX(-5px);
    }

    .reactions-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .reaction-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #333;
        font-weight: 600;
    }

    .reaction-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .reaction-btn.liked {
        background: #28a745;
        color: white;
        border-color: #28a745;
    }

    .reaction-btn.disliked {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .reaction-count {
        font-size: 1.1rem;
        font-weight: 700;
        color: #667eea;
    }

    .comments-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .comments-header {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .comment-form {
        margin-bottom: 2rem;
    }

    .comment-input {
        width: 100%;
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    .comment-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .comment-item {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 1rem;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .comment-author {
        font-weight: 600;
        color: #667eea;
    }

    .comment-date {
        color: #999;
        font-size: 0.9rem;
    }

    .comment-content {
        color: #333;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
    }

    .comment-action {
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .comment-action:hover {
        text-decoration: underline;
    }

    .comment-replies {
        margin-left: 2rem;
        margin-top: 1rem;
        padding-left: 1rem;
        border-left: 3px solid #e0e0e0;
    }

    .login-prompt {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        color: #666;
    }

    .blog-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        color: white;
    }
</style>

<a href="{{ route('home') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Back to Blog
</a>

@auth
@if($blog->author_id === auth()->user()->id || auth()->user()->isAdmin())
<div class="blog-actions">
    <a href="{{ route('blog.edit', $blog->id) }}" class="btn-action btn-edit">
        <i class="bi bi-pencil"></i> Edit Post
    </a>
    <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-action btn-delete">
            <i class="bi bi-trash"></i> Delete Post
        </button>
    </form>
</div>
@endif
@endauth

<div class="blog-post-header">
    <h1 class="blog-post-title">{{ $blog->title }}</h1>
    <div class="blog-post-meta">
        <div class="blog-post-meta-item">
            <i class="bi bi-person"></i>
            <span>{{ $blog->author->username ?? 'Admin' }}</span>
        </div>
        <div class="blog-post-meta-item">
            <i class="bi bi-calendar"></i>
            <span>{{ $blog->published_at ? $blog->published_at->format('F d, Y') : 'Not published' }}</span>
        </div>
        <div class="blog-post-meta-item">
            <i class="bi bi-eye"></i>
            <span>{{ $blog->views }} views</span>
        </div>
    </div>
</div>

@if($blog->featured_image)
<div class="blog-post-featured-image" style="background-image: url('{{ $blog->featured_image }}'); background-size: cover; background-position: center;">
</div>
@else
<div class="blog-post-featured-image">
    <i class="bi bi-file-text"></i>
</div>
@endif

<div class="blog-post-content">
    {!! nl2br(e($blog->content)) !!}
</div>

<!-- Reactions Section -->
<div class="reactions-section">
    <div class="reaction-count">
        <span id="likes-count">{{ $blog->likes()->count() }}</span> likes
    </div>
    <div class="reaction-count">
        <span id="dislikes-count">{{ $blog->dislikes()->count() }}</span> dislikes
    </div>
    @auth
    <button class="reaction-btn {{ $userReaction === 'like' ? 'liked' : '' }}" id="like-btn" data-type="like">
        <i class="bi bi-hand-thumbs-up"></i> Like
    </button>
    <button class="reaction-btn {{ $userReaction === 'dislike' ? 'disliked' : '' }}" id="dislike-btn" data-type="dislike">
        <i class="bi bi-hand-thumbs-down"></i> Dislike
    </button>
    @else
    <a href="{{ route('login') }}" class="reaction-btn">
        <i class="bi bi-hand-thumbs-up"></i> Login to like/dislike
    </a>
    @endauth
</div>

<!-- Comments Section -->
<div class="comments-section">
    <h2 class="comments-header">
        <i class="bi bi-chat-dots"></i> Comments (<span id="comments-count">{{ $blog->comments()->count() }}</span>)
    </h2>

    @auth
    <div class="comment-form">
        <form id="comment-form">
            @csrf
            <textarea class="comment-input" id="comment-content" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="btn-submit mt-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600;">
                <i class="bi bi-send"></i> Post Comment
            </button>
        </form>
    </div>
    @else
    <div class="login-prompt">
        <a href="{{ route('login') }}">Login</a> to post a comment
    </div>
    @endauth

    <div id="comments-container">
        @foreach($blog->comments as $comment)
        <div class="comment-item" data-comment-id="{{ $comment->id }}">
            <div class="comment-header">
                <span class="comment-author">{{ $comment->user->username ?? 'Unknown' }}</span>
                <span class="comment-date">{{ $comment->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="comment-content">{{ $comment->content }}</div>
            <div class="comment-actions">
                @auth
                @if($comment->user_id === auth()->user()->id || auth()->user()->isAdmin())
                <a href="#" class="comment-action delete-comment" data-comment-id="{{ $comment->id }}">
                    <i class="bi bi-trash"></i> Delete
                </a>
                @endif
                @endauth
            </div>
            @if($comment->replies->count() > 0)
            <div class="comment-replies">
                @foreach($comment->replies as $reply)
                <div class="comment-item" data-comment-id="{{ $reply->id }}">
                    <div class="comment-header">
                        <span class="comment-author">{{ $reply->user->username ?? 'Unknown' }}</span>
                        <span class="comment-date">{{ $reply->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="comment-content">{{ $reply->content }}</div>
                    <div class="comment-actions">
                        @auth
                        @if($reply->user_id === auth()->user()->id || auth()->user()->isAdmin())
                        <a href="#" class="comment-action delete-comment" data-comment-id="{{ $reply->id }}">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                        @endif
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

@auth
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Like/Dislike functionality
    $('.reaction-btn[data-type]').click(function(e) {
        e.preventDefault();
        const type = $(this).data('type');
        const blogId = '{{ $blog->id }}';
        
        $.ajax({
            url: '/blog/' + blogId + '/like',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                type: type
            },
            success: function(response) {
                $('#likes-count').text(response.likes);
                $('#dislikes-count').text(response.dislikes);
                
                // Update button states
                $('#like-btn').removeClass('liked');
                $('#dislike-btn').removeClass('disliked');
                
                if (response.user_reaction === 'like') {
                    $('#like-btn').addClass('liked');
                    window.showAlert('Post liked!', 'success', 2000);
                } else if (response.user_reaction === 'dislike') {
                    $('#dislike-btn').addClass('disliked');
                    window.showAlert('Post disliked', 'info', 2000);
                } else {
                    window.showAlert('Reaction removed', 'info', 2000);
                }
            },
            error: function() {
                window.showAlert('Failed to update reaction. Please try again.', 'error');
            }
        });
    });

    // Comment form submission
    $('#comment-form').submit(function(e) {
        e.preventDefault();
        const content = $('#comment-content').val();
        const blogId = '{{ $blog->id }}';
        
        if (!content.trim()) {
            window.showAlert('Please enter a comment', 'warning', 3000);
            return;
        }
        
        $.ajax({
            url: '/blog/' + blogId + '/comment',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                content: content
            },
            success: function(response) {
                if (response.success) {
                    const commentHtml = `
                        <div class="comment-item" data-comment-id="${response.comment.id}">
                            <div class="comment-header">
                                <span class="comment-author">${response.comment.user.username}</span>
                                <span class="comment-date">${response.comment.created_at}</span>
                            </div>
                            <div class="comment-content">${response.comment.content}</div>
                            <div class="comment-actions">
                                <a href="#" class="comment-action delete-comment" data-comment-id="${response.comment.id}">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    `;
                    $('#comments-container').prepend(commentHtml);
                    $('#comment-content').val('');
                    $('#comments-count').text(parseInt($('#comments-count').text()) + 1);
                    window.showAlert('Comment posted successfully!', 'success');
                }
            },
            error: function() {
                window.showAlert('Failed to post comment. Please try again.', 'error');
            }
        });
    });

    // Delete comment
    $(document).on('click', '.delete-comment', function(e) {
        e.preventDefault();
        const commentId = $(this).data('comment-id');
        
        if (!confirm('Are you sure you want to delete this comment?')) {
            return;
        }
        
        $.ajax({
            url: '/comment/' + commentId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('[data-comment-id="' + commentId + '"]').fadeOut(function() {
                        $(this).remove();
                        $('#comments-count').text(parseInt($('#comments-count').text()) - 1);
                    });
                    window.showAlert('Comment deleted successfully', 'success', 2000);
                }
            },
            error: function() {
                window.showAlert('Failed to delete comment. Please try again.', 'error');
            }
        });
    });
});
</script>
@endauth

@endsection

