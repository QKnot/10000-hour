@extends('layouts.main')

@section('title', 'User Management - Admin')

@section('content')
<style>
    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .admin-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .users-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-header th {
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .users-table .table tbody tr {
        transition: all 0.3s ease;
    }

    .users-table .table tbody tr:hover {
        background-color: #f8f9ff;
        transform: scale(1.01);
    }

    .badge-admin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-member {
        background: #e9ecef;
        color: #6c757d;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .btn-make-admin {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-make-admin:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-remove-admin {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-remove-admin:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .btn-remove-admin:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .stats-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .current-user-row {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }

    .alert-custom {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Admin Header -->
<div class="admin-header">
    <h1>
        <i class="bi bi-people-fill"></i>
        User Management
    </h1>
    <p class="mb-0 fs-5">Manage user roles and permissions for the 10,000 Hour application</p>
    
    <div class="stats-row mt-3">
        <div class="stat-item">
            <i class="bi bi-person-fill"></i>
            <span><strong>{{ $users->count() }}</strong> Total Users</span>
        </div>
        <div class="stat-item">
            <i class="bi bi-shield-fill"></i>
            <span><strong>{{ $users->where('role', 'admin')->count() }}</strong> Admins</span>
        </div>
        <div class="stat-item">
            <i class="bi bi-person"></i>
            <span><strong>{{ $users->where('role', 'member')->count() }}</strong> Members</span>
        </div>
    </div>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success alert-custom mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-custom mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
    </div>
@endif

<!-- Users Table -->
<div class="users-table">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Habits</th>
                    <th>Hours</th>
                    <th>Blog Posts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr {{ $user['id'] === auth()->id() ? 'class="current-user-row"' : '' }}>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="bi bi-person-fill text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $user['username'] }}</div>
                                <small class="text-muted">{{ $user['email'] }}</small>
                                @if($user['id'] === auth()->id())
                                    <div class="badge bg-warning text-dark mt-1">You</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user['role'] === 'admin')
                            <span class="badge-admin">
                                <i class="bi bi-shield-fill me-1"></i>
                                Admin
                            </span>
                        @else
                            <span class="badge-member">
                                <i class="bi bi-person me-1"></i>
                                Member
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-list-check text-info me-2"></i>
                            <span class="fw-bold">{{ $user['habit_count'] }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            <span class="fw-bold">{{ number_format($user['total_hours'], 2) }}h</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-journal-text text-success me-2"></i>
                            <span class="fw-bold">{{ $user['blog_posts_count'] }}</span>
                        </div>
                    </td>
                    <td>
                        @if($user['role'] === 'admin')
                            <form action="{{ route('admin.users.remove-admin', $user['id']) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-remove-admin btn-sm"
                                        onclick="return confirm('Are you sure you want to remove admin role from {{ $user['username'] }}? This action cannot be undone.')"
                                        {{ $user['id'] === auth()->id() || $users->where('role', 'admin')->count() <= 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-shield-slash me-1"></i>
                                    Remove Admin
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.make-admin', $user['id']) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-make-admin btn-sm"
                                        onclick="return confirm('Are you sure you want to make {{ $user['username'] }} an admin? This will give them full administrative access.')">
                                    <i class="bi bi-shield-plus me-1"></i>
                                    Make Admin
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Info Section -->
<div class="mt-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Admin Role Management
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-success mb-2">
                        <i class="bi bi-shield-fill me-1"></i>
                        Admin Privileges
                    </h6>
                    <ul class="small text-muted">
                        <li>Access to admin dashboard and statistics</li>
                        <li>Manage user roles and permissions</li>
                        <li>Approve/reject blog posts</li>
                        <li>View all user data and analytics</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-warning mb-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Important Notes
                    </h6>
                    <ul class="small text-muted">
                        <li>At least one admin must remain at all times</li>
                        <li>You cannot remove your own admin role</li>
                        <li>Admin changes are logged and irreversible</li>
                        <li>Be careful when granting admin access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
