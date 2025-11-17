<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HabitsController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page - Blog listing
Route::get('/', [BlogController::class, 'index'])->name('home');

// Public blog routes
Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create')->middleware('auth');
Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store')->middleware('auth');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('blog.edit')->middleware('auth');
Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update')->middleware('auth');
Route::delete('/blog/{id}', [BlogController::class, 'destroy'])->name('blog.destroy')->middleware('auth');

// Like/Dislike routes
Route::post('/blog/{id}/like', [LikeController::class, 'toggle'])->name('blog.like')->middleware('auth');

// Comment routes
Route::post('/blog/{id}/comment', [CommentController::class, 'store'])->name('blog.comment.store')->middleware('auth');
Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy')->middleware('auth');

Route::get('/dashboard', [HabitsController::class, 'index'])->name('habits.dashboard');

Route::get('/motivation', function () {
    return "Andrej Karpathy believes that mastering any skill requires consistent practice over time — the idea behind the 10,000 Hour app is to help users track and focus on deliberate practice to achieve expertise.";
});
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('store', 'store')->name('store');
    Route::get('login', 'login')->name('login');
    Route::post('authenticate', 'authenticate')->name('authenticate');
    Route::get('dashboard', 'dashboard')->name('dashboard')->middleware('auth');
    Route::post('logout', 'logout')->name('logout');
});

Route::prefix('/habits')->middleware('auth')->controller(HabitsController::class)->group(function () {
    Route::get('/{id}', 'index')->name('habits.index');
    Route::get('/{id}/analisis', 'analisis')->name('habits.analisis');
    Route::post('/checkin', 'checkin')->name('habits.checkin');
    Route::post('/log-time', 'logTime')->name('habits.logtime');
    Route::post('/store', 'store')->name('habits.store');
    Route::get('/update/{id}', 'updatepage')->name('habits.updatepage');
    Route::put('/{id}', 'update')->name('habits.update');
    Route::delete('/{id}', 'destroy')->name('habits.destroy');
});

Route::prefix('/badges')->middleware('auth')->controller(BadgeController::class)->group(function () {
    Route::get('/', 'index')->name('badges.index');
});

Route::prefix('/leaderboard')->middleware('auth')->controller(LeaderboardController::class)->group(function () {
    Route::get('/', 'index')->name('leaderboard.index');
});

Route::prefix('/profile')->middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'show')->name('profile.show');
    Route::get('/delete', 'deleteConfirm')->name('profile.delete.confirm');
    Route::delete('/delete', 'deleteAccount')->name('profile.delete');
});

Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User management routes
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/make-admin', [AdminController::class, 'makeAdmin'])->name('admin.users.make-admin');
    Route::post('/users/{user}/remove-admin', [AdminController::class, 'removeAdmin'])->name('admin.users.remove-admin');
    
    // Blog management routes
    Route::prefix('/blogs')->controller(BlogController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('admin.blogs.index');
        Route::get('/create', 'create')->name('admin.blogs.create');
        Route::post('/store', 'store')->name('admin.blogs.store');
        Route::get('/{id}/edit', 'edit')->name('admin.blogs.edit');
        Route::put('/{id}', 'update')->name('admin.blogs.update');
        Route::delete('/{id}', 'destroy')->name('admin.blogs.destroy');
        
        // Approval routes
        Route::post('/{id}/approve', 'approve')->name('admin.blogs.approve');
        Route::post('/{id}/reject', 'reject')->name('admin.blogs.reject');
    });
});

// API route for analysis data
Route::middleware('auth')->get('/api/getdata/{id}', function (Request $req, $id) {
    $habit = \App\Models\habits::find($id);
    
    if (!$habit || $habit->user_id !== auth()->id()) {
        return response()->json(['code' => 403, 'msg' => "Unauthorized"], 403);
    }
    
    // Get duration by date (in hours)
    $durationByDate = \App\Models\habits::getDurationByDate($id);
    $dates = array_keys($durationByDate);
    $hours = array_values($durationByDate);
    
    // Get success/failure stats
    $habitinfo = \App\Models\habits::getSuccessFailureStats($id);
    
    // Get additional statistics
    $totalHours = \App\Models\habits::getTotalHours($id);
    $averageHours = \App\Models\habits::getAverageHoursPerDay($id);
    $currentStreak = \App\Models\habits::getCurrentStreak($id);
    $bestDay = \App\Models\habits::getBestDay($id);
    $weeklyStats = \App\Models\habits::getWeeklyStats($id);
    $monthlyStats = \App\Models\habits::getMonthlyStats($id);
    $goalHours = $habit->getGoalHours();
    $goalProgress = $habit->goalProgressPercentage();
    $goalReachedAt = optional($habit->goal_reached_at)->toDateTimeString();
    
    $result = [
        "logs" => [
            "index" => $dates,
            "value" => $hours
        ],
        "info" => $habitinfo,
        "statistics" => [
            "total_hours" => $totalHours,
            "average_hours_per_day" => $averageHours,
            "current_streak" => $currentStreak,
            "best_day" => $bestDay,
            "weekly_stats" => $weeklyStats,
            "monthly_stats" => $monthlyStats,
            "goal_hours" => $goalHours,
            "goal_progress" => $goalProgress,
            "goal_reached" => $habit->hasReachedGoal(),
            "goal_reached_at" => $goalReachedAt,
        ]
    ];
    
    return response()->json(['code' => 200, 'msg' => "Success", "result" => $result]);
})->name('api.getdata');
