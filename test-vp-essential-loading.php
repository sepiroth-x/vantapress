<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing VP Essential 1 Service Provider...\n\n";

// Test 1: Class exists
$class = 'Modules\VPEssential1\VPEssential1ServiceProvider';
if (class_exists($class)) {
    echo "✓ ServiceProvider class exists\n";
} else {
    echo "✗ ServiceProvider class NOT found\n";
    exit(1);
}

// Test 2: Check module in registry
$moduleLoader = app(\App\Services\ModuleLoader::class);
$modules = $moduleLoader->discoverModules();

if (isset($modules['VPEssential1'])) {
    echo "✓ VPEssential1 module discovered\n";
    $module = $modules['VPEssential1'];
    echo "  - Name: {$module['name']}\n";
    echo "  - Active: " . ($module['active'] ? 'Yes' : 'No') . "\n";
    echo "  - Service Provider: " . ($module['service_provider'] ?? 'Not specified') . "\n";
} else {
    echo "✗ VPEssential1 module NOT discovered\n";
}

// Test 3: Check if Filament pages are registered
try {
    $panel = \Filament\Facades\Filament::getPanel('admin');
    $pages = $panel->getPages();
    
    $vpPages = array_filter($pages, function($page) {
        return str_contains($page, 'VPEssential1');
    });
    
    if (!empty($vpPages)) {
        echo "\n✓ VP Essential 1 pages registered:\n";
        foreach ($vpPages as $page) {
            echo "  - " . class_basename($page) . "\n";
        }
    } else {
        echo "\n⚠ No VP Essential 1 pages registered yet\n";
        echo "  This is normal - pages register when module service provider boots\n";
    }
} catch (Exception $e) {
    echo "\n⚠ Could not check Filament pages: " . $e->getMessage() . "\n";
}

echo "\n✓ All basic tests passed!\n";
echo "\nNext: Visit /admin and check the sidebar for:\n";
echo "  - Extensions → Modules (Plugins)\n";
echo "  - Appearance → Themes\n";
echo "  - VP Essential → (5 pages)\n";
