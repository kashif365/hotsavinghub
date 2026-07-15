<?php

namespace Database\Seeders;

use App\Models\Events;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'event_name' => 'Black Friday Sale',
                'event_type' => 'Sale',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(7)->format('Y-m-d'),
                'seo_url' => 'black-friday-sale',
                'meta_title' => 'Black Friday Sale - Massive Discounts',
                'meta_keywords' => 'black friday, sale, discounts, deals',
                'meta_description' => 'Get massive discounts during our Black Friday sale event.',
                'front_image' => 'black-friday-front.jpg',
                'button_icon' => 'sale-icon.png',
                'cover_image' => 'black-friday-cover.jpg',
                'no_coupon_cover' => 'black-friday-no-coupon.jpg',
                'event_short_content' => 'Massive Black Friday discounts on all products',
                'detail_description' => 'Join our biggest sale event of the year with discounts up to 80% off on selected items.',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'event_name' => 'Cyber Monday',
                'event_type' => 'Digital Sale',
                'date_available' => now()->addDays(1)->format('Y-m-d'),
                'date_expiry' => now()->addDays(8)->format('Y-m-d'),
                'seo_url' => 'cyber-monday',
                'meta_title' => 'Cyber Monday - Tech Deals',
                'meta_keywords' => 'cyber monday, tech deals, electronics, digital',
                'meta_description' => 'Best tech deals and electronics discounts on Cyber Monday.',
                'front_image' => 'cyber-monday-front.jpg',
                'button_icon' => 'tech-icon.png',
                'cover_image' => 'cyber-monday-cover.jpg',
                'no_coupon_cover' => 'cyber-monday-no-coupon.jpg',
                'event_short_content' => 'Exclusive tech deals and electronics discounts',
                'detail_description' => 'Discover amazing deals on electronics, gadgets, and tech accessories.',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'event_name' => 'Summer Sale',
                'event_type' => 'Seasonal Sale',
                'date_available' => now()->addDays(2)->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
                'seo_url' => 'summer-sale',
                'meta_title' => 'Summer Sale - Hot Deals',
                'meta_keywords' => 'summer sale, hot deals, seasonal discounts',
                'meta_description' => 'Cool down with our hot summer sale deals.',
                'front_image' => 'summer-sale-front.jpg',
                'button_icon' => 'summer-icon.png',
                'cover_image' => 'summer-sale-cover.jpg',
                'no_coupon_cover' => 'summer-sale-no-coupon.jpg',
                'event_short_content' => 'Hot summer deals and discounts',
                'detail_description' => 'Beat the heat with our cool summer sale featuring amazing discounts.',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'event_name' => 'Back to School',
                'event_type' => 'Educational',
                'date_available' => now()->addDays(3)->format('Y-m-d'),
                'date_expiry' => now()->addDays(45)->format('Y-m-d'),
                'seo_url' => 'back-to-school',
                'meta_title' => 'Back to School - Student Deals',
                'meta_keywords' => 'back to school, student deals, education, supplies',
                'meta_description' => 'Get ready for school with our back to school deals.',
                'front_image' => 'back-to-school-front.jpg',
                'button_icon' => 'school-icon.png',
                'cover_image' => 'back-to-school-cover.jpg',
                'no_coupon_cover' => 'back-to-school-no-coupon.jpg',
                'event_short_content' => 'Essential school supplies at discounted prices',
                'detail_description' => 'Prepare for the new school year with discounted supplies and essentials.',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'event_name' => 'Holiday Special',
                'event_type' => 'Holiday',
                'date_available' => now()->addDays(5)->format('Y-m-d'),
                'date_expiry' => now()->addDays(60)->format('Y-m-d'),
                'seo_url' => 'holiday-special',
                'meta_title' => 'Holiday Special - Festive Deals',
                'meta_keywords' => 'holiday special, festive deals, celebration',
                'meta_description' => 'Celebrate the holidays with our special festive deals.',
                'front_image' => 'holiday-special-front.jpg',
                'button_icon' => 'holiday-icon.png',
                'cover_image' => 'holiday-special-cover.jpg',
                'no_coupon_cover' => 'holiday-special-no-coupon.jpg',
                'event_short_content' => 'Festive deals and holiday specials',
                'detail_description' => 'Make your holidays special with our exclusive festive deals and offers.',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'event_name' => 'New Year Clearance',
                'event_type' => 'Clearance',
                'date_available' => now()->addDays(7)->format('Y-m-d'),
                'date_expiry' => now()->addDays(90)->format('Y-m-d'),
                'seo_url' => 'new-year-clearance',
                'meta_title' => 'New Year Clearance - Fresh Start',
                'meta_keywords' => 'new year clearance, fresh start, clearance sale',
                'meta_description' => 'Start the new year with our clearance sale.',
                'front_image' => 'new-year-clearance-front.jpg',
                'button_icon' => 'clearance-icon.png',
                'cover_image' => 'new-year-clearance-cover.jpg',
                'no_coupon_cover' => 'new-year-clearance-no-coupon.jpg',
                'event_short_content' => 'Clearance sale for the new year',
                'detail_description' => 'Clear out old inventory with our new year clearance sale.',
                'status' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($events as $eventData) {
            Events::firstOrCreate(
                ['seo_url' => $eventData['seo_url']],
                $eventData
            );
        }
    }
}