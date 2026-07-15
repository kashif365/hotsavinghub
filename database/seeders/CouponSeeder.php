<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Store;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some stores to associate with coupons
        $stores = Store::take(20)->get();
        
        if ($stores->count() == 0) {
            $this->command->info('No stores found. Please run StoreSeeder first.');
            return;
        }

        $coupons = [
            [
                'coupon_title' => 'Get 20% Off Your First Order',
                'coupon_code' => 'WELCOME20',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Valid for new customers only. Minimum spend £50. Cannot be combined with other offers.',
                'sort_order' => 1,
                'description' => 'Get 20% off your first order with this exclusive coupon code.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Free Delivery on Orders Over £30',
                'coupon_code' => 'FREEDEL30',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Free standard delivery on orders over £30. Valid for UK mainland only.',
                'sort_order' => 2,
                'description' => 'Free delivery on orders over £30.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Save £10 on Orders Over £75',
                'coupon_code' => 'SAVE10',
                'exclusive' => false,
                'verified' => true,
                'status' => true,
                'terms' => 'Save £10 when you spend £75 or more. Excludes sale items.',
                'sort_order' => 3,
                'description' => 'Save £10 on orders over £75.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => '15% Off Everything',
                'coupon_code' => 'SAVE15',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => '15% off all items. Valid until end of month. Cannot be used with other offers.',
                'sort_order' => 4,
                'description' => '15% off everything in store.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Buy One Get One Half Price',
                'coupon_code' => 'BOGOHP',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Buy one item and get the second item at half price. Valid on selected items only.',
                'sort_order' => 5,
                'description' => 'Buy one get one half price offer.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Free Gift with Purchase',
                'coupon_code' => 'FREEGIFT',
                'exclusive' => false,
                'verified' => true,
                'status' => true,
                'terms' => 'Free gift with any purchase over £25. While stocks last.',
                'sort_order' => 6,
                'description' => 'Free gift with purchase over £25.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => '25% Off Sale Items',
                'coupon_code' => 'SALE25',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Extra 25% off all sale items. Cannot be combined with other offers.',
                'sort_order' => 7,
                'description' => '25% off all sale items.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Student Discount - 10% Off',
                'coupon_code' => 'STUDENT10',
                'exclusive' => false,
                'verified' => true,
                'status' => true,
                'terms' => '10% discount for students with valid student ID. Valid on full-price items only.',
                'sort_order' => 8,
                'description' => 'Student discount - 10% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Flash Sale - 30% Off',
                'coupon_code' => 'FLASH30',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Flash sale - 30% off selected items. Limited time offer. While stocks last.',
                'sort_order' => 9,
                'description' => 'Flash sale - 30% off selected items.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Free Returns for 30 Days',
                'coupon_code' => 'FREERETURNS',
                'exclusive' => false,
                'verified' => true,
                'status' => true,
                'terms' => 'Free returns within 30 days of purchase. Original packaging required.',
                'sort_order' => 10,
                'description' => 'Free returns within 30 days.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'VIP Member - 20% Off',
                'coupon_code' => 'VIP20',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'VIP members get 20% off all purchases. Valid for registered VIP members only.',
                'sort_order' => 11,
                'description' => 'VIP member discount - 20% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Weekend Special - £15 Off',
                'coupon_code' => 'WEEKEND15',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Weekend special offer. £15 off orders over £60. Valid Friday to Sunday only.',
                'sort_order' => 12,
                'description' => 'Weekend special - £15 off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'New Customer - 25% Off',
                'coupon_code' => 'NEW25',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => '25% off for new customers. Valid on first order only. Minimum spend £40.',
                'sort_order' => 13,
                'description' => 'New customer discount - 25% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Clearance Sale - 50% Off',
                'coupon_code' => 'CLEARANCE50',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => '50% off all clearance items. Final sale - no returns. While stocks last.',
                'sort_order' => 14,
                'description' => 'Clearance sale - 50% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Bundle Deal - Save £20',
                'coupon_code' => 'BUNDLE20',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Save £20 when you buy 3 or more items. Valid on selected bundles only.',
                'sort_order' => 15,
                'description' => 'Bundle deal - save £20.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Early Bird - 15% Off',
                'coupon_code' => 'EARLY15',
                'exclusive' => false,
                'verified' => true,
                'status' => true,
                'terms' => 'Early bird discount. 15% off orders placed before 12pm. Valid Monday to Friday.',
                'sort_order' => 16,
                'description' => 'Early bird discount - 15% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Refer a Friend - £10 Off',
                'coupon_code' => 'REFER10',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Get £10 off when you refer a friend. Both you and your friend get the discount.',
                'sort_order' => 17,
                'description' => 'Refer a friend - £10 off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Birthday Special - 30% Off',
                'coupon_code' => 'BIRTHDAY30',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Birthday special offer. 30% off for registered members. Valid on birthday month only.',
                'sort_order' => 18,
                'description' => 'Birthday special - 30% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Loyalty Reward - £25 Off',
                'coupon_code' => 'LOYALTY25',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Loyalty reward for frequent customers. £25 off orders over £100.',
                'sort_order' => 19,
                'description' => 'Loyalty reward - £25 off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
            [
                'coupon_title' => 'Seasonal Sale - 40% Off',
                'coupon_code' => 'SEASONAL40',
                'exclusive' => true,
                'verified' => true,
                'status' => true,
                'terms' => 'Seasonal sale - 40% off selected items. Limited time offer. Valid until stock lasts.',
                'sort_order' => 20,
                'description' => 'Seasonal sale - 40% off.',
                'date_available' => now()->format('Y-m-d'),
                'date_expiry' => now()->addDays(30)->format('Y-m-d'),
            ],
        ];

        foreach ($coupons as $index => $couponData) {
            $store = $stores[$index % $stores->count()]; // Cycle through stores
            $couponData['brand_store'] = $store->store_name;
            $couponData['affiliate_url'] = $store->affiliate_url;
            Coupon::create($couponData);
        }
    }
}