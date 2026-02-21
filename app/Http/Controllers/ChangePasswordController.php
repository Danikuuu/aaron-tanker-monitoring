<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Show the change-password form.
     */
    public function edit()
    {
        if (Auth::user()->role === 'super_admin') {
            return view('super_admin.super_admin-password-reset');
        }
        return view('admin.admin-password-reset');
    }

    /**
     * Update the authenticated user's password.
     */
    public function update(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        $route = $user->role === 'admin'
            ? 'admin.password.edit'
            : 'super_admin.password.edit';

        return redirect()
            ->route($route)
            ->with('status', 'Password updated successfully.');
    }
}