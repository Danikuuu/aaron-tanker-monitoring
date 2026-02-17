<?php

namespace App\Services\Auth;

use App\Mail\NewStaffRegistered;
use App\Mail\OtpMail;
use App\Models\AuditLog;
use App\Models\User;
use App\Repositories\Auth\RegisterInterface;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class RegisterService
{
    protected RegisterInterface $registerRepository;

    public function __construct(RegisterInterface $registerRepository)
    {
        $this->registerRepository = $registerRepository;
    }

    /**
     * Step 1: Handle initial registration request
     * - Verify reCAPTCHA
     * - Check duplicate email
     * - Generate OTP (DO NOT create user yet)
     */
    public function register(array $data, OtpService $otpService): void
    {
        // 1️⃣ Verify reCAPTCHA
        $this->verifyRecaptcha($data['g-recaptcha-response']);

        // 2️⃣ Prevent duplicate email
        if ($this->registerRepository->emailExists($data['email'])) {
            throw ValidationException::withMessages([
                'email' => 'This email address is already registered.',
            ]);
        }

        // 3️⃣ Generate OTP and store registration payload
        $otp = $otpService->generate('register', [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'role'       => 'staff',
        ]);

        // 4️⃣ Send OTP email
        Mail::to($data['email'])->send(new OtpMail($otp));

        AuditLog::record('Register', "New staff registration OTP sent to: {$data['email']}");
    }

    /**
     * Step 2: Called AFTER OTP is verified
     * - Actually create user in database
     */
    public function completeRegistration(array $payload): User
    {
        $user = $this->registerRepository->create($payload);

        AuditLog::record('Register', "New staff account created: {$user->email}", $user);

        // Notify all admins of new registration
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewStaffRegistered($user));
        }

        return $user;
    }

    /**
     * Verify Google reCAPTCHA v2 token.
     */
    private function verifyRecaptcha(string $token): void
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => request()->ip(),
            ]
        );

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'reCAPTCHA verification failed.',
            ]);
        }
    }
}