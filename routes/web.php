<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\FranchiseController;
use App\Models\User;
use App\Http\Controllers\Front\FranchiseAuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\WithdrawController;

// Login

Route::get('/check-permissions', function () {
    $user = \App\Models\User::find(1);
    $user->hasRole('admin');

    if (!$user) {
        return 'User not found.';
    }

    return $user->getPermissionNames();
});
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');

// Register
Route::get('admin/register', [RegisterController::class, 'showRegisterForm'])->name('admin.register');
Route::post('admin/register', [RegisterController::class, 'adminRegister'])->name('admin.register.submit');

// Franchise Authentication Routes (Outside admin/auth middleware)
Route::prefix('franchise')->name('franchise.')->group(function () {
    Route::get('login', [FranchiseAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [FranchiseAuthController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:franchise'])->group(function () {
        Route::get('dashboard', [FranchiseAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [FranchiseAuthController::class, 'logout'])->name('logout');
    });
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

    Route::get('/user/index', [UserController::class, 'index'])->name('admin.users.index')->middleware(['permission:User List']);
    Route::get('/user/create', [UserController::class, 'create'])->name('admin.users.create')->middleware(['permission:User Add']);
    Route::any('/user/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit')->middleware(['permission:User Edit']);
    Route::any('/user/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/user/show/{id}', [UserController::class, 'show'])->name('admin.users.show')->middleware(['permission:User View']);
    Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy')->middleware(['permission:User Delete']);
    Route::get('/user/update-password/{id}', [UserController::class, 'updatePassword'])->name('admin.users.update_password');
    Route::post('/user/update-password/store', [UserController::class, 'passwordStore'])->name('admin.users.passwordUpdate.store');

    // Change Password Routes for Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'index'])->name('change-password.index');
        Route::post('change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'store'])->name('change-password.store');
    });

    // Franchise Routes
    Route::prefix('admin/franchises')->name('admin.franchises.')->group(function () {
        Route::get('/', [FranchiseController::class, 'index'])->name('index')->middleware(['permission:Franchise List']);
        Route::get('create', [FranchiseController::class, 'create'])->name('create')->middleware(['permission:Franchise Add']);
        Route::post('store', [FranchiseController::class, 'store'])->name('store')->middleware(['permission:Franchise Add']);
        Route::get('edit/{id}', [FranchiseController::class, 'edit'])->name('edit')->middleware(['permission:Franchise Edit']);
        Route::put('update/{id}', [FranchiseController::class, 'update'])->name('update')->middleware(['permission:Franchise Edit']);
        Route::put('update-status/{id}', [FranchiseController::class, 'updateStatus'])->name('updateStatus')->middleware(['permission:Franchise Edit']);
        Route::get('show/{id}', [FranchiseController::class, 'show'])->name('show')->middleware(['permission:Franchise View']);
        Route::delete('destroy/{id}', [FranchiseController::class, 'destroy'])->name('destroy')->middleware(['permission:Franchise Delete']);
    });

    // Customer Routes
    Route::prefix('admin/customers')->name('admin.customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index')->middleware(['permission:Customer List']);
        Route::get('create', [CustomerController::class, 'create'])->name('create')->middleware(['permission:Customer Add']);
        Route::post('store', [CustomerController::class, 'store'])->name('store')->middleware(['permission:Customer Add']);
        Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('edit')->middleware(['permission:Customer Edit']);
        Route::put('update/{id}', [CustomerController::class, 'update'])->name('update')->middleware(['permission:Customer Edit']);
        Route::get('show/{id}', [CustomerController::class, 'show'])->name('show')->middleware(['permission:Customer View']);
        Route::delete('destroy/{id}', [CustomerController::class, 'destroy'])->name('destroy')->middleware(['permission:Customer Delete']);
    });

    // KYC Routes
    Route::prefix('admin/kycs')->name('admin.kycs.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index')->middleware(['permission:KYC List']);
        Route::get('create', [KycController::class, 'create'])->name('create')->middleware(['permission:KYC Add']);
        Route::post('store', [KycController::class, 'store'])->name('store')->middleware(['permission:KYC Add']);
        Route::get('edit/{id}', [KycController::class, 'edit'])->name('edit')->middleware(['permission:KYC Edit']);
        Route::put('update/{id}', [KycController::class, 'update'])->name('update')->middleware(['permission:KYC Edit']);
        Route::get('show/{id}', [KycController::class, 'show'])->name('show')->middleware(['permission:KYC View']);
        Route::delete('destroy/{id}', [KycController::class, 'destroy'])->name('destroy')->middleware(['permission:KYC Delete']);
    });

    // Deposit Routes
    Route::prefix('admin/deposits')->name('admin.deposits.')->group(function () {
        Route::get('/', [DepositController::class, 'index'])->name('index')->middleware(['permission:Deposit List']);
        Route::get('create', [DepositController::class, 'create'])->name('create')->middleware(['permission:Deposit Add']);
        Route::post('store', [DepositController::class, 'store'])->name('store')->middleware(['permission:Deposit Add']);
        Route::get('edit/{id}', [DepositController::class, 'edit'])->name('edit')->middleware(['permission:Deposit Edit']);
        Route::put('update/{id}', [DepositController::class, 'update'])->name('update')->middleware(['permission:Deposit Edit']);
        Route::get('show/{id}', [DepositController::class, 'show'])->name('show')->middleware(['permission:Deposit View']);
        Route::delete('destroy/{id}', [DepositController::class, 'destroy'])->name('destroy')->middleware(['permission:Deposit Delete']);
    });


    // Withdraw Routes
    Route::prefix('admin/withdraws')->name('admin.withdraws.')->group(function () {
        Route::get('/', [WithdrawController::class, 'index'])->name('index')->middleware(['permission:Withdraw List']);
        Route::get('create', [WithdrawController::class, 'create'])->name('create')->middleware(['permission:Withdraw Add']);
        Route::post('store', [WithdrawController::class, 'store'])->name('store')->middleware(['permission:Withdraw Add']);
        Route::get('edit/{id}', [WithdrawController::class, 'edit'])->name('edit')->middleware(['permission:Withdraw Edit']);
        Route::put('update/{id}', [WithdrawController::class, 'update'])->name('update')->middleware(['permission:Withdraw Edit']);
        Route::get('show/{id}', [WithdrawController::class, 'show'])->name('show')->middleware(['permission:Withdraw View']);
        Route::delete('destroy/{id}', [WithdrawController::class, 'destroy'])->name('destroy')->middleware(['permission:Withdraw Delete']);
    });

    // Order Routes
    Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index')->middleware(['permission:Order List']);
        Route::get('create', [OrderController::class, 'create'])->name('create')->middleware(['permission:Order Add']);
        Route::post('store', [OrderController::class, 'store'])->name('store')->middleware(['permission:Order Add']);
        Route::get('edit/{id}', [OrderController::class, 'edit'])->name('edit')->middleware(['permission:Order Edit']);
        Route::put('update/{id}', [OrderController::class, 'update'])->name('update')->middleware(['permission:Order Edit']);
        Route::get('show/{id}', [OrderController::class, 'show'])->name('show')->middleware(['permission:Order View']);
        Route::delete('destroy/{id}', [OrderController::class, 'destroy'])->name('destroy')->middleware(['permission:Order Delete']);
    });

    // Transaction Routes
    Route::prefix('admin/transactions')->name('admin.transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index')->middleware(['permission:Transaction List']);
        Route::get('create', [TransactionController::class, 'create'])->name('create')->middleware(['permission:Deposit Add']);
        Route::post('store', [TransactionController::class, 'store'])->name('store')->middleware(['permission:Deposit Add']);
        Route::get('edit/{id}', [TransactionController::class, 'edit'])->name('edit')->middleware(['permission:Deposit Edit']);
        Route::put('update/{id}', [TransactionController::class, 'update'])->name('update')->middleware(['permission:Deposit Edit']);
        Route::get('show/{id}', [TransactionController::class, 'show'])->name('show')->middleware(['permission:Deposit View']);
        Route::delete('destroy/{id}', [TransactionController::class, 'destroy'])->name('destroy')->middleware(['permission:Deposit Delete']);
    });


    Route::get('/user/import-excel/view', [UserController::class, 'import_excel_view'])->name('admin.users.import_excel_view')->middleware(['permission:User Excel Import View']);
    Route::post('/user/import-excel', [UserController::class, 'import_excel'])->name('admin.users.import_excel')->middleware(['permission:User Excel Import']);

    // Setting Routes
    Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
        Route::get('main-settings', [SettingsController::class, 'mainSettings'])->name('main');
        Route::put('main-settings-update', [SettingsController::class, 'update'])->name('update');
        Route::get('preference-settings', [SettingsController::class, 'preferenceSettings'])->name('preference');
        Route::put('preference-settings', [SettingsController::class, 'preferenceSettingsUpdate'])->name('preference.update');
        Route::get('smtp', [SettingsController::class, 'smtpSettings'])->name('smtp');
        Route::post('smtp', [SettingsController::class, 'smtpSettingsUpdate'])->name('smtp.update');
        Route::get('social-login', [SettingsController::class, 'socialLoginSettings'])->name('social_login');
        Route::post('social-login', [SettingsController::class, 'socialLoginSettingsUpdate'])->name('social_login.update');
        Route::get('payment-gateway', [SettingsController::class, 'paymentGatewaySettings'])->name('payment_gateway');
        Route::post('payment-gateway', [SettingsController::class, 'paymentGatewaySettingsUpdate'])->name('payment_gateway.update');
    });

    Route::get('/admin/users/{id}/pincode', [UserController::class, 'pincode_index'])->name('users.pincode_index');
    Route::post('/users/pincode/store', [UserController::class, 'pincode_store'])->name('users.pincode_store');
    Route::post('/users/pincode/update/{id}', [UserController::class, 'pincode_update'])->name('users.pincode_update');
    Route::get('/users/{id}/pincode/list', [UserController::class, 'pincode_list'])->name('users.pincode_list');
    Route::delete('users/pincode/delete/{id}', [UserController::class, 'pincode_delete'])->name('users.pincode_delete');



    Route::get('/role/index', [RoleController::class, 'index'])->name('roles.index')->middleware(['permission:role-list']);
    Route::get('/role/create', [RoleController::class, 'create'])->name('roles.create')->middleware(['permission:role-create']);
    Route::any('/role/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/role/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware(['permission:role-edit']);
    Route::any('/role/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('/role/show/{id}', [RoleController::class, 'show'])->name('roles.show')->middleware(['permission:Role View']);
    Route::get('/role/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware(['permission:role-delete']);

    Route::prefix('admin/permissions')->name('admin.permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index')->middleware(['permission:Permission List']);
        Route::get('create', [PermissionController::class, 'create'])->name('create')->middleware(['permission:Permission Add']);
        Route::post('store', [PermissionController::class, 'store'])->name('store')->middleware(['permission:Permission Add']);
        Route::get('edit/{id}', [PermissionController::class, 'edit'])->name('edit')->middleware(['permission:Permission Edit']);
        Route::put('update/{id}', [PermissionController::class, 'update'])->name('update')->middleware(['permission:Permission Edit']);
        Route::get('show/{id}', [WithdrawController::class, 'show'])->name('show')->middleware(['permission:Permission View']);
        Route::delete('destroy/{id}', [PermissionController::class, 'destroy'])->name('destroy')->middleware(['permission:Permission Delete']);
    });
});
