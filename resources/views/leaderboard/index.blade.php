@extends('layouts.main')

@section('title', 'Leaderboard')

@section('content')
<style>
    .leaderboard-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .leaderboard-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .leaderboard-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin: 0;
    }

    .current-user-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.3);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .current-user-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        font-size: 1.5rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }

    .user-stats {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .user-stats strong {
        font-size: 1.1rem;
    }

    .user-stats span {
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .leaderboard-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        font-weight: 600;
    }

    .table-header th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border: none;
    }

    .leaderboard-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .leaderboard-table tbody tr:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .leaderboard-table tbody tr.current-user-row {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left: 4px solid #667eea;
        font-weight: 600;
    }

    .rank-cell {
        text-align: center;
        font-weight: 700;
        font-size: 1.2rem;
        color: #667eea;
        width: 80px;
    }

    .rank-cell.top-1 {
        color: #ffd700;
        font-size: 1.5rem;
    }

    .rank-cell.top-2 {
        color: #c0c0c0;
        font-size: 1.4rem;
    }

    .rank-cell.top-3 {
        color: #cd7f32;
        font-size: 1.3rem;
    }

    .username-cell {
        font-weight: 600;
        color: #333;
    }

    .hours-cell {
        font-weight: 700;
        color: #667eea;
        font-size: 1.1rem;
    }

    .habits-cell {
        color: #666;
        font-size: 0.95rem;
    }

    .medal-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
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
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .stats-summary {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .stat-card {
        flex: 1;
        min-width: 200px;
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-label {
        color: #666;
        font-size: 0.95rem;
    }
</style>

<div class="leaderboard-header">
    <h1><i class="bi bi-trophy-fill"></i> Leaderboard</h1>
    <p>Ranking based on total focused hours across all habits</p>
</div>

@if($currentUserRank)
<div class="current-user-card">
    <div class="current-user-info">
        <div class="rank-badge">#{{ $currentUserRank }}</div>
        <div class="user-stats">
            <strong>{{ $currentUser->username }}</strong>
            <span>{{ number_format($currentUserHours, 2) }} hours focused</span>
        </div>
    </div>
    <div class="user-stats">
        <strong>{{ $currentUser->getTotalHabits() }}</strong>
        <span>Active Habits</span>
    </div>
</div>
@else
<div class="current-user-card">
    <div class="current-user-info">
        <div class="user-stats">
            <strong>{{ $currentUser->username }}</strong>
            <span>Start logging hours to appear on the leaderboard!</span>
        </div>
    </div>
    <div class="user-stats">
        <strong>{{ number_format($currentUserHours, 2) }}</strong>
        <span>Total Hours</span>
    </div>
</div>
@endif

<!-- <div class="stats-summary">
    <div class="stat-card">
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Active Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($totalHoursAll ?? 0, 2) }}</div>
        <div class="stat-label">Total Hours Logged</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($avgHoursAll ?? 0, 2) }}</div>
        <div class="stat-label">Average Hours per User</div>
    </div>
</div> -->

@if($users->count() > 0)
<div class="leaderboard-table">
    <table class="table table-hover mb-0">
        <thead class="table-header">
            <tr>
                <th class="rank-cell">Rank</th>
                <th>User</th>
                <th class="text-end">Total Hours</th>
                <th class="text-center">Habits</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            @php
                $rank = ($users->currentPage() - 1) * $users->perPage() + $index + 1;
                $isCurrentUser = $user['id'] === $currentUser->id;
            @endphp
            <tr class="{{ $isCurrentUser ? 'current-user-row' : '' }}">
                <td class="rank-cell {{ $rank <= 3 ? 'top-' . $rank : '' }}">
                    @if($rank == 1)
                        <i class="bi bi-trophy-fill medal-icon" style="color: #ffd700;"></i>
                    @elseif($rank == 2)
                        <i class="bi bi-trophy-fill medal-icon" style="color: #c0c0c0;"></i>
                    @elseif($rank == 3)
                        <i class="bi bi-trophy-fill medal-icon" style="color: #cd7f32;"></i>
                    @endif
                    #{{ $rank }}
                </td>
                <td class="username-cell">
                    {{ $user['username'] }}
                    @if($isCurrentUser)
                        <span class="badge bg-primary ms-2">You</span>
                    @endif
                </td>
                <td class="text-end hours-cell">{{ number_format($user['total_hours'], 2) }}h</td>
                <td class="text-center habits-cell">{{ $user['total_habits'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-wrapper">
    {{ $users->links() }}
</div>
@else
<div class="empty-state">
    <i class="bi bi-inbox"></i>
    <h3>No users on the leaderboard yet</h3>
    <p>Start logging hours to be the first!</p>
</div>
@endif

@endsection

