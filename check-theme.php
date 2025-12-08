<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== THEME DIAGNOSTIC ===\n\n";

echo "1. Config File (cms.themes.active): " . config('cms.themes.active') . "\n";
echo "2. Config File (cms.themes.active_theme): " . config('cms.themes.active_theme') . "\n\n";

$dbTheme = \App\Models\Theme::where('is_active', true)->first();
echo "3. Database Active Theme: " . ($dbTheme ? $dbTheme->slug : 'NONE') . "\n\n";

$themeManager = app(\App\Services\CMS\ThemeManager::class);
echo "4. ThemeManager->getActiveTheme(): " . $themeManager->getActiveTheme() . "\n\n";

$themePath = base_path("themes/{$themeManager->getActiveTheme()}/theme.json");
if (file_exists($themePath)) {
    $themeData = json_decode(file_get_contents($themePath), true);
    echo "5. Theme JSON admin_colors:\n";
    if (isset($themeData['admin_colors'])) {
        print_r($themeData['admin_colors']);
    } else {
        echo "   NOT DEFINED\n";
    }
} else {
    echo "5. Theme JSON file not found: $themePath\n";
}

echo "\n=== END DIAGNOSTIC ===\n";
