<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class habits extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $timestamps = true;

    protected $hidden = [
        'user_id'
    ];

    protected $fillable = [
        "id",
        "name",
        "description",
        "daily_count",
        "user_id",
        "goal_hours",
        "goal_reached_at",
    ];

    protected $casts = [
        'goal_reached_at' => 'datetime',
    ];

    public const DEFAULT_GOAL_HOURS = 10000;

    public function logs()
    {
        return $this->hasMany(habits_logs::class, 'habit_id');
    }

    public static function isDuplicate($name, $userId): bool
    {
        return self::where('name', $name)->where('user_id', $userId)->exists();
    }

    public static function getHabitsByUser($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function findHabitsByUser($habitId, $userId): habits
    {
        return self::where('id', $habitId)
            ->where('user_id', $userId)
            ->first();
    }

    public static function countLogsByDate($id) {
        $habit = self::with('logs')->find($id);
        $hitunganLogs = [];

        foreach ($habit->logs as $log) {
            $tanggal = $log->date;

            if(!isset($hitunganLogs[$tanggal])) {
                $hitunganLogs[$tanggal] = 1;
            } else {
                $hitunganLogs[$tanggal]++;
            }
        }

        return $hitunganLogs;
    }

    public static function recordHabit($id) {
        // Use the new duration-based method
        return self::getSuccessFailureStats($id);
    }

    public function getGoalHours(): int
    {
        return $this->goal_hours ?? self::DEFAULT_GOAL_HOURS;
    }

    public function hasReachedGoal(): bool
    {
        return $this->goal_reached_at !== null || self::getTotalHours($this->id) >= $this->getGoalHours();
    }

    public function goalProgressPercentage(): float
    {
        $goalHours = max(1, $this->getGoalHours());
        $totalHours = self::getTotalHours($this->id);
        return round(min(100, ($totalHours / $goalHours) * 100), 2);
    }

    public function markGoalReachedIfNeeded(): bool
    {
        if ($this->hasReachedGoal() && $this->goal_reached_at === null) {
            $this->goal_reached_at = now('Asia/Dhaka');
            $this->save();
            return true;
        }

        return false;
    }

    // Get total duration in seconds for today
    public static function getTodayDuration($id) {
        $habit = self::find($id);
        if (!$habit) return 0;
        
        return $habit->logs()
            ->whereDate('date', now('Asia/Dhaka'))
            ->sum('duration');
    }

    // Get total duration in seconds for all time
    public static function getTotalDuration($id) {
        $habit = self::find($id);
        if (!$habit) return 0;
        
        return $habit->logs()->sum('duration');
    }

    // Convert daily_count from hours to seconds for comparison
    public function getDailyTargetSeconds() {
        return $this->daily_count * 3600; // Convert hours to seconds
    }

    // Get today's progress percentage
    public function getTodayProgress() {
        $todaySeconds = self::getTodayDuration($this->id);
        $targetSeconds = $this->getDailyTargetSeconds();
        
        if ($targetSeconds == 0) return 0;
        return min(100, round(($todaySeconds / $targetSeconds) * 100, 2));
    }

    // Format seconds to hours:minutes:seconds
    public static function formatDuration($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $secs);
    }

    // Get total hours (for 10,000 hour goal)
    public static function getTotalHours($id) {
        $totalSeconds = self::getTotalDuration($id);
        return round($totalSeconds / 3600, 2);
    }

    // Get duration by date (returns hours) - sorted by date
    public static function getDurationByDate($id) {
        $habit = self::find($id);
        if (!$habit) return [];
        
        $logs = $habit->logs()->orderBy('date', 'asc')->get();
        $durationByDate = [];
        
        foreach ($logs as $log) {
            $date = $log->date;
            if (!isset($durationByDate[$date])) {
                $durationByDate[$date] = 0;
            }
            // Handle null duration (for old logs before duration was added)
            $duration = $log->duration ?? 0;
            $durationByDate[$date] += (int)$duration;
        }
        
        // Convert to hours
        foreach ($durationByDate as $date => $seconds) {
            $durationByDate[$date] = round($seconds / 3600, 2);
        }
        
        // Sort by date (chronological order)
        ksort($durationByDate);
        
        return $durationByDate;
    }

    // Get weekly statistics
    public static function getWeeklyStats($id) {
        $habit = self::find($id);
        if (!$habit) return [];
        
        $logs = $habit->logs()->orderBy('date', 'asc')->get(); // Order by asc for chronological order
        $weeklyData = [];
        
        foreach ($logs as $log) {
            $date = \Carbon\Carbon::parse($log->date);
            // Carbon's dayOfWeek: 0 = Sunday, 6 = Saturday
            // Calculate days from Sunday (if Sunday, daysFromSunday = 0)
            $dayOfWeek = $date->dayOfWeek;
            $weekStartDate = $date->copy()->subDays($dayOfWeek);
            $weekStart = $weekStartDate->format('Y-m-d');
            $weekEnd = $weekStartDate->copy()->addDays(6);
            $weekLabel = $weekStartDate->format('M d') . ' - ' . $weekEnd->format('M d, Y');
            
            if (!isset($weeklyData[$weekStart])) {
                $weeklyData[$weekStart] = [
                    'label' => $weekLabel,
                    'duration' => 0,
                    'days' => 0,
                    'dates' => []
                ];
            }
            $weeklyData[$weekStart]['duration'] += (int)($log->duration ?? 0);
            if (!isset($weeklyData[$weekStart]['dates'][$log->date])) {
                $weeklyData[$weekStart]['dates'][$log->date] = true;
                $weeklyData[$weekStart]['days']++;
            }
        }
        
        // Convert to hours and sort by date
        foreach ($weeklyData as $week => $data) {
            $weeklyData[$week]['duration'] = round($data['duration'] / 3600, 2);
            unset($weeklyData[$week]['dates']);
        }
        
        // Sort by week start date
        ksort($weeklyData);
        
        return array_values($weeklyData);
    }

    // Get monthly statistics
    public static function getMonthlyStats($id) {
        $habit = self::find($id);
        if (!$habit) return [];
        
        $logs = $habit->logs()->orderBy('date', 'asc')->get(); // Order by asc for chronological order
        $monthlyData = [];
        
        foreach ($logs as $log) {
            $date = \Carbon\Carbon::parse($log->date);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('F Y');
            
            if (!isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey] = [
                    'label' => $monthLabel,
                    'duration' => 0,
                    'days' => 0,
                    'dates' => []
                ];
            }
            $monthlyData[$monthKey]['duration'] += (int)($log->duration ?? 0);
            if (!isset($monthlyData[$monthKey]['dates'][$log->date])) {
                $monthlyData[$monthKey]['dates'][$log->date] = true;
                $monthlyData[$monthKey]['days']++;
            }
        }
        
        // Convert to hours
        foreach ($monthlyData as $month => $data) {
            $monthlyData[$month]['duration'] = round($data['duration'] / 3600, 2);
            unset($monthlyData[$month]['dates']);
        }
        
        // Sort by month key (chronological order)
        ksort($monthlyData);
        
        return array_values($monthlyData);
    }

    // Get success/failure statistics based on duration
    public static function getSuccessFailureStats($id) {
        $habit = self::find($id);
        if (!$habit) return ['berhasil' => 0, 'gagal' => 0];
        
        $logs = $habit->logs()->get();
        $dates = $logs->groupBy('date');
        
        $berhasil = 0;
        $gagal = 0;
        $targetSeconds = $habit->getDailyTargetSeconds();
        
        foreach ($dates as $date => $dateLogs) {
            $totalDuration = $dateLogs->sum(function($log) {
                return (int)($log->duration ?? 0);
            });
            if ($totalDuration >= $targetSeconds) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }
        
        return ['berhasil' => $berhasil, 'gagal' => $gagal];
    }

    // Get average hours per day
    public static function getAverageHoursPerDay($id) {
        $habit = self::find($id);
        if (!$habit) return 0;
        
        $logs = $habit->logs()->get();
        $dates = $logs->groupBy('date');
        $totalDays = count($dates);
        
        if ($totalDays === 0) return 0;
        
        $totalSeconds = self::getTotalDuration($id);
        $averageSeconds = $totalSeconds / $totalDays;
        
        return round($averageSeconds / 3600, 2);
    }

    // Get current streak
    public static function getCurrentStreak($id) {
        $habit = self::find($id);
        if (!$habit) return 0;
        
        $logs = $habit->logs()->orderBy('date', 'desc')->get();
        if ($logs->isEmpty()) return 0;
        
        $dates = $logs->groupBy('date');
        $sortedDates = $dates->keys()->sort()->reverse()->values();
        
        $streak = 0;
        $today = now('Asia/Dhaka')->toDateString();
        $checkDate = $today;
        $targetSeconds = $habit->getDailyTargetSeconds();
        
        foreach ($sortedDates as $date) {
            // Check if we can skip today if no log yet
            if ($streak === 0 && $date != $today && $checkDate == $today) {
                $checkDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
            }
            
            if ($date == $checkDate || $date == date('Y-m-d', strtotime($checkDate . ' -1 day'))) {
                $dateLogs = $dates[$date];
                $totalDuration = $dateLogs->sum(function($log) {
                    return (int)($log->duration ?? 0);
                });
                
                if ($totalDuration >= $targetSeconds) {
                    $streak++;
                    if ($date != $checkDate) {
                        $checkDate = $date;
                    }
                    $checkDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
                } else {
                    break;
                }
            } else {
                break;
            }
        }
        
        return $streak;
    }

    // Get best day (most hours logged)
    public static function getBestDay($id) {
        $durationByDate = self::getDurationByDate($id);
        if (empty($durationByDate)) return null;
        
        $bestDate = array_keys($durationByDate, max($durationByDate))[0];
        $bestHours = max($durationByDate);
        
        return [
            'date' => $bestDate,
            'hours' => $bestHours,
            'formatted_date' => \Carbon\Carbon::parse($bestDate)->format('M d, Y')
        ];
    }
}
