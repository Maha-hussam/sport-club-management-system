<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TrainerDashboardController;
use App\Http\Controllers\PlayerDashboardController;
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerProfileImageController;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::middleware(['auth', 'role:trainer'])->group(function () {

    Route::get('/trainer/dashboard', [TrainerDashboardController::class, 'index'])
        ->name('trainer.dashboard');

    Route::resource('/trainer/dashboard/players', PlayerController::class)
        ->parameters(['players' => 'player'])
        ->except(['show'])
        ->names('trainer.players');

    Route::resource('/trainer/dashboard/matches', MatchController::class)
        ->parameters(['matches' => 'gameMatch'])
        ->names('trainer.matches');

    Route::put(
        '/trainer/dashboard/matches/{gameMatch}/players',
        [MatchController::class, 'updatePlayers']
    )->name('trainer.matches.players.update');
});

Route::middleware(['auth', 'role:player'])->group(function () {

    Route::get('/player/dashboard', [PlayerDashboardController::class, 'index'])
        ->name('player.dashboard');

    Route::get('/player/dashboard/profile', [PlayerDashboardController::class, 'profile'])
        ->name('player.profile');

    Route::get('/player/dashboard/matches', [PlayerDashboardController::class, 'matches'])
        ->name('player.matches');

    Route::get('/player/dashboard/matches/{matchId}', [
        PlayerDashboardController::class,
        'showMatch',
    ])->whereNumber('matchId')->name('player.matches.show');

    Route::post('/player/dashboard/profile/image', [PlayerProfileImageController::class, 'store'])
        ->name('player.profile.image.store');

    Route::delete('/player/dashboard/profile/image', [PlayerProfileImageController::class, 'destroy'])
        ->name('player.profile.image.destroy');

});

require __DIR__ . '/auth.php';