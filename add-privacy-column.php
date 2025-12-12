<?php

// Run this file directly: php add-privacy-column.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Check if column exists
    $exists = \Illuminate\Support\Facades\Schema::hasColumn('vp_user_profiles', 'privacy');
    
    if ($exists) {
        echo "Privacy column already exists!\n";
    } else {
        // Add privacy column
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE vp_user_profiles 
            ADD COLUMN privacy ENUM('public', 'friends_only', 'private') DEFAULT 'public' AFTER linkedin
        ");
        echo "✓ Privacy column added successfully!\n";
    }
    
    // Also ensure verification_status column exists on users table
    $verificationExists = \Illuminate\Support\Facades\Schema::hasColumn('users', 'verification_status');
    if (!$verificationExists) {
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE users 
            ADD COLUMN verification_status ENUM('none', 'verified', 'business', 'creator', 'vip') DEFAULT 'none' AFTER remember_token,
            ADD COLUMN verification_note TEXT NULL AFTER verification_status
        ");
        echo "✓ Verification columns added to users table!\n";
    } else {
        echo "Verification columns already exist!\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
