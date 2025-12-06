<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRODUCTION DIAGNOSTIC ===\n\n";

// 1. Check fix scripts
echo "1. FIX SCRIPTS IN database/migration-fixes/:\n";
$fixFiles = glob(database_path('migration-fixes/*.php'));
foreach ($fixFiles as $file) {
    echo "   ✓ " . basename($file) . "\n";
}
echo "\n";

// 2. Check version in config file
echo "2. VERSION IN config/version.php:\n";
$configContent = file_get_contents(base_path('config/version.php'));
if (preg_match("/'version'\s*=>\s*env\([^,]+,\s*['\"]([^'\"]+)['\"]\)/", $configContent, $matches)) {
    echo "   Hardcoded: {$matches[1]}\n";
}
echo "\n";

// 3. Check version in .env
echo "3. VERSION IN .env:\n";
$envContent = file_get_contents(base_path('.env'));
preg_match('/^APP_VERSION=(.*)$/m', $envContent, $matches);
$envVersion = $matches[1] ?? 'NOT SET';
echo "   .env value: {$envVersion}\n";
echo "\n";

// 4. Check current loaded version
echo "4. CURRENTLY LOADED VERSION:\n";
echo "   config('version.version'): " . config('version.version') . "\n";
echo "   env('APP_VERSION'): " . env('APP_VERSION') . "\n";
echo "\n";

// 5. Check orphaned migrations
echo "5. ORPHANED MIGRATION ENTRIES:\n";
$menuMigrations = [
    'create_menus_table' => 'menus',
    'create_menu_items_table' => 'menu_items',
    'create_vp_menus_tables' => 'vp_menus'
];

foreach ($menuMigrations as $migrationPattern => $tableName) {
    $tracked = DB::table('migrations')
        ->where('migration', 'like', "%{$migrationPattern}%")
        ->exists();
    
    $tableExists = Schema::hasTable($tableName);
    
    $status = $tracked && !$tableExists ? '⚠️ ORPHANED' : '✓ OK';
    echo "   {$status} {$migrationPattern}: tracked=" . ($tracked ? 'YES' : 'NO') . ", table_exists=" . ($tableExists ? 'YES' : 'NO') . "\n";
}
echo "\n";

echo "=== END DIAGNOSTIC ===\n";
