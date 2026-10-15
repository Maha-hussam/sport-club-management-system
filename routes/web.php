<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TrainerDashboardController;
use App\Http\Controllers\PlayerDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::middleware(['auth', 'role:trainer'])->group(function () {
    Route::get('/trainer/dashboard', [TrainerDashboardController::class, 'index'])
        ->name('trainer.dashboard');

    Route::resource('/trainer/players', PlayerController::class)
        ->parameters(['players' => 'player'])
        ->except(['show'])
        ->names('trainer.players');
});

Route::middleware(['auth', 'role:player'])->group(function () {
    Route::get('/player/dashboard', [PlayerDashboardController::class, 'index'])
        ->name('player.dashboard');
});

require __DIR__ . '/auth.php';
