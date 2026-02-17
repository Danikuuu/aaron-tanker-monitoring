<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
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

            // TODO: Mail the OTP to the user in production
            // Mail::to($request->email)->send(new PasswordResetOtpMail($otp));

            // Dev only — remove in production
            session()->flash('dev_otp', $otp);

            return redirect()->route('otp');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
    }
}