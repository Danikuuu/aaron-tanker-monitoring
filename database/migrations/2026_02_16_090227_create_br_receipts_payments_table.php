<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('br_receipt_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('br_receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->string('client_name');
            $table->decimal('total_amount', 12, 2);          // mirrors grand_total
            $table->decimal('down_payment', 12, 2)->default(0);
            $table->date('down_payment_date')->nullable();
            $table->decimal('final_payment', 12, 2)->default(0);
            $table->date('final_payment_date')->nullable();
            $table->date('due_date')->nullable();             // admin-set due date for final
            $table->text('notes')->nullable();
            // status: unpaid | partial | paid
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('br_receipt_payments');
    }
};