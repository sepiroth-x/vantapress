<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Theme;

echo "=== Theme Activation Test ===\n\n";

// Check current active theme
$activeTheme = Theme::where('is_active', true)->first();

if ($activeTheme) {
    echo "✓ Active Theme Found:\n";
    echo "  Name: {$activeTheme->name}\n";
    echo "  Slug: {$activeTheme->slug}\n";
    echo "  Path: themes/{$activeTheme->slug}\n";
    echo "  Home View: themes/{$activeTheme->slug}/pages/home.blade.php\n";
    
    // Check if home view exists
    $homeViewPath = base_path("themes/{$activeTheme->slug}/pages/home.blade.php");
    if (file_exists($homeViewPath)) {
        echo "  ✓ Home view file exists\n";
    } else {
        echo "  ✗ Home view file NOT found\n";
    }
    
    // Check if layout exists
    $layoutPath = base_path("themes/{$activeTheme->slug}/layouts/main.blade.php");
    if (file_exists($layoutPath)) {
        echo "  ✓ Main layout file exists\n";
    } else {
        echo "  ✗ Main layout file NOT found\n";
    }
} else {
    echo "✗ No active theme found\n";
    echo "\nAvailable themes:\n";
    $themes = Theme::all();
    foreach ($themes as $theme) {
        echo "  - {$theme->name} (slug: {$theme->slug})\n";
    }
}

echo "\n=== Homepage Route Test ===\n";
echo "When you visit http://127.0.0.1:8000/ it should:\n";
if ($activeTheme) {
    echo "1. Check for active theme in database ✓\n";
    echo "2. Load theme: {$activeTheme->slug} ✓\n";
    echo "3. Display: themes/{$activeTheme->slug}/pages/home.blade.php\n";
} else {
    echo "1. Check for active theme in database ✗\n";
    echo "2. Fallback to default welcome view\n";
}

echo "\n✓ Test complete!\n";
