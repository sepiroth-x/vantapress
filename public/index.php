<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Manually load .env for shared hosting compatibility
$envPath = __DIR__.'/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }
}

// Check if APP_KEY is set before booting Laravel
// If missing, show pre-installation welcome page
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$isUtilityScript = preg_match('#/(install|diagnose|fix-app-key)\.php#', $requestUri);

if ((!isset($_ENV['APP_KEY']) || empty($_ENV['APP_KEY']) || $_ENV['APP_KEY'] === '') && !$isUtilityScript) {
    // APP_KEY missing - show pre-installation welcome page
    require __DIR__.'/../resources/views/pre-install-welcome.php';
    exit;
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
