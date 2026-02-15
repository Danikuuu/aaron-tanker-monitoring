<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'is_blocked']);
            $table->enum('status', ['pending', 'approved', 'blocked'])->default('pending')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_blocked')->default(false);
        });
    }
};
