<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\NetworksController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\VerificationTagController;
use Illuminate\Http\Request;
use App\Models\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


/*
|--------------------------------------------------------------------------
| Admin Routes (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'track.visits'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        $response = response()->view('admin.dashboard');
        
        // Prevent caching of dashboard page to avoid back button issues after logout
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        return $response;
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Coupons Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('coupons', CouponController::class);
    Route::delete('coupons', [CouponController::class, 'bulkDelete'])->name('coupons.bulkDelete');
    Route::post('coupons/reorder', [CouponController::class, 'reorder'])->name('coupons.reorder');
    Route::patch('coupons/{coupon}/update-status', [CouponController::class, 'updateStatus'])->name('coupons.update-status');

    /*
    |--------------------------------------------------------------------------
    | Events Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('events', EventsController::class);
    Route::delete('events', [EventsController::class, 'bulkDelete'])->name('events.bulkDelete');
    Route::post('events/reorder', [EventsController::class, 'reorder'])->name('events.reorder');
    Route::patch('events/{event}/update-status', [EventsController::class, 'updateStatus'])->name('events.update-status');

    /*
    |--------------------------------------------------------------------------
    | Networks Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('networks', NetworksController::class)->except(['show']);
    Route::delete('networks', [NetworksController::class, 'bulkDelete'])->name('networks.bulkDelete');
    Route::post('networks/reorder', [NetworksController::class, 'reorder'])->name('networks.reorder');

    /*
    |--------------------------------------------------------------------------
    | Categories Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::delete('categories', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::patch('categories/{category}/update-status', [CategoryController::class, 'updateStatus'])->name('categories.update-status');

    /*
    |--------------------------------------------------------------------------
    | Pages Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('pages', PageController::class)->except(['show']);
    Route::delete('pages', [PageController::class, 'bulkDelete'])->name('pages.bulk-delete');
    Route::post('pages/reorder', [PageController::class, 'reorder'])->name('pages.reorder');

    /*
    |--------------------------------------------------------------------------
    | Stores Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('stores', StoreController::class);
    Route::delete('stores', [StoreController::class, 'bulkDelete'])->name('stores.bulkDelete');
    Route::post('stores/reorder', [StoreController::class, 'reorder'])->name('stores.reorder');
    Route::patch('stores/{store}/update-status', [StoreController::class, 'updateStatus'])->name('stores.update-status');

    /*
    |--------------------------------------------------------------------------
    | Sliders Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('sliders', \App\Http\Controllers\SliderController::class);
    Route::delete('sliders', [\App\Http\Controllers\SliderController::class, 'bulkDelete'])->name('sliders.bulkDelete');
    Route::post('sliders/reorder', [\App\Http\Controllers\SliderController::class, 'reorder'])->name('sliders.reorder');
    Route::patch('sliders/{slider}/update-status', [\App\Http\Controllers\SliderController::class, 'updateStatus'])->name('sliders.update-status');

    /*
    |--------------------------------------------------------------------------
    | Spotlight Cards Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('spotlight-cards', \App\Http\Controllers\SpotlightCardController::class);
    Route::delete('spotlight-cards', [\App\Http\Controllers\SpotlightCardController::class, 'bulkDelete'])->name('spotlight-cards.bulkDelete');
    Route::post('spotlight-cards/reorder', [\App\Http\Controllers\SpotlightCardController::class, 'reorder'])->name('spotlight-cards.reorder');
    Route::patch('spotlight-cards/{spotlight_card}/update-status', [\App\Http\Controllers\SpotlightCardController::class, 'updateStatus'])->name('spotlight-cards.update-status');

    /*
    |--------------------------------------------------------------------------
    | Home Content Blocks Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('home-content-blocks', \App\Http\Controllers\HomeContentBlockController::class);
    Route::delete('home-content-blocks', [\App\Http\Controllers\HomeContentBlockController::class, 'bulkDelete'])->name('home-content-blocks.bulkDelete');
    Route::post('home-content-blocks/reorder', [\App\Http\Controllers\HomeContentBlockController::class, 'reorder'])->name('home-content-blocks.reorder');
    Route::patch('home-content-blocks/{home_content_block}/update-status', [\App\Http\Controllers\HomeContentBlockController::class, 'updateStatus'])->name('home-content-blocks.update-status');

    /*
    |--------------------------------------------------------------------------
    | Blog Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('blogs', BlogController::class);
    Route::delete('blogs', [BlogController::class, 'bulkDelete'])->name('blogs.bulkDelete');
    Route::post('blogs/reorder', [BlogController::class, 'reorder'])->name('blogs.reorder');

    /*
    |--------------------------------------------------------------------------
    | Blog Category Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('blog-categories', BlogCategoryController::class);
    Route::delete('blog-categories', [BlogCategoryController::class, 'bulkDelete'])->name('blog-categories.bulkDelete');
    Route::post('blog-categories/reorder', [BlogCategoryController::class, 'reorder'])->name('blog-categories.reorder');

    /*
    |--------------------------------------------------------------------------
    | Contact Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::resource('contacts', ContactController::class)->except(['create', 'edit', 'store', 'update']);
        Route::patch('contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');
        Route::delete('contacts/bulk-delete', [ContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');
    });

    /*
    |--------------------------------------------------------------------------
    | User Management Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');
        Route::delete('users', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');
        Route::post('users/reorder', [UserController::class, 'reorder'])->name('users.reorder');
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Management Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::patch('customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.update-status');
        Route::delete('customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulk-delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Settings Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/reset', [SettingsController::class, 'reset'])->name('settings.reset');
        Route::get('settings/colors.css', [SettingsController::class, 'generateColorCss'])->name('settings.colors.css');
    });

    /*
    |--------------------------------------------------------------------------
    | Verification Tags (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::resource('verification-tags', VerificationTagController::class)->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Newsletter Management Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::get('newsletters', [FrontendController::class, 'adminNewsletters'])->name('newsletters.index');
        Route::delete('newsletters/{newsletter}', [FrontendController::class, 'adminNewsletterDelete'])->name('newsletters.destroy');
        Route::delete('newsletters/bulk-delete', [FrontendController::class, 'adminNewsletterBulkDelete'])->name('newsletters.bulk-delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Media Library Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::get('media', [MediaLibraryController::class, 'index'])->name('media.index');
        Route::get('media/images', [MediaLibraryController::class, 'getImages'])->name('media.images'); // API endpoint
        Route::post('media', [MediaLibraryController::class, 'store'])->name('media.store');
        Route::post('media/delete', [MediaLibraryController::class, 'destroy'])->name('media.destroy');
        Route::post('media/bulk-delete', [MediaLibraryController::class, 'bulkDelete'])->name('media.bulk-delete');
        Route::post('media/convert-webp', [MediaLibraryController::class, 'convertToWebP'])->name('media.convert-webp');
        Route::post('media/optimize', [MediaLibraryController::class, 'optimize'])->name('media.optimize');
    });

    /*
    |--------------------------------------------------------------------------
    | Unused Images Management Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::get('unused-images', [\App\Http\Controllers\UnusedImageController::class, 'index'])->name('unused-images.index');
        Route::get('unused-images/refresh', [\App\Http\Controllers\UnusedImageController::class, 'refresh'])->name('unused-images.refresh');
        Route::delete('unused-images', [\App\Http\Controllers\UnusedImageController::class, 'destroy'])->name('unused-images.destroy');
        Route::post('unused-images/delete-single', [\App\Http\Controllers\UnusedImageController::class, 'deleteSingle'])->name('unused-images.delete-single');
    });

    // Redirects management
    Route::patch('redirects/{redirect}/toggle', [RedirectController::class, 'toggle'])->name('redirects.toggle');
    Route::resource('redirects', RedirectController::class)->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Activity Logs Routes (Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.only')->group(function () {
        Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{activityLog}', [\App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::get('activity-logs/user/{user}', [\App\Http\Controllers\ActivityLogController::class, 'userActivities'])->name('activity-logs.user');
        Route::get('activity-logs-export', [\App\Http\Controllers\ActivityLogController::class, 'export'])->name('activity-logs.export');
        Route::post('activity-logs-cleanup', [\App\Http\Controllers\ActivityLogController::class, 'cleanup'])->name('activity-logs.cleanup');
        Route::get('activity-logs-dashboard', [\App\Http\Controllers\ActivityLogController::class, 'dashboard'])->name('activity-logs.dashboard');
        Route::post('track-link-click', [\App\Http\Controllers\ActivityLogController::class, 'trackLinkClick'])->name('track-link-click');
    });
});



/*
|--------------------------------------------------------------------------
| Dynamic CSS Route (Public)
|--------------------------------------------------------------------------
*/
Route::get('/css/colors.css', [SettingsController::class, 'generateColorCss'])->name('colors.css');

