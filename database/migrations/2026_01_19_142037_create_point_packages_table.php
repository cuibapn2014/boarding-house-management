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
        Schema::create('point_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Tên gói (VD: Gói 100k)');
            $table->text('description')->nullable()->comment('Mô tả gói');
            $table->decimal('price', 15, 2)->comment('Giá tiền (VNĐ)');
            $table->integer('points')->comment('Số điểm nhận được');
            $table->integer('bonus_points')->default(0)->comment('Điểm thưởng (nếu có)');
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->integer('sort_order')->default(0)->comment('Thứ tự hiển thị');
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_packages');
    }
};
