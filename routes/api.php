<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;

Route::middleware(['api.key'])->group(function () {
    Route::prefix('v1/customer')->group(function () {
        Route::any('login', [CustomerAuthController::class, 'login'])->name('login');
        Route::post('register', [CustomerAuthController::class, 'register']);

        Route::middleware(['jwt.customer'])->group(function () { // ✅ Apna naya middleware use karo
            Route::post('verifyotp', [CustomerAuthController::class, 'verifyOtp']);
            Route::post('logout', [CustomerAuthController::class, 'logout']);
            Route::post('profile', [CustomerAuthController::class, 'profile']);
        });
    });
});
