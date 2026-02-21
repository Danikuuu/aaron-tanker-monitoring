<?php

namespace App\Http\Controllers;

use App\Requests\OTPRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\OtpService as AuthOtpService;
use App\Services\Auth\RegisterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    /**
     * Handle OTP verification for login, registration, and password reset contexts.
      * Determines context based on session data and processes accordingly.
      * On successful verification, logs in the user and redirects based on role.
      * On failure, returns back with appropriate error messages.
     */
    public function verify(
        OTPRequest $request,
        AuthOtpService $otpService,
        LoginService $loginService,
        RegisterService $registerService
    ) {
        $enteredOtp = implode('', $request->otp);

        try {
            if (Session::has('otp.login')) {
                $payload = $otpService->verify('login', $enteredOtp);
                $user = $loginService->loginById(
                    $payload['user_id'],
                    $payload['remember'] ?? false
                );
                return $this->redirectByRole($user);
            }

            if (Session::has('otp.register')) {
                $payload = $otpService->verify('register', $enteredOtp);
                $user = $registerService->completeRegistration($payload);
                $otpService->clear('register');
                return $this->redirectByRole($user);
            }

            if (Session::has('otp.password_reset')) {
                $payload = $otpService->verify('password_reset', $enteredOtp);
                $otpService->clear('password_reset');

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

    /**
     * Resend OTP based on current context
     */
    public function resend(Request $request, AuthOtpService $otpService)
    {
        try {
            if (Session::has('otp.login')) {
                $data = Session::get('otp.login');
                $userId = $data['payload']['user_id'] ?? null;
                
                if (!$userId) {
                    throw new \Exception('User ID not found in session');
                }
                
                $user = User::find($userId);
                
                if (!$user) {
                    throw new \Exception('User not found');
                }
                
                $newOtp = $otpService->resend('login');
                
                Mail::to($user->email)->send(new OtpMail($newOtp));
                
                return response()->json([
                    'success' => true,
                    'message' => 'A new OTP has been sent to your email.',
                    'expires_at' => now()->addMinutes(5)->timestamp
                ]);
            }

            if (Session::has('otp.register')) {
                $data = Session::get('otp.register');

                $email = $data['payload']['email'] ?? null;
                
                if (!$email) {
                    throw new \Exception('Email not found in registration payload');
                }
                
                $newOtp = $otpService->resend('register');
                
                Mail::to($email)->send(new OtpMail($newOtp));
                
                return response()->json([
                    'success' => true,
                    'message' => 'A new OTP has been sent to your email.',
                    'expires_at' => now()->addMinutes(5)->timestamp
                ]);
            }

            if (Session::has('otp.password_reset')) {
                $data = Session::get('otp.password_reset');
                $userId = $data['payload']['user_id'] ?? null;
                
                if (!$userId) {
                    $email = $data['payload']['email'] ?? null;
                    
                    if (!$email) {
                        throw new \Exception('User ID or email not found in session');
                    }
                } else {
                    $user = User::find($userId);
                    
                    if (!$user) {
                        throw new \Exception('User not found');
                    }
                    
                    $email = $user->email;
                }
                
                $newOtp = $otpService->resend('password_reset');
                
                Mail::to($email)->send(new OtpMail($newOtp));
                
                return response()->json([
                    'success' => true,
                    'message' => 'A new OTP has been sent to your email.',
                    'expires_at' => now()->addMinutes(5)->timestamp
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'OTP session expired. Please restart the process.'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Resend OTP error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }


    /**
     * Helper method to redirect users based on their role after successful login or registration.
      * Handles pending and blocked statuses for staff members, and redirects admins to the overview page.
      * Logs out and redirects users with unauthorized roles.
      * @param User $user The authenticated user object
      * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectByRole($user)
    {
        // dd($user);
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
        
        if ($user->role === 'super_admin') {
            return redirect()->route('super_admin.overview');
        }
        

        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Unauthorized role.']);
    }
}