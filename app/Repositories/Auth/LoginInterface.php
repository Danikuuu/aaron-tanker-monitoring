<?php

namespace App\Repositories\Auth;

use App\Models\User;

interface LoginInterface
{
    public function findByEmail(string $email): ?User;
     public function findById(int $id): ?User;
}