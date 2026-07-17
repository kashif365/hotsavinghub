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
            $table->string('label')->nullable()->after('background_image');
            $table->string('heading')->nullable()->after('label');
            $table->string('subtitle')->nullable()->after('heading');
            $table->string('cta_text')->nullable()->after('subtitle');
            $table->string('cta_url')->nullable()->after('cta_text');
            $table->string('secondary_image')->nullable()->after('cta_url');
            $table->string('logo')->nullable()->after('secondary_image');
            $table->string('badge_color', 7)->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn([
                'label',
                'heading',
                'subtitle',
                'cta_text',
                'cta_url',
                'secondary_image',
                'logo',
                'badge_color',
            ]);
        });
    }
};
