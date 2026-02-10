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
            $table->unsignedTinyInteger('listing_days')->nullable()->after('is_publish')->comment('Số ngày hiển thị tin: 10, 15, 30, 60');
            $table->timestamp('published_at')->nullable()->after('listing_days')->comment('Thời điểm đăng tin');
            $table->timestamp('expires_at')->nullable()->after('published_at')->comment('Thời điểm hết hạn hiển thị');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropColumn(['listing_days', 'published_at', 'expires_at']);
        });
    }
};
