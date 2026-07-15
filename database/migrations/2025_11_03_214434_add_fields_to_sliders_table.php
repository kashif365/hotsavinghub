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
        Schema::table('sliders', function (Blueprint $table) {
            // Make any existing heading/content columns nullable if they exist
            if (Schema::hasColumn('sliders', 'heading')) {
                $table->string('heading')->nullable()->change();
            }
            if (Schema::hasColumn('sliders', 'content')) {
                $table->text('content')->nullable()->change();
            }
            
            // Add new columns if they don't exist
            if (!Schema::hasColumn('sliders', 'background_image')) {
                $table->string('background_image')->nullable()->after('id');
            }
            if (!Schema::hasColumn('sliders', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('background_image');
            }
            if (!Schema::hasColumn('sliders', 'status')) {
                $table->boolean('status')->default(1)->after('sort_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['background_image', 'sort_order', 'status']);
        });
    }
};
