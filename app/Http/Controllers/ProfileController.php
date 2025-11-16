<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\habits;
use App\Models\Blog;
use App\Models\habits_logs;
use App\Models\BlogComment;
use App\Models\BlogLike;
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

    /**
     * Show account deletion confirmation page
     */
    public function deleteConfirm()
    {
        $user = auth()->user();
        
        // Get user's statistics for warning message
        $totalHours = $user->getTotalHours();
        $totalHabits = $user->getTotalHabits();
        $totalBlogPosts = $user->blogPosts()->count();
        
        return view('profile.delete-confirm', [
            'user' => $user,
            'totalHours' => $totalHours,
            'totalHabits' => $totalHabits,
            'totalBlogPosts' => $totalBlogPosts,
        ]);
    }

    /**
     * Permanently delete user account and all associated data
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|string|in:DELETE',
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        // Verify password
        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Account deletion cancelled.']);
        }

        try {
            // Delete user's habits and logs
            $user->habits()->each(function ($habit) {
                $habit->logs()->delete();
                $habit->delete();
            });

            // Delete user's blog posts and related data
            $user->blogPosts()->each(function ($blog) {
                // Delete comments on this blog
                BlogComment::where('blog_id', $blog->id)->delete();
                // Delete likes on this blog
                BlogLike::where('blog_id', $blog->id)->delete();
                $blog->delete();
            });

            // Delete user's comments on other blogs
            BlogComment::where('user_id', $user->id)->delete();

            // Delete user's likes on other blogs
            BlogLike::where('user_id', $user->id)->delete();

            // Delete user's badges
            $user->badges()->detach();

            // Logout the user
            auth()->logout();

            // Delete the user account
            $user->delete();

            // Clear session and redirect
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', '✅ Account deleted successfully! 👋 We\'re sorry to see you go');

        } catch (\Exception $e) {
            \Log::error('Account deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while deleting your account. Please try again or contact support.']);
        }
    }
}
