<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\FuelSummaryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TankerHistoryController;
use App\Http\Controllers\TankerDepartureController;
use App\Http\Controllers\TankerArrivalController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::view('/', 'auth.login')->name('login');
Route::view('/register', 'auth.signup')->name('register');
Route::view('/otp', 'auth.otp')->name('otp');


// ===============================
// REGISTER
// ===============================
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.attempt');


// ===============================
// LOGIN
// ===============================
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('login.attempt');


// ===============================
// OTP (Unified for login + register)
// ===============================
Route::post('/otp', [OtpController::class, 'verify'])
    ->middleware('throttle:5,1')
    ->name('otp.verify');


// ===============================
// LOGOUT
// ===============================
Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');


    // modify tommorow
// Route::view('/reset-password', 'auth.reset-password')->name('reset-password');



Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    // Route::view('/overview', 'admin.overview')->name('admin.overview');
    // Route::view('/analytics', 'admin.analytics')->name('admin.analytics');
    // Route::view('/staff-management', 'admin.staff-management')->name('admin.staff-management');
    // Route::view('/transaction-history', 'admin.transaction-history')->name('admin.transaction-history');
    Route::view('/create-admin', 'admin.create-admin')->name('admin.create');
    Route::view('/admin-password', 'admin.admin-password')->name('admin.password-reset');
    Route::view('/br-receipt', 'admin.receipt.reciept')->name('admin.br-receipt');

    Route::get('/overview', [OverviewController::class, 'index'])->name('admin.overview');

    Route::get('/analytics',        [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('admin.analytics.export');

    Route::get('/fuel-summary',                    [FuelSummaryController::class, 'index'])->name('admin.fuel-summary');
    Route::get('/fuel-summary/export/arrivals',    [FuelSummaryController::class, 'exportArrivals'])->name('admin.fuel-summary.export.arrivals');
    Route::get('/fuel-summary/export/departures',  [FuelSummaryController::class, 'exportDepartures'])->name('admin.fuel-summary.export.departures');

    Route::get('/transaction-history',        [TransactionHistoryController::class, 'index'])->name('admin.transaction-history');
    Route::get('/transaction-history/export', [TransactionHistoryController::class, 'export'])->name('admin.transaction-history.export');

    Route::get('/staff-management', [ApprovalController::class, 'index'])->name('admin.staff-management');
    Route::post('/staff/{staffId}/approve',  [ApprovalController::class, 'approve'])->name('admin.staff.approve');
    Route::post('/staff/{staffId}/block',    [ApprovalController::class, 'block'])->name('admin.staff.block');
    Route::post('/staff/{staffId}/unblock',  [ApprovalController::class, 'unblock'])->name('admin.staff.unblock');
    Route::delete('/staff/{staffId}',        [ApprovalController::class, 'destroy'])->name('admin.staff.delete');

    Route::get('/forgot-password',  [PasswordResetController::class, 'create'])->name('admin.password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('admin.password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('admin.password.reset');
    Route::post('/reset-password',  [PasswordResetController::class, 'update'])->name('admin.password.update');
    
});

Route::middleware([RoleMiddleware::class . ':staff'])->prefix('staff')->group(function () {
    // Route::view('/fuel-supply', 'staff.fuel-supply')->name('staff.fuel-supply');
    // Route::get('/fuel-suppy', [TankerHistoryController::class, 'index'])->name('staff.fuel-supply');
    Route::get('/fuel-supply', [TankerHistoryController::class, 'index'])->name('staff.fuel-supply');
    Route::view('/tanker-departure', 'staff.tanker-departure')->name('staff.tanker-out');
    Route::view('/tanker-in', 'staff.tanker-in')->name('staff.tanker-in');

    Route::post('/tanker-arrival', [TankerArrivalController::class, 'store'])->name('tanker-arrival.store');
    Route::post('/tanker-departure', [TankerDepartureController::class, 'store'])->name('staff.tanker-departure.store');
});



Route::get('/admin/delete-user/{id}', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }

    $user->delete();

    return redirect()->back()->with('success', 'User deleted successfully');
})->name('admin.delete-user');