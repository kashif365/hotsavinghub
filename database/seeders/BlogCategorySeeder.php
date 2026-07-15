<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Shopping Tips',
                'description' => 'Expert advice on smart shopping and finding the best deals',
                'color' => '#FF6B6B',
                'sort_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Money Saving',
                'description' => 'Tips and tricks to save money on your purchases',
                'color' => '#4ECDC4',
                'sort_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Fashion & Style',
                'description' => 'Latest fashion trends and style guides',
                'color' => '#45B7D1',
                'sort_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Technology',
                'description' => 'Tech reviews, deals, and gadget recommendations',
                'color' => '#96CEB4',
                'sort_order' => 4,
                'status' => true,
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement tips and garden inspiration',
                'color' => '#FFEAA7',
                'sort_order' => 5,
                'status' => true,
            ],
            [
                'name' => 'Health & Beauty',
                'description' => 'Health tips and beauty product reviews',
                'color' => '#DDA0DD',
                'sort_order' => 6,
                'status' => true,
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel guides, tips, and destination reviews',
                'color' => '#98D8C8',
                'sort_order' => 7,
                'status' => true,
            ],
            [
                'name' => 'Food & Beverage',
                'description' => 'Food reviews, recipes, and dining deals',
                'color' => '#F7DC6F',
                'sort_order' => 8,
                'status' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            BlogCategory::create($categoryData);
        }
    }
}
