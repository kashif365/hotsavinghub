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
        // Insert home banner setting if it doesn't exist
        DB::table('settings')->insertOrIgnore([
            'key' => 'home_banner',
            'value' => '',
            'type' => 'image',
            'group' => 'branding',
            'label' => 'Home Page Banner',
            'description' => 'Upload banner image for home page hero section',
            'sort_order' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update sort_order for other branding settings
        DB::table('settings')
            ->where('key', 'primary_color')
            ->update(['sort_order' => 6]);

        DB::table('settings')
            ->where('key', 'secondary_color')
            ->update(['sort_order' => 7]);

        DB::table('settings')
            ->where('key', 'background_primary_color')
            ->update(['sort_order' => 8]);

        DB::table('settings')
            ->where('key', 'background_secondary_color')
            ->update(['sort_order' => 9]);

        DB::table('settings')
            ->where('key', 'text_color')
            ->update(['sort_order' => 10]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove home banner setting
        DB::table('settings')->where('key', 'home_banner')->delete();

        // Restore original sort_order for other branding settings
        DB::table('settings')
            ->where('key', 'primary_color')
            ->update(['sort_order' => 5]);

        DB::table('settings')
            ->where('key', 'secondary_color')
            ->update(['sort_order' => 6]);

        DB::table('settings')
            ->where('key', 'background_primary_color')
            ->update(['sort_order' => 7]);

        DB::table('settings')
            ->where('key', 'background_secondary_color')
            ->update(['sort_order' => 8]);

        DB::table('settings')
            ->where('key', 'text_color')
            ->update(['sort_order' => 9]);
    }
};