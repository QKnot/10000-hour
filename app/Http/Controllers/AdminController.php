<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\habits;
use App\Models\habits_logs;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display admin dashboard with user and habit statistics
     */
    public function dashboard()
    {
        // Total users count
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalMembers = User::where('role', 'member')->count();

        // Total habits count
        $totalHabits = habits::count();
        
        // Habits per user statistics
        $habitsPerUser = DB::table('habits')
            ->select('user_id', DB::raw('COUNT(*) as habit_count'))
            ->groupBy('user_id')
            ->get();
        
        $avgHabitsPerUser = $habitsPerUser->count() > 0 
            ? round($habitsPerUser->avg('habit_count'), 2) 
            : 0;
        
        $maxHabitsPerUser = $habitsPerUser->max('habit_count') ?? 0;
        $minHabitsPerUser = $habitsPerUser->min('habit_count') ?? 0;

        // Total hours logged across all users
        $totalHoursLogged = round((habits_logs::sum('duration') ?? 0) / 3600, 2);
        
        // Blog statistics
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $draftBlogs = Blog::where('status', 'draft')->count();
        $totalBlogViews = Blog::sum('views');
        
        // Users with their habit counts and total hours
        $usersWithStats = User::with('habits')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'habit_count' => $user->getTotalHabits(),
                'total_hours' => $user->getTotalHours(),
                'created_at' => $user->habits()->min('created_at'),
            ];
        })->sortByDesc('total_hours')->values();

        // Habits statistics
        $habitsWithStats = habits::with('logs')->get()->map(function ($habit) {
            return [
                'id' => $habit->id,
                'name' => $habit->name,
                'user_id' => $habit->user_id,
                'user' => User::find($habit->user_id)->username ?? 'Unknown',
                'total_hours' => habits::getTotalHours($habit->id),
                'log_count' => $habit->logs()->count(),
            ];
        })->sortByDesc('total_hours')->values();

        // Recent activity (last 10 log entries)
        $recentLogs = habits_logs::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                $user = User::find($log->user_id);
                $habit = habits::find($log->habit_id);
                return [
                    'id' => $log->id,
                    'user' => $user->username ?? 'Unknown',
                    'habit' => $habit->name ?? 'Unknown',
                    'duration' => round(($log->duration ?? 0) / 3600, 2),
                    'date' => $log->date,
                    'created_at' => $log->created_at,
                ];
            });

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
            'totalMembers' => $totalMembers,
            'totalHabits' => $totalHabits,
            'avgHabitsPerUser' => $avgHabitsPerUser,
            'maxHabitsPerUser' => $maxHabitsPerUser,
            'minHabitsPerUser' => $minHabitsPerUser,
            'totalHoursLogged' => $totalHoursLogged,
            'totalBlogs' => $totalBlogs,
            'publishedBlogs' => $publishedBlogs,
            'draftBlogs' => $draftBlogs,
            'totalBlogViews' => $totalBlogViews,
            'usersWithStats' => $usersWithStats,
            'habitsWithStats' => $habitsWithStats,
            'recentLogs' => $recentLogs,
        ]);
    }
}
