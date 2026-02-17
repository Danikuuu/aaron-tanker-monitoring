<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface ForgotPasswordInterface
{
    /**
     * Find a user by their email address.
     *
     * @param  string  $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}