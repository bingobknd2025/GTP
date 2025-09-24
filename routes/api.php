<?php

use App\Http\Controllers\Api\V1\CustomerAuthController;
use App\Http\Controllers\Api\V1\CustomerDataController;
use App\Http\Controllers\Api\V1\FranchiseAuthController;
use App\Http\Controllers\Api\V1\FranchiseDataController;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('get-country', [CustomerAuthController::class, 'getCountry']);
        Route::post('get-franchises', [CustomerDataController::class, 'getFranchises']);

        Route::prefix('customer')->group(function () {
            Route::post('register', [CustomerAuthController::class, 'register']);
            Route::post('login', [CustomerAuthController::class, 'login']);

            Route::middleware('auth:customer')->group(function () {
                // OTP API
                Route::post('verify-otp', [CustomerAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [CustomerAuthController::class, 'resendOtp']);

                // Online KYC API
                Route::post('kyc/status', [CustomerAuthController::class, 'kycstatus']);
                Route::post('kyc/access-token', [CustomerAuthController::class, 'getAccessToken']);
                Route::post('kyc/webhook', [CustomerAuthController::class, 'handleWebhook']);

                // Offline KYC APIs
                Route::post('kyc/get-status', [CustomerAuthController::class, 'getKycStatus']);
                Route::post('kyc/submit-identity', [CustomerAuthController::class, 'submitIdentity']);
                Route::post('kyc/submit-residential', [CustomerAuthController::class, 'submitResidentialAddress']);
                Route::post('kyc/submit-address', [CustomerAuthController::class, 'submitAddressProof']);
                Route::post('kyc/submit-mobile', [CustomerAuthController::class, 'submitMobile']);
                Route::post('kyc/submit-final', [CustomerAuthController::class, 'finalSubmit']);

                // Order APIs
                Route::post('order-create', [CustomerDataController::class, 'createOrder']);
                Route::post('orders-list', [CustomerDataController::class, 'listOrders']);
                Route::post('order-details', [CustomerDataController::class, 'orderDetails']);

                // Profile APIs
                Route::post('profile', [CustomerDataController::class, 'getProfile']);
                Route::post('profile-update', [CustomerDataController::class, 'updateProfile']);
                Route::post('change-password', [CustomerDataController::class, 'changePassword']);



                Route::post('logout', [CustomerAuthController::class, 'logout']);
            });
        });

        // Franchise APIs
        Route::prefix('franchise')->group(function () {
            Route::post('register', [FranchiseAuthController::class, 'register']);
            Route::post('login', [FranchiseAuthController::class, 'login']);

            Route::middleware('auth:franchise')->group(function () {
                // OTP API
                Route::post('verify-otp', [FranchiseAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [FranchiseAuthController::class, 'resendOtp']);
                Route::post('logout', [FranchiseAuthController::class, 'logout']);

                // Customer Management APIs
                Route::post('customers-list', [FranchiseDataController::class, 'getCustomers']);

                // Order Management APIs
                Route::post('orders-all', [FranchiseDataController::class, 'listAllOrders']);
                Route::post('order-details', [FranchiseDataController::class, 'orderDetails']);

                Route::get('profile', function () {
                    return auth('franchise')->user();
                });
            });
        });
    });
});
