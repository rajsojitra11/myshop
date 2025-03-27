<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('mobile_no');
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('uid')->after('id')->nullable(); // Add uid column
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade'); // Add foreign key
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['uid']); // Drop foreign key first
            $table->dropColumn('uid'); // Then drop column
        });

        Schema::dropIfExists('customers');
    }
};
