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
            // Determine context from session, don't blindly try both
            if (Session::has('otp.login')) {
                $payload = $otpService->verify('login', $enteredOtp);
                // dd($payload);
                $user = $loginService->loginById($payload['user_id']);
                return $this->redirectByRole($user);
            }

            if (Session::has('otp.register')) {
                $payload = $otpService->verify('register', $enteredOtp);
                // dd($payload);
                $user = $registerService->completeRegistration($payload);
                $otpService->clear('register');
                // Auth::login($user);
                return $this->redirectByRole($user);
            }

            return back()->withErrors(['otp' => 'OTP session expired.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => $e->getMessage()]);
        }
    }

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
