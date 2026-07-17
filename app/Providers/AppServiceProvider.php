<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Store;
use App\Models\VerificationTag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Aggressively disable all caching in development mode for instant changes
        if (app()->environment('local') || config('app.debug')) {
            // Disable view caching - views will be compiled fresh on every request
            config(['view.cache' => false]);
        }

        // Share trending stores and top categories data with all frontend views
        View::composer('frontend.*', function ($view) {
            $trendingStores = Store::where('show_trending', 1)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(20)
                ->get();

            // If no stores are manually marked trending, fall back to the most-viewed active stores
            // (mirrors the same fallback used in FrontendController::home/topDiscounts/category).
            if ($trendingStores->isEmpty()) {
                $trendingStores = Store::where('status', 1)
                    ->orderByDesc('views_count')
                    ->take(20)
                    ->get();
            }

            $topCategories = Category::where('show_top', 1)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(10)
                ->get();

            // Get all active categories for header dropdown
            $allCategories = Category::where('status', 1)
                ->orderBy('category_name', 'asc')
                ->get();

            // Compact categories mega menu: categories with their active stores eager-loaded
            // (single query, no N+1), plus a "Popular" store list. Cached for an hour and
            // invalidated in CategoryController/StoreController whenever the underlying data changes.
            $navCategoryMenu = Cache::remember('nav_category_menu_v1', 3600, function () {
                $categories = Category::where('status', 1)
                    ->orderBy('sort_order')
                    ->with(['stores' => function ($query) {
                        $query->select('stores.id', 'stores.store_name', 'stores.seo_url', 'stores.sort_order')
                            ->where('stores.status', 1)
                            ->orderBy('stores.sort_order')
                            ->orderBy('stores.store_name');
                    }])
                    ->get(['id', 'category_name', 'seo_url', 'sort_order']);

                $popularStores = Store::where('status', 1)
                    ->orderByDesc('featured')
                    ->orderByDesc('views_count')
                    ->orderBy('sort_order')
                    ->take(8)
                    ->get(['id', 'store_name', 'seo_url']);

                return ['categories' => $categories, 'popular' => $popularStores];
            });

            $verificationTagPlacements = collect();
            if (Schema::hasTable('verification_tags')) {
                $verificationTagPlacements = VerificationTag::active()
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->groupBy('placement');
            }

            $view->with([
                'trendingStores' => $trendingStores,
                'topCategories' => $topCategories,
                'allCategories' => $allCategories,
                'navCategoryMenu' => $navCategoryMenu,
                'verificationTagPlacements' => $verificationTagPlacements,
            ]);
        });
    }
}
