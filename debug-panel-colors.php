<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEEP COLOR DIAGNOSTIC ===\n\n";

// Get the actual panel instance
try {
    $panel = \Filament\Facades\Filament::getPanel('admin');
    
    echo "Panel Instance: " . get_class($panel) . "\n";
    echo "Panel ID: " . $panel->getId() . "\n\n";
    
    // Get colors from panel
    $colors = $panel->getColors();
    
    echo "Colors returned by Panel:\n";
    if (empty($colors)) {
        echo "  ❌ NO COLORS RETURNED!\n";
    } else {
        foreach ($colors as $name => $colorArray) {
            if (is_array($colorArray)) {
                // Check if it has the key shade values
                $shade50 = $colorArray[50] ?? 'MISSING';
                $shade500 = $colorArray[500] ?? 'MISSING';
                $shade950 = $colorArray[950] ?? 'MISSING';
                
                echo "  ✓ {$name}:\n";
                echo "    - Shade 50: {$shade50}\n";
                echo "    - Shade 500: {$shade500}\n";
                echo "    - Shade 950: {$shade950}\n";
            } else {
                echo "  ✗ {$name}: " . gettype($colorArray) . "\n";
            }
        }
    }
    
    echo "\n--- Theme Info ---\n";
    $themeManager = app(\App\Services\CMS\ThemeManager::class);
    $activeTheme = $themeManager->getActiveTheme();
    echo "Active Theme: {$activeTheme}\n";
    
    $themePath = base_path("themes/{$activeTheme}/theme.json");
    if (file_exists($themePath)) {
        $themeData = json_decode(file_get_contents($themePath), true);
        echo "Theme admin_colors:\n";
        foreach ($themeData['admin_colors'] ?? [] as $key => $value) {
            echo "  - {$key}: {$value}\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
