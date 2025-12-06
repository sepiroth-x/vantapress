<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Delete menu migration entries from migrations table
DB::table('migrations')->where('migration', 'like', '%menu%')->delete();

echo "Deleted menu migration entries from migrations table.\n";

// Show remaining migrations
$remaining = DB::table('migrations')->get();
echo "Remaining migrations: " . $remaining->count() . "\n";
foreach ($remaining as $migration) {
    echo "  - {$migration->migration}\n";
}
