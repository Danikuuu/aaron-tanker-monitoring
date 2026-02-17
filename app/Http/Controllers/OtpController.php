<?php

namespace App\Http\Controllers;

use App\Requests\OTPRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\OtpService as AuthOtpService;
use App\Services\Auth\RegisterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    public function verify(
        OTPRequest $request,
        AuthOtpService $otpService,
        LoginService $loginService,
        RegisterService $registerService
    ) {
        $enteredOtp = implode('', $request->otp);

        try {
            // ── Login context ──────────────────────────────────────────────
            if (Session::has('otp.login')) {
                $payload = $otpService->verify('login', $enteredOtp);
                $user = $loginService->loginById($payload['user_id']);
                return $this->redirectByRole($user);
            }

            // ── Registration context ───────────────────────────────────────
            if (Session::has('otp.register')) {
                $payload = $otpService->verify('register', $enteredOtp);
                $user = $registerService->completeRegistration($payload);
                $otpService->clear('register');
                return $this->redirectByRole($user);
            }

            // ── Password-reset context ─────────────────────────────────────
            if (Session::has('otp.password_reset')) {
                $payload = $otpService->verify('password_reset', $enteredOtp);
                $otpService->clear('password_reset');

                // Store verified user ID so the reset-password form is protected
                Session::put('password_reset_verified_user', $payload['user_id']);

                return redirect()->route('password.reset.show');
            }

            return back()->withErrors(['otp' => 'OTP session expired.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => $e->getMessage()]);
        }
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function redirectByRole($user)
    {
        if ($user->role === 'staff') {
            if ($user->isPending()) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account is pending admin approval.']);
            }
            if ($user->isBlocked()) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been blocked.']);
            }
            return redirect()->route('staff.fuel-supply');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.overview');
        }

        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Unauthorized role.']);
    }
}