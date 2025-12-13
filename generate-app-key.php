<?php
/**
 * Generate Application Key for Shared Hosting
 * 
 * This script generates a new APP_KEY and updates the .env file.
 * Run this by visiting: yourdomain.com/generate-app-key.php
 */

$envPath = __DIR__ . '/.env';

if (!file_exists($envPath)) {
    die("❌ Error: .env file not found at: {$envPath}");
}

// Generate a new 32-character random key
$key = 'base64:' . base64_encode(random_bytes(32));

// Read .env file
$envContent = file_get_contents($envPath);

// Check if APP_KEY exists
if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
    // Replace existing APP_KEY
    $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY={$key}", $envContent);
    $action = 'updated';
} else {
    // Add APP_KEY if it doesn't exist
    $envContent .= "\nAPP_KEY={$key}\n";
    $action = 'added';
}

// Write back to .env file
if (file_put_contents($envPath, $envContent)) {
    echo "✅ Success! APP_KEY has been {$action}.\n\n";
    echo "New APP_KEY: {$key}\n\n";
    echo "🔒 IMPORTANT: Delete this script immediately for security!\n";
    echo "Run: rm generate-app-key.php\n\n";
    echo "You can now visit your site normally.";
} else {
    echo "❌ Error: Could not write to .env file. Check file permissions.";
}
