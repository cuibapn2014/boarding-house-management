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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 50);
            $table->string('phone', 11);
            $table->integer('total_person');
            $table->integer('total_bike');
            $table->integer('boarding_house_id');
            $table->date('move_in_date')->nullable();
            $table->string('status', 32);
            $table->string('note', 255)->nullable();
            $table->dateTime('appointment_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
