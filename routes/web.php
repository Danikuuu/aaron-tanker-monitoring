<?php

use App\Http\Controllers\LoginController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::view('/', 'auth.login')->name('login');
Route::view('/register', 'auth.signup')->name('register');
Route::view('/otp', 'auth.otp')->name('otp');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1')->name('login.attempt');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');

Route::post('/otp', [LoginController::class, 'verifyOtp'])
    ->middleware('throttle:5,1')
    ->name('otp.verify');


    // modify tommorow
Route::view('/reset-password', 'auth.reset-password')->name('reset-password');


Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::view('/overview', 'admin.overview')->name('admin.overview');
    Route::view('/analytics', 'admin.analytics')->name('admin.analytics');
    Route::view('/staff-management', 'admin.staff-management')->name('admin.staff-management');
    Route::view('/transaction-history', 'admin.transaction-history')->name('admin.transaction-history');
    Route::view('/create-admin', 'admin.create-admin')->name('admin.create');
    Route::view('/admin-password', 'admin.admin-password')->name('admin.password-reset');
    Route::view('/br-receipt', 'admin.receipt.reciept')->name('admin.br-receipt');
});

Route::middleware([RoleMiddleware::class . ':staff'])->prefix('staff')->group(function () {
    Route::view('/fuel-supply', 'staff.fuel-supply')->name('staff.fuel-supply');
});



Route::get('/admin/delete-user/{id}', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }

    $user->delete();

    return redirect()->back()->with('success', 'User deleted successfully');
})->name('admin.delete-user');