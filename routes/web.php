<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HabitsController;
use App\Http\Controllers\BadgeController;
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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return View::make('welcome');
})->name('home');

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
