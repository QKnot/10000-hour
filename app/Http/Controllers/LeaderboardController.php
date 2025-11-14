<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the leaderboard ranking users by total hours
     */
    public function index(Request $request)
    {
        // Optimize: Use raw query to get total hours per user directly from habits_logs
        $usersWithHours = \DB::table('habits_logs')
            ->select('user_id', \DB::raw('SUM(COALESCE(duration, 0)) as total_seconds'))
            ->groupBy('user_id')
            ->havingRaw('SUM(COALESCE(duration, 0)) > 0')
            ->get()
            ->keyBy('user_id');

        // Get users and calculate their stats
        $users = User::all()->map(function ($user) use ($usersWithHours) {
            $userHours = $usersWithHours->get($user->id);
            $totalSeconds = $userHours ? $userHours->total_seconds : 0;
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'total_hours' => round($totalSeconds / 3600, 2),
                'total_habits' => $user->getTotalHabits(),
            ];
        })->filter(function ($user) {
            // Only show users with at least some hours logged
            return $user['total_hours'] > 0;
        })->sortByDesc('total_hours')->values();

        // Get current user's rank
        $currentUser = auth()->user();
        $currentUserHours = $currentUser->getTotalHours();
        $currentUserRank = $users->search(function ($user) use ($currentUser) {
            return $user['id'] === $currentUser->id;
        });
        
        // If user not found in leaderboard (0 hours), set rank to null
        $currentUserRank = $currentUserRank !== false ? $currentUserRank + 1 : null;

        // Paginate results (15 per page)
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $total = $users->count();
        $items = $users->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Calculate summary statistics
        $totalHoursAll = $users->sum('total_hours');
        $avgHoursAll = $users->count() > 0 ? $users->avg('total_hours') : 0;

        return view('leaderboard.index', [
            'users' => $paginatedUsers,
            'currentUser' => $currentUser,
            'currentUserHours' => $currentUserHours,
            'currentUserRank' => $currentUserRank,
            'totalUsers' => $total,
            'totalHoursAll' => $totalHoursAll,
            'avgHoursAll' => $avgHoursAll,
        ]);
    }
}
