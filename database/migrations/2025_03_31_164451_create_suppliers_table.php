<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('supplier_id')->unique();
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('contact_no');
            $table->string('country');
            $table->string('bank_details');
            $table->string('product_categories');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};
