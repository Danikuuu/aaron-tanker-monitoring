<?php

namespace App\Services\Auth;

use App\Repositories\Auth\ForgotPasswordInterface;
use Illuminate\Validation\ValidationException;

class ForgotPasswordService
{
    public function __construct(
        protected ForgotPasswordInterface $forgotPasswordRepository,
        protected OtpService $otpService,
    ) {}

    /**
     * Check the email exists, then generate and store a
     * password_reset OTP tied to that user.
     *
     * @throws ValidationException  When no account matches the email
     */
    public function sendOtp(string $email): int
    {
        $user = $this->forgotPasswordRepository->findByEmail($email);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with that email address.',
            ]);
        }

        return $this->otpService->generate('password_reset', [
            'user_id' => $user->id,
            'email'   => $user->email,
        ]);
    }
}