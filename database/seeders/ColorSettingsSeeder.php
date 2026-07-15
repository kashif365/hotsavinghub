<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class ColorSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colorSettings = [
            [
                'key' => 'background_primary_color',
                'value' => '#FFFFFF',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Background Primary Color',
                'description' => 'Main background color',
                'sort_order' => 11
            ],
            [
                'key' => 'background_secondary_color',
                'value' => '#F8F9FA',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Background Secondary Color',
                'description' => 'Secondary background color',
                'sort_order' => 12
            ],
            [
                'key' => 'text_color',
                'value' => '#333333',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Text Color',
                'description' => 'Main text color',
                'sort_order' => 13
            ]
        ];

        foreach ($colorSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}