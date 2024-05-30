<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('checkout_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->integer('age')->nullable();
            $table->string('locationCity')->nullable();
            $table->string('carToRent')->nullable();
            $table->string('token')->nullable();
            $table->string('sessionId')->nullable();
            $table->string('email')->nullable();
            $table->string('last4')->nullable();
            $table->string('amount_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_sessions');
    }
};
