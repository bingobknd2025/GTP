<?php

use App\Http\Controllers\Api\V1\CustomerAuthController;
use App\Http\Controllers\Api\V1\FranchiseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('api.key')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('get-country', [CustomerAuthController::class, 'getCountry']);

        Route::prefix('customer')->group(function () {
            Route::post('register', [CustomerAuthController::class, 'register']);
            Route::post('login', [CustomerAuthController::class, 'login']);

            Route::middleware('auth:customer')->group(function () {
                // OTP API
                Route::post('verify-otp', [CustomerAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [CustomerAuthController::class, 'resendOtp']);

                // Kyc API
                Route::post('kyc/stauts', [CustomerAuthController::class, 'kycstatus']);
                Route::post('kyc/access-token', [CustomerAuthController::class, 'getAccessToken']);
                Route::post('kyc/webhook', [CustomerAuthController::class, 'handleWebhook']);

                Route::post('kyc/submit-identity', [CustomerAuthController::class, 'submitIdentity']);
                Route::post('kyc/submit-residential', [CustomerAuthController::class, 'submitResidentialAddress']);
                Route::post('kyc/submit-address', [CustomerAuthController::class, 'submitAddressProof']);
                Route::post('kyc/submit-mobile', [CustomerAuthController::class, 'submitMobile']);
                Route::post('kyc/submit-final', [CustomerAuthController::class, 'finalSubmit']);




                Route::get('profile', function () {
                    return auth('customer')->user();
                });
                Route::post('logout', [CustomerAuthController::class, 'logout']);
            });
        });

        // Franchise APIs
        Route::prefix('franchise')->group(function () {
            Route::post('register', [FranchiseAuthController::class, 'register']);
            Route::post('login', [FranchiseAuthController::class, 'login']);

            Route::middleware('auth:franchise')->group(function () {
                Route::post('verify-otp', [FranchiseAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [FranchiseAuthController::class, 'resendOtp']);
                Route::post('logout', [FranchiseAuthController::class, 'logout']);
                Route::get('profile', function () {
                    return auth('franchise')->user();
                });
            });
        });
    });
});