// Sitemap generator endpoint (regenerates and writes public/sitemap.xml)
Route::get('/sitemap', function () {
    Artisan::call('sitemap:generate');
    return 'Sitemap generated successfully';
});

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'home'])->name('home');

// Dynamic Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/top-discounts', [FrontendController::class, 'topDiscounts'])->name('top-discounts');
Route::get('/categories', [FrontendController::class, 'categories'])->name('categories');

Route::get('/events', [FrontendController::class, 'events'])->name('events');
Route::get('/event/{slug}', [FrontendController::class, 'eventDetail'])->name('event.detail');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/mobile-app', [FrontendController::class, 'mobileApp'])->name('mobile-app');

Route::get('/share', [FrontendController::class, 'share'])->name('share');
Route::get('/deal-seeker', [FrontendController::class, 'dealSeeker'])->name('deal-seeker');
Route::get('/smash-voucher-codes', [FrontendController::class, 'smashVoucherCodes'])->name('smash-voucher-codes');

Route::get('/student-discount', [FrontendController::class, 'studentDiscount'])->name('student-discount');
Route::get('/black-friday-deals', [FrontendController::class, 'blackFridayDeals'])->name('black-friday-deals');
Route::get('/cyber-monday-voucher-codes', [FrontendController::class, 'cyberMondayVoucherCodes'])->name('cyber-monday-voucher-codes');

