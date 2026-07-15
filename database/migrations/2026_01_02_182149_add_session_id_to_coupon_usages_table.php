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
        Schema::table('coupon_usages', function (Blueprint $table) {
            if (!Schema::hasColumn('coupon_usages', 'session_id')) {
                $table->string('session_id')->nullable()->after('ip_address');
                $table->index(['coupon_id', 'ip_address', 'session_id', 'usage_date'], 'coupon_usage_unique_check');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_usages', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_usages', 'session_id')) {
                $table->dropIndex('coupon_usage_unique_check');
                $table->dropColumn('session_id');
            }
        });
    }
};
