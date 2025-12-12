<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING THEMES ===\n\n";

$themes = \Illuminate\Support\Facades\DB::table('themes')
    ->select('id', 'name', 'slug', 'is_active')
    ->get();

foreach ($themes as $theme) {
    echo "ID: {$theme->id}\n";
    echo "Name: {$theme->name}\n";
    echo "Slug: {$theme->slug}\n";
    echo "Active: " . ($theme->is_active ? 'YES' : 'NO') . "\n";
    echo "---\n";
}

echo "\nTotal themes: " . $themes->count() . "\n";
echo "Active themes: " . $themes->where('is_active', 1)->count() . "\n";

// Fix: Deactivate all except VP Social
if ($themes->where('is_active', 1)->count() > 1) {
    echo "\n⚠ Multiple themes active! Fixing...\n";
    \Illuminate\Support\Facades\DB::table('themes')->update(['is_active' => 0]);
    \Illuminate\Support\Facades\DB::table('themes')->where('slug', 'vp-social')->update(['is_active' => 1]);
    echo "✓ Fixed! Only VP Social is now active.\n";
}
