<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;
use Illuminate\Support\Str;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // First Log Badge
            [
                'id' => Str::random(12),
                'name' => 'Getting Started',
                'description' => 'Logged your first activity',
                'icon' => '🎯',
                'badge_type' => 'first_log',
                'requirement_value' => null,
                'color' => '#10b981',
                'sort_order' => 1
            ],
            // Total Hours Badges
            [
                'id' => Str::random(12),
                'name' => 'First Hour',
                'description' => 'Reached 1 hour of practice',
                'icon' => '⭐',
                'badge_type' => 'total_hours',
                'requirement_value' => 1,
                'color' => '#f59e0b',
                'sort_order' => 2
            ],
            [
                'id' => Str::random(12),
                'name' => 'Centurion',
                'description' => 'Reached 100 hours of practice',
                'icon' => '🏅',
                'badge_type' => 'total_hours',
                'requirement_value' => 100,
                'color' => '#3b82f6',
                'sort_order' => 3
            ],
            [
                'id' => Str::random(12),
                'name' => 'Half Thousand',
                'description' => 'Reached 500 hours of practice',
                'icon' => '🥇',
                'badge_type' => 'total_hours',
                'requirement_value' => 500,
                'color' => '#8b5cf6',
                'sort_order' => 4
            ],
            [
                'id' => Str::random(12),
                'name' => 'Thousand Club',
                'description' => 'Reached 1,000 hours of practice',
                'icon' => '👑',
                'badge_type' => 'total_hours',
                'requirement_value' => 1000,
                'color' => '#ec4899',
                'sort_order' => 5
            ],
            [
                'id' => Str::random(12),
                'name' => 'Five Thousand',
                'description' => 'Reached 5,000 hours of practice',
                'icon' => '💎',
                'badge_type' => 'total_hours',
                'requirement_value' => 5000,
                'color' => '#06b6d4',
                'sort_order' => 6
            ],
            [
                'id' => Str::random(12),
                'name' => 'Master',
                'description' => 'Reached 10,000 hours of practice - You are a Master!',
                'icon' => '🌟',
                'badge_type' => 'total_hours',
                'requirement_value' => 10000,
                'color' => '#fbbf24',
                'sort_order' => 7
            ],
            // Daily Goal Badges
            [
                'id' => Str::random(12),
                'name' => 'Daily Champion',
                'description' => 'Achieved daily goal 1 time',
                'icon' => '✅',
                'badge_type' => 'daily_goal',
                'requirement_value' => 1,
                'color' => '#10b981',
                'sort_order' => 8
            ],
            [
                'id' => Str::random(12),
                'name' => 'Week Warrior',
                'description' => 'Achieved daily goal 7 times',
                'icon' => '🔥',
                'badge_type' => 'daily_goal',
                'requirement_value' => 7,
                'color' => '#f59e0b',
                'sort_order' => 9
            ],
            [
                'id' => Str::random(12),
                'name' => 'Month Master',
                'description' => 'Achieved daily goal 30 times',
                'icon' => '💪',
                'badge_type' => 'daily_goal',
                'requirement_value' => 30,
                'color' => '#3b82f6',
                'sort_order' => 10
            ],
            [
                'id' => Str::random(12),
                'name' => 'Century Champion',
                'description' => 'Achieved daily goal 100 times',
                'icon' => '🏆',
                'badge_type' => 'daily_goal',
                'requirement_value' => 100,
                'color' => '#8b5cf6',
                'sort_order' => 11
            ],
            // Streak Badges
            [
                'id' => Str::random(12),
                'name' => 'Week Streak',
                'description' => 'Maintained a 7-day streak',
                'icon' => '🔥',
                'badge_type' => 'streak',
                'requirement_value' => 7,
                'color' => '#f59e0b',
                'sort_order' => 12
            ],
            [
                'id' => Str::random(12),
                'name' => 'Month Streak',
                'description' => 'Maintained a 30-day streak',
                'icon' => '⚡',
                'badge_type' => 'streak',
                'requirement_value' => 30,
                'color' => '#3b82f6',
                'sort_order' => 13
            ],
            [
                'id' => Str::random(12),
                'name' => 'Hundred Day Streak',
                'description' => 'Maintained a 100-day streak',
                'icon' => '🌋',
                'badge_type' => 'streak',
                'requirement_value' => 100,
                'color' => '#8b5cf6',
                'sort_order' => 14
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
