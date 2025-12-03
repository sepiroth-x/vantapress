<?php
/**
 * VantaPress Default Theme & Module Integration Test
 * 
 * Run this script to verify The Villain Arise theme and VP Essential 1 module
 * are properly installed and integrated.
 * 
 * Usage: php test-theme-module-integration.php
 */

echo "========================================\n";
echo "VantaPress Integration Test\n";
echo "========================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// Test 1: Check theme exists
echo "[1/12] Checking theme files...\n";
$themeFiles = [
    'themes/TheVillainArise/theme.json',
    'themes/TheVillainArise/layouts/main.blade.php',
    'themes/TheVillainArise/partials/header.blade.php',
    'themes/TheVillainArise/partials/footer.blade.php',
    'themes/TheVillainArise/components/hero.blade.php',
    'themes/TheVillainArise/pages/home.blade.php',
    'themes/TheVillainArise/assets/css/theme.css',
    'themes/TheVillainArise/assets/js/theme.js',
    'themes/TheVillainArise/README.md',
];

foreach ($themeFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $success[] = "✓ Found: $file";
    } else {
        $errors[] = "✗ Missing: $file";
    }
}

// Test 2: Check module exists
echo "[2/12] Checking module files...\n";
$moduleFiles = [
    'Modules/VPEssential1/module.json',
    'Modules/VPEssential1/VPEssential1ServiceProvider.php',
    'Modules/VPEssential1/routes.php',
    'Modules/VPEssential1/helpers/functions.php',
    'Modules/VPEssential1/README.md',
];

foreach ($moduleFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $success[] = "✓ Found: $file";
    } else {
        $errors[] = "✗ Missing: $file";
    }
}

// Test 3: Check models
echo "[3/12] Checking models...\n";
$models = [
    'Modules/VPEssential1/Models/ThemeSetting.php',
    'Modules/VPEssential1/Models/Menu.php',
    'Modules/VPEssential1/Models/MenuItem.php',
    'Modules/VPEssential1/Models/WidgetArea.php',
    'Modules/VPEssential1/Models/Widget.php',
    'Modules/VPEssential1/Models/UserProfile.php',
    'Modules/VPEssential1/Models/Tweet.php',
    'Modules/VPEssential1/Models/TweetLike.php',
];

foreach ($models as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $success[] = "✓ Found: $file";
    } else {
        $errors[] = "✗ Missing: $file";
    }
}

// Test 4: Check migrations
echo "[4/12] Checking migrations...\n";
$migrations = glob(__DIR__ . '/Modules/VPEssential1/migrations/*.php');
if (count($migrations) === 5) {
    $success[] = "✓ Found all 5 migrations";
} else {
    $errors[] = "✗ Expected 5 migrations, found " . count($migrations);
}

// Test 5: Check Filament pages
echo "[5/12] Checking Filament pages...\n";
$filamentPages = [
    'Modules/VPEssential1/Filament/Pages/ThemeCustomizer.php',
    'Modules/VPEssential1/Filament/Pages/MenuBuilder.php',
    'Modules/VPEssential1/Filament/Pages/WidgetManager.php',
    'Modules/VPEssential1/Filament/Pages/ProfileManager.php',
    'Modules/VPEssential1/Filament/Pages/TweetManager.php',
];

foreach ($filamentPages as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $success[] = "✓ Found: $file";
    } else {
        $errors[] = "✗ Missing: $file";
    }
}

// Test 6: Check Filament views
echo "[6/12] Checking Filament views...\n";
$filamentViews = [
    'Modules/VPEssential1/views/filament/pages/theme-customizer.blade.php',
    'Modules/VPEssential1/views/filament/pages/menu-builder.blade.php',
    'Modules/VPEssential1/views/filament/pages/widget-manager.blade.php',
    'Modules/VPEssential1/views/filament/pages/profile-manager.blade.php',
    'Modules/VPEssential1/views/filament/pages/tweet-manager.blade.php',
];

foreach ($filamentViews as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $success[] = "✓ Found: $file";
    } else {
        $errors[] = "✗ Missing: $file";
    }
}

// Test 7: Validate theme.json
echo "[7/12] Validating theme.json...\n";
$themeJson = @file_get_contents(__DIR__ . '/themes/TheVillainArise/theme.json');
if ($themeJson) {
    $themeData = json_decode($themeJson, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $success[] = "✓ theme.json is valid JSON";
        if (isset($themeData['widget_areas']) && count($themeData['widget_areas']) === 3) {
            $success[] = "✓ theme.json has 3 widget areas";
        } else {
            $warnings[] = "⚠ theme.json widget areas not configured correctly";
        }
        if (isset($themeData['menu_locations']) && count($themeData['menu_locations']) === 2) {
            $success[] = "✓ theme.json has 2 menu locations";
        } else {
            $warnings[] = "⚠ theme.json menu locations not configured correctly";
        }
    } else {
        $errors[] = "✗ theme.json is invalid JSON";
    }
} else {
    $errors[] = "✗ Cannot read theme.json";
}

