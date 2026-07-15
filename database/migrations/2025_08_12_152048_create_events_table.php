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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('event_type')->nullable();
            $table->date('date_available')->nullable();
            $table->date('date_expiry')->nullable();
            $table->string('seo_url')->unique();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('front_image')->nullable();
            $table->string('button_icon')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('no_coupon_cover')->nullable();
            $table->text('event_short_content')->nullable();
            $table->text('detail_description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
