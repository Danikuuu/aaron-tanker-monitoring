<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $faker = Faker::create();

        return [
            'first_name' => $faker->firstName(),
            'last_name'  => $faker->lastName(),
            'email'      => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'   => Hash::make('password'),
            'role'       => 'staff',
            'remember_token' => Str::random(10),
            'status'     => 'approved',
        ];
    }
}
