<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml in the public directory';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sitemap = Sitemap::create();

        // Static pages
        $staticPaths = [
            '/', '/about-us', '/contact', '/privacy-policy', '/terms-conditions',
            '/stores', '/coupons', '/events', '/blogs', '/categories'
        ];
        foreach ($staticPaths as $path) {
            $sitemap->add(Url::create(url($path)));
        }

        // Stores (seo_url)
        if (class_exists(\App\Models\Store::class) && Schema::hasTable('stores')) {
            $stores = \App\Models\Store::query()
                ->where('status', 1)
                ->whereNotNull('seo_url')
                ->select('seo_url', 'updated_at')
                ->get();

            foreach ($stores as $store) {
                $sitemap->add(Url::create(url('/store/' . $store->seo_url))
                    ->setLastModificationDate($store->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        }

        // Categories (seo_url)
        if (class_exists(\App\Models\Category::class) && Schema::hasTable('categories')) {
            $categories = \App\Models\Category::query()
                ->where('status', 1)
                ->whereNotNull('seo_url')
                ->select('seo_url', 'updated_at')
                ->get();

            foreach ($categories as $category) {
                $sitemap->add(Url::create(url('/category/' . $category->seo_url))
                    ->setLastModificationDate($category->updated_at)
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            }
        }

        // Events (seo_url)
        if (class_exists(\App\Models\Events::class) && Schema::hasTable('events')) {
            $events = \App\Models\Events::query()
                ->where('status', 1)
                ->whereNotNull('seo_url')
                ->select('seo_url', 'updated_at')
                ->get();

            foreach ($events as $event) {
                $sitemap->add(Url::create(url('/event/' . $event->seo_url))
                    ->setLastModificationDate($event->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        }

        // Blog posts: prefer App\Models\Post, fallback to App\Models\Blog
        if (class_exists(\App\Models\Post::class) && Schema::hasTable('posts')) {
            $posts = \App\Models\Post::query()
                ->where('status', 'published')
                ->whereNotNull('slug')
                ->select('slug', 'updated_at')
                ->get();

            foreach ($posts as $post) {
                $sitemap->add(Url::create(url('/blog/' . $post->slug))
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        } elseif (class_exists(\App\Models\Blog::class) && Schema::hasTable('blogs')) {
            $blogs = \App\Models\Blog::query()
                ->where('status', 'published')
                ->whereNotNull('slug')
                ->select('slug', 'updated_at')
                ->get();

            foreach ($blogs as $blog) {
                $sitemap->add(Url::create(url('/blog/' . $blog->slug))
                    ->setLastModificationDate($blog->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        }

        // Pages (seo_url)
        if (class_exists(\App\Models\Page::class) && Schema::hasTable('pages')) {
            $pages = \App\Models\Page::query()
                ->where('status', 1)
                ->whereNotNull('seo_url')
                ->select('seo_url', 'updated_at')
                ->get();

            foreach ($pages as $page) {
                $sitemap->add(Url::create(url('/page/' . $page->seo_url))
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
            }
        }

        // Coupons
        if (class_exists(\App\Models\Coupon::class) && Schema::hasTable('coupons')) {
            $coupons = \App\Models\Coupon::query()
                ->where('status', 1)
                ->select('id', 'updated_at')
                ->get();

            foreach ($coupons as $coupon) {
                $sitemap->add(Url::create(url('/coupon/' . $coupon->id))
                    ->setLastModificationDate($coupon->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            }
        }

        $targetPath = public_path('sitemap.xml');
        $sitemap->writeToFile($targetPath);

        $this->info('Sitemap generated successfully at ' . $targetPath);

        return self::SUCCESS;
    }
}


