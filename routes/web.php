<?php

use App\Http\Controllers\BrReceiptPaymentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\BrReceiptController;
use App\Http\Controllers\FuelSummaryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\StaffManagementController;
use App\Http\Controllers\TankerHistoryController;
use App\Http\Controllers\TankerDepartureController;
use App\Http\Controllers\TankerArrivalController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


// ===============================
// REGISTER
// ===============================
Route::view('/register', 'auth.signup')->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.attempt');

Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend.otp');


// ===============================
// LOGIN
// ===============================
Route::view('/', 'auth.login')->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('login.attempt');


// ===============================
// OTP (Unified for login + register + forget password)
// ===============================
Route::view('/otp', 'auth.otp')->name('otp');

Route::post('/otp', [OtpController::class, 'verify'])
    ->middleware('throttle:5,1')
    ->name('otp.verify');

Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');


// ===============================
// Forgot password
// ===============================

Route::get('/forgot-password',  [ForgotPasswordController::class, 'show'])->name('password.forgot.show');
Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.forgot.send');
Route::post('/forgot-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend.otp');

// ===============================
// Reset password
// ===============================
Route::get('/reset-password',  [ResetPasswordController::class, 'show'])->name('password.reset.show');
Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.reset.update');

// ===============================
// LOGOUT
// ===============================
Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');


