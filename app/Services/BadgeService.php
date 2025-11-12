<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\habits;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Check and award badges for a user
     */
    public static function checkAndAwardBadges($userId, $habitId = null)
    {
        $user = User::find($userId);
        if (!$user) {
            return [];
        }

        $awardedBadges = [];

        // Get all badges
        $badges = Badge::orderBy('sort_order')->get();

        foreach ($badges as $badge) {
            // Skip if user already has this badge
            if ($user->hasBadge($badge->id)) {
                continue;
            }

            // Check if user qualifies for this badge
            if (self::qualifiesForBadge($user, $badge, $habitId)) {
                // Award the badge
                self::awardBadge($user, $badge);
                $awardedBadges[] = $badge;
            }
        }

        return $awardedBadges;
    }

    /**
     * Check if user qualifies for a badge
     */
    private static function qualifiesForBadge(User $user, Badge $badge, $habitId = null)
    {
        switch ($badge->badge_type) {
            case 'total_hours':
                return self::checkTotalHoursBadge($user, $badge, $habitId);
            
            case 'daily_goal':
                return self::checkDailyGoalBadge($user, $badge, $habitId);
            
            case 'streak':
                return self::checkStreakBadge($user, $badge, $habitId);
            
            case 'first_log':
                return self::checkFirstLogBadge($user, $badge);
            
            default:
                return false;
        }
    }

    /**
     * Check total hours badge
     */
    private static function checkTotalHoursBadge(User $user, Badge $badge, $habitId = null)
    {
        $requirement = $badge->requirement_value; // in hours
        
        // Total hours badges are always checked across ALL user's habits
        $totalHours = self::getUserTotalHours($user->id);
        return $totalHours >= $requirement;
    }

    /**
     * Check daily goal badge
     */
    private static function checkDailyGoalBadge(User $user, Badge $badge, $habitId = null)
    {
        $requirement = $badge->requirement_value; // number of days
        
        if ($habitId) {
            $habit = habits::find($habitId);
            if (!$habit) return false;
            
            $successCount = 0;
            $logs = $habit->logs()->orderBy('date', 'desc')->get();
            $dates = $logs->groupBy('date');
            
            foreach ($dates as $date => $dateLogs) {
                $totalDuration = $dateLogs->sum('duration');
                if ($totalDuration >= $habit->getDailyTargetSeconds()) {
                    $successCount++;
                    if ($successCount >= $requirement) {
                        return true;
                    }
                }
            }
            
            return false;
        } else {
            // Check across all habits
            $userHabits = habits::getHabitsByUser($user->id);
            $dates = [];
            
            foreach ($userHabits as $habit) {
                $logs = $habit->logs()->get();
                foreach ($logs as $log) {
                    $date = $log->date;
                    if (!isset($dates[$date])) {
                        $dates[$date] = [];
                    }
                    if (!isset($dates[$date][$habit->id])) {
                        $dates[$date][$habit->id] = 0;
                    }
                    $dates[$date][$habit->id] += $log->duration;
                }
            }
            
            $successCount = 0;
            foreach ($dates as $date => $habitsData) {
                $allGoalsMet = true;
                foreach ($userHabits as $habit) {
                    $duration = $dates[$date][$habit->id] ?? 0;
                    if ($duration < $habit->getDailyTargetSeconds()) {
                        $allGoalsMet = false;
                        break;
                    }
                }
                if ($allGoalsMet) {
                    $successCount++;
                    if ($successCount >= $requirement) {
                        return true;
                    }
                }
            }
            
            return false;
        }
    }

    /**
     * Check streak badge
     */
    private static function checkStreakBadge(User $user, Badge $badge, $habitId = null)
    {
        $requirement = $badge->requirement_value; // number of consecutive days
        
        if ($habitId) {
            $habit = habits::find($habitId);
            if (!$habit) return false;
            
            return self::calculateStreak($habit, $requirement);
        } else {
            // Check across all habits (any habit with streak)
            $userHabits = habits::getHabitsByUser($user->id);
            
            foreach ($userHabits as $habit) {
                if (self::calculateStreak($habit, $requirement)) {
                    return true;
                }
            }
            
            return false;
        }
    }

    /**
     * Calculate streak for a habit
     */
    private static function calculateStreak($habit, $requiredDays)
    {
        $logs = $habit->logs()->orderBy('date', 'desc')->get();
        if ($logs->isEmpty()) return false;
        
        $dates = $logs->groupBy('date');
        $sortedDates = $dates->keys()->sort()->reverse()->values()->toArray();
        
        if (empty($sortedDates)) return false;
        
        $streak = 0;
        $today = now('Asia/Dhaka')->toDateString();
        $checkDate = $today;
        
        // Start from today and work backwards
        $currentDate = $today;
        
        for ($i = 0; $i < $requiredDays; $i++) {
            // Check if we have logs for this date
            if (!in_array($currentDate, $sortedDates)) {
                // Check if we can skip today if there's no log yet
                if ($i == 0 && $currentDate == $today) {
                    // Allow starting from yesterday if today hasn't been logged yet
                    $currentDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
                    continue;
                } else {
                    return false;
                }
            }
            
            $dateLogs = $dates[$currentDate];
            $totalDuration = $dateLogs->sum('duration');
            
            if ($totalDuration >= $habit->getDailyTargetSeconds()) {
                $streak++;
                if ($streak >= $requiredDays) {
                    return true;
                }
            } else {
                return false;
            }
            
            // Move to previous day
            $currentDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
        }
        
        return $streak >= $requiredDays;
    }

    /**
     * Check first log badge
     */
    private static function checkFirstLogBadge(User $user, Badge $badge)
    {
        $userHabits = habits::getHabitsByUser($user->id);
        
        foreach ($userHabits as $habit) {
            if ($habit->logs()->count() > 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Award a badge to a user
     */
    private static function awardBadge(User $user, Badge $badge)
    {
        UserBadge::create([
            'id' => Str::random(12),
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'earned_at' => now()
        ]);
    }

    /**
     * Get user's total hours across all habits
     */
    public static function getUserTotalHours($userId)
    {
        $user = User::find($userId);
        if (!$user) return 0;
        
        $userHabits = habits::getHabitsByUser($userId);
        $totalHours = 0;
        
        foreach ($userHabits as $habit) {
            $totalHours += habits::getTotalHours($habit->id);
        }
        
        return $totalHours;
    }
}

