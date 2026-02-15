<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface RegisterInterface
{
    /**
     * Check whether an email already exists in the database.
     */
    public function emailExists(string $email): bool;

    /**
     * Persist a new user record and return the created model.
     */
    public function create(array $data): User;
}
