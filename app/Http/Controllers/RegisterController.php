<?php

namespace App\Http\Controllers;

use App\Requests\RegisterRequest;
use App\Services\Auth\RegisterService;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            $this->registerService->register($data, $this->otpService);

            return redirect()->route('otp')->with('success', 'OTP sent to your email.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Something went wrong.'])->withInput();
        }
    }

    /**
     * Resend OTP for registration
     */
    public function resendOtp(Request $request)
    {
        try {
            if (!Session::has('otp.register')) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP session expired. Please restart the registration process.'
                ], 400);
            }

            $data = Session::get('otp.register');
            
            $email = $data['payload']['email'] ?? null;
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in registration data. Please restart the registration process.'
                ], 400);
            }
            
            $newOtp = $this->otpService->resend('register');
            
            Mail::to($email)->send(new OtpMail($newOtp));
            
            session()->flash('dev_otp', $newOtp);
            
            return response()->json([
                'success' => true,
                'message' => 'A new OTP has been sent to your email.',
                'expires_at' => now()->addMinutes(5)->timestamp
            ]);

        } catch (\Exception $e) {
            Log::error('Resend registration OTP error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Step 2: Complete registration after OTP verification
     */
    public function completeOtpVerification($enteredOtp)
    {
        try {
            $payload = $this->otpService->verify('register', $enteredOtp);

            $user = $this->registerService->completeRegistration($payload);

            Auth::login($user);

            $this->otpService->clear('register');
            
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