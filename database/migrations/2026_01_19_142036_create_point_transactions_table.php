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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người dùng');
            $table->string('transaction_type', 50)->comment('Loại giao dịch: top_up, deduction, refund, service_payment');
            $table->decimal('amount', 15, 2)->comment('Số điểm (dương = cộng, âm = trừ)');
            $table->decimal('balance_before', 15, 2)->comment('Số dư trước giao dịch');
            $table->decimal('balance_after', 15, 2)->comment('Số dư sau giao dịch');
            $table->string('reference_type')->nullable()->comment('Loại tham chiếu: PointPackage, ServicePayment, Payment');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('ID tham chiếu');
            $table->text('description')->nullable()->comment('Mô tả giao dịch');
            $table->json('metadata')->nullable()->comment('Dữ liệu bổ sung');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('transaction_type');
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
