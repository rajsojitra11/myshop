<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // Supplier ID
            $table->unsignedBigInteger('uid'); // Ensure it matches users.id
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade'); // Foreign key
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('contact_no');
            $table->string('country');
            $table->text('bank_details');
            $table->text('product_categories');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
