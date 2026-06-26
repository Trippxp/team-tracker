<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public auth routes
Route::middleware('guest')->group(function () {
    Route::get('/',      [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',[AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activities
    Route::resource('activities', ActivityController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
