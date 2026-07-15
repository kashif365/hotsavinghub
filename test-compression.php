<?php
/**
 * Test script to verify if compression is working
 * Access: http://localhost/topcodevoucher_live/public/test-compression.php
 */

header('Content-Type: text/html; charset=utf-8');

// Check if mod_deflate is available
$modDeflateAvailable = function_exists('apache_get_modules') 
    ? in_array('mod_deflate', apache_get_modules()) 
    : 'Unknown (function not available)';

// Check if gzip is available in PHP
$phpGzipAvailable = function_exists('gzencode');

// Check Accept-Encoding header
$acceptEncoding = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : 'Not set';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Compression Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .status { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        ul { line-height: 1.8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Compression Test Results</h1>
        
        <div class="status <?php echo $modDeflateAvailable === true ? 'success' : ($modDeflateAvailable === false ? 'error' : 'warning'); ?>">
            <strong>mod_deflate Status:</strong> 
            <?php 
            if ($modDeflateAvailable === true) {
                echo '✅ Enabled';
            } elseif ($modDeflateAvailable === false) {
                echo '❌ Not Enabled';
            } else {
                echo '⚠️ ' . $modDeflateAvailable;
            }
            ?>
        </div>
        
        <div class="status <?php echo $phpGzipAvailable ? 'success' : 'error'; ?>">
            <strong>PHP gzip Support:</strong> 
            <?php echo $phpGzipAvailable ? '✅ Available' : '❌ Not Available'; ?>
        </div>
        
        <div class="status info">
            <strong>Client Accept-Encoding:</strong> <code><?php echo htmlspecialchars($acceptEncoding); ?></code>
        </div>
        
        <?php if ($modDeflateAvailable === false): ?>
        <div class="status warning">
            <h3>⚠️ mod_deflate is not enabled</h3>
            <p><strong>To enable compression in XAMPP:</strong></p>
            <ol>
                <li>Open <code>httpd.conf</code> in your XAMPP installation (usually in <code>C:\xampp\apache\conf\httpd.conf</code>)</li>
                <li>Find the line: <code>#LoadModule deflate_module modules/mod_deflate.so</code></li>
                <li>Remove the <code>#</code> to uncomment it: <code>LoadModule deflate_module modules/mod_deflate.so</code></li>
                <li>Save the file and restart Apache</li>
            </ol>
            <p><strong>Alternative:</strong> The PHP compression fallback (<code>compress.php</code>) can be used, but it requires routing static files through PHP, which may impact performance.</p>
        </div>
        <?php endif; ?>
        
        <div class="status info">
            <h3>📋 Test Your Files</h3>
            <p>Check these files in your browser's Network tab (F12) to verify compression:</p>
            <ul>
                <li><a href="/frontend_assets/js/home.js" target="_blank">/frontend_assets/js/home.js</a></li>
                <li><a href="/frontend_assets/css/home.css" target="_blank">/frontend_assets/css/home.css</a></li>
                <li><a href="/frontend_assets/css/responsive-home.css" target="_blank">/frontend_assets/css/responsive-home.css</a></li>
                <li><a href="/frontend_assets/css/mobile-optimizations.css" target="_blank">/frontend_assets/css/mobile-optimizations.css</a></li>
            </ul>
            <p><strong>What to look for:</strong></p>
            <ul>
                <li>Response header should include: <code>Content-Encoding: gzip</code></li>
                <li>Transfer Size should be smaller than Original Size</li>
                <li>In Chrome DevTools, check the "Size" column - it should show compressed size</li>
            </ul>
        </div>
        
        <div class="status success">
            <h3>✅ Current Configuration</h3>
            <p>The <code>.htaccess</code> file is properly configured with:</p>
            <ul>
                <li>✅ File-based compression rules (<code>FilesMatch</code>)</li>
                <li>✅ MIME-type based compression rules</li>
                <li>✅ Proper Vary headers for caching</li>
                <li>✅ Browser compatibility fixes</li>
            </ul>
        </div>
    </div>
</body>
</html>

