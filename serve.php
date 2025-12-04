<?php
/**
 * VantaPress Development Server
 * 
 * Use this instead of `php artisan serve` for local development
 * Since we don't have a public/ folder structure
 */

$host = $argv[1] ?? '127.0.0.1';
$port = $argv[2] ?? '8000';

echo "VantaPress Development Server\n";
echo "==============================\n";
echo "Server: http://{$host}:{$port}\n";
echo "Document Root: " . __DIR__ . "\n";
echo "Press Ctrl+C to stop\n\n";

// Start PHP built-in server from root directory with router
$command = sprintf(
    'php -S %s:%s -t %s %s',
    escapeshellarg($host),
    escapeshellarg($port),
    escapeshellarg(__DIR__),
    escapeshellarg(__DIR__ . '/server.php')
);

passthru($command);