// Test 8: Validate module.json
echo "[8/12] Validating module.json...\n";
$moduleJson = @file_get_contents(__DIR__ . '/Modules/VPEssential1/module.json');
if ($moduleJson) {
    $moduleData = json_decode($moduleJson, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $success[] = "✓ module.json is valid JSON";
        if ($moduleData['active'] ?? false) {
            $success[] = "✓ Module is set to active";
        } else {
            $errors[] = "✗ Module is not set to active";
        }
        if (isset($moduleData['service_provider'])) {
            $success[] = "✓ Service provider registered";
        } else {
            $warnings[] = "⚠ No service provider in module.json";
        }
    } else {
        $errors[] = "✗ module.json is invalid JSON";
    }
} else {
    $errors[] = "✗ Cannot read module.json";
}

// Test 9: Check helper functions file
echo "[9/12] Checking helper functions...\n";
$helpersFile = __DIR__ . '/Modules/VPEssential1/helpers/functions.php';
if (file_exists($helpersFile)) {
    $helpersContent = file_get_contents($helpersFile);
    $expectedFunctions = [
        'vp_get_theme_setting',
        'vp_set_theme_setting',
        'vp_get_menu',
        'vp_get_widget_area',
        'vp_get_hero_config',
        'vp_get_current_user_profile',
        'vp_get_user_profile',
        'vp_get_recent_tweets',
        'vp_render_widget',
        'vp_render_menu_widget',
        'vp_render_recent_posts_widget',
    ];
    
    $foundCount = 0;
    foreach ($expectedFunctions as $func) {
        if (strpos($helpersContent, "function $func(") !== false) {
            $foundCount++;
        } else {
            $errors[] = "✗ Missing helper function: $func";
        }
    }
    
    if ($foundCount === count($expectedFunctions)) {
        $success[] = "✓ All 11 helper functions found";
    }
} else {
    $errors[] = "✗ Helper functions file not found";
}

// Test 10: Check documentation
echo "[10/12] Checking documentation...\n";
if (file_exists(__DIR__ . '/themes/TheVillainArise/README.md')) {
    $success[] = "✓ Theme README exists";
} else {
    $warnings[] = "⚠ Theme README missing";
}

if (file_exists(__DIR__ . '/Modules/VPEssential1/README.md')) {
    $success[] = "✓ Module README exists";
} else {
    $warnings[] = "⚠ Module README missing";
}

if (file_exists(__DIR__ . '/DEFAULT_THEME_MODULE_COMPLETE.md')) {
    $success[] = "✓ Integration summary exists";
} else {
    $warnings[] = "⚠ Integration summary missing";
}

// Test 11: Check PHP syntax
echo "[11/12] Checking PHP syntax...\n";
$phpFiles = array_merge(
    glob(__DIR__ . '/Modules/VPEssential1/Models/*.php'),
    glob(__DIR__ . '/Modules/VPEssential1/Filament/Pages/*.php'),
    glob(__DIR__ . '/Modules/VPEssential1/migrations/*.php'),
    [
        __DIR__ . '/Modules/VPEssential1/VPEssential1ServiceProvider.php',
        __DIR__ . '/Modules/VPEssential1/routes.php',
        __DIR__ . '/Modules/VPEssential1/helpers/functions.php',
    ]
);

$syntaxErrors = 0;
foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = shell_exec('php -l "' . $file . '" 2>&1');
        if (strpos($output, 'No syntax errors') === false) {
            $errors[] = "✗ Syntax error in: " . basename($file);
            $syntaxErrors++;
        }
    }
}

if ($syntaxErrors === 0) {
    $success[] = "✓ No PHP syntax errors found";
}

// Test 12: File permissions
echo "[12/12] Checking file structure...\n";
if (is_dir(__DIR__ . '/themes/TheVillainArise')) {
    $success[] = "✓ Theme directory exists";
} else {
    $errors[] = "✗ Theme directory not found";
}

if (is_dir(__DIR__ . '/Modules/VPEssential1')) {
    $success[] = "✓ Module directory exists";
} else {
    $errors[] = "✗ Module directory not found";
}

// Summary
echo "\n========================================\n";
echo "Test Results\n";
echo "========================================\n\n";

echo "✓ Success: " . count($success) . "\n";
echo "⚠ Warnings: " . count($warnings) . "\n";
echo "✗ Errors: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "ERRORS:\n";
    foreach ($errors as $error) {
        echo "  $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "  $warning\n";
    }
    echo "\n";
}

if (count($errors) === 0 && count($warnings) === 0) {
    echo "🎉 ALL TESTS PASSED!\n\n";
    echo "Next steps:\n";
    echo "1. Run migrations: php artisan migrate\n";
    echo "2. Access admin panel: /admin\n";
    echo "3. Configure theme: VP Essential → Theme Customizer\n";
    echo "4. Create menus: VP Essential → Menu Builder\n";
    echo "5. Add widgets: VP Essential → Widget Manager\n\n";
    echo "VantaPress is ready! 🚀\n";
} else {
    echo "⚠ Please fix the errors above before proceeding.\n";
    exit(1);
}

echo "\n========================================\n";
