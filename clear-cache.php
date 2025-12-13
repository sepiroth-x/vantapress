<?php
/**
 * Cache Clearing Script for Shared Hosting
 * 
 * Access via: https://vantapress.com/clear-cache.php
 * 
 * WARNING: Delete this file after use for security!
 */

// Change this to a secure random string
define('SECRET_KEY', 'vp_clear_' . md5('vantapress2025'));

// Check if secret key is provided
if (!isset($_GET['key']) || $_GET['key'] !== SECRET_KEY) {
    die('Access denied. Provide correct key parameter.');
}

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$results = [];

try {
    // Clear config cache
    $configPath = $app->bootstrapPath('cache/config.php');
    if (file_exists($configPath)) {
        unlink($configPath);
        $results[] = '✓ Config cache cleared';
    } else {
        $results[] = '- Config cache already clear';
    }
    
    // Clear route cache
    $routesPath = $app->bootstrapPath('cache/routes-v7.php');
    if (file_exists($routesPath)) {
        unlink($routesPath);
        $results[] = '✓ Route cache cleared';
    } else {
        $results[] = '- Route cache already clear';
    }
    
    // Clear view cache
    $viewPath = storage_path('framework/views');
    if (is_dir($viewPath)) {
        $files = glob($viewPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        $results[] = '✓ View cache cleared (' . count($files) . ' files)';
    }
    
    // Clear application cache
    $cachePath = storage_path('framework/cache/data');
    if (is_dir($cachePath)) {
        $files = glob($cachePath . '/*');
        $count = 0;
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                unlink($file);
                $count++;
            }
        }
        $results[] = '✓ Application cache cleared (' . $count . ' files)';
    }
    
    // Clear Laravel cache via Artisan
    try {
        Artisan::call('cache:clear');
        $results[] = '✓ Laravel cache cleared via Artisan';
    } catch (\Exception $e) {
        $results[] = '! Could not clear cache via Artisan: ' . $e->getMessage();
    }
    
    $results[] = '';
    $results[] = '===========================================';
    $results[] = '✓ All caches cleared successfully!';
    $results[] = '===========================================';
    $results[] = '';
    $results[] = 'Next steps:';
    $results[] = '1. Try logging in to admin panel';
    $results[] = '2. DELETE THIS FILE for security!';
    
} catch (\Exception $e) {
    $results[] = 'ERROR: ' . $e->getMessage();
    $results[] = 'Trace: ' . $e->getTraceAsString();
}

// Output results
header('Content-Type: text/plain');
echo implode("\n", $results);
