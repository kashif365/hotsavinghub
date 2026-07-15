<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track authenticated users
        if (auth()->check()) {
            $this->trackPageVisit($request);
        }

        return $response;
    }

    /**
     * Track page visit
     */
    private function trackPageVisit(Request $request)
    {
        // Skip tracking for certain routes
        $skipRoutes = [
            'admin.activity-logs-dashboard',
            'admin.activity-logs.index',
            'admin.activity-logs.show',
            'admin.activity-logs.export',
            'admin.activity-logs.cleanup',
        ];

        $routeName = $request->route()?->getName();
        
        if (in_array($routeName, $skipRoutes)) {
            return;
        }

        // Skip AJAX requests
        if ($request->ajax()) {
            return;
        }

        // Skip asset requests
        if ($request->is('assets/*') || $request->is('css/*') || $request->is('js/*') || $request->is('img/*')) {
            return;
        }

        try {
            $user = auth()->user();
            $url = $request->fullUrl();
            $method = $request->method();
            $routeName = $request->route()?->getName() ?? 'unknown';
            $pageTitle = $this->getPageTitle($request);

            // Create description
            $description = "Visited page: {$pageTitle} ({$routeName})";

            // Log the page visit
            ActivityLogService::log('view', $description, null, null, [
                'url' => $url,
                'method' => $method,
                'route_name' => $routeName,
                'page_title' => $pageTitle,
                'referrer' => $request->header('referer'),
            ], $request);

        } catch (\Exception $e) {
            // Don't break the application if logging fails
            \Log::error('Page visit tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Get page title based on route
     */
    private function getPageTitle(Request $request)
    {
        $routeName = $request->route()?->getName();
        
        $titles = [
            'admin.dashboard' => 'Admin Dashboard',
            'admin.blogs.index' => 'Blogs List',
            'admin.blogs.create' => 'Create Blog',
            'admin.blogs.edit' => 'Edit Blog',
            'admin.blogs.show' => 'View Blog',
            'admin.coupons.index' => 'Coupons List',
            'admin.coupons.create' => 'Create Coupon',
            'admin.coupons.edit' => 'Edit Coupon',
            'admin.coupons.show' => 'View Coupon',
            'admin.users.index' => 'Users List',
            'admin.users.create' => 'Create User',
            'admin.users.edit' => 'Edit User',
            'admin.users.show' => 'View User',
            'admin.stores.index' => 'Stores List',
            'admin.stores.create' => 'Create Store',
            'admin.stores.edit' => 'Edit Store',
            'admin.stores.show' => 'View Store',
            'admin.categories.index' => 'Categories List',
            'admin.categories.create' => 'Create Category',
            'admin.categories.edit' => 'Edit Category',
            'admin.events.index' => 'Events List',
            'admin.events.create' => 'Create Event',
            'admin.events.edit' => 'Edit Event',
            'admin.contacts.index' => 'Contact Submissions',
            'admin.newsletters.index' => 'Newsletter Subscribers',
            'admin.settings.index' => 'Settings',
            'admin.activity-logs.index' => 'Activity Logs',
            'admin.activity-logs.show' => 'View Activity Log',
        ];

        return $titles[$routeName] ?? ucfirst(str_replace(['admin.', '.', '_'], ['', ' ', ' '], $routeName ?? 'Unknown Page'));
    }
}
