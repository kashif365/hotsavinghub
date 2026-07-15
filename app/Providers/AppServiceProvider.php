<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Store;
use App\Models\Category;
use App\Models\VerificationTag;

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
            
            $topCategories = Category::where('show_top', 1)
                ->where('status', 1)
                ->orderBy('sort_order', 'asc')
                ->take(10)
                ->get();
            
            // Get all active categories for header dropdown
            $allCategories = Category::where('status', 1)
                ->orderBy('category_name', 'asc')
                ->get();
            
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
                'verificationTagPlacements' => $verificationTagPlacements,
            ]);
        });
    }
}
