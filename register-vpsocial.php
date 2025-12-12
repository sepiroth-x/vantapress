<?php

/**
 * Register VP Social Theme in Database
 * 
 * Quick script to sync VP Social theme from filesystem to database
 * so it appears in the admin panel theme list.
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Theme;
use Illuminate\Support\Facades\File;

try {
    $themePath = base_path('themes/VPSocial');
    $themeJsonPath = $themePath . '/theme.json';
    
    if (!File::exists($themeJsonPath)) {
        echo "❌ Error: theme.json not found at {$themeJsonPath}\n";
        exit(1);
    }
    
    $themeData = json_decode(File::get($themeJsonPath), true);
    
    if (!$themeData) {
        echo "❌ Error: Invalid theme.json\n";
        exit(1);
    }
    
    $theme = Theme::updateOrCreate(
        ['slug' => 'VPSocial'],
        [
            'name' => $themeData['name'] ?? 'VP Social',
            'description' => $themeData['description'] ?? 'Social networking theme with dark mode support',
            'version' => $themeData['version'] ?? '1.0.0',
            'author' => $themeData['author'] ?? 'VantaPress',
            'is_active' => true, // Already active in .env
            'config' => json_encode($themeData['config'] ?? []),
        ]
    );
    
    echo "✅ VP Social Theme registered successfully!\n";
    echo "   - Name: {$theme->name}\n";
    echo "   - Version: {$theme->version}\n";
    echo "   - Status: " . ($theme->is_active ? 'Active' : 'Inactive') . "\n";
    echo "\n";
    echo "🌐 Visit http://127.0.0.1:8001/admin/themes to see it in the theme list.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
