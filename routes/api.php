<?php

use App\Http\Controllers\Api\V1\CustomerAuthController;
use App\Http\Controllers\Api\V1\FranchiseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::prefix('v1')->group(function () {

        // Customer APIs
        Route::prefix('customer')->group(function () {
            Route::post('register', [CustomerAuthController::class, 'register']);
            Route::post('login', [CustomerAuthController::class, 'login']);


            Route::middleware('auth:customer')->group(function () {
                // OTP
                Route::post('verify-otp', [CustomerAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [CustomerAuthController::class, 'resendOtp']);
                Route::post('logout', [CustomerAuthController::class, 'logout']);
                Route::get('profile', function () {
                    return auth('customer')->user();
                });
            });
        });

        // Franchise APIs
        Route::prefix('franchise')->group(function () {
            Route::post('register', [FranchiseAuthController::class, 'register']);
            Route::post('login', [FranchiseAuthController::class, 'login']);

            Route::middleware('auth:franchise')->group(function () {
                Route::get('dashboard', function () {
                    return auth('franchise')->user();
                });
            });
        });
    });
});
