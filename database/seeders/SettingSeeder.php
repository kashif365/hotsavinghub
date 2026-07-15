<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // Branding Settings
            ['key' => 'site_name', 'value' => 'Social Offerz', 'type' => 'text', 'group' => 'branding', 'label' => 'Site Name', 'description' => 'The name of your website', 'sort_order' => 1],
            ['key' => 'site_tagline', 'value' => 'Save More, Shop Smart', 'type' => 'text', 'group' => 'branding', 'label' => 'Site Tagline', 'description' => 'A short tagline for your website', 'sort_order' => 2],
            ['key' => 'site_logo', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Site Logo', 'description' => 'Upload your site logo', 'sort_order' => 3],
            ['key' => 'site_favicon', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Site Favicon', 'description' => 'Upload your site favicon', 'sort_order' => 4],
            ['key' => 'primary_color', 'value' => '#FF0000', 'type' => 'text', 'group' => 'branding', 'label' => 'Primary Color', 'description' => 'Main color for your brand', 'sort_order' => 5],
            ['key' => 'secondary_color', 'value' => '#000000', 'type' => 'text', 'group' => 'branding', 'label' => 'Secondary Color', 'description' => 'Secondary color for your brand', 'sort_order' => 6],
            ['key' => 'home_banner', 'value' => '', 'type' => 'image', 'group' => 'branding', 'label' => 'Home Page Banner', 'description' => 'Upload banner image for home page hero section', 'sort_order' => 7],
            ['key' => 'home_heading', 'value' => 'Save Big with Exclusive Coupon Codes', 'type' => 'text', 'group' => 'branding', 'label' => 'Home Page Heading', 'description' => 'Main heading text for home page hero section', 'sort_order' => 8],
            ['key' => 'home_subheading', 'value' => 'Discover thousands of verified discount codes from your favorite brands. Start saving money on every purchase today!', 'type' => 'textarea', 'group' => 'branding', 'label' => 'Home Page Subheading', 'description' => 'Subheading text for home page hero section', 'sort_order' => 9],
            ['key' => 'home_overlay_color', 'value' => 'rgba(0, 0, 0, 0.5)', 'type' => 'text', 'group' => 'branding', 'label' => 'Home Page Overlay Color', 'description' => 'Background overlay color for home page banner', 'sort_order' => 10],

            // Contact Settings
            ['key' => 'contact_email', 'value' => 'support@bighsavinghub.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Contact Email', 'description' => 'Main contact email address', 'sort_order' => 1],
            ['key' => 'contact_phone', 'value' => '+44 20 7946 0958', 'type' => 'phone', 'group' => 'contact', 'label' => 'Contact Phone', 'description' => 'Main contact phone number', 'sort_order' => 2],
            ['key' => 'contact_address', 'value' => '123 Business Street, London, UK SW1A 1AA', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Contact Address', 'description' => 'Physical address of your business', 'sort_order' => 3],
            ['key' => 'business_hours', 'value' => 'Mon-Fri: 9AM-6PM GMT', 'type' => 'text', 'group' => 'contact', 'label' => 'Business Hours', 'description' => 'Your business operating hours', 'sort_order' => 4],
            ['key' => 'support_email', 'value' => 'support@bighsavinghub.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Support Email', 'description' => 'Customer support email', 'sort_order' => 5],
            ['key' => 'partnership_email', 'value' => 'partnerships@bighsavinghub.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Partnership Email', 'description' => 'Partnership inquiries email', 'sort_order' => 6],

            // Social Media Settings
            ['key' => 'facebook_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Your Facebook page URL', 'sort_order' => 1],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Twitter URL', 'description' => 'Your Twitter profile URL', 'sort_order' => 2],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Your Instagram profile URL', 'sort_order' => 3],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'LinkedIn URL', 'description' => 'Your LinkedIn profile URL', 'sort_order' => 4],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'YouTube URL', 'description' => 'Your YouTube channel URL', 'sort_order' => 5],
            ['key' => 'tiktok_url', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'TikTok URL', 'description' => 'Your TikTok profile URL', 'sort_order' => 6],

            // General Settings
            ['key' => 'site_description', 'value' => 'Find the best discount codes and deals for your favorite stores. Save money on every purchase with Social Offerz.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'SEO description for your website', 'sort_order' => 1],
            ['key' => 'site_keywords', 'value' => 'discount codes, coupons, deals, savings, shopping', 'type' => 'text', 'group' => 'general', 'label' => 'Site Keywords', 'description' => 'SEO keywords for your website', 'sort_order' => 2],
            ['key' => 'timezone', 'value' => 'Europe/London', 'type' => 'text', 'group' => 'general', 'label' => 'Timezone', 'description' => 'Default timezone for your website', 'sort_order' => 3],
            ['key' => 'currency', 'value' => 'GBP', 'type' => 'text', 'group' => 'general', 'label' => 'Currency', 'description' => 'Default currency for your website', 'sort_order' => 4],
            ['key' => 'items_per_page', 'value' => '20', 'type' => 'text', 'group' => 'general', 'label' => 'Items Per Page', 'description' => 'Number of items to show per page', 'sort_order' => 5],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
