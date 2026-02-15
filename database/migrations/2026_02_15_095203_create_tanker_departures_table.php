<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanker_departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->string('tanker_number');
            $table->string('driver');
            $table->date('departure_date');
            $table->timestamps();
        });

        Schema::create('tanker_departure_fuels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanker_departure_id')->constrained()->cascadeOnDelete();
            $table->enum('fuel_type', ['diesel', 'premium', 'unleaded', 'methanol']);
            $table->decimal('liters', 10, 2);
            $table->decimal('methanol_percent', 5, 2)->default(0);
            $table->decimal('methanol_liters', 10, 2)->default(0);
            $table->decimal('pure_liters', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanker_departure_fuels');
        Schema::dropIfExists('tanker_departures');
    }
};