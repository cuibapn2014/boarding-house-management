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
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->string('meta_title', 70)->nullable()->after('tags');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('canonical_url', 500)->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'canonical_url']);
        });
    }
};
