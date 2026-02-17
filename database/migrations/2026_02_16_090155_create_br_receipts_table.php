<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('br_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanker_departure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->string('receipt_no');
            $table->string('delivered_to')->nullable();
            $table->string('address')->nullable();
            $table->string('tin')->nullable();
            $table->string('terms')->nullable();
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('br_receipt_fuels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('br_receipt_id')->constrained()->cascadeOnDelete();
            $table->string('fuel_type');
            $table->decimal('liters', 10, 2);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('br_receipt_fuels');
        Schema::dropIfExists('br_receipts');
    }
};