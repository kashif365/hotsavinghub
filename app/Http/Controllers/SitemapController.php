<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Coupon;
use App\Models\Events;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap
     */
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';
        
        // Add static pages
        $sitemap .= $this->addStaticPages();
        
        // Add dynamic content
        $sitemap .= $this->addStores();
        $sitemap .= $this->addCoupons();
        $sitemap .= $this->addEvents();
        $sitemap .= $this->addPages();
        $sitemap .= $this->addBlogs();
        $sitemap .= $this->addCategories();
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Add static pages to sitemap
     */
    private function addStaticPages()
    {
        $baseUrl = config('app.url');
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/about-us', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/contact', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/privacy-policy', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/terms-conditions', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/stores', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => '/coupons', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/events', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/blogs', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => '/categories', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];
        
        $xml = '';
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . $page['url'] . '</loc>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '<lastmod>' . now()->toISOString() . '</lastmod>';
            $xml .= '</url>';
        }
        
        return $xml;
    }
    
    /**
     * Add stores to sitemap
     */
    private function addStores()
    {
        $baseUrl = config('app.url');
        $stores = Store::where('status', 1)->get();
        
        $xml = '';
        foreach ($stores as $store) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/store/' . $store->seo_url . '</loc>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<lastmod>' . $store->updated_at->toISOString() . '</lastmod>';
            
            // Add store image if exists
            if ($store->store_logo) {
                $xml .= '<image:image>';
                $xml .= '<image:loc>' . $baseUrl . '/uploads/' . $store->store_logo . '</image:loc>';
                $xml .= '<image:title>' . htmlspecialchars($store->store_name) . '</image:title>';
                $xml .= '</image:image>';
            }
            
            $xml .= '</url>';
        }
        
        return $xml;
    }
    
    /**
     * Add coupons to sitemap
     */
    private function addCoupons()
    {
        $baseUrl = config('app.url');
        $coupons = Coupon::where('status', 1)->get();
        
        $xml = '';
        foreach ($coupons as $coupon) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/coupon/' . $coupon->id . '</loc>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<lastmod>' . $coupon->updated_at->toISOString() . '</lastmod>';
            
            // Add coupon image if exists
            if ($coupon->cover_logo) {
                $xml .= '<image:image>';
                $xml .= '<image:loc>' . $baseUrl . '/' . $coupon->cover_logo . '</image:loc>';
                $xml .= '<image:title>' . htmlspecialchars($coupon->coupon_title) . '</image:title>';
                $xml .= '</image:image>';
            }
            
            $xml .= '</url>';
        }
        
        return $xml;
    }
    
    /**
     * Add events to sitemap
     */
    private function addEvents()
    {
        $baseUrl = config('app.url');
        $events = Events::where('status', 1)->get();
        
        $xml = '';
        foreach ($events as $event) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/event/' . $event->seo_url . '</loc>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<lastmod>' . $event->updated_at->toISOString() . '</lastmod>';
            $xml .= '</url>';
        }
        
        return $xml;
    }
    
    /**
     * Add pages to sitemap
     */
    private function addPages()
    {
        $baseUrl = config('app.url');
        $pages = Page::where('status', 1)->get();
        
        $xml = '';
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/page/' . $page->seo_url . '</loc>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<lastmod>' . $page->updated_at->toISOString() . '</lastmod>';
            $xml .= '</url>';
        }
        
        return $xml;
    }
    
    /**
     * Add blogs to sitemap
     */
    private function addBlogs()
    {
        $baseUrl = config('app.url');
        $blogs = Blog::where('status', 'published')->get();
        
        $xml = '';
        foreach ($blogs as $blog) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/blog/' . $blog->slug . '</loc>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<lastmod>' . $blog->updated_at->toISOString() . '</lastmod>';
            
            // Add blog image if exists
            if ($blog->featured_image) {
                $xml .= '<image:image>';
                $xml .= '<image:loc>' . $baseUrl . '/' . $blog->featured_image . '</image:loc>';
                $xml .= '<image:title>' . htmlspecialchars($blog->title) . '</image:title>';
                $xml .= '</image:image>';
            }
            
            $xml .= '</url>';
        }
        
        return $xml;
    }
    
    /**
     * Add categories to sitemap
     */
    private function addCategories()
    {
        $baseUrl = config('app.url');
        $categories = Category::where('status', 1)->get();
        
        $xml = '';
        foreach ($categories as $category) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/category/' . $category->seo_url . '</loc>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<lastmod>' . $category->updated_at->toISOString() . '</lastmod>';
            $xml .= '</url>';
        }
        
        return $xml;
    }
}