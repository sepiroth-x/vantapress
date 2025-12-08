<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FILAMENT COLOR DIAGNOSTIC ===\n\n";

// Get the AdminPanelProvider
$provider = new \App\Providers\Filament\AdminPanelProvider($app);
$panel = \Filament\Facades\Filament::getPanel('admin');

echo "Panel ID: " . $panel->getId() . "\n";
echo "Panel Path: " . $panel->getPath() . "\n\n";

// Try to get colors
try {
    $colors = $panel->getColors();
    echo "Registered Colors:\n";
    if (empty($colors)) {
        echo "  NO COLORS REGISTERED!\n";
    } else {
        foreach ($colors as $name => $color) {
            if (is_array($color)) {
                echo "  - $name: Array with " . count($color) . " shades\n";
                echo "    Primary shade (500): " . ($color[500] ?? 'NOT SET') . "\n";
            } elseif (is_object($color)) {
                echo "  - $name: " . get_class($color) . "\n";
            } else {
                echo "  - $name: " . var_export($color, true) . "\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "Error getting colors: " . $e->getMessage() . "\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
