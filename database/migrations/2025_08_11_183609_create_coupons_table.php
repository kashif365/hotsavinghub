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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->boolean('exclusive')->default(0);
            $table->boolean('featured')->default(0);
            $table->boolean('recommended')->default(0);
            $table->boolean('verified')->default(0);
            $table->boolean('status')->default(1);
            $table->string('coupon_title');
            $table->string('brand_store');
            $table->string('coupon_code')->nullable();

            // event_id will be added in a separate migration after events table is created

            $table->string('submitted_by')->nullable();
            $table->string('affiliate_url')->nullable();
            $table->date('date_available')->nullable();
            $table->date('date_expiry')->nullable();
            $table->boolean('expiry_soon')->default(0);
            $table->text('description')->nullable();
            $table->text('terms')->nullable();
            $table->string('cover_logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
