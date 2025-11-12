@extends('layouts.main')

@section('title', 'Badges')

@section('content')
<style>
    .badge-card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .badge-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .badge-card.earned {
        border: 3px solid;
    }

    .badge-card.locked {
        opacity: 0.5;
        filter: grayscale(100%);
    }

    .badge-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
        text-align: center;
    }

    .badge-name {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .badge-description {
        color: #666;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    .badge-requirement {
        background: rgba(0, 0, 0, 0.05);
        padding: 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        text-align: center;
        color: #555;
    }

    .badge-earned-date {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(16, 185, 129, 0.9);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .stats-value {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .progress-section {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .progress-bar-custom {
        height: 30px;
        border-radius: 15px;
        background: #e5e7eb;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        transition: width 1s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .section-title {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: #333;
    }
</style>

<div class="container mt-4">
    <!-- Stats Section -->
    <div class="stats-card text-center">
        <div class="stats-value">{{ number_format($totalHours, 2) }}h</div>
        <div class="stats-label">Total Hours Logged</div>
        <div class="mt-3">
            <small>Progress to 10,000 hours: {{ number_format(($totalHours / 10000) * 100, 2) }}%</small>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-section">
        <h5 class="mb-3">Progress to Master Badge</h5>
        <div class="progress-bar-custom">
            <div class="progress-fill" style="width: {{ min(100, ($totalHours / 10000) * 100) }}%">
                @if($totalHours >= 10000)
                    100% - Master Achieved! 🌟
                @else
                    {{ number_format(($totalHours / 10000) * 100, 1) }}%
                @endif
            </div>
        </div>
        <div class="mt-2 text-center">
            <small class="text-muted">{{ number_format($totalHours, 2) }} / 10,000 hours</small>
        </div>
    </div>

    <!-- Earned Badges Section -->
    <div class="mb-5">
        <h3 class="section-title">
            <i class="bi bi-trophy-fill me-2"></i>Your Badges ({{ $userBadges->count() }}/{{ $allBadges->count() }})
        </h3>
        <div class="row g-4">
            @forelse($userBadges as $badge)
                <div class="col-md-4 col-lg-3">
                    <div class="card badge-card earned" style="border-color: {{ $badge->color }};">
                        <div class="card-body text-center p-4">
                            <span class="badge-earned-date">Earned</span>
                            <div class="badge-icon" style="color: {{ $badge->color }};">
                                {{ $badge->icon }}
                            </div>
                            <div class="badge-name" style="color: {{ $badge->color }};">
                                {{ $badge->name }}
                            </div>
                            <div class="badge-description">
                                {{ $badge->description }}
                            </div>
                            <div class="badge-requirement">
                                <small>
                                    <i class="bi bi-calendar-check me-1"></i>
                                    {{ \Carbon\Carbon::parse($badge->pivot->earned_at)->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        You haven't earned any badges yet. Start logging your activities to earn badges!
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- All Badges Section -->
    <div class="mb-5">
        <h3 class="section-title">
            <i class="bi bi-star me-2"></i>All Available Badges
        </h3>
        <div class="row g-4">
            @foreach($allBadges as $badge)
                @php
                    $isEarned = $userBadges->contains('id', $badge->id);
                @endphp
                <div class="col-md-4 col-lg-3">
                    <div class="card badge-card {{ $isEarned ? 'earned' : 'locked' }}" 
                         style="{{ $isEarned ? 'border-color: ' . $badge->color . ';' : '' }}">
                        <div class="card-body text-center p-4">
                            @if($isEarned)
                                <span class="badge-earned-date">Earned</span>
                            @endif
                            <div class="badge-icon" style="color: {{ $badge->color }}; {{ !$isEarned ? 'opacity: 0.5;' : '' }}">
                                {{ $badge->icon }}
                            </div>
                            <div class="badge-name" style="color: {{ $badge->color }}; {{ !$isEarned ? 'opacity: 0.5;' : '' }}">
                                {{ $badge->name }}
                            </div>
                            <div class="badge-description">
                                {{ $badge->description }}
                            </div>
                            @if($badge->requirement_value)
                                <div class="badge-requirement">
                                    @if($badge->badge_type == 'total_hours')
                                        <small><i class="bi bi-clock me-1"></i>Requires: {{ number_format($badge->requirement_value) }} hours</small>
                                    @elseif($badge->badge_type == 'daily_goal')
                                        <small><i class="bi bi-calendar-check me-1"></i>Requires: {{ $badge->requirement_value }} days</small>
                                    @elseif($badge->badge_type == 'streak')
                                        <small><i class="bi bi-fire me-1"></i>Requires: {{ $badge->requirement_value }}-day streak</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

