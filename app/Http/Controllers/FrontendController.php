<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Store;
use App\Models\Category;
use App\Models\Events;
use App\Models\Page;
use App\Models\Newsletter;
use App\Models\Slider;
use App\Models\CouponUsage;
use App\Helpers\SettingsHelper;
use App\Helpers\ViewTrackingHelper;

class FrontendController extends Controller
{
    public function home()
    {
        $request = request();

        // Disable full-page cache in development mode for instant changes
        $isDevelopment = app()->environment('local') || config('app.debug');
        
        // Full-page cache for guests (no query params) to reduce server response time / TTFB
        // Skip cache in development mode
        if (!$isDevelopment && !auth()->check() && !$request->query->count()) {
            $cachedHtml = \Cache::get('home_page_html');
            if ($cachedHtml) {
                $cachedResponse = response($cachedHtml);
                $cachedResponse->headers->set('Cache-Control', 'public, max-age=300'); // 5 minutes
                $cachedResponse->headers->set('X-Content-Type-Options', 'nosniff');
                $cachedResponse->headers->set('Content-Type', 'text/html; charset=utf-8');
                return $cachedResponse;
            }
        }

        // Use cache for statistics to reduce database load
        $totalCoupons = \Cache::remember('stats_total_coupons', 3600, function() {
            return Coupon::where('status', 1)->count();
        });
        $totalStores = \Cache::remember('stats_total_stores', 3600, function() {
            return Store::where('status', 1)->count();
        });
        $totalCategories = \Cache::remember('stats_total_categories', 3600, function() {
            return Category::where('status', 1)->count();
        });

        // Load hot deals coupons for home page with eager loading - 8 for desktop, CSS will hide 2 on mobile
        $featuredCoupons = Coupon::with('store')
            ->where('status', 1)
            ->where('hot_deals', 1)
            ->orderBy('sort_order', 'asc')
            ->take(8)
            ->get();

        // Load featured stores with optimized query - 10 for desktop, CSS will hide 4 on mobile
        $featuredStores = Store::withCount(['coupons' => function($query) {
            $query->where('status', 1);
        }])
            ->where('featured', 1)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->take(10)
            ->get();

        // Load trending stores with optimized query
        $trendingStores = Store::where('show_trending', 1)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->take(20)
            ->get();

        // Load featured categories with store counts for home page - 8 for desktop, CSS will hide 2 on mobile
        $categories = Category::withCount(['stores' => function($query) {
            $query->where('status', 1);
        }])
        ->where('status', 1)
        ->where('featured', 1)
        ->orderBy('sort_order', 'asc')
        ->take(8)
        ->get();

        // Optimize homeCategories: Load all categories first, then batch load coupons
        $homeCategories = Category::where('status', 1)
            ->where('show_home', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
        
        if ($homeCategories->isNotEmpty()) {
            $homeCategoryIds = $homeCategories->pluck('id');
            
            // Batch load all coupons for these categories in one query with eager loading
            $allCategoryCoupons = Coupon::with(['store', 'store.categories' => function($query) use ($homeCategoryIds) {
                $query->whereIn('category_id', $homeCategoryIds);
            }])
                ->whereHas('store.categories', function($query) use ($homeCategoryIds) {
                    $query->whereIn('category_id', $homeCategoryIds);
                })
                ->where('status', 1)
                ->where('featured', 1)
                ->orderBy('sort_order', 'asc')
                ->get();
                
            // Group coupons by category
            $couponsByCategory = [];
            foreach ($allCategoryCoupons as $coupon) {
                if ($coupon->store && $coupon->store->categories) {
                    $categoryId = $coupon->store->categories->pluck('id')->intersect($homeCategoryIds)->first();
                    if ($categoryId) {
                        if (!isset($couponsByCategory[$categoryId])) {
                            $couponsByCategory[$categoryId] = collect();
                        }
                        $couponsByCategory[$categoryId]->push($coupon);
                    }
                }
            }
            
            // Attach coupons to categories - 8 for desktop, CSS will hide 2 on mobile
            $homeCategories = $homeCategories->map(function($category) use ($couponsByCategory) {
                $category->coupons = isset($couponsByCategory[$category->id]) 
                    ? $couponsByCategory[$category->id]->take(8) 
                    : collect();
                return $category;
            });
        } else {
            $homeCategories = collect();
        }

        // Optimize recommendedCategories: Batch load stores
        $recommendedCategories = Category::where('status', 1)
            ->where('recommended', 1)
            ->orderBy('sort_order', 'asc')
            ->take(4)
            ->get();
        
        if ($recommendedCategories->isNotEmpty()) {
            $recommendedCategoryIds = $recommendedCategories->pluck('id');
            
            // Batch load all stores for these categories with eager loading
            $allRecommendedStores = Store::with(['categories' => function($query) use ($recommendedCategoryIds) {
                $query->whereIn('category_id', $recommendedCategoryIds);
            }])
            ->whereHas('categories', function($query) use ($recommendedCategoryIds) {
                $query->whereIn('category_id', $recommendedCategoryIds);
                })
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->get();
                
            // Group stores by category
            $storesByCategory = [];
            foreach ($allRecommendedStores as $store) {
                if ($store->categories) {
                    $categoryId = $store->categories->pluck('id')->intersect($recommendedCategoryIds)->first();
                    if ($categoryId) {
                        if (!isset($storesByCategory[$categoryId])) {
                            $storesByCategory[$categoryId] = collect();
                        }
                        $storesByCategory[$categoryId]->push($store);
                    }
                }
            }
            
            // Attach stores to categories - Reduced to 4 per category for mobile DOM optimization
            $recommendedCategories = $recommendedCategories->map(function($category) use ($storesByCategory) {
                $category->stores = isset($storesByCategory[$category->id]) 
                    ? $storesByCategory[$category->id]->take(4) 
                    : collect();
                return $category;
            });
        } else {
            $recommendedCategories = collect();
        }

        // Load active events with optimized query
        $activeEvents = Events::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Get home banner from settings
        $homeBanner = \App\Models\Setting::get('home_banner', '');
        $homeHeading = \App\Models\Setting::get('home_heading', 'Save Big with Exclusive Coupon Codes');
        $homeSubheading = \App\Models\Setting::get('home_subheading', 'Discover thousands of verified discount codes from your favorite brands. Start saving money on every purchase today!');
        $homeOverlayColor = \App\Models\Setting::get('home_overlay_color', 'rgba(0, 0, 0, 0.5)');
        $backgroundSecondaryColor = \App\Models\Setting::get('background_secondary_color', '#F8F9FA');
        $secondaryColor = \App\Models\Setting::get('secondary_color', '#000000');

        // Load home page SEO data from pages table
        $homePage = Page::where('seo_url', 'home')
            ->where('status', 1)
            ->first();

        // Load active sliders
        try {
            $sliders = Slider::where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->get();
        } catch (\Exception $e) {
            // If sliders table doesn't exist or has issues, return empty collection
            $sliders = collect([]);
        }

        // Load featured/recent blog posts for home page - 6 for desktop, CSS will hide 2 on mobile
        try {
            $featuredBlogs = \App\Models\Blog::with('category')
                ->where('status', 'published')
                ->where(function($query) {
                    $query->where('featured', 1)
                          ->orWhere('recommended', 1);
                })
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            
            // If no featured blogs, get recent blogs
            if ($featuredBlogs->isEmpty()) {
                $featuredBlogs = \App\Models\Blog::with('category')
                    ->where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();
            }
        } catch (\Exception $e) {
            // If blogs table doesn't exist or has issues, return empty collection
            $featuredBlogs = collect([]);
        }

        $response = response()->view('frontend.home.index', compact(
            'featuredCoupons',
            'featuredStores', 
            'trendingStores',
            'categories',
            'homeCategories',
            'recommendedCategories',
            'activeEvents',
            'totalCoupons',
            'totalStores',
            'totalCategories',
            'homeBanner',
            'homeHeading',
            'homeSubheading',
            'homeOverlayColor',
            'backgroundSecondaryColor',
            'secondaryColor',
            'homePage',
            'sliders',
            'featuredBlogs'
        ));
        
        // Optimize response headers for faster TTFB and better caching
        // In development mode, disable caching completely
        if ($isDevelopment) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        } else {
            $response->headers->set('Cache-Control', 'public, max-age=300'); // 5 minutes
        }
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        
        // Enable compression if client supports it (reduces response size and TTFB)
        $acceptEncoding = $request->header('Accept-Encoding', '');
        $rawHtml = $response->getContent(); // keep uncompressed HTML for caching / fallback
        if (strpos($acceptEncoding, 'gzip') !== false && function_exists('gzencode')) {
            $compressed = gzencode($rawHtml, 6); // Compression level 6 (balanced)
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Vary', 'Accept-Encoding');
            }
        }
        
