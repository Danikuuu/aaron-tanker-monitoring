<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanker_arrivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->string('tanker_number');
            $table->date('arrival_date');
            $table->timestamps();
        });

        Schema::create('tanker_arrival_fuels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanker_arrival_id')->constrained()->cascadeOnDelete();
            $table->enum('fuel_type', ['diesel', 'premium', 'unleaded', 'methanol']);
            $table->decimal('liters', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanker_arrival_fuels');
        Schema::dropIfExists('tanker_arrivals');
    }
};