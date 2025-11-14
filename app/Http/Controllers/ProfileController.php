<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\habits;
use App\Models\Blog;
use App\Models\habits_logs;
use App\Services\BadgeService;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user profile
     */
    public function show()
    {
        $user = auth()->user();
        
        // Load relationships
        $user->load(['habits', 'badges', 'blogPosts']);
        
        // Calculate statistics
        $totalHours = $user->getTotalHours();
        $totalHabits = $user->getTotalHabits();
        $totalBadges = $user->badges()->count();
        $totalBlogPosts = $user->blogPosts()->count();
        $publishedBlogPosts = $user->blogPosts()->where('status', 'published')->count();
        $totalBlogViews = $user->blogPosts()->sum('views');
        
        // Get user's rank on leaderboard
        $allUsers = User::all()->map(function ($u) {
            return [
                'id' => $u->id,
                'total_hours' => $u->getTotalHours(),
            ];
        })->filter(function ($u) {
            return $u['total_hours'] > 0;
        })->sortByDesc('total_hours')->values();
        
        $userRank = $allUsers->search(function ($u) use ($user) {
            return $u['id'] === $user->id;
        });
        $userRank = $userRank !== false ? $userRank + 1 : null;
        
        // Get recent habits
        $recentHabits = $user->habits()->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get recent blog posts
        $recentBlogPosts = $user->blogPosts()->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get recent activity (last 10 log entries)
        $recentActivity = habits_logs::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                $habit = habits::find($log->habit_id);
                return [
                    'id' => $log->id,
                    'habit' => $habit->name ?? 'Unknown',
                    'duration' => round(($log->duration ?? 0) / 3600, 2),
                    'date' => $log->date,
                    'created_at' => $log->created_at,
                ];
            });
        
        // Get earned badges
        $earnedBadges = $user->badges()->orderBy('sort_order')->get();
        
        return view('profile.show', [
            'user' => $user,
            'totalHours' => $totalHours,
            'totalHabits' => $totalHabits,
            'totalBadges' => $totalBadges,
            'totalBlogPosts' => $totalBlogPosts,
            'publishedBlogPosts' => $publishedBlogPosts,
            'totalBlogViews' => $totalBlogViews,
            'userRank' => $userRank,
            'recentHabits' => $recentHabits,
            'recentBlogPosts' => $recentBlogPosts,
            'recentActivity' => $recentActivity,
            'earnedBadges' => $earnedBadges,
        ]);
    }
}
