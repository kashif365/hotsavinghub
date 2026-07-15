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
        // Insert home overlay color setting if it doesn't exist
        DB::table('settings')->insertOrIgnore([
            'key' => 'home_overlay_color',
            'value' => 'rgba(0, 0, 0, 0.5)',
            'type' => 'text',
            'group' => 'branding',
            'label' => 'Home Page Overlay Color',
            'description' => 'Background overlay color for home page banner',
            'sort_order' => 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update sort_order for other branding settings
        DB::table('settings')
            ->where('key', 'primary_color')
            ->update(['sort_order' => 9]);

        DB::table('settings')
            ->where('key', 'secondary_color')
            ->update(['sort_order' => 10]);

        DB::table('settings')
            ->where('key', 'background_primary_color')
            ->update(['sort_order' => 11]);

        DB::table('settings')
            ->where('key', 'background_secondary_color')
            ->update(['sort_order' => 12]);

        DB::table('settings')
            ->where('key', 'text_color')
            ->update(['sort_order' => 13]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove home overlay color setting
        DB::table('settings')->where('key', 'home_overlay_color')->delete();

        // Restore original sort_order for other branding settings
        DB::table('settings')
            ->where('key', 'primary_color')
            ->update(['sort_order' => 8]);

        DB::table('settings')
            ->where('key', 'secondary_color')
            ->update(['sort_order' => 9]);

        DB::table('settings')
            ->where('key', 'background_primary_color')
            ->update(['sort_order' => 10]);

        DB::table('settings')
            ->where('key', 'background_secondary_color')
            ->update(['sort_order' => 11]);

        DB::table('settings')
            ->where('key', 'text_color')
            ->update(['sort_order' => 12]);
    }
};