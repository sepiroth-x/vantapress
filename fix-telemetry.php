<?php
/**
 * VPTelemetryServer Fix Script
 * Upload this file to your server root and visit: yourdomain.com/fix-telemetry.php
 * Delete this file after running!
 */

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h1>VPTelemetryServer Fix Script</h1>";
echo "<pre>";

// Step 1: Clear all caches
echo "Step 1: Clearing all caches...\n";
Artisan::call('config:clear');
echo "✓ Config cache cleared\n";

Artisan::call('cache:clear');
echo "✓ Application cache cleared\n";

Artisan::call('route:clear');
echo "✓ Route cache cleared\n";

Artisan::call('view:clear');
echo "✓ View cache cleared\n";

Artisan::call('optimize:clear');
echo "✓ Optimization cache cleared\n";

// Step 2: Regenerate autoloader
echo "\nStep 2: Regenerating autoloader...\n";
$composerPath = base_path('composer.json');
if (file_exists($composerPath)) {
    // Run composer dump-autoload programmatically
    $output = shell_exec('cd ' . base_path() . ' && composer dump-autoload 2>&1');
    if ($output) {
        echo $output . "\n";
    } else {
        echo "✓ Autoloader regenerated\n";
    }
} else {
    echo "✗ composer.json not found\n";
}

// Step 3: Check if VPTelemetryServer exists
echo "\nStep 3: Checking VPTelemetryServer...\n";
$modulePath = base_path('Modules/VPTelemetryServer');
if (is_dir($modulePath)) {
    echo "✓ Module folder exists: $modulePath\n";
    
    $moduleJson = $modulePath . '/module.json';
    if (file_exists($moduleJson)) {
        $metadata = json_decode(file_get_contents($moduleJson), true);
        echo "✓ module.json found\n";
        echo "  - Name: " . ($metadata['name'] ?? 'N/A') . "\n";
        echo "  - Active: " . ($metadata['active'] ? 'true' : 'false') . "\n";
        echo "  - Version: " . ($metadata['version'] ?? 'N/A') . "\n";
    } else {
        echo "✗ module.json not found\n";
    }
    
    $serviceProvider = $modulePath . '/VPTelemetryServerServiceProvider.php';
    if (file_exists($serviceProvider)) {
        echo "✓ ServiceProvider exists\n";
    } else {
        echo "✗ ServiceProvider not found\n";
    }
} else {
    echo "✗ Module folder not found\n";
}

// Step 4: Check if class can be loaded
echo "\nStep 4: Testing class autoloading...\n";
if (class_exists('Modules\VPTelemetryServer\VPTelemetryServerServiceProvider')) {
    echo "✓ VPTelemetryServerServiceProvider class can be autoloaded\n";
} else {
    echo "✗ VPTelemetryServerServiceProvider class NOT found\n";
    echo "  This means the class is not being autoloaded properly.\n";
}

// Step 5: Check database entry
echo "\nStep 5: Checking database entry...\n";
try {
    $module = DB::table('modules')->where('slug', 'VPTelemetryServer')->first();
    if ($module) {
        echo "✓ Module found in database\n";
        echo "  - Slug: " . $module->slug . "\n";
        echo "  - Name: " . $module->name . "\n";
        echo "  - Enabled: " . ($module->is_enabled ? 'Yes' : 'No') . "\n";
    } else {
        echo "✗ Module not found in database\n";
        echo "  Run module discovery to add it.\n";
    }
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n</pre>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>Go to Admin → Modules</li>";
echo "<li>Try enabling VPTelemetryServer</li>";
echo "<li>If it works, <strong>DELETE THIS FILE (fix-telemetry.php)</strong> for security!</li>";
echo "</ol>";
?>
