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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('discount_type')->default('percentage')->after('coupon_code');
            $table->decimal('discount_value', 8, 2)->default(0)->after('discount_type');
            $table->unsignedBigInteger('store_id')->nullable()->after('discount_value');
            $table->integer('usage_limit')->default(1000)->after('store_id');
            $table->integer('used_count')->default(0)->after('usage_limit');
            
            // Add foreign key for store_id
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn(['discount_type', 'discount_value', 'store_id', 'usage_limit', 'used_count']);
        });
    }
};