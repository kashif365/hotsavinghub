<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceOptimization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add performance headers and compression
        if ($response instanceof Response) {
            // Enable compression via PHP if mod_deflate is not available
            $contentType = $response->headers->get('Content-Type', '');
            $isCompressible = strpos($contentType, 'text/html') !== false || 
                             strpos($contentType, 'text/css') !== false || 
                             strpos($contentType, 'application/javascript') !== false ||
                             strpos($contentType, 'text/javascript') !== false ||
                             strpos($contentType, 'application/json') !== false;
            
            if ($isCompressible && !$response->headers->has('Content-Encoding')) {
                $content = $response->getContent();
                if ($content && strlen($content) > 512) { // Compress if > 512 bytes
                    // Check if client accepts gzip
                    $acceptEncoding = $request->headers->get('Accept-Encoding', '');
                    if (strpos($acceptEncoding, 'gzip') !== false || strpos($acceptEncoding, 'deflate') !== false) {
                        $compressed = @gzencode($content, 6); // Compression level 6 (balanced)
                        if ($compressed !== false && strlen($compressed) < strlen($content)) {
                            $response->setContent($compressed);
                            $response->headers->set('Content-Encoding', 'gzip');
                            $response->headers->set('Vary', 'Accept-Encoding');
                            $response->headers->set('Content-Length', strlen($compressed));
                        }
                    }
                }
            }
            // Cache static assets for 1 year with efficient cache policies
            $path = $request->path();
            $requestPath = $request->getPathInfo();
            $requestUri = $request->getRequestUri();
            
            // Check for static assets - CSS, JS, fonts
            if (preg_match('/\.(css|js|woff|woff2|ttf|eot)$/i', $path) || 
                preg_match('/frontend_assets\/(css|js|fonts)\//i', $requestUri) ||
                preg_match('/\/fonts\//i', $requestUri) ||
                preg_match('/\/css\//i', $requestUri) ||
                preg_match('/\/js\//i', $requestUri)) {
                // In development mode, disable caching for instant changes
                if (app()->environment('local') || config('app.debug')) {
                    $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
                    $response->headers->set('Pragma', 'no-cache');
                    $response->headers->set('Expires', '0');
                } else {
                    // Production: Cache static assets for 1 year
                $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
                }
            } 
            // Cache images for 1 year (immutable)
            elseif (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|ico|avif)$/i', $path) ||
                    preg_match('/\/uploads\//i', $requestUri)) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
                $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
            }
            // HTML pages - allow bfcache but no cache for dynamic content
            elseif ($response->headers->get('Content-Type') && strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
                // In development mode, disable all caching for instant changes
                if (app()->environment('local') || config('app.debug')) {
                    $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
                    $response->headers->set('Pragma', 'no-cache');
                    $response->headers->set('Expires', '0');
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
                    $response->headers->set('ETag', md5(time())); // Always different ETag
                } else {
                    // Production: Use no-cache instead of no-store to allow bfcache (back/forward cache)
                $response->headers->set('Cache-Control', 'no-cache, must-revalidate, max-age=0');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
                }
            }
            
            // Security headers for Best Practices
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
            
            // Content Security Policy (CSP) - adjust as needed for Best Practices
            // Note: CSP can be strict, so we'll use report-only mode or relaxed policy
            // For production, you may want to customize this based on your needs
            if (!$response->headers->has('Content-Security-Policy')) {
                // Relaxed CSP that allows necessary resources while maintaining security
                $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; img-src 'self' data: https: http: blob:; connect-src 'self' https:; frame-ancestors 'self'; base-uri 'self'; form-action 'self';";
                // Using report-only for now to avoid breaking functionality
                // $response->headers->set('Content-Security-Policy', $csp);
                // Uncomment above line when ready for production
            }
            
            // Performance hints
            $response->headers->set('X-DNS-Prefetch-Control', 'on');
            
            // Enable compression for text-based responses
            $contentType = $response->headers->get('Content-Type', '');
            if (strpos($contentType, 'text/html') !== false || 
                strpos($contentType, 'text/css') !== false || 
                strpos($contentType, 'application/javascript') !== false ||
                strpos($contentType, 'text/javascript') !== false) {
                // Note: Actual compression should be handled by web server (Apache/Nginx)
                // This header indicates content is compressible
                if (!$response->headers->has('Content-Encoding')) {
                    // Let web server handle compression via mod_deflate or gzip
                }
            }
            
            // Preload hints for critical resources
            if ($response->headers->get('Content-Type') && strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
                $preloadLinks = [
                    '<' . asset('frontend_assets/js/home.js') . '>; rel=preload; as=script',
                    '<' . asset('frontend_assets/css/fonts.css') . '>; rel=preload; as=style',
                    '<https://code.jquery.com/jquery-3.7.1.min.js>; rel=preload; as=script; crossorigin'
                ];
                $response->headers->set('Link', implode(', ', $preloadLinks), false);
            }
        }
        
        return $response;
    }
}
