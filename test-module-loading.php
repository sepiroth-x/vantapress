<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Module;
use App\Services\ModuleLoader;

echo "=== Module Database Check ===\n\n";

$module = Module::where('slug', 'VPEssential1')->first();

if ($module) {
    echo "✓ Module found in database:\n";
    echo "  Name: {$module->name}\n";
    echo "  Slug: {$module->slug}\n";
    echo "  Enabled: " . ($module->is_enabled ? "YES ✓" : "NO ✗") . "\n";
    echo "  Path: {$module->path}\n";
} else {
    echo "✗ Module NOT found in database\n";
}

echo "\n=== ModuleLoader Check ===\n\n";

$loader = app(ModuleLoader::class);
$modules = $loader->discoverModules();

if (isset($modules['VPEssential1'])) {
    echo "✓ Module discovered by loader:\n";
    $meta = $modules['VPEssential1'];
    echo "  Name: {$meta['name']}\n";
    echo "  Active: " . (($meta['active'] ?? false) ? "YES ✓" : "NO ✗") . "\n";
    echo "  Service Provider: " . ($meta['service_provider'] ?? 'NOT SET') . "\n";
    
    if (isset($meta['service_provider'])) {
        $exists = class_exists($meta['service_provider']);
        echo "  Provider Class Exists: " . ($exists ? "YES ✓" : "NO ✗") . "\n";
    }
} else {
    echo "✗ Module NOT discovered by loader\n";
}

echo "\n=== Loaded Service Providers ===\n\n";
$providers = array_keys(app()->getLoadedProviders());
$vpProvider = 'Modules\\VPEssential1\\VPEssential1ServiceProvider';

if (in_array($vpProvider, $providers)) {
    echo "✓ VPEssential1ServiceProvider IS loaded\n";
} else {
    echo "✗ VPEssential1ServiceProvider is NOT loaded\n";
    echo "\nSearching for similar providers:\n";
    foreach ($providers as $provider) {
        if (stripos($provider, 'VPEssential') !== false || stripos($provider, 'Essential') !== false) {
            echo "  - {$provider}\n";
        }
    }
}

echo "\n=== Helper Function Test ===\n";
echo "vp_get_theme_setting exists: " . (function_exists('vp_get_theme_setting') ? "YES ✓" : "NO ✗") . "\n";
