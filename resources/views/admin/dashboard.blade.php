@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .admin-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.3);
    }

    .admin-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.primary {
        border-left-color: #667eea;
    }

    .stat-card.success {
        border-left-color: #28a745;
    }

    .stat-card.info {
        border-left-color: #17a2b8;
    }

    .stat-card.warning {
        border-left-color: #ffc107;
    }

    .stat-card.danger {
        border-left-color: #dc3545;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #666;
        font-size: 1rem;
        font-weight: 500;
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.8;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #667eea;
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

    .badge-admin {
        background: #dc3545;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-member {
        background: #6c757d;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1rem;
    }
</style>

<div class="admin-header">
    <h1>
        <i class="bi bi-shield-check"></i> Admin Dashboard
    </h1>
    <p style="margin: 0; opacity: 0.9;">Manage and monitor users and habits</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon" style="color: #667eea;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon" style="color: #28a745;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-value">{{ $totalMembers }}</div>
            <div class="stat-label">Members</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card danger">
            <div class="stat-icon" style="color: #dc3545;">
                <i class="bi bi-shield-fill"></i>
            </div>
            <div class="stat-value">{{ $totalAdmins }}</div>
            <div class="stat-label">Admins</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon" style="color: #17a2b8;">
                <i class="bi bi-list-check"></i>
            </div>
            <div class="stat-value">{{ $totalHabits }}</div>
            <div class="stat-label">Total Habits</div>
        </div>
    </div>
</div>

<!-- Blog Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon" style="color: #667eea;">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="stat-value">{{ $totalBlogs }}</div>
            <div class="stat-label">Total Blog Posts</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon" style="color: #28a745;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $publishedBlogs }}</div>
            <div class="stat-label">Published Posts</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon" style="color: #ffc107;">
                <i class="bi bi-file-earmark"></i>
            </div>
            <div class="stat-value">{{ $draftBlogs }}</div>
            <div class="stat-label">Draft Posts</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon" style="color: #17a2b8;">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stat-value">{{ $totalBlogViews }}</div>
            <div class="stat-label">Total Blog Views</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-lightning"></i> Quick Actions
    </h2>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-primary">
            <i class="bi bi-journal-text"></i> Manage Blog Posts
        </a>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Create New Blog Post
        </a>
    </div>
</div>

<!-- Additional Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card warning">
            <div class="stat-icon" style="color: #ffc107;">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-value">{{ number_format($totalHoursLogged, 2) }}h</div>
            <div class="stat-label">Total Hours Logged</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card info">
            <div class="stat-icon" style="color: #17a2b8;">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
            <div class="stat-value">{{ $avgHabitsPerUser }}</div>
            <div class="stat-label">Avg Habits per User</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card success">
            <div class="stat-icon" style="color: #28a745;">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div class="stat-value">{{ $maxHabitsPerUser }}</div>
            <div class="stat-label">Max Habits (Single User)</div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-people"></i> Users Overview
    </h2>
    <div class="data-table">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Habits</th>
                    <th class="text-end">Total Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usersWithStats as $user)
                <tr>
                    <td class="fw-bold">{{ $user['username'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        @if($user['role'] === 'admin')
                            <span class="badge-admin">Admin</span>
                        @else
                            <span class="badge-member">Member</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $user['habit_count'] }}</td>
                    <td class="text-end fw-bold" style="color: #667eea;">{{ number_format($user['total_hours'], 2) }}h</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No users found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Habits Table -->
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-list-check"></i> Habits Overview
    </h2>
    <div class="data-table">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>Habit Name</th>
                    <th>User</th>
                    <th class="text-center">Log Entries</th>
                    <th class="text-end">Total Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($habitsWithStats as $habit)
                <tr>
                    <td class="fw-bold">{{ $habit['name'] }}</td>
                    <td>{{ $habit['user'] }}</td>
                    <td class="text-center">{{ $habit['log_count'] }}</td>
                    <td class="text-end fw-bold" style="color: #667eea;">{{ number_format($habit['total_hours'], 2) }}h</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No habits found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Activity -->
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-clock-history"></i> Recent Activity
    </h2>
    <div class="data-table">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>User</th>
                    <th>Habit</th>
                    <th>Date</th>
                    <th class="text-end">Duration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td class="fw-bold">{{ $log['user'] }}</td>
                    <td>{{ $log['habit'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($log['date'])->format('M d, Y') }}</td>
                    <td class="text-end fw-bold" style="color: #667eea;">{{ number_format($log['duration'], 2) }}h</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No recent activity</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

