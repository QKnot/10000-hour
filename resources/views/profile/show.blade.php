@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        text-align: center;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 1.5rem;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .profile-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-header .role-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        font-weight: 600;
        margin-top: 1rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 4px solid;
        height: 100%;
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

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.8;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #667eea;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #666;
    }

    .info-value {
        color: #333;
        font-weight: 500;
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

    .badge-mini {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0.25rem;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .empty-state i {
        font-size: 2rem;
        color: #ccc;
        margin-bottom: 0.5rem;
    }
</style>

<div class="profile-header">
    <div class="profile-avatar">
        <i class="bi bi-person-circle"></i>
    </div>
    <h1>{{ $user->username }}</h1>
    <p style="opacity: 0.9; margin-bottom: 0.5rem;">{{ $user->email }}</p>
    <span class="role-badge">
        @if($user->isAdmin())
            <i class="bi bi-shield-check"></i> Administrator
        @else
            <i class="bi bi-person"></i> Member
        @endif
    </span>
    @if($userRank)
    <div style="margin-top: 1rem; opacity: 0.9;">
        <i class="bi bi-trophy"></i> Rank #{{ $userRank }} on Leaderboard
    </div>
    @endif
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon" style="color: #667eea;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-value">{{ number_format($totalHours, 2) }}h</div>
            <div class="stat-label">Total Hours Focused</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-icon" style="color: #28a745;">
                <i class="bi bi-list-check"></i>
            </div>
            <div class="stat-value">{{ $totalHabits }}</div>
            <div class="stat-label">Active Habits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon" style="color: #17a2b8;">
                <i class="bi bi-trophy"></i>
            </div>
            <div class="stat-value">{{ $totalBadges }}</div>
            <div class="stat-label">Badges Earned</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon" style="color: #ffc107;">
                <i class="bi bi-journal-text"></i>
            </div>
            <div class="stat-value">{{ $totalBlogPosts }}</div>
            <div class="stat-label">Blog Posts</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Information -->
    <div class="col-md-6 mb-4">
        <div class="info-card">
            <h2 class="section-title">
                <i class="bi bi-info-circle"></i> Account Information
            </h2>
            <div class="info-item">
                <span class="info-label">Username</span>
                <span class="info-value">{{ $user->username }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Role</span>
                <span class="info-value">
                    @if($user->isAdmin())
                        <span class="badge-mini">Admin</span>
                    @else
                        <span class="badge-mini">Member</span>
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Leaderboard Rank</span>
                <span class="info-value">
                    @if($userRank)
                        #{{ $userRank }}
                    @else
                        Not ranked yet
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Blog Statistics -->
    <div class="col-md-6 mb-4">
        <div class="info-card">
            <h2 class="section-title">
                <i class="bi bi-journal-text"></i> Blog Statistics
            </h2>
            <div class="info-item">
                <span class="info-label">Total Posts</span>
                <span class="info-value">{{ $totalBlogPosts }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Published Posts</span>
                <span class="info-value">{{ $publishedBlogPosts }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Views</span>
                <span class="info-value">{{ $totalBlogViews }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Draft Posts</span>
                <span class="info-value">{{ $totalBlogPosts - $publishedBlogPosts }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Earned Badges -->
@if($earnedBadges->count() > 0)
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-trophy"></i> Earned Badges ({{ $totalBadges }})
    </h2>
    <div class="row g-3">
        @foreach($earnedBadges as $badge)
        <div class="col-md-3 col-sm-4 col-6">
            <div class="badge-card earned" style="border-color: {{ $badge->color }};">
                <div class="badge-icon" style="color: {{ $badge->color }};">
                    {{ $badge->icon ?? '🏆' }}
                </div>
                <div class="badge-name">{{ $badge->name }}</div>
                <div class="badge-description">{{ $badge->description }}</div>
                @if($badge->pivot && $badge->pivot->earned_at)
                <div class="badge-earned-date">
                    {{ \Carbon\Carbon::parse($badge->pivot->earned_at)->format('M Y') }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Recent Habits -->
@if($recentHabits->count() > 0)
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-list-check"></i> Recent Habits
    </h2>
    <div class="data-table">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>Habit Name</th>
                    <th>Daily Target</th>
                    <th>Total Hours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentHabits as $habit)
                <tr>
                    <td class="fw-bold">{{ $habit->name }}</td>
                    <td>{{ $habit->daily_count }}h/day</td>
                    <td>{{ number_format(\App\Models\habits::getTotalHours($habit->id), 2) }}h</td>
                    <td>
                        <a href="{{ route('habits.index', $habit->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Recent Blog Posts -->
@if($recentBlogPosts->count() > 0)
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-journal-text"></i> Recent Blog Posts
    </h2>
    <div class="data-table">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBlogPosts as $post)
                <tr>
                    <td class="fw-bold">{{ $post->title }}</td>
                    <td>
                        @if($post->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                    <td>{{ $post->views }}</td>
                    <td>
                        @if($post->status === 'published')
                        <a href="{{ route('blog.show', $post->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        @endif
                        <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Recent Activity -->
@if($recentActivity->count() > 0)
<div class="mb-4">
    <h2 class="section-title">
        <i class="bi bi-clock-history"></i> Recent Activity
    </h2>
    <div class="data-table">
        <table class="table table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th>Habit</th>
                    <th>Date</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivity as $activity)
                <tr>
                    <td class="fw-bold">{{ $activity['habit'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($activity['date'])->format('M d, Y') }}</td>
                    <td>{{ number_format($activity['duration'], 2) }}h</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

