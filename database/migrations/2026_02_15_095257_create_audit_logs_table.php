<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');           // e.g. 'tanker_arrival', 'staff_approved'
            $table->string('description');      // human readable
            $table->string('model_type')->nullable();   // e.g. TankerArrival
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('meta')->nullable();   // extra data
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};