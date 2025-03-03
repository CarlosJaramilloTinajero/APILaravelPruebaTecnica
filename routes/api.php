<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticacion
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
});

// Rutas para usuarios autenticados
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
});
