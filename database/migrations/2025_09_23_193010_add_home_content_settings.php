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
        // Insert home heading setting if it doesn't exist
        DB::table('settings')->insertOrIgnore([
            'key' => 'home_heading',
            'value' => 'Save Big with Exclusive Coupon Codes',
            'type' => 'text',
            'group' => 'branding',
            'label' => 'Home Page Heading',
            'description' => 'Main heading text for home page hero section',
            'sort_order' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert home subheading setting if it doesn't exist
        DB::table('settings')->insertOrIgnore([
            'key' => 'home_subheading',
            'value' => 'Discover thousands of verified discount codes from your favorite brands. Start saving money on every purchase today!',
            'type' => 'textarea',
            'group' => 'branding',
            'label' => 'Home Page Subheading',
            'description' => 'Subheading text for home page hero section',
            'sort_order' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update sort_order for other branding settings
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove home content settings
        DB::table('settings')->where('key', 'home_heading')->delete();
        DB::table('settings')->where('key', 'home_subheading')->delete();

        // Restore original sort_order for other branding settings
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
};