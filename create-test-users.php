<?php

/**
 * Create Test Users for VP Social Features
 * 
 * Run: php create-test-users.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Modules\VPEssential1\Models\UserProfile;

echo "\n🔧 Creating Test Users for VP Social Features...\n\n";

// Check if users already exist
if (User::where('email', 'john@test.com')->exists()) {
    echo "⚠️  Test users already exist!\n";
    echo "   john@test.com\n";
    echo "   jane@test.com\n";
    echo "   alex@test.com\n\n";
    echo "✅ You can login with password: password\n\n";
    exit;
}

try {
    // Create User 1: John Doe
    $user1 = User::create([
        'name' => 'John Doe',
        'email' => 'john@test.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]);

    UserProfile::create([
        'user_id' => $user1->id,
        'bio' => 'Software developer and tech enthusiast. Love building cool stuff with Laravel and VantaPress! 🚀',
        'location' => 'New York, USA',
        'website' => 'https://johndoe.com',
        'is_verified' => false
    ]);

    echo "✅ Created: John Doe (ID: {$user1->id})\n";

    // Create User 2: Jane Smith
    $user2 = User::create([
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]);

    UserProfile::create([
        'user_id' => $user2->id,
        'bio' => 'Designer & Creative Director | Helping brands tell their stories through design 🎨',
        'location' => 'Los Angeles, USA',
        'website' => 'https://janesmith.com',
        'is_verified' => true
    ]);

    echo "✅ Created: Jane Smith (ID: {$user2->id}) [Verified]\n";

    // Create User 3: Alex Chen
    $user3 = User::create([
        'name' => 'Alex Chen',
        'email' => 'alex@test.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]);

    UserProfile::create([
        'user_id' => $user3->id,
        'bio' => 'Marketing guru 📱 | Digital nomad 🌏 | Coffee addict ☕',
        'location' => 'Singapore',
        'website' => 'https://alexchen.io',
        'is_verified' => false
    ]);

    echo "✅ Created: Alex Chen (ID: {$user3->id})\n\n";

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✨ Test Users Created Successfully!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🔐 Login Credentials (all use same password):\n\n";
    echo "   Email: john@test.com\n";
    echo "   Email: jane@test.com\n";
    echo "   Email: alex@test.com\n";
    echo "   Password: password\n\n";
    echo "🚀 Start Testing:\n\n";
    echo "   1. Login: http://127.0.0.1:8001/login\n";
    echo "   2. Newsfeed: http://127.0.0.1:8001/social/newsfeed\n";
    echo "   3. Profile: http://127.0.0.1:8001/social/profile\n";
    echo "   4. Friends: http://127.0.0.1:8001/social/friends\n";
    echo "   5. Messages: http://127.0.0.1:8001/social/messages\n\n";
    echo "📖 Full Testing Guide: TESTING_SOCIAL_FEATURES.md\n\n";

} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
