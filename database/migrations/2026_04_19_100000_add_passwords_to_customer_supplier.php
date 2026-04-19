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
        // Add password column to suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
        });

        // Create customer_passwords table for storing customer hashed passwords
        Schema::create('customer_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('mobile_no')->nullable()->index();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('password');
        });

        Schema::dropIfExists('customer_passwords');
    }
};