// ===============================
// ADMIN ROUTES
// ===============================
Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {

    // ===============================
    // Admin change password (for their own account)
    // ===============================
    Route::view('/admin-password', 'admin.admin-password')->name('admin.password-reset');


    // ===============================
    // BR Receipt Management
    // ===============================
    // Route::get('/br-receipt', [BrReceiptController::class, 'index'])->name('admin.br-receipt');

    // Route::post('/br-receipt', [BrReceiptController::class, 'store'])->name('admin.br-receipt.store');
    
    // Route::get('/br-receipt/next-number', [BrReceiptController::class, 'getNextReceiptNumber'])->name('admin.br-receipt.next-number');

    // Route::get('/br-receipt-payments', [BrReceiptPaymentController::class, 'index'])
    //     ->name('admin.br-receipt-payments.index');

    // Route::get('/br-receipt-payments/{id}', [BrReceiptPaymentController::class, 'show'])
    //     ->name('admin.br-receipt-payments.show');

    // Route::put('/br-receipt-payments/{id}/payment', [BrReceiptPaymentController::class, 'upsertPayment'])
    //     ->name('admin.br-receipt-payments.upsert');


    // ===============================
    // Admin dashboard & reports
    // ===============================
    Route::get('/overview', [OverviewController::class, 'index'])->name('admin.overview');

    Route::get('/analytics',        [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('admin.analytics.export');

    Route::get('/fuel-summary',                    [FuelSummaryController::class, 'index'])->name('admin.fuel-summary');
    Route::get('/fuel-summary/export/arrivals',    [FuelSummaryController::class, 'exportArrivals'])->name('admin.fuel-summary.export.arrivals');
    Route::get('/fuel-summary/export/departures',  [FuelSummaryController::class, 'exportDepartures'])->name('admin.fuel-summary.export.departures');
    Route::get('/fuel-summary/export/arrivals/pdf',    [FuelSummaryController::class, 'exportArrivalsPdf'])->name('admin.fuel-summary.export.arrivals.pdf');
    Route::get('/fuel-summary/export/departures/pdf',  [FuelSummaryController::class, 'exportDeparturesPdf'])->name('admin.fuel-summary.export.departures.pdf');

    Route::get('/transaction-history',        [TransactionHistoryController::class, 'index'])->name('admin.transaction-history');
    Route::get('/transaction-history/export', [TransactionHistoryController::class, 'export'])->name('admin.transaction-history.export');
    Route::get('/transaction-history/export/pdf', [TransactionHistoryController::class, 'exportPdf'])->name('admin.transaction-history.export.pdf');
    Route::get('/transaction-history/{type}/{id}', [TransactionHistoryController::class, 'show'])->name('admin.transaction-history.show');


    // ===============================
    // Staff management (create, approve, block/unblock, delete)
    // ===============================
    // Route::post('/staff-management', [StaffManagementController::class, 'store'])
    //     ->name('admin.staff.create');
    // Route::get('/staff-management', [ApprovalController::class, 'index'])->name('admin.staff-management');
    // Route::post('/staff/{staffId}/approve',  [ApprovalController::class, 'approve'])->name('admin.staff.approve');
    // Route::post('/staff/{staffId}/block',    [ApprovalController::class, 'block'])->name('admin.staff.block');
    // Route::post('/staff/{staffId}/unblock',  [ApprovalController::class, 'unblock'])->name('admin.staff.unblock');
    // Route::delete('/staff/{staffId}',        [ApprovalController::class, 'destroy'])->name('admin.staff.delete');


    Route::get('/forgot-password',  [PasswordResetController::class, 'create'])->name('admin.password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('admin.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('admin.password.reset');
    Route::post('/reset-password',  [PasswordResetController::class, 'update'])->name('admin.password.update');


    Route::get('/admin/notifications', [NotificationController::class, 'index'])
    ->name('admin.notifications');
    
});


// ===============================
// SUPER ADMIN ROUTES
// ===============================
Route::middleware([RoleMiddleware::class . ':super_admin'])->prefix('super_admin')->group(function () {

    // ===============================
    // Admin change password (for their own account)
    // ===============================
    Route::view('/admin-password', 'admin.admin-password')->name('super_admin.password-reset');


    // ===============================
    // BR Receipt Management
    // ===============================
    Route::get('/br-receipt', [BrReceiptController::class, 'index'])->name('super_admin.br-receipt');

    Route::post('/br-receipt', [BrReceiptController::class, 'store'])->name('super_admin.br-receipt.store');
    
    Route::get('/br-receipt/next-number', [BrReceiptController::class, 'getNextReceiptNumber'])->name('super_admin.br-receipt.next-number');

    Route::get('/br-receipt-payments', [BrReceiptPaymentController::class, 'index'])
        ->name('super_admin.br-receipt-payments.index');

    Route::get('/br-receipt-payments/{id}', [BrReceiptPaymentController::class, 'show'])
        ->name('super_admin.br-receipt-payments.show');

    Route::put('/br-receipt-payments/{id}/payment', [BrReceiptPaymentController::class, 'upsertPayment'])
        ->name('super_admin.br-receipt-payments.upsert');


    // ===============================
    // Admin dashboard & reports
    // ===============================
    Route::get('/overview', [OverviewController::class, 'index'])->name('super_admin.overview');

    Route::get('/analytics',        [AnalyticsController::class, 'index'])->name('super_admin.analytics');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('super_admin.analytics.export');

    Route::get('/fuel-summary',                    [FuelSummaryController::class, 'index'])->name('super_admin.fuel-summary');
    Route::get('/fuel-summary/export/arrivals',    [FuelSummaryController::class, 'exportArrivals'])->name('super_admin.fuel-summary.export.arrivals');
    Route::get('/fuel-summary/export/departures',  [FuelSummaryController::class, 'exportDepartures'])->name('super_admin.fuel-summary.export.departures');
    Route::get('/fuel-summary/export/arrivals/pdf',    [FuelSummaryController::class, 'exportArrivalsPdf'])->name('super_admin.fuel-summary.export.arrivals.pdf');
    Route::get('/fuel-summary/export/departures/pdf',  [FuelSummaryController::class, 'exportDeparturesPdf'])->name('super_admin.fuel-summary.export.departures.pdf');

    Route::get('/transaction-history',        [TransactionHistoryController::class, 'index'])->name('super_admin.transaction-history');
    Route::get('/transaction-history/export', [TransactionHistoryController::class, 'export'])->name('super_admin.transaction-history.export');
    Route::get('/transaction-history/export/pdf', [TransactionHistoryController::class, 'exportPdf'])->name('super_admin.transaction-history.export.pdf');
    Route::get('/transaction-history/{type}/{id}', [TransactionHistoryController::class, 'show'])->name('super_admin.transaction-history.show');


    // ===============================
    // Staff management (create, approve, block/unblock, delete)
    // ===============================
    Route::post('/staff-management', [StaffManagementController::class, 'store'])
        ->name('super_admin.staff.create');
    Route::get('/staff-management', [ApprovalController::class, 'index'])->name('super_admin.staff-management');
    Route::post('/staff/{staffId}/approve',  [ApprovalController::class, 'approve'])->name('super_admin.staff.approve');
    Route::post('/staff/{staffId}/block',    [ApprovalController::class, 'block'])->name('super_admin.staff.block');
    Route::post('/staff/{staffId}/unblock',  [ApprovalController::class, 'unblock'])->name('super_admin.staff.unblock');
    Route::delete('/staff/{staffId}',        [ApprovalController::class, 'destroy'])->name('super_admin.staff.delete');


    Route::get('/forgot-password',  [PasswordResetController::class, 'create'])->name('super_admin.password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('super_admin.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('super_admin.password.reset');
    Route::post('/reset-password',  [PasswordResetController::class, 'update'])->name('super_admin.password.update');

     Route::get('/admin/notifications', [NotificationController::class, 'index'])
    ->name('super_admin.notifications');
    
});


// ===============================
// STAFF ROUTES
// ===============================
Route::middleware([RoleMiddleware::class . ':staff'])->prefix('staff')->group(function () {


    // ===============================
    // Staff routes for in and out of tankers
    // ===============================
    Route::get('/fuel-supply', [TankerHistoryController::class, 'index'])->name('staff.fuel-supply');
    Route::view('/tanker-departure', 'staff.tanker-departure')->name('staff.tanker-out');
    Route::view('/tanker-in', 'staff.tanker-in')->name('staff.tanker-in');

    Route::post('/tanker-arrival', [TankerArrivalController::class, 'store'])->name('tanker-arrival.store');
    Route::post('/tanker-departure', [TankerDepartureController::class, 'store'])->name('staff.tanker-departure.store');
});