        // Store rendered HTML in cache for guests to speed up subsequent requests (reduces TTFB from 670ms to <100ms on cache hit)
        // Skip cache storage in development mode
        if (!$isDevelopment && !auth()->check() && !$request->query->count()) {
            \Cache::put('home_page_html', $rawHtml, 300); // 5 minutes
        }
        
        return $response;
    }

    public function topDiscounts()
    {
        // Load exclusive coupons only with today's usage count
        $topCoupons = Coupon::with('store')
            ->where('status', 1)
            ->where('exclusive', 1)
            ->where('verified', 1)
            ->withCount(['usages as today_usage_count' => function($query) {
                $query->whereDate('usage_date', today());
            }])
            ->orderBy('sort_order', 'asc')
            ->take(20)
            ->get();

        // Load trending stores for sidebar
        $trendingStores = Store::where('show_trending', 1)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->take(20)
            ->get();

        return view('frontend.top-discounts', compact('topCoupons', 'trendingStores'));
    }

    public function categories()
    {
        // Load all categories with store counts - sorted alphabetically
        $categories = Category::withCount(['stores' => function($query) {
            $query->where('status', 1);
        }])
        ->where('status', 1)
        ->orderBy('category_name', 'asc') // ABC order
        ->get();

        // Load stores for each category
        foreach ($categories as $category) {
            // All stores for text links - organized in columns, sorted alphabetically
            $category->brands = Store::whereHas('categories', function($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('status', 1)
            ->orderBy('store_name', 'asc') // ABC order for stores
            ->get();
        }

        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();

        return view('frontend.categories', compact('categories', 'settings'));
    }

    public function events()
    {
        // Load all active events
        $events = Events::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Load coupons for each event
        $eventCoupons = [];
        foreach ($events as $event) {
            $eventCoupons[$event->id] = Coupon::where('event_id', $event->id)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(6)
                ->get();
        }

        return view('frontend.events', compact('events', 'eventCoupons'));
    }

    public function eventDetail($slug)
    {
        // Load event by slug
        $event = Events::where('seo_url', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Track view count (session-based)
        ViewTrackingHelper::trackView('event', $event->id, $event);

        // Load stores associated with this event
        $eventStores = $event->stores()->where('status', 1)->get();

        // Load coupons for this event
        $eventCoupons = Coupon::where('event_id', $event->id)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        // Load related events (same type or similar)
        $relatedEvents = Events::where('event_type', $event->event_type)
            ->where('id', '!=', $event->id)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        return view('frontend.event-detail', compact(
            'event', 
            'eventStores', 
            'eventCoupons', 
            'relatedEvents'
        ));
    }

    public function contact()
    {
        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();
        
        return view('frontend.contact', compact('settings'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Create contact submission
        \App\Models\Contact::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'new'
        ]);

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    public function mobileApp()
    {
        return view('frontend.mobile-app');
    }

    public function share()
    {
        return view('frontend.share');
    }

    public function dealSeeker()
    {
        return view('frontend.deal-seeker');
    }

    public function smashVoucherCodes()
    {
        // Load inspiring/trending coupons
        $inspiringCoupons = Coupon::where('status', 1)
            ->where('featured', 1)
            ->orderBy('sort_order', 'asc')
            ->take(12)
            ->get();

        return view('frontend.smash-voucher-codes', compact('inspiringCoupons'));
    }

    public function studentDiscount()
    {
        // Get all student discount coupons with their categories
        $studentCoupons = Coupon::where('status', 1)
            ->where('student_offer', 1)
            ->whereNotNull('category_id')
            ->with(['store', 'category'])
            ->orderBy('sort_order', 'asc')
            ->get();

        // Group coupons by category
        $groupedCoupons = $studentCoupons->groupBy('category_id');

        // Get categories that have student discount coupons
        $studentCategories = Category::where('status', 1)
            ->whereIn('id', $groupedCoupons->keys())
            ->orderBy('sort_order', 'asc')
            ->get();

        // Attach grouped coupons to each category
        foreach ($studentCategories as $category) {
            $category->studentCoupons = $groupedCoupons->get($category->id, collect())->take(8);
        }

        // Simple banner content (customize via settings later if needed)
        $bannerHeading = 'Social Offerz Student Discounts';
        $bannerSubheading = 'Get exclusive student discount codes & vouchers across top brands';

        return view('frontend.student-discount', compact('studentCategories', 'bannerHeading', 'bannerSubheading'));
    }

    public function blackFridayDeals()
    {
        // Load Black Friday event and coupons
        $blackFridayEvent = Events::where('event_name', 'like', '%black friday%')
            ->where('status', 1)
            ->first();

        $blackFridayCoupons = [];
        if ($blackFridayEvent) {
            $blackFridayCoupons = Coupon::where('event_id', $blackFridayEvent->id)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(20)
                ->get();
        }

        return view('frontend.black-friday-deals', compact('blackFridayCoupons', 'blackFridayEvent'));
    }

    public function cyberMondayVoucherCodes()
    {
        // Load Cyber Monday event and coupons
        $cyberMondayEvent = Events::where('event_name', 'like', '%cyber monday%')
            ->where('status', 1)
            ->first();

        $cyberMondayCoupons = [];
        if ($cyberMondayEvent) {
            $cyberMondayCoupons = Coupon::where('event_id', $cyberMondayEvent->id)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(20)
                ->get();
        }

        return view('frontend.cyber-monday-voucher-codes', compact('cyberMondayCoupons', 'cyberMondayEvent'));
    }

    public function christmasDealsOnline()
    {
        // Load Christmas event and coupons
        $christmasEvent = Events::where('event_name', 'like', '%christmas%')
            ->where('status', 1)
            ->first();

        $christmasCoupons = [];
        if ($christmasEvent) {
            $christmasCoupons = Coupon::where('event_id', $christmasEvent->id)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(20)
                ->get();
        }

        return view('frontend.christmas-deals-online', compact('christmasCoupons', 'christmasEvent'));
    }

    public function aboutUs()
    {
        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();
        
        // Load about us page SEO data from pages table
        $aboutPage = Page::where('seo_url', 'about-us')
            ->where('status', 1)
            ->first();
        
        return view('frontend.about-us', compact('settings', 'aboutPage'));
    }

    public function advertiseWithUs()
    {
        // Load advertise page content from admin
        $advertisePage = Page::where('page_slug', 'advertise-with-us')
            ->where('status', 1)
            ->first();

        return view('frontend.advertise-with-us', compact('advertisePage'));
    }

    public function privacyPolicy()
    {
        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();
        
        return view('frontend.privacy-policy', compact('settings'));
    }

    public function blog(Request $request)
    {
        // Load published blog posts with categories
        $blogs = \App\Models\Blog::with('category')
            ->published()
            ->ordered()
            ->paginate(6);
        
        // Load blog categories for navigation
        $blogCategories = \App\Models\BlogCategory::active()
            ->ordered()
            ->get();
        
        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();
        
        // If AJAX request, return only the blog content
        if ($request->ajax()) {
            return view('frontend.partials.blog-content', compact('blogs'))->render();
        }
        
        return view('frontend.blog', compact('blogs', 'blogCategories', 'settings'));
    }

    public function blogCategory($slug)
    {
        // Load blog category
        $category = \App\Models\BlogCategory::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        
        // Load published blog posts for this category
        $blogs = \App\Models\Blog::with('category')
            ->where('category_id', $category->id)
            ->published()
            ->ordered()
            ->paginate(6);
        
        // Load all blog categories for navigation
        $blogCategories = \App\Models\BlogCategory::active()
            ->ordered()
            ->get();
        
        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();
        
        return view('frontend.blog', compact('blogs', 'blogCategories', 'category', 'settings'));
    }

    public function blogShow($slug)
    {
        $blog = \App\Models\Blog::with('category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Get settings for dynamic colors
        $settings = SettingsHelper::getBranding();

        // Get previous and next blog posts for navigation
        $previousBlog = \App\Models\Blog::published()
            ->where('sort_order', '<', $blog->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        $nextBlog = \App\Models\Blog::published()
            ->where('sort_order', '>', $blog->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        // Get related blogs (e.g., 3 random from the same category, excluding current)
        $relatedBlogs = \App\Models\Blog::with('category')
            ->published()
            ->where('id', '!=', $blog->id)
            ->when($blog->category_id, function ($query) use ($blog) {
                return $query->where('category_id', $blog->category_id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Load all blog categories for sidebar navigation
        $blogCategories = \App\Models\BlogCategory::active()
            ->ordered()
            ->get();
        
        return view('frontend.blog-single', compact('blog', 'settings', 'previousBlog', 'nextBlog', 'relatedBlogs', 'blogCategories'));
    }

    public function blogView($slug)
    {
        $blog = \App\Models\Blog::where('slug', $slug)->first();
        if ($blog) {
            // Track view count (session-based)
            ViewTrackingHelper::trackView('blog', $blog->id, $blog);
        }
        return response()->json(['success' => true]);
    }
    
    /**
     * Track coupon view (called via AJAX when reveal code/get deal is clicked)
     */
    public function trackCouponView(Request $request)
    {
        $couponId = $request->input('coupon_id');
        
        if (!$couponId) {
            return response()->json(['success' => false, 'message' => 'Coupon ID is required'], 400);
        }
        
        $coupon = Coupon::find($couponId);
        
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Coupon not found'], 404);
        }
        
        // Track view count (session-based)
        $tracked = ViewTrackingHelper::trackView('coupon', $couponId, $coupon);
        
        return response()->json([
            'success' => true,
            'tracked' => $tracked,
            'views_count' => $coupon->fresh()->views_count
        ]);
    }

    public function allBrandsUk(Request $request)
    {
        $query = $request->get('q');
        
        // If no query parameter, show all stores grouped by alphabet
        if (!$query) {
            $allStores = Store::where('status', 1)
                ->orderBy('store_name', 'asc')
                ->get();
            
            // Group stores by first letter
            $storesByLetter = $allStores->groupBy(function($store) {
                $firstLetter = strtoupper(substr($store->store_name, 0, 1));
                // Group numbers together
                if (is_numeric($firstLetter)) {
                    return '0-9';
                }
                return $firstLetter;
            });
            
            return view('frontend.all-brands', compact('storesByLetter', 'query'));
        }
        
        // Load stores filtered by alphabet
        $storesQuery = Store::where('status', 1);
        
        // Filter by alphabet if query parameter exists
        if ($query && $query !== '0-9') {
            $storesQuery->where('store_name', 'like', $query . '%');
        } elseif ($query === '0-9') {
            $storesQuery->whereRaw('store_name REGEXP "^[0-9]"');
        }
        
        $stores = $storesQuery->orderBy('store_name', 'asc')->get();

        return view('frontend.all-brands', compact('stores', 'query'));
    }

    public function contactDetails()
    {
        // Load contact page content from admin
        $contactPage = Page::where('page_slug', 'contact-us')
            ->where('status', 1)
            ->first();

        return view('frontend.contact-details', compact('contactPage'));
    }

    public function category($slug)
    {
        // Load category by seo_url
        $category = Category::where('seo_url', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Track view count (session-based)
        ViewTrackingHelper::trackView('category', $category->id, $category);

        // Load stores in this category
        $stores = Store::whereHas('categories', function($query) use ($category) {
            $query->where('category_id', $category->id);
        })
        ->where('status', 1)
        ->orderBy('sort_order', 'asc')
        ->paginate(20);

        // Load coupons for stores in this category with today's usage count
        $categoryCoupons = Coupon::whereHas('store', function($query) use ($category) {
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('category_id', $category->id);
            });
        })
        ->where('status', 1)
        ->withCount(['usages as today_usage_count' => function($query) {
            $query->whereDate('usage_date', today());
        }])
        ->orderBy('sort_order', 'asc')
        ->take(12)
        ->get();

        // Load related categories
        $relatedCategories = Category::where('status', 1)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        // Load trending stores for sidebar
        $trendingStores = Store::where('show_trending', 1)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->take(30)
            ->get();

        return view('frontend.single_category', compact('category', 'stores', 'categoryCoupons', 'relatedCategories', 'trendingStores'));
    }

    public function store($slug)
    {
        // Load store by slug
        $store = Store::where('seo_url', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Track view count (session-based)
        ViewTrackingHelper::trackView('store', $store->id, $store);

        // Load store's categories
        $storeCategories = $store->categories()->where('status', 1)->get();

        // Load store's events
        $storeEvents = $store->events()->where('status', 1)->get();

        // Load coupons for this store with today's usage count
        $storeCoupons = Coupon::where('brand_store', $store->store_name)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->withCount(['usages as today_usage_count' => function($query) {
                $query->whereDate('usage_date', today());
            }])
            ->get();

        // Load related stores
        $relatedStores = Store::whereHas('categories', function($query) use ($store) {
            $query->whereIn('category_id', $store->categories->pluck('id'));
        })
        ->where('id', '!=', $store->id)
        ->where('status', 1)
        ->orderBy('sort_order', 'asc')
        ->take(6)
        ->get();

        return view('frontend.store', compact(
            'store', 
            'storeCategories', 
            'storeEvents', 
            'storeCoupons', 
            'relatedStores'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return redirect()->route('home');
        }

        // Search in stores
        $stores = Store::where('status', 1)
            ->where(function($q) use ($query) {
                $q->where('store_name', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('sort_order', 'asc')
            ->paginate(20);

        // Search in coupons
        $coupons = Coupon::where('status', 1)
            ->where(function($q) use ($query) {
                $q->where('coupon_title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('sort_order', 'asc')
            ->paginate(20);

        // Search in categories
        $categories = Category::where('category_name', 'like', "%{$query}%")
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.search', compact('query', 'stores', 'coupons', 'categories'));
    }

    public function getHeaderSearchDefault(Request $request)
    {
        // Return default search data for the header search overlay
        $featuredCoupons = Coupon::with('store')
            ->where('status', 1)
            ->where('featured', 1)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        // If no featured coupons, get some regular coupons
        if ($featuredCoupons->isEmpty()) {
            $featuredCoupons = Coupon::with('store')
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(6)
                ->get();
        }

        $trendingStores = Store::where('show_trending', 1)
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->take(8)
            ->get();

        // If no trending stores, get some regular stores
        if ($trendingStores->isEmpty()) {
            $trendingStores = Store::where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(8)
                ->get();
        }

        $featuredCategories = Category::where('status', 1)
            ->where('featured', 1)
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        // If no featured categories, get some regular categories
        if ($featuredCategories->isEmpty()) {
            $featuredCategories = Category::where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(6)
                ->get();
        }

        return response()->json([
            'coupons' => $featuredCoupons,
            'stores' => $trendingStores,
            'categories' => $featuredCategories
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([
                'stores' => [],
                'coupons' => [],
                'categories' => [],
                'message' => 'Please enter at least 2 characters',
                'total_results' => 0
            ]);
        }

        // Search in stores
        $stores = Store::where('status', 1)
            ->where(function($q) use ($query) {
                $q->where('store_name', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('sort_order', 'asc')
            ->take(10)
            ->get();

        // Search in coupons with store relationship
        $coupons = Coupon::with('store')
            ->where('status', 1)
            ->where(function($q) use ($query) {
                $q->where('coupon_title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('brand_store', 'like', "%{$query}%");
            })
            ->orderBy('sort_order', 'asc')
            ->take(10)
            ->get();

        // Search in categories
        $categories = Category::where('status', 1)
            ->where('category_name', 'like', "%{$query}%")
            ->orderBy('sort_order', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'stores' => $stores,
            'coupons' => $coupons,
            'categories' => $categories,
            'query' => $query,
            'total_results' => $stores->count() + $coupons->count() + $categories->count()
        ]);
    }

    public function testSearch($query)
    {
        // Test search functionality
        $stores = Store::where('status', 1)
            ->where(function($q) use ($query) {
                $q->where('store_name', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('sort_order', 'asc')
            ->take(10)
            ->get();

        $coupons = Coupon::with('store')
            ->where('status', 1)
            ->where(function($q) use ($query) {
                $q->where('coupon_title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('brand_store', 'like', "%{$query}%");
            })
            ->orderBy('sort_order', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'query' => $query,
            'stores_count' => $stores->count(),
            'coupons_count' => $coupons->count(),
            'stores' => $stores->pluck('store_name'),
            'coupons' => $coupons->pluck('coupon_title'),
            'debug' => [
                'total_stores' => Store::where('status', 1)->count(),
                'total_coupons' => Coupon::where('status', 1)->count(),
                'search_query' => $query
            ]
        ]);
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // Check if email already exists
            $existingNewsletter = Newsletter::where('email', $request->email)->first();
            
            if ($existingNewsletter) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.'
                ], 422);
            }

            Newsletter::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_active' => true,
                'subscribed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to our newsletter!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function adminNewsletters(Request $request)
    {
        $newsletters = Newsletter::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.newsletters.index', compact('newsletters'));
    }

    public function adminNewsletterDelete(Newsletter $newsletter)
    {
        $newsletter->delete();
        
        return redirect()->route('admin.newsletters.index')
                        ->with('success', 'Newsletter subscriber deleted successfully!');
    }

    public function adminNewsletterBulkDelete(Request $request)
    {
        $request->validate([
            'newsletter_ids' => 'required|array|min:1',
            'newsletter_ids.*' => 'exists:newsletters,id',
        ]);

        Newsletter::whereIn('id', $request->newsletter_ids)->delete();

        return redirect()->route('admin.newsletters.index')
                        ->with('success', 'Selected newsletter subscribers deleted successfully!');
    }

    public function recommendedStores(Request $request)
    {
        $type = $request->get('type');
        
        $query = Store::where('status', 1);
        
        // If type is provided, filter by category
        if ($type) {
            $query->whereHas('categories', function($q) use ($type) {
                $q->where('category_id', $type);
            });
        } else {
            $query->where('featured', 1);
        }
        
        $stores = $query->orderBy('sort_order', 'asc')
            ->take(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'stores' => $stores->map(function($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->store_name,
                    'slug' => $store->seo_url ?? '',
                    'sname' => $store->store_name,
                    'sid' => $store->id,
                    'logo' => $store->store_logo ? asset($store->store_logo) : (asset('assets/img/default-store.png')),
                    'description' => $store->store_tagline ?? '',
                    'total_coupons' => $store->coupons()->where('status', 1)->count()
                ];
            })
        ]);
    }

    public function trackCouponUsage(Request $request)
    {
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id'
        ]);

        try {
            $couponId = $request->coupon_id;
            $ipAddress = $request->ip();
            $sessionId = $request->session()->getId();
            
            // Create unique key combining session ID and IP for better tracking
            $sessionKey = 'coupon_used_' . $couponId . '_' . $sessionId;
            
            // Check if user has already used this coupon in this session
            if ($request->session()->has($sessionKey)) {
                // User already used this coupon, return current counts without incrementing
                $coupon = Coupon::findOrFail($couponId);
                
                // Get today's count (from database)
                $todayCount = $coupon->usages()
                    ->whereDate('usage_date', today())
                    ->count();
                
                return response()->json([
                    'success' => true,
                    'already_used' => true,
                    'used_count' => $coupon->used_count,
                    'today_count' => $todayCount
                ]);
            }
            
            // Also check in database if this IP+Session combination already used this coupon today
            $existingUsage = CouponUsage::where('coupon_id', $couponId)
                ->whereDate('usage_date', today())
                ->where('ip_address', $ipAddress)
                ->where('session_id', $sessionId)
                ->first();
            
            if ($existingUsage) {
                // Already used today with same IP and session
                $coupon = Coupon::findOrFail($couponId);
                $todayCount = $coupon->usages()
                    ->whereDate('usage_date', today())
                    ->count();
                
                // Mark in session to prevent future calls
                $request->session()->put($sessionKey, true);
                $request->session()->save();
                
                return response()->json([
                    'success' => true,
                    'already_used' => true,
                    'used_count' => $coupon->used_count,
                    'today_count' => $todayCount
                ]);
            }
            
            $coupon = Coupon::findOrFail($couponId);
            
            // Mark as used in session BEFORE creating database entry
            $request->session()->put($sessionKey, true);
            $request->session()->save(); // Force save session
            
            // Create usage record with session ID
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'usage_date' => today(),
                'ip_address' => $ipAddress,
                'session_id' => $sessionId
            ]);

            // Increment used_count
            $coupon->increment('used_count');

            // Get today's count
            $todayCount = $coupon->usages()
                ->whereDate('usage_date', today())
                ->count();

            return response()->json([
                'success' => true,
                'already_used' => false,
                'used_count' => $coupon->used_count,
                'today_count' => $todayCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Coupon usage tracking error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to track usage'
            ], 500);
        }
    }
}
