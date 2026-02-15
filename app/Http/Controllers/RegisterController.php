<?php

namespace App\Http\Controllers;

use App\Requests\RegisterRequest;
use App\Services\Auth\RegisterService;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected RegisterService $registerService;
    protected OtpService $otpService;

    public function __construct(RegisterService $registerService, OtpService $otpService)
    {
        $this->registerService = $registerService;
        $this->otpService = $otpService;
    }

    /**
     * Show registration form
     */
    public function create()
    {
        return view('auth.signup');
    }

    /**
     * Step 1: Handle registration request
     * - Validate input
     * - Generate OTP (do not create user yet)
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();

        try {
            // Generate OTP and store registration payload
            $this->registerService->register($data, $this->otpService);

            return redirect()->route('otp')->with('success', 'OTP sent to your email.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Something went wrong.'])->withInput();
        }
    }

    /**
     * Step 2: Complete registration after OTP verification
     */
    public function completeOtpVerification($enteredOtp)
    {
        try {
            // Verify OTP and retrieve registration payload
            $payload = $this->otpService->verify('register', $enteredOtp);

            // Create user in DB
            $user = $this->registerService->completeRegistration($payload);

            // Login user
            Auth::login($user);

            // Clear OTP session (optional, but safe)
            $this->otpService->clear('register');

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
}
