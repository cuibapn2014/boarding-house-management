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
            $table->boolean('require_deposit')->default(false)->after('price');
            $table->integer('deposit_amount')->nullable()->after('require_deposit');
            $table->integer('min_contract_months')->nullable()->after('deposit_amount');
            $table->integer('area')->nullable()->after('min_contract_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->dropColumn(['require_deposit', 'deposit_amount', 'min_contract_months', 'area']);
        });
    }
};
