<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check if installation is needed (before loading Laravel)
// This prevents APP_KEY errors on fresh install
$envPath = __DIR__ . '/.env';
$installPath = __DIR__ . '/install.php';

// Manually load .env first
$appKeyExists = false;
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        
        if ($key === 'APP_KEY' && !empty($value) && $value !== 'base64:') {
            $appKeyExists = true;
        }
        
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }
}

// Redirect to installer if install.php exists and APP_KEY is missing
if (file_exists($installPath) && !$appKeyExists) {
    header('Location: /install.php');
    exit;
}

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Override public path to use base directory (root-level structure)
// This prevents Filament from creating a public/ folder
$app->usePublicPath(__DIR__);

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
