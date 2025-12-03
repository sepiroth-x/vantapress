<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== All Settings Debug ===\n\n";

$settings = \App\Models\Setting::all();

foreach ($settings as $setting) {
    $rawValue = $setting->getRawOriginal('value');
    $decodedValue = $setting->value;
    
    echo "Key: {$setting->key}\n";
    echo "Type: {$setting->type}\n";
    echo "Raw Value: " . (is_array($rawValue) ? json_encode($rawValue) : $rawValue) . "\n";
    echo "Decoded Value: " . (is_array($decodedValue) ? json_encode($decodedValue) : $decodedValue) . "\n";
    echo "Is Array After Decode: " . (is_array($decodedValue) ? 'YES' : 'NO') . "\n";
    echo "---\n";
}
