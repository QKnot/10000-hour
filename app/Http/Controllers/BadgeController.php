<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;
use App\Services\BadgeService;

class BadgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's badges
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get all badges ordered by sort_order
        $allBadges = Badge::orderBy('sort_order')->get();
        
        // Get user badges - using relationship which includes pivot data
        $userBadgesCollection = $user->badges;
        
        // Create a collection of user badges in sort_order, with pivot data
        $userBadges = $allBadges->filter(function($badge) use ($userBadgesCollection) {
            return $userBadgesCollection->contains('id', $badge->id);
        })->map(function($badge) use ($userBadgesCollection) {
            // Find the badge in user's badges to get pivot data
            $userBadge = $userBadgesCollection->firstWhere('id', $badge->id);
            if ($userBadge && $userBadge->pivot) {
                $badge->pivot = $userBadge->pivot;
            }
            return $badge;
        });
        
        // Get user's total hours
        $totalHours = BadgeService::getUserTotalHours($user->id);
        
        return view('badges.index', compact('userBadges', 'allBadges', 'totalHours'));
    }
}
