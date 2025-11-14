<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public $timestamps = false;
    protected $primaryKey = "id";
    protected $keyType = "string";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class, 'user_id');
    }

    public function hasBadge($badgeId)
    {
        return $this->badges()->where('badge_id', $badgeId)->exists();
    }

    public function habits()
    {
        return $this->hasMany(habits::class, 'user_id');
    }

    public function blogPosts()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Get total hours across all habits for this user
     */
    public function getTotalHours(): float
    {
        // Optimize: directly sum from habits_logs table
        $totalSeconds = \App\Models\habits_logs::where('user_id', $this->id)
            ->sum('duration') ?? 0;
        
        return round($totalSeconds / 3600, 2);
    }

    /**
     * Get total number of habits for this user
     */
    public function getTotalHabits(): int
    {
        return $this->habits()->count();
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
