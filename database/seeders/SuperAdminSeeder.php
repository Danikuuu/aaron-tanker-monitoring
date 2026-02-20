<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Super Admin',
            'email' => 'superadmin@aaron.com',
            'password' => bcrypt('111111'),
            'role' => 'super_admin',
            'status' => 'approved',
        ]);
    }
}
