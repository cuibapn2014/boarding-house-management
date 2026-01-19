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
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người dùng');
            $table->string('service_type', 50)->comment('Loại dịch vụ: push_listing, priority_listing, extend_listing');
            $table->string('service_name', 100)->comment('Tên dịch vụ');
            $table->integer('points_cost')->comment('Số điểm cần thanh toán');
            $table->decimal('cash_amount', 15, 2)->nullable()->comment('Số tiền mặt (nếu thanh toán bằng tiền)');
            $table->string('payment_method', 20)->comment('Phương thức thanh toán: points, cash, mixed');
            $table->foreignId('boarding_house_id')->nullable()->constrained('boarding_houses')->onDelete('set null')->comment('Phòng trọ liên quan');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null')->comment('Thanh toán tiền mặt (nếu có)');
            $table->string('status', 20)->default('pending')->comment('Trạng thái: pending, completed, failed, cancelled');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->json('metadata')->nullable()->comment('Dữ liệu bổ sung');
            $table->timestamp('completed_at')->nullable()->comment('Thời gian hoàn thành');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('service_type');
            $table->index('status');
            $table->index('boarding_house_id');
            $table->index('created_at');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};
