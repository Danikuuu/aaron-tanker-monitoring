<?php

namespace App\Services\Auth;

use App\Models\AuditLog;
use App\Repositories\Auth\ResetPasswordInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ResetPasswordService
{
    public function __construct(
        protected ResetPasswordInterface $resetPasswordRepository,
    ) {}

    /**
     * Pull the verified user ID from session, find the user,
     * update their password, and clear the session key.
     *
     * @throws ValidationException  When the session has expired or user is not found
     */
    public function reset(string $newPassword): void
    {
        $userId = Session::get('password_reset_verified_user');

        if (!$userId) {
            throw ValidationException::withMessages([
                'password' => 'Session expired. Please restart the password reset process.',
            ]);
        }

        $user = $this->resetPasswordRepository->findById($userId);

        if (!$user) {
            throw ValidationException::withMessages([
                'password' => 'User not found.',
            ]);
        }

        $this->resetPasswordRepository->updatePassword($user, $newPassword);

        AuditLog::record('Reset Password', "User reset their password: {$user->email}", $user);

        // Clean up — prevent the reset form from being reused
        Session::forget('password_reset_verified_user');
    }
}