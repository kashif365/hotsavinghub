<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->unique();
            $table->boolean('status')->default(1);
            $table->integer('sort_order')->default(0); // Sort Order field in DB
            $table->text('page_content')->nullable();
            $table->string('seo_url')->unique();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('media')->nullable(); // main image
            $table->string('banner_image')->nullable(); // banner image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};


