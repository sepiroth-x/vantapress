<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== USERS AND ROLES ===" . PHP_EOL . PHP_EOL;

$users = \App\Models\User::with('roles')->get();

foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->toArray();
    echo "ID: {$user->id}" . PHP_EOL;
    echo "Name: {$user->name}" . PHP_EOL;
    echo "Email: {$user->email}" . PHP_EOL;
    echo "Roles: " . (empty($roles) ? '(none)' : implode(', ', $roles)) . PHP_EOL;
    echo str_repeat('-', 50) . PHP_EOL;
}

echo PHP_EOL . "To assign super-admin role to a user, run:" . PHP_EOL;
echo "php artisan tinker" . PHP_EOL;
echo "Then: User::find(USER_ID)->assignRole('super-admin')" . PHP_EOL;
