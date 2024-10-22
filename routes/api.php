<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\GameController;


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('auth.user');
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('games', GameController::class);
    Route::get('standings', [TeamController::class, 'standings'])->name('teams.standings');

});
