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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code', 50)->unique()->comment('Mã thanh toán duy nhất (SE123456)');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Người thanh toán');
            $table->foreignId('boarding_house_id')->nullable()->constrained('boarding_houses')->onDelete('set null')->comment('Phòng trọ liên quan');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null')->comment('Cuộc hẹn liên quan');
            $table->string('payment_type', 50)->comment('Loại thanh toán: deposit, rent, booking_fee');
            $table->decimal('amount', 15, 2)->comment('Số tiền thanh toán');
            $table->string('currency', 10)->default('VND')->comment('Đơn vị tiền tệ');
            $table->string('status', 20)->default('pending')->comment('Trạng thái: pending, processing, completed, failed, cancelled');
            $table->text('description')->nullable()->comment('Mô tả thanh toán');
            $table->string('sepay_reference_code')->nullable()->comment('Mã tham chiếu từ SePay');
            $table->string('sepay_transaction_id')->nullable()->comment('ID giao dịch từ SePay');
            $table->timestamp('paid_at')->nullable()->comment('Thời gian thanh toán thành công');
            $table->timestamp('expires_at')->nullable()->comment('Thời gian hết hạn thanh toán');
            $table->json('metadata')->nullable()->comment('Dữ liệu bổ sung');
            $table->timestamps();
            
            // Single column indexes
            $table->index('payment_code');
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
            
            // Composite indexes for common queries
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'expires_at']);
            $table->index(['boarding_house_id', 'status']);
            $table->index(['payment_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
