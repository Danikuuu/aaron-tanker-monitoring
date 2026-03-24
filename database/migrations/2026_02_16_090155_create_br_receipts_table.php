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
            $table->decimal('downpayment', 12, 2)->default(0)->change();
            $table->timestamps();
        });

        Schema::create('br_receipt_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('br_receipt_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('payment_method')->nullable();  // cash, check, transfer, etc.
            $table->string('reference_no')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('br_receipt_fuels');
        Schema::dropIfExists('br_receipts');
    }
};