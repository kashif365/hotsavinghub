<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'page_title' => 'About Us',
                'seo_url' => 'about-us',
                'meta_title' => 'About Us - Coupon Website',
                'meta_keywords' => 'about us, company info, coupon website',
                'meta_description' => 'Learn more about our coupon website and our mission to help you save money.',
                'page_content' => '<h1>About Us</h1><p>Welcome to our coupon website! We are dedicated to helping you save money by providing the best discount codes and deals from your favorite stores.</p><p>Our team works tirelessly to find and verify the latest coupons, ensuring you get the best possible savings on your purchases.</p><h2>Our Mission</h2><p>To make shopping more affordable for everyone by providing access to the best deals and discount codes.</p>',
                'media' => 'uploads/about-us.jpg',
                'banner_image' => 'uploads/about-us-banner.jpg',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'page_title' => 'Contact Us',
                'seo_url' => 'contact-us',
                'meta_title' => 'Contact Us - Get in Touch',
                'meta_keywords' => 'contact us, get in touch, support',
                'meta_description' => 'Get in touch with us for any questions or support regarding our coupon services.',
                'page_content' => '<h1>Contact Us</h1><p>Have a question or need help? We\'re here to assist you!</p><h2>Get in Touch</h2><p>Email: support@couponwebsite.com</p><p>Phone: +1 (555) 123-4567</p><p>Address: 123 Coupon Street, Deal City, DC 12345</p><h2>Business Hours</h2><p>Monday - Friday: 9:00 AM - 6:00 PM</p><p>Saturday: 10:00 AM - 4:00 PM</p><p>Sunday: Closed</p>',
                'media' => 'uploads/contact-us.jpg',
                'banner_image' => 'uploads/contact-us-banner.jpg',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'page_title' => 'Privacy Policy',
                'seo_url' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - Your Privacy Matters',
                'meta_keywords' => 'privacy policy, data protection, privacy',
                'meta_description' => 'Read our privacy policy to understand how we protect and use your personal information.',
                'page_content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h2>Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account or subscribe to our newsletter.</p><h2>How We Use Your Information</h2><p>We use your information to provide and improve our services, communicate with you, and personalize your experience.</p><h2>Data Protection</h2><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>',
                'media' => 'uploads/privacy-policy.jpg',
                'banner_image' => 'uploads/privacy-policy-banner.jpg',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'page_title' => 'Terms of Service',
                'seo_url' => 'terms-of-service',
                'meta_title' => 'Terms of Service - User Agreement',
                'meta_keywords' => 'terms of service, user agreement, terms',
                'meta_description' => 'Read our terms of service to understand the rules and guidelines for using our website.',
                'page_content' => '<h1>Terms of Service</h1><p>Welcome to our website! These terms of service govern your use of our coupon website.</p><h2>Acceptance of Terms</h2><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h2>Use License</h2><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p><h2>Disclaimer</h2><p>The materials on our website are provided on an \'as is\' basis. We make no warranties, expressed or implied.</p>',
                'media' => 'uploads/terms.jpg',
                'banner_image' => 'uploads/terms-banner.jpg',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'page_title' => 'How It Works',
                'seo_url' => 'how-it-works',
                'meta_title' => 'How It Works - Save Money Easily',
                'meta_keywords' => 'how it works, save money, coupon process',
                'meta_description' => 'Learn how our coupon system works and how you can start saving money today.',
                'page_content' => '<h1>How It Works</h1><p>Saving money with our coupons is simple and easy!</p><h2>Step 1: Browse Coupons</h2><p>Search through our extensive collection of verified discount codes and deals.</p><h2>Step 2: Select Your Coupon</h2><p>Choose the coupon that best fits your shopping needs.</p><h2>Step 3: Apply at Checkout</h2><p>Copy the coupon code and apply it during checkout at the retailer\'s website.</p><h2>Step 4: Save Money</h2><p>Enjoy your savings! It\'s that simple.</p>',
                'media' => 'uploads/how-it-works.jpg',
                'banner_image' => 'uploads/how-it-works-banner.jpg',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'page_title' => 'FAQ',
                'seo_url' => 'faq',
                'meta_title' => 'Frequently Asked Questions',
                'meta_keywords' => 'FAQ, frequently asked questions, help',
                'meta_description' => 'Find answers to the most frequently asked questions about our coupon services.',
                'page_content' => '<h1>Frequently Asked Questions</h1><h2>How do I use a coupon code?</h2><p>Simply copy the coupon code and paste it into the promo code field during checkout at the retailer\'s website.</p><h2>Are all coupons verified?</h2><p>Yes, we verify all our coupons before publishing them to ensure they work.</p><h2>What if a coupon doesn\'t work?</h2><p>If a coupon doesn\'t work, please let us know and we\'ll investigate and update our database.</p><h2>How often do you update coupons?</h2><p>We update our coupons daily to ensure you have access to the latest deals.</p>',
                'media' => 'uploads/faq.jpg',
                'banner_image' => 'uploads/faq-banner.jpg',
                'status' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['seo_url' => $pageData['seo_url']],
                $pageData
            );
        }
    }
}