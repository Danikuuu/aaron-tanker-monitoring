<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterRepository implements RegisterInterface
{
    public function emailExists(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function create(array $data): User
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => $data['password'], // already hashed in service
            'role'       => 'staff', // default role on registration
        ]);
    }
}
