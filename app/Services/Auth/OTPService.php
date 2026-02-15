<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class OtpService
{
    protected int $expiryMinutes = 5;
    protected int $maxAttempts = 5;

    /**
     * Generate and store OTP for a specific context.
     */
    public function generate(string $context, array $payload = []): int
    {
        // $otp = rand(1000, 9999);
        $otp = 1234; // For testing purposes, replace with random generation in production
        Session::put("otp.$context", [
            'code' => $otp,
            'expires_at' => now()->addMinutes($this->expiryMinutes),
            'attempts' => 0,
            'payload' => $payload,
        ]);

        return $otp;
    }

    /**
     * Verify OTP for a given context.
     */
    public function verify(string $context, string $enteredOtp): array
    {
        $data = Session::get("otp.$context");

        if (!$data) {
            throw ValidationException::withMessages(['otp' => 'OTP session expired.']);
        }

        if (now()->greaterThan($data['expires_at'])) {
            Session::forget("otp.$context");
            throw ValidationException::withMessages(['otp' => 'OTP expired.']);
        }

        if ((string)$enteredOtp !== (string)$data['code']) {
            $data['attempts']++;
            Session::put("otp.$context", $data);
            throw ValidationException::withMessages(['otp' => 'Invalid OTP.']);
        }

        // Return full registration payload
        return $data['payload'];
    }

    /**
     * Resend OTP (regenerates code but keeps payload)
     */
    public function resend(string $context): int
    {
        $data = Session::get("otp.$context");

        if (!$data) {
            throw ValidationException::withMessages([
                'otp' => 'Session expired. Please restart process.',
            ]);
        }

        // $otp = rand(1000, 9999);
        $otp = 1234; // For testing purposes

        Session::put("otp.$context", [
            'code' => $otp,
            'expires_at' => now()->addMinutes($this->expiryMinutes),
            'attempts' => 0,
            'payload' => $data['payload'],
        ]);

        return $otp;
    }

    /**
     * Clear OTP session for a given context
     */
    public function clear(string $context): void
    {
        Session::forget("otp.$context");
    }
}
