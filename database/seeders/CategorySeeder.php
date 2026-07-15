<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Fashion & Clothing',
                'seo_url' => 'fashion-clothing',
                'meta_title' => 'Fashion & Clothing Discount Codes',
                'meta_description' => 'Get the best discount codes for fashion and clothing stores.',
                'short_content' => 'Latest fashion deals and discounts',
                'description' => 'Discover amazing discounts on fashion and clothing from top brands.',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'category_name' => 'Electronics',
                'seo_url' => 'electronics',
                'meta_title' => 'Electronics Discount Codes',
                'meta_description' => 'Save money on electronics with our discount codes.',
                'short_content' => 'Electronics deals and offers',
                'description' => 'Get the best deals on electronics and gadgets.',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'category_name' => 'Home & Garden',
                'seo_url' => 'home-garden',
                'meta_title' => 'Home & Garden Discount Codes',
                'meta_description' => 'Transform your home with our home and garden discount codes.',
                'short_content' => 'Home improvement deals',
                'description' => 'Make your home beautiful with discounted home and garden products.',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'category_name' => 'Food & Drink',
                'seo_url' => 'food-drink',
                'meta_title' => 'Food & Drink Discount Codes',
                'meta_description' => 'Save on food and drinks with our discount codes.',
                'short_content' => 'Food and beverage deals',
                'description' => 'Enjoy great food and drinks at discounted prices.',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'category_name' => 'Health & Beauty',
                'seo_url' => 'health-beauty',
                'meta_title' => 'Health & Beauty Discount Codes',
                'meta_description' => 'Look and feel great with our health and beauty discount codes.',
                'short_content' => 'Health and beauty offers',
                'description' => 'Take care of yourself with discounted health and beauty products.',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'category_name' => 'Travel & Holidays',
                'seo_url' => 'travel-holidays',
                'meta_title' => 'Travel & Holidays Discount Codes',
                'meta_description' => 'Plan your perfect trip with our travel discount codes.',
                'short_content' => 'Travel deals and offers',
                'description' => 'Explore the world with discounted travel and holiday packages.',
                'status' => true,
                'sort_order' => 6,
            ],
            [
                'category_name' => 'Sports & Fitness',
                'seo_url' => 'sports-fitness',
                'meta_title' => 'Sports & Fitness Discount Codes',
                'meta_description' => 'Stay active with our sports and fitness discount codes.',
                'short_content' => 'Sports and fitness deals',
                'description' => 'Get fit and stay healthy with discounted sports and fitness equipment.',
                'status' => true,
                'sort_order' => 7,
            ],
            [
                'category_name' => 'Books & Media',
                'seo_url' => 'books-media',
                'meta_title' => 'Books & Media Discount Codes',
                'meta_description' => 'Entertain yourself with our books and media discount codes.',
                'short_content' => 'Books and media offers',
                'description' => 'Enjoy books, movies, and music at discounted prices.',
                'status' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['seo_url' => $categoryData['seo_url']],
                $categoryData
            );
        }

        // Add some subcategories
        $parentCategories = Category::take(4)->get();
        
        $subcategories = [
            ['category_name' => 'Men\'s Clothing', 'seo_url' => 'mens-clothing'],
            ['category_name' => 'Women\'s Clothing', 'seo_url' => 'womens-clothing'],
            ['category_name' => 'Kids\' Clothing', 'seo_url' => 'kids-clothing'],
            ['category_name' => 'Shoes', 'seo_url' => 'shoes'],
        ];

        foreach ($subcategories as $index => $subcategoryData) {
            if (isset($parentCategories[$index])) {
                Category::firstOrCreate(
                    ['category_name' => $subcategoryData['category_name']],
                    [
                        'parent_id' => $parentCategories[$index]->id,
                        'seo_url' => $subcategoryData['seo_url'],
                        'meta_title' => $subcategoryData['category_name'] . ' Discount Codes',
                        'meta_description' => 'Get discounts on ' . $subcategoryData['category_name'] . '.',
                        'short_content' => $subcategoryData['category_name'] . ' deals',
                        'description' => 'Find great deals on ' . $subcategoryData['category_name'] . '.',
                        'status' => true,
                        'sort_order' => $index + 10,
                    ]
                );
            }
        }
    }
}