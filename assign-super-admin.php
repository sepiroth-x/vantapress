<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Assigning super-admin role to Administrator (ID: 1)..." . PHP_EOL;

$user = \App\Models\User::find(1);

if ($user) {
    $user->assignRole('super-admin');
    echo "✓ Successfully assigned super-admin role to {$user->name}" . PHP_EOL;
    echo "Roles: " . $user->getRoleNames()->join(', ') . PHP_EOL;
} else {
    echo "✗ User not found!" . PHP_EOL;
}
