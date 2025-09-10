<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;

Route::middleware(['api.key'])->group(function () {
    Route::prefix('v1/customer')->group(function () {
        Route::post('login', [CustomerAuthController::class, 'login']);
        Route::post('register', [CustomerAuthController::class, 'register']);

        Route::middleware('auth:customer')->group(function () {
            Route::post('verify-otp', [CustomerAuthController::class, 'verifyOtp']);
            Route::get('profile', [CustomerAuthController::class, 'profile']);
            Route::post('logout', [CustomerAuthController::class, 'logout']);
        });
    });
});
