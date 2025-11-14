@extends('layouts.main')

@section('title', 'Manage Blog Posts')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #667eea;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .btn-create {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .data-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
    }

    .table-header th {
        padding: 1rem;
        font-weight: 600;
        border: none;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .data-table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-published {
        background: #28a745;
        color: white;
    }

    .badge-draft {
        background: #6c757d;
        color: white;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #17a2b8;
        color: white;
    }

    .btn-edit:hover {
        background: #138496;
        color: white;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
        color: white;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<div class="page-header">
    <h1><i class="bi bi-journal-text"></i> Manage Blog Posts</h1>
    <a href="{{ route('admin.blogs.create') }}" class="btn-create">
        <i class="bi bi-plus-circle"></i> Create New Post
    </a>
</div>


<div class="data-table">
    <table class="table table-hover mb-0">
        <thead class="table-header">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Status</th>
                <th>Published</th>
                <th>Views</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blogs as $blog)
            <tr>
                <td class="fw-bold">{{ $blog->title }}</td>
                <td>{{ $blog->author->username ?? 'Unknown' }}</td>
                <td>
                    @if($blog->status === 'published')
                        <span class="badge-status badge-published">Published</span>
                    @else
                        <span class="badge-status badge-draft">Draft</span>
                    @endif
                </td>
                <td>
                    @if($blog->published_at)
                        {{ $blog->published_at->format('M d, Y') }}
                    @else
                        <span class="text-muted">Not published</span>
                    @endif
                </td>
                <td>{{ $blog->views }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn-action btn-edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 2rem; color: #ccc;"></i>
                    <p class="mt-2 text-muted">No blog posts yet. Create your first post!</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($blogs->hasPages())
<div class="mt-3 d-flex justify-content-center">
    {{ $blogs->links() }}
</div>
@endif

@endsection

