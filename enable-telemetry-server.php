<?php

/**
 * Enable VPTelemetryServer Module
 * 
 * This script enables the VPTelemetryServer module and runs its migrations
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Enabling VPTelemetryServer module...\n";

try {
    // Get ModuleLoader
    $moduleLoader = $app->make(App\Services\ModuleLoader::class);
    
    // Activate module
    $result = $moduleLoader->activateModule('VPTelemetryServer');
    
    if ($result) {
        echo "✓ VPTelemetryServer enabled successfully!\n";
        echo "✓ Migrations have been run automatically.\n";
    } else {
        echo "✗ Failed to enable VPTelemetryServer\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

$kernel->terminate(new Illuminate\Http\Request, 0);
