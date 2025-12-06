<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing WebMigrationService...\n\n";

$service = new \App\Services\WebMigrationService();
$result = $service->runMigrations();

echo "Result:\n";
print_r($result);

echo "\n\nCheck storage/logs/laravel.log for [Migration Fixes] logs\n";
