<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface ResetPasswordInterface
{
    /**
     * Find a user by their ID.
     *
     * @param  int  $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Update the user's password.
     *
     * @param  User    $user
     * @param  string  $newPassword  Plain-text password (will be hashed here)
     * @return void
     */
    public function updatePassword(User $user, string $newPassword): void;
}