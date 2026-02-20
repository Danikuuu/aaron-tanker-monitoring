<?php

namespace App\Services\Auth;

use App\Models\AuditLog;
use App\Repositories\Auth\LoginInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class LoginService
{
    protected $loginRepository;

    public function __construct(LoginInterface $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    public function login(array $data): void
    {
        // Verify reCAPTCHA
        $this->verifyRecaptcha($data['g-recaptcha-response']);

        // Check user exists
        $user = $this->loginRepository->findByEmail($data['email']);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        if ($user->role === 'staff' && !$user->is_approved) {
            throw ValidationException::withMessages([
                'email' => 'Your account is pending admin approval. You will be notified once approved.',
            ]);
        }

        if ($user->is_blocked) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been blocked. Please contact an administrator.',
            ]);
        }

        // Attempt authentication
        if (!Auth::attempt([
            'email'    => $data['email'],
            'password' => $data['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        // Regenerate session
    }

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

    public function checkCredentials(array $data)
    {
        $this->verifyRecaptcha($data['g-recaptcha-response']);

        $user = $this->loginRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        if ($user->role === 'staff' && $user->isPending()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is pending admin approval. You will be notified once approved.',
            ]);
        }

        if ($user->isBlocked()) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been blocked. Please contact an administrator.',
            ]);
        }

        AuditLog::record('Login', "User passed credential check: {$user->email}", $user);

        return $user;
    }

    public function loginById(int $userId)
    {
        $user = $this->loginRepository->findById($userId);

        Auth::login($user);
        session()->regenerate();

        AuditLog::record('Login', "User logged in successfully: {$user->email}", $user);

        return $user;
    }
}