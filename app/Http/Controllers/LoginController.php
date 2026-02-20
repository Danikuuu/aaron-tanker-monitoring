<?php

namespace App\Http\Controllers;

use App\Requests\LoginRequest;
use App\Requests\OTPRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\OtpService;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected LoginService $loginService;
    protected OtpService $otpService;

    public function __construct(LoginService $loginService, OtpService $otpService)
    {
        $this->loginService = $loginService;
        $this->otpService = $otpService;
    }

    /**
     * Handle login request
     */
    public function store(LoginRequest $request)
    {
        try {
            $user = $this->loginService->checkCredentials($request->validated());

            $otp = $this->otpService->generate('login', [
                'user_id' => $user->id,
            ]);

            Mail::to($user->email)->send(new OtpMail($otp));

            return redirect()->route('otp')->with('success', 'OTP sent to your email.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
    }

    /**
     * Verify login OTP
     */
    public function verifyOtp(OTPRequest $request)
    {
        $enteredOtp = implode('', $request->otp);

        try {
            $payload = $this->otpService->verify('login', $enteredOtp);

            $user = $this->loginService->loginById($payload['user_id']);

            // Redirect based on role
            if ($user->role === 'staff') {
                return redirect()->route('staff.fuel-supply');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.overview');
            }

            Auth::logout();
            abort(403, 'Unauthorized role');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => $e->getMessage()]);
        }
    }

    /**
     * Logout
     */
    public function destroy()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
