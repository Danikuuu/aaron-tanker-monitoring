<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function __construct(
        protected ResetPasswordService $resetPasswordService,
    ) {}

    /**
     * Show the reset password form.
     * Guard against direct access without completing OTP first.
     */
    public function show()
    {
        if (!Session::has('password_reset_verified_user')) {
            return redirect()->route('password.forgot.show');
        }

        return view('auth.reset-password');
    }

    /**
     * Handle the new password submission:
     * validate → reset password → redirect to login.
     */
    public function update(ResetPasswordRequest $request)
    {
        try {
            $this->resetPasswordService->reset($request->password);

            return redirect()->route('login')
                ->with('success', 'Password reset successfully. You can now log in.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}