<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;



Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('auth.user');

});