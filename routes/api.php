<?php

use App\Http\Controllers\Api\V1\CustomerAuthController;
use App\Http\Controllers\Api\V1\CustomerDataController;
use App\Http\Controllers\Api\V1\FranchiseAuthController;
use App\Http\Controllers\Api\V1\FranchiseDataController;
use App\Http\Controllers\HomeController;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('get-country', [CustomerAuthController::class, 'getCountry']);
        Route::post('get-franchises', [CustomerDataController::class, 'getFranchises']);
        Route::post('enquiry-store', [CustomerDataController::class, 'enquiryStore']);
        Route::post('/gold/fetch', [HomeController::class, 'getGoldprice'])->name('admin.gold.fetch');
        Route::post('/valid-franchise', [FranchiseDataController::class, 'getValidFranchises'])->name('valid.franchise');

        Route::prefix('customer')->group(function () {
            Route::post('register', [CustomerAuthController::class, 'register']);
            Route::post('login', [CustomerAuthController::class, 'login']);
            Route::post('forgot-password', [CustomerAuthController::class, 'forgotPassword']);
            Route::post('resend-forgot-password', [CustomerAuthController::class, 'resendForgotPasswordOtp']);
            Route::post('reset-password', [CustomerAuthController::class, 'resetPassword']);

            Route::middleware('auth:customer')->group(function () {
                // OTP API
                Route::post('verify-otp', [CustomerAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [CustomerAuthController::class, 'resendOtp']);

                Route::middleware('account.verified')->group(function () {
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
                    Route::post('kyc/send-firebase-otp', [CustomerAuthController::class, 'sendFirebaseOtp']);
                    Route::post('kyc/verify-firebase-otp', [CustomerAuthController::class, 'verifyFirebaseOtp']);
                    Route::post('kyc/submit-final', [CustomerAuthController::class, 'finalSubmit']);

                    Route::post('dashboard', [CustomerDataController::class, 'dashboard']);

                    // Order APIs
                    Route::post('order-create', [CustomerDataController::class, 'createOrder']);
                    Route::post('order-approve', [CustomerDataController::class, 'updateOrderApproval']);
                    Route::post('orders-list', [CustomerDataController::class, 'listOrders']);
                    Route::post('order-details', [CustomerDataController::class, 'orderDetails']);

                    // Payment APIs
                    Route::post('payments-list', [CustomerDataController::class, 'listPayments']);
                    Route::post('payment-details', [CustomerDataController::class, 'paymentDetail']);
                    Route::post('generate-invoice', [CustomerDataController::class, 'generateInvoice']);

                    // Withdrawal APIs
                    Route::post('withdrawal-methods', [CustomerDataController::class, 'withdrawMethods']);
                    Route::post('withdrawal-request', [CustomerDataController::class, 'requestWithdrawal']);
                    Route::post('withdrawal-history', [CustomerDataController::class, 'listWithdrawals']);

                    // Profile APIs
                    Route::post('profile', [CustomerDataController::class, 'getProfile']);
                    Route::post('profile-update', [CustomerDataController::class, 'updateProfile']);
                    Route::post('bank-details-update', [CustomerDataController::class, 'updateBankDetails']);
                    Route::post('change-password-otp', [CustomerAuthController::class, 'requestChangePasswordOtp']);
                    Route::post('change-password', [CustomerAuthController::class, 'changePassword']);

                    // Support Ticket APIs
                    Route::post('ticket-create', [CustomerDataController::class, 'createTicket']);
                    Route::post('tickets-list', [CustomerDataController::class, 'listTickets']);
                    // Route::post('ticket-details', [CustomerDataController::class, 'ticketDetails']);

                    Route::post('logout', [CustomerAuthController::class, 'logout']);
                    Route::post('check-token', [CustomerAuthController::class, 'checkTokenExpiry']);
                });
            });
        });

        // Franchise APIs
        Route::prefix('franchise')->group(function () {
            Route::post('register', [FranchiseAuthController::class, 'register']);
            Route::post('login', [FranchiseAuthController::class, 'login']);
            Route::post('forgot-password', [FranchiseAuthController::class, 'forgotPassword']);
            Route::post('resend-forgot-password', [FranchiseAuthController::class, 'resendForgotPasswordOtp']);
            Route::post('reset-password', [FranchiseAuthController::class, 'resetPassword']);

            Route::middleware('auth:franchise')->group(function () {

                Route::post('dashboard', [FranchiseDataController::class, 'dashboard']);
                Route::post('profile', [FranchiseDataController::class, 'profile']);
                Route::post('profile-update', [FranchiseDataController::class, 'updateProfile']);
                Route::post('bank-details-update', [FranchiseDataController::class, 'updateBankDetails']);
                Route::post('change-password-otp', [FranchiseAuthController::class, 'requestChangePasswordOtp']);
                Route::post('change-password', [FranchiseAuthController::class, 'changePassword']);
                // OTP API
                Route::post('verify-otp', [FranchiseAuthController::class, 'verifyOtp']);
                Route::post('resend-otp', [FranchiseAuthController::class, 'resendOtp']);
                Route::post('logout', [FranchiseAuthController::class, 'logout']);

                // KYC Management APIs
                Route::post('kyc-list', [FranchiseDataController::class, 'getKycList']);
                Route::post('kyc-details', [FranchiseDataController::class, 'kycDetails']);
                Route::post('kyc-update-status', [FranchiseDataController::class, 'updateKycStatus']);

                // Customer Management APIs
                Route::post('get-all-customers', [FranchiseDataController::class, 'getAllCustomers']);
                Route::post('customers-list', [FranchiseDataController::class, 'getCustomers']);

                // Order Management APIs
                Route::post('orders-all', [FranchiseDataController::class, 'listAllOrders']);
                Route::post('order-create', [FranchiseDataController::class, 'createOrder']);
                Route::post('order-update', [FranchiseDataController::class, 'updateOrder']);
                Route::post('order-confirm', [FranchiseDataController::class, 'confirmApproval']);
                Route::post('order-details', [FranchiseDataController::class, 'orderDetails']);

                // Payment APIs
                Route::post('payments-list', [FranchiseDataController::class, 'listPayments']);
                Route::post('payment-details', [FranchiseDataController::class, 'paymentDetail']);
                Route::post('update-payment-status', [FranchiseDataController::class, 'updatePaymentStatus']);
                Route::post('generate-invoice', [FranchiseDataController::class, 'generateInvoice']);


                Route::post('logout', [FranchiseAuthController::class, 'logout']);
                Route::post('check-token', [FranchiseAuthController::class, 'checkTokenExpiry']);
            });
        });
    });
});
