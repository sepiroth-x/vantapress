<?php

/**
 * Run VPTelemetry Module Migrations
 * 
 * This script runs the migrations for VPTelemetry module
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Running VPTelemetry migrations...\n";

try {
    $exitCode = $kernel->call('migrate', [
        '--path' => 'Modules/VPTelemetry/database/migrations',
        '--force' => true,
    ]);
    
    if ($exitCode === 0) {
        echo "✓ VPTelemetry migrations completed successfully!\n";
    } else {
        echo "✗ Migration failed with exit code: $exitCode\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

$kernel->terminate(new Illuminate\Http\Request, 0);
