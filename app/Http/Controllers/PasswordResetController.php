<?php

namespace App\Http\Controllers;

use App\Requests\NewPasswordRequest;
use App\Requests\PasswordResetRequest;
use App\Services\Auth\PasswordResetService;

class PasswordResetController extends Controller
{
    public function __construct(
        protected PasswordResetService $passwordResetService
    ) {}

    // Show the forgot password form
    public function create()
    {
        return view('admin.admin-password-reset');
    }

    // Send the reset link email
    public function store(PasswordResetRequest $request)
    {
        try {
            $this->passwordResetService->sendResetLink(
                $request->validated()['email']
            );

            return back()->with('success', 'Password reset link sent to your email.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    // Show the new password form
    public function edit(string $token)
    {
        return view('auth.new-password', ['token' => $token]);
    }

    // Save the new password
    public function update(NewPasswordRequest $request)
    {
        try {
            $this->passwordResetService->resetPassword(
                $request->only('email', 'password', 'password_confirmation', 'token')
            );

            return redirect()->route('login')
                ->with('success', 'Password reset successfully. You can now log in.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Something went wrong. Please try again.'])->withInput();
        }
    }
}