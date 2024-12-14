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
        Schema::create('boarding_houses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('thumbnail')->nullable();
            $table->string('description', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('district', 50)->nullable();
            $table->string('ward', 50)->nullable();
            $table->string('address', 50)->nullable();
            $table->string('phone')->nullable();
            $table->integer('price');
            $table->string('status')->nullable();
            $table->boolean('is_publish')->default(false);
            $table->string('tags', 500)->nullable();

            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarding_houses');
    }
};
