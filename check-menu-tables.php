<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check for all menu-related tables
$tables = DB::select('SHOW TABLES');
$database = config('database.connections.mysql.database');
$tableColumn = "Tables_in_{$database}";

echo "All menu-related tables:\n";
foreach ($tables as $table) {
    $tableName = $table->$tableColumn;
    if (str_contains($tableName, 'menu')) {
        echo "  - {$tableName}\n";
    }
}
