<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashback_brands', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('store_name');
            $table->string('cashback_rate', 20);
            $table->string('redirect_url', 2048)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashback_brands');
    }
};