Route::get('/christmas-deals-online', [FrontendController::class, 'christmasDealsOnline'])->name('christmas-deals-online');
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/advertise-with-us', [FrontendController::class, 'advertiseWithUs'])->name('advertise-with-us');

Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog/category/{slug}', [FrontendController::class, 'blogCategory'])->name('blog.category');
Route::get('/blog/{slug}', [FrontendController::class, 'blogShow'])->name('blog.show');
Route::post('/blog/{slug}/view', [FrontendController::class, 'blogView'])->name('blog.view');
Route::post('/coupon/track-view', [FrontendController::class, 'trackCouponView'])->name('coupon.track-view');
Route::post('/coupon/track-usage', [FrontendController::class, 'trackCouponUsage'])->name('coupon.track-usage');
Route::get('/all-brands', [FrontendController::class, 'allBrandsUk'])->name('all-brands');
// Backward-compatible redirect from old slug
Route::get('/all-brands-uk', function () { return redirect('/all-brands', 301); });
Route::get('/all-stores', [FrontendController::class, 'allBrandsUk'])->name('all-stores');

Route::get('/contact-details', [FrontendController::class, 'contactDetails'])->name('contact-details');
Route::get('/category/{slug}', [FrontendController::class, 'category'])->name('category');
Route::get('/store/{slug}', [FrontendController::class, 'store'])->name('store');

// Search functionality
Route::get('/search', [FrontendController::class, 'search'])->name('search');
Route::get('/storesearch', [FrontendController::class, 'search'])->name('storesearch');
Route::get('/getHeaderSearchDefault', [FrontendController::class, 'getHeaderSearchDefault'])->name('getHeaderSearchDefault');
Route::get('/ajax-search', [FrontendController::class, 'ajaxSearch'])->name('ajax.search');
Route::get('/test-search/{query}', [FrontendController::class, 'testSearch'])->name('test.search');

// Recommended stores route
Route::get('/recommended-stores', [FrontendController::class, 'recommendedStores'])->name('recommended.stores');

// Newsletter subscription
Route::post('/newsletter/subscribe', [FrontendController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// Image resizing route for responsive images
Route::get('/image/resize', [\App\Http\Controllers\ImageController::class, 'resize'])->name('image.resize');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::get('/customer/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
Route::get('/customer/dashboard', [CustomerAuthController::class, 'dashboard'])->name('customer.dashboard');

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

// Fallback: handle 404s with DB-based redirects
Route::fallback(function (Request $request) {
    $pathWithSlash = '/' . ltrim($request->path(), '/');
    $fullUrl = url($pathWithSlash);

    $redirect = Redirect::query()
        ->where('status', true)
        ->whereIn('old_url', [$pathWithSlash, $fullUrl])
        ->first();

    if ($redirect) {
        $target = $redirect->new_url;
        if (Str::startsWith($target, ['/']) || Str::startsWith($target, ['#'])) {
            $target = url($target);
        }
        return redirect()->to($target, (int) $redirect->type);
    }

    abort(404);
});
