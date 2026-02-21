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

    /**
     * Show the form to request a password reset link.
     */
    public function create()
    {
        return view('admin.admin-password-reset');
    }

    public function superadmin()
    {
        return view('super_admin.admin-password-reset');
    }

    /**
     * Handle the form submission to send a password reset link to the user's email.
     */
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

    /**
     * Show the form to enter a new password, accessed via the token in the reset link.
      * Validates the token and email before showing the form.
      * If token is invalid or expired, shows an error message.
      * The form submits to the update() method to save the new password.
     */
    public function edit(string $token)
    {
        return view('auth.new-password', ['token' => $token]);
    }

    /**
     * Handle the submission of the new password form. Validates the token, email, and new password.
      * If validation passes, updates the user's password and redirects to login with success message.
      * If validation fails (e.g., invalid token, password mismatch), returns back with error messages.
     */
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