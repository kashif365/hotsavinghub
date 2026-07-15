<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableCacheInDevelopment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Aggressively disable all caching in development mode
        if (app()->environment('local') || config('app.debug')) {
            // Force disable ALL browser caching - override any existing cache headers
            $response->headers->remove('Cache-Control');
            $response->headers->remove('Pragma');
            $response->headers->remove('Expires');
            $response->headers->remove('Last-Modified');
            $response->headers->remove('ETag');
            
            // Set aggressive no-cache headers
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private, no-transform');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
            $response->headers->set('ETag', md5(time() . rand() . microtime()));
            
            // Add version query string to prevent browser caching for HTML responses
            if ($response->headers->get('Content-Type') && strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
                $content = $response->getContent();
                if ($content) {
                    // Add unique timestamp to CSS/JS links to bust cache
                    $timestamp = time() . rand(1000, 9999);
                    $content = preg_replace_callback(
                        '/(href|src)=["\']([^"\']+\.(css|js))["\']/i',
                        function($matches) use ($timestamp) {
                            $url = $matches[2];
                            // Remove existing version parameters
                            $url = preg_replace('/[?&]v=\d+/', '', $url);
                            $separator = strpos($url, '?') !== false ? '&' : '?';
                            return $matches[1] . '="' . $url . $separator . 'v=' . $timestamp . '"';
                        },
                        $content
                    );
                    $response->setContent($content);
                }
            }
        }
        
        return $response;
    }
}

