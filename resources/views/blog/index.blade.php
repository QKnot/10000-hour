@extends('layouts.main')

@section('title', 'Home - 10000 Hour Blog')

@section('content')
<style>
    .blog-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        margin-bottom: 3rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .blog-hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .blog-hero p {
        font-size: 1.2rem;
        opacity: 0.95;
    }

    .blog-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .blog-card-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .blog-card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .blog-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .blog-card-title a:hover {
        color: #667eea;
    }

    .blog-card-excerpt {
        color: #666;
        margin-bottom: 1rem;
        flex: 1;
        line-height: 1.6;
    }

    .blog-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
        font-size: 0.9rem;
        color: #999;
    }

    .blog-card-author {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .blog-card-views {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }

    .blog-card-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .btn-action-small {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit-small {
        background: #17a2b8;
        color: white;
    }

    .btn-edit-small:hover {
        background: #138496;
        color: white;
    }

    .btn-delete-small {
        background: #dc3545;
        color: white;
    }

    .btn-delete-small:hover {
        background: #c82333;
        color: white;
    }
</style>

<div class="blog-hero">
    <h1><i class="bi bi-journal-text"></i> 10000 Hour Blog</h1>
    <p>Insights, tips, and stories about mastering skills through deliberate practice</p>
    @auth
    <div class="mt-2">
        <small class="text-white-50">
            <i class="bi bi-info-circle"></i> 
            Your pending posts are visible only to you until approved by an admin.
        </small>
    </div>
    <div class="mt-3">
        <a href="{{ route('blog.create') }}" class="btn btn-light btn-lg">
            <i class="bi bi-plus-circle"></i> Create New Post
        </a>
    </div>
    @else
    <div class="mt-3">
        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
            <i class="bi bi-box-arrow-in-right"></i> Login to Post
        </a>
    </div>
    @endauth
</div>

@if($blogs->count() > 0)
<div class="row g-4">
    @foreach($blogs as $blog)
    <div class="col-md-6 col-lg-4">
        <div class="blog-card">
            @if($blog->featured_image)
            <div class="blog-card-image" style="background-image: url('{{ $blog->featured_image }}'); background-size: cover; background-position: center;">
            </div>
            @else
            <div class="blog-card-image">
                <i class="bi bi-file-text"></i>
            </div>
            @endif
            <div class="blog-card-body">
                <h2 class="blog-card-title">
                    <a href="{{ route('blog.show', $blog->id) }}">{{ $blog->title }}</a>
                </h2>
                <p class="blog-card-excerpt">
                    {{ $blog->getExcerptOrGenerated() }}
                </p>
                <div class="blog-card-meta">
                    <div class="blog-card-author">
                        <i class="bi bi-person"></i>
                        <span>{{ $blog->author->username ?? 'Admin' }}</span>
                    </div>
                    <div class="blog-card-views">
                        <i class="bi bi-eye"></i>
                        <span>{{ $blog->views }}</span>
                    </div>
                </div>
                <div class="blog-card-meta" style="border-top: none; padding-top: 0.5rem;">
                    <small>
                        <i class="bi bi-calendar"></i>
                        {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Not published' }}
                    </small>
                    @if($blog->approval_status !== 'approved')
                        <span class="badge bg-warning ms-2">
                            <i class="bi bi-clock"></i> 
                            {{ $blog->approval_status === 'pending' ? 'Pending Approval' : 'Rejected' }}
                        </span>
                    @endif
                </div>
                @auth
                @if($blog->author_id === auth()->user()->id || auth()->user()->isAdmin())
                <div class="blog-card-actions">
                    <a href="{{ route('blog.edit', $blog->id) }}" class="btn-action-small btn-edit-small">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action-small btn-delete-small">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="pagination-wrapper">
    {{ $blogs->links() }}
</div>
@else
<div class="empty-state">
    <i class="bi bi-inbox"></i>
    <h3>No blog posts yet</h3>
    <p>Check back soon for new content!</p>
</div>
@endif

@endsection

