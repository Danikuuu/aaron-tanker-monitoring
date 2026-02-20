<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Auth\OtpService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function __construct(
        protected ForgotPasswordService $forgotPasswordService,
    ) {}

    /**
     * Show the forgot-password form.
     */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the email submission:
     * validate → check existence → generate OTP → redirect to OTP page.
     */
    public function send(ForgotPasswordRequest $request)
    {
        try {
            $otp = $this->forgotPasswordService->sendOtp($request->email);

            // Send OTP via email
            Mail::to($request->email)->send(new OtpMail($otp));

            // Dev only — remove in production
            session()->flash('dev_otp', $otp);

            return redirect()->route('otp');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
    }

    /**
     * Resend OTP for password reset
     */
    public function resendOtp(Request $request, OtpService $otpService)
    {
        try {
            if (!Session::has('otp.password_reset')) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP session expired. Please restart the password reset process.'
                ], 400);
            }

            $data = Session::get('otp.password_reset');
            
            $userId = $data['payload']['user_id'] ?? null;
            $email = $data['payload']['email'] ?? null;
            
            if ($userId) {
                $user = User::find($userId);
                
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found. Please restart the password reset process.'
                    ], 404);
                }
                
                $email = $user->email;
            }
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found. Please restart the password reset process.'
                ], 400);
            }
            
            $newOtp = $otpService->resend('password_reset');
            
            Mail::to($email)->send(new OtpMail($newOtp));
            
            session()->flash('dev_otp', $newOtp);
            
            return response()->json([
                'success' => true,
                'message' => 'A new OTP has been sent to your email.',
                'expires_at' => now()->addMinutes(5)->timestamp
            ]);

        } catch (\Exception $e) {
            Log::error('Resend password reset OTP error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }
}