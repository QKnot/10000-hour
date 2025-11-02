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
        "id", "name", "description", "daily_count", "user_id"
    ];

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
        $habit = self::findOrFail($id);
        $habitLogs = self::countLogsByDate($id);
        $berhasil = 0;
        $gagal = 0;
        foreach($habitLogs as $key => $value) {
            ($value >= $habit->daily_count) ? $berhasil++ : $gagal++;
        }

        return ["berhasil" => $berhasil, "gagal" => $gagal];
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
}
