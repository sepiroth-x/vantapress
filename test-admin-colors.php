<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ADMIN COLORS DIAGNOSTIC ===\n\n";

$themeManager = app(\App\Services\CMS\ThemeManager::class);
$activeTheme = $themeManager->getActiveTheme();
echo "Active Theme: {$activeTheme}\n\n";

$themePath = base_path("themes/{$activeTheme}/theme.json");
if (file_exists($themePath)) {
    $themeData = json_decode(file_get_contents($themePath), true);
    
    echo "Theme JSON admin_colors:\n";
    if (isset($themeData['admin_colors'])) {
        print_r($themeData['admin_colors']);
    } else {
        echo "NOT DEFINED!\n";
    }
    echo "\n";
    
    // Simulate what AdminPanelProvider does
    if (isset($themeData['admin_colors'])) {
        echo "CSS Variables that should be generated:\n";
        $tailwindColors = [
            'blue' => ['500' => '#3b82f6', '600' => '#2563eb'],
            'purple' => ['500' => '#a855f7', '600' => '#9333ea'],
            'slate' => ['500' => '#64748b', '600' => '#475569'],
        ];
        
        foreach ($themeData['admin_colors'] as $key => $colorName) {
            if (isset($tailwindColors[$colorName])) {
                foreach ($tailwindColors[$colorName] as $shade => $hex) {
                    echo "  --{$key}-{$shade}: {$hex}\n";
                }
            }
        }
    }
}

echo "\n=== END DIAGNOSTIC ===\n";
