<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VP Essential 1 Function Test ===\n\n";

// Check if functions exist
$functions = [
    'vp_get_theme_setting',
    'vp_set_theme_setting',
    'vp_delete_theme_setting',
    'vp_get_all_theme_settings',
];

foreach ($functions as $function) {
    $exists = function_exists($function);
    echo ($exists ? "✓" : "✗") . " {$function}: " . ($exists ? "EXISTS" : "NOT FOUND") . "\n";
}

// Check module status
use App\Models\Module;

echo "\n=== Module Status ===\n";
$vpEssential = Module::where('slug', 'VPEssential1')->first();

if ($vpEssential) {
    echo "Module found in database:\n";
    echo "  Name: {$vpEssential->name}\n";
    echo "  Slug: {$vpEssential->slug}\n";
    echo "  Enabled: " . ($vpEssential->is_enabled ? "YES" : "NO") . "\n";
    echo "  Path: {$vpEssential->path}\n";
} else {
    echo "✗ VP Essential 1 module not found in database\n";
}

// Check if helpers file exists
$helpersPath = base_path('Modules/VPEssential1/Helpers/theme-helpers.php');
echo "\n=== Helper File ===\n";
if (file_exists($helpersPath)) {
    echo "✓ Helper file exists: {$helpersPath}\n";
    
    // Check if it's been required
    $included = in_array(realpath($helpersPath), get_included_files());
    echo ($included ? "✓" : "✗") . " Helper file " . ($included ? "IS" : "IS NOT") . " loaded\n";
} else {
    echo "✗ Helper file not found\n";
}

echo "\n=== Service Provider Check ===\n";
$providers = app()->getLoadedProviders();
if (isset($providers['Modules\\VPEssential1\\Providers\\VPEssential1ServiceProvider'])) {
    echo "✓ VPEssential1ServiceProvider is loaded\n";
} else {
    echo "✗ VPEssential1ServiceProvider is NOT loaded\n";
}
