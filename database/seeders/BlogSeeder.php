<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $shoppingCategory = BlogCategory::where('name', 'Shopping Tips')->first();
        $moneyCategory = BlogCategory::where('name', 'Money Saving')->first();
        $fashionCategory = BlogCategory::where('name', 'Fashion & Style')->first();
        $techCategory = BlogCategory::where('name', 'Technology')->first();

        $blogs = [
            [
                'title' => '10 Smart Shopping Tips to Save Money Online',
                'slug' => 'smart-shopping-tips-save-money-online',
                'excerpt' => 'Discover the best strategies to save money while shopping online with these expert tips.',
                'description' => '<p>Online shopping has become the norm, but it can also drain your wallet if you\'re not careful. Here are 10 proven strategies to help you save money while shopping online:</p><h3>1. Use Price Comparison Tools</h3><p>Before making any purchase, compare prices across different websites...</p>',
                'category_id' => $shoppingCategory ? $shoppingCategory->id : null,
                'author' => 'Social Offerz',
                'status' => 'published',
                'sort_order' => 1,
                'meta_title' => 'Smart Shopping Tips - Save Money Online',
                'meta_description' => 'Learn 10 expert tips to save money while shopping online. Compare prices, use coupons, and shop smart.',
                'meta_keywords' => 'shopping tips, save money, online shopping, discounts',
            ],
            [
                'title' => 'How to Build an Emergency Fund on a Budget',
                'slug' => 'build-emergency-fund-budget',
                'excerpt' => 'Learn how to create a financial safety net even when money is tight.',
                'description' => '<p>An emergency fund is crucial for financial security, but building one can seem impossible when you\'re living paycheck to paycheck...</p>',
                'category_id' => $moneyCategory ? $moneyCategory->id : null,
                'author' => 'Social Offerz',
                'status' => 'published',
                'sort_order' => 2,
                'meta_title' => 'Build Emergency Fund on Budget - Money Saving Tips',
                'meta_description' => 'Discover practical ways to build an emergency fund even with limited income. Start small and grow your savings.',
                'meta_keywords' => 'emergency fund, budget, savings, financial planning',
            ],
            [
                'title' => 'Spring Fashion Trends 2024: What to Wear',
                'slug' => 'spring-fashion-trends-2024',
                'excerpt' => 'Stay ahead of the fashion curve with these must-have spring trends.',
                'description' => '<p>Spring is here, and with it comes fresh fashion trends that will define the season...</p>',
                'category_id' => $fashionCategory ? $fashionCategory->id : null,
                'author' => 'Social Offerz',
                'status' => 'published',
                'sort_order' => 3,
                'meta_title' => 'Spring Fashion Trends 2024 - Style Guide',
                'meta_description' => 'Explore the latest spring fashion trends for 2024. Get inspired with outfit ideas and styling tips.',
                'meta_keywords' => 'spring fashion, trends 2024, style guide, fashion tips',
            ],
            [
                'title' => 'Best Tech Gadgets Under $100',
                'slug' => 'best-tech-gadgets-under-100',
                'excerpt' => 'Discover amazing technology gadgets that won\'t break the bank.',
                'description' => '<p>You don\'t need to spend a fortune to get great tech gadgets. Here are our top picks under $100...</p>',
                'category_id' => $techCategory ? $techCategory->id : null,
                'author' => 'Social Offerz',
                'status' => 'published',
                'sort_order' => 4,
                'meta_title' => 'Best Tech Gadgets Under $100 - Budget Tech',
                'meta_description' => 'Find the best technology gadgets under $100. Quality tech that fits your budget.',
                'meta_keywords' => 'tech gadgets, budget tech, affordable technology, gadgets under 100',
            ],
            [
                'title' => 'Ultimate Guide to Coupon Stacking',
                'slug' => 'ultimate-guide-coupon-stacking',
                'excerpt' => 'Master the art of combining multiple discounts for maximum savings.',
                'description' => '<p>Coupon stacking is one of the most effective ways to maximize your savings...</p>',
                'category_id' => $moneyCategory ? $moneyCategory->id : null,
                'author' => 'Social Offerz',
                'status' => 'published',
                'sort_order' => 5,
                'meta_title' => 'Coupon Stacking Guide - Maximize Savings',
                'meta_description' => 'Learn how to stack coupons and discounts for maximum savings. Expert tips and strategies.',
                'meta_keywords' => 'coupon stacking, discounts, savings tips, coupon codes',
            ],
        ];

        foreach ($blogs as $blogData) {
            Blog::create($blogData);
        }
    }
}
