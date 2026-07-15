<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Coupon;
use App\Models\Store;
use App\Models\Category;
use App\Models\Events;

echo "--- Debugging Data Visibility ---\n";

// 1. Featured Coupons
$featuredCouponsCount = Coupon::where('status', 1)->where('hot_deals', 1)->count();
echo "Featured Coupons (status=1, hot_deals=1): $featuredCouponsCount\n";
if ($featuredCouponsCount == 0) {
    echo "  -> Total Coupons: " . Coupon::count() . "\n";
    echo "  -> Active Coupons (status=1): " . Coupon::where('status', 1)->count() . "\n";
    echo "  -> Hot Deals (hot_deals=1): " . Coupon::where('hot_deals', 1)->count() . "\n";
}

// 2. Featured Stores
$featuredStoresCount = Store::where('featured', 1)->where('status', 1)->count();
echo "Featured Stores (featured=1, status=1): $featuredStoresCount\n";
if ($featuredStoresCount == 0) {
    echo "  -> Total Stores: " . Store::count() . "\n";
    echo "  -> Active Stores (status=1): " . Store::where('status', 1)->count() . "\n";
    echo "  -> Featured Stores (featured=1): " . Store::where('featured', 1)->count() . "\n";
}

// 3. Featured Categories
$categoriesCount = Category::where('status', 1)->where('featured', 1)->count();
echo "Featured Categories (status=1, featured=1): $categoriesCount\n";
if ($categoriesCount == 0) {
    echo "  -> Total Categories: " . Category::count() . "\n";
    echo "  -> Active Categories (status=1): " . Category::where('status', 1)->count() . "\n";
    echo "  -> Featured Categories (featured=1): " . Category::where('featured', 1)->count() . "\n";
}

// 4. Home Categories
$homeCategoriesCount = Category::where('status', 1)->where('show_home', 1)->count();
echo "Home Categories (status=1, show_home=1): $homeCategoriesCount\n";
if ($homeCategoriesCount == 0) {
     echo "  -> Home Categories (show_home=1): " . Category::where('show_home', 1)->count() . "\n";
}

// 5. Active Events
$activeEventsCount = Events::where('status', 1)->count();
echo "Active Events (status=1): $activeEventsCount\n";

echo "--- End Debug ---\n";
