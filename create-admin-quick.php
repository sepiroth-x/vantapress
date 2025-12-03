<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n=== VantaPress Admin User Creator ===\n\n";

// Check if admin exists
$existingAdmin = User::where('email', 'admin@vantapress.com')->first();

if ($existingAdmin) {
    echo "✓ Admin user already exists: admin@vantapress.com\n";
    echo "  Resetting password to: admin123\n\n";
    
    $existingAdmin->password = Hash::make('admin123');
    $existingAdmin->save();
    
    echo "✓ Password reset successfully!\n\n";
} else {
    echo "Creating new admin user...\n\n";
    
    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@vantapress.com',
        'password' => Hash::make('admin123'),
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    
    echo "✓ Admin user created successfully!\n\n";
}

echo "Login Credentials:\n";
echo "  Email: admin@vantapress.com\n";
echo "  Password: admin123\n\n";
echo "  Admin URL: /admin\n\n";
echo "⚠ Remember to change the password after first login!\n\n";
