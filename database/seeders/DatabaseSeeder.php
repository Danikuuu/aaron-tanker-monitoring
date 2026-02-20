<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Staff',
            'email' => '6ifau5nfyl@ruutukf.com',
            'password' => bcrypt('111111'),
            'role' => 'staff',
            'status' => 'approved',
        ]);

        $this->call(FuelOperationsSeeder::class);

        
        $this->call(DemoDataSeeder::class);

        $this->call(SuperAdminSeeder::class);
    }
}
