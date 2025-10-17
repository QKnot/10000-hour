<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HabitsController;
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
    return redirect()->route('login');
})->name('home');

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
    Route::post('/store', 'store')->name('habits.store');
    Route::get('/update/{id}', 'updatepage')->name('habits.updatepage');
    Route::put('/{id}', 'update')->name('habits.update');
    Route::delete('/{id}', 'destroy')->name('habits.destroy');
});
