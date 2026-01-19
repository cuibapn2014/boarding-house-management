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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_id')->nullable()->comment('ID từ SePay webhook');
            $table->string('payment_code')->nullable()->index()->comment('Mã thanh toán được extract');
            $table->string('status', 20)->default('pending')->comment('pending, processing, success, failed');
            $table->string('gateway')->nullable()->comment('Ngân hàng (MBBank, etc.)');
            $table->string('transfer_type', 10)->nullable()->comment('in, out');
            $table->decimal('transfer_amount', 15, 2)->nullable()->comment('Số tiền từ webhook');
            $table->string('account_number')->nullable()->comment('Số tài khoản');
            $table->text('content')->nullable()->comment('Nội dung chuyển khoản');
            $table->text('raw_payload')->nullable()->comment('Raw JSON payload');
            $table->text('validation_errors')->nullable()->comment('Lỗi validation nếu có');
            $table->text('processing_errors')->nullable()->comment('Lỗi xử lý nếu có');
            $table->string('ip_address', 45)->nullable()->comment('IP của request');
            $table->string('user_agent')->nullable()->comment('User agent');
            $table->timestamp('processed_at')->nullable()->comment('Thời gian xử lý xong');
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index(['payment_code', 'status']);
            $table->index('webhook_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
