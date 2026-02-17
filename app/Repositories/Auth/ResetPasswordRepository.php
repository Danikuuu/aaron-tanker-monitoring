<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordRepository implements ResetPasswordInterface
{
    /**
     * Find a user by their ID.
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Hash and save the new password.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}