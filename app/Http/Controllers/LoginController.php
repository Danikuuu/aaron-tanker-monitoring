<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Requests\LoginRequest;
use App\Requests\OTPRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    protected LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function store(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            // 1. Verify credentials manually, but do NOT log in
            $user = $this->loginService->checkCredentials($data);

            // 2. Generate OTP
            // $otp = rand(100000, 999999);
            $otp = 1234; // for testing, use a fixed OTP. In production, use random OTP.

            // 3. Store user ID and OTP in session (or database if you want persistence)
            session([
                'login_user_id' => $user->id,
                'login_otp' => $otp,
                'login_otp_expires' => now()->addMinutes(5), // optional expiration
            ]);

            // 4. Send OTP via email
            Mail::to($user->email)->send(new OtpMail($otp));

            // 5. Redirect to OTP page
            return redirect()->route('otp');

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }
    }

    public function verifyOtp(OTPRequest $request)
    {
        $userId = session('login_user_id');
        $otp = session('login_otp');
        $expires = session('login_otp_expires');

        if (!$userId || !$otp || !$expires || now()->greaterThan($expires)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $enteredOtp = implode('', $request->otp); // if OTP is split in 4 boxes

        if ((string) $enteredOtp !== (string) $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        $user = $this->loginService->loginById($userId); // make sure it returns the User model

        // STORE ROLE IN SESSION
        session([
            'role' => $user->role, // <-- store role here
            'login_user_id' => $user->id, // optional, if needed elsewhere
        ]);

        // Clear OTP session
        session()->forget(['login_otp', 'login_otp_expires']);

        if ($user->role === 'staff') {
            return redirect()->route('staff.fuel-supply');
        } else if ($user->role === 'admin') {
            return redirect()->route('admin.overview');
        } else {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Unauthorized role.']);
        }
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
