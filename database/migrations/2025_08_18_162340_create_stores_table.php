<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->boolean('covid_disable')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('recommended')->default(false);
            $table->boolean('auto_sort')->default(false);
            $table->boolean('show_trending')->default(false);
            $table->boolean('status')->default(true);

            $table->string('store_name');
            $table->string('affiliate_url')->nullable();

            // Relations (nullable in case not assigned yet)
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();

            $table->string('store_logo')->nullable();

            // Networks relations
            $table->unsignedBigInteger('current_network')->nullable();
            $table->unsignedBigInteger('available_network')->nullable();

            $table->text('content')->nullable();
            $table->text('detail_description')->nullable();

            // SEO Fields
            $table->string('title_heading')->nullable();
            $table->string('seo_url')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->string('cover_image')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');

            $table->foreign('current_network')->references('id')->on('networks')->onDelete('set null');
            $table->foreign('available_network')->references('id')->on('networks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
