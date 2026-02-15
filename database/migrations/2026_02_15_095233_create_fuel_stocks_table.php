<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_stocks', function (Blueprint $table) {
            $table->id();
            $table->enum('fuel_type', ['diesel', 'premium', 'unleaded', 'methanol'])->unique();
            $table->decimal('liters', 10, 2)->default(0);
            $table->timestamps();
        });

        // Seed initial stock rows
        DB::table('fuel_stocks')->insert([
            ['fuel_type' => 'diesel',   'liters' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['fuel_type' => 'premium',  'liters' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['fuel_type' => 'unleaded', 'liters' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['fuel_type' => 'methanol', 'liters' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_stocks');
    }
};