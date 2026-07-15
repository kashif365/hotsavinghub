<?php
/**
 * PHP-based compression fallback for static files
 * Used when mod_deflate is not available
 */

// Get the file path from query string
$file = isset($_GET['file']) ? $_GET['file'] : '';

if (empty($file)) {
    http_response_code(404);
    exit;
}

// Security: Only allow files from public directory
$file = ltrim($file, '/');
// Remove query string if present (e.g., ?v=176...)
$file = preg_replace('/\?.*$/', '', $file);
$filePath = __DIR__ . '/' . $file;

// Prevent directory traversal and ensure file is within public directory
$realFilePath = realpath($filePath);
$realDir = realpath(__DIR__);

if (strpos($file, '..') !== false || 
    !file_exists($filePath) || 
    !is_file($filePath) ||
    $realFilePath === false ||
    $realDir === false ||
    strpos($realFilePath, $realDir) !== 0) {
    http_response_code(404);
    exit;
}

// Only compress text-based files
$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$compressible = in_array($ext, ['js', 'css', 'html', 'htm', 'txt', 'xml', 'json', 'svg']);

if (!$compressible) {
    // Serve file normally if not compressible
    $mimeType = mime_content_type($filePath);
    header('Content-Type: ' . $mimeType);
    readfile($filePath);
    exit;
}

// Determine MIME type
$mimeTypes = [
    'js' => 'application/javascript',
    'css' => 'text/css',
    'html' => 'text/html',
    'htm' => 'text/html',
    'txt' => 'text/plain',
    'xml' => 'text/xml',
    'json' => 'application/json',
    'svg' => 'image/svg+xml'
];

$mimeType = $mimeTypes[$ext] ?? 'text/plain';

// Check if client accepts gzip
$acceptEncoding = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';
$supportsGzip = strpos($acceptEncoding, 'gzip') !== false || strpos($acceptEncoding, 'deflate') !== false;

// Read file content
$content = file_get_contents($filePath);
$originalSize = strlen($content);

// Always compress if file is > 512 bytes and client supports it
// Use compression level 9 for maximum compression (better for static files)
if ($supportsGzip && $originalSize > 512) {
    // Compress with gzip (level 9 for maximum compression of static assets)
    $compressed = @gzencode($content, 9);
    
    if ($compressed !== false && strlen($compressed) < $originalSize) {
        // Set proper headers for compressed content
        // Set no-gzip environment variable to prevent mod_deflate from double-compressing
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        
        header('Content-Type: ' . $mimeType . '; charset=utf-8');
        header('Content-Encoding: gzip');
        header('Vary: Accept-Encoding');
        header('Content-Length: ' . strlen($compressed));
        header('Cache-Control: public, max-age=31536000, immutable');
        header('ETag: "' . md5($compressed) . '"');
        
        // Prevent mod_deflate from processing this response
        header('X-Compressed-By: PHP-gzip');
        
        // Handle If-None-Match for caching
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $etag = trim($_SERVER['HTTP_IF_NONE_MATCH'], '"');
            if ($etag === md5($compressed)) {
                http_response_code(304);
                exit;
            }
        }
        
        echo $compressed;
        exit;
    }
}

// Serve uncompressed if compression failed or not supported
header('Content-Type: ' . $mimeType . '; charset=utf-8');
header('Cache-Control: public, max-age=31536000, immutable');
header('Content-Length: ' . $originalSize);
header('ETag: "' . md5($content) . '"');

// Handle If-None-Match for caching
if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    $etag = trim($_SERVER['HTTP_IF_NONE_MATCH'], '"');
    if ($etag === md5($content)) {
        http_response_code(304);
        exit;
    }
}

echo $content;

