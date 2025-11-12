<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'icon',
        'badge_type',
        'requirement_value',
        'color',
        'sort_order'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges', 'badge_id', 'user_id')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class, 'badge_id');
    }
}
