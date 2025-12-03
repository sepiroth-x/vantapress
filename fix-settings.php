<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Fix allowed_file_types setting
$setting = \App\Models\Setting::where('key', 'allowed_file_types')->first();

if ($setting) {
    echo "Current type: {$setting->type}\n";
    echo "Current raw value: " . $setting->getRawOriginal('value') . "\n";
    
    // Update to string type with comma-separated values
    $setting->update([
        'type' => 'string',
        'value' => 'jpg,jpeg,png,gif,pdf,doc,docx'
    ]);
    
    echo "✓ Updated allowed_file_types to string type\n";
} else {
    echo "Setting not found, creating...\n";
    \App\Models\Setting::create([
        'key' => 'allowed_file_types',
        'value' => 'jpg,jpeg,png,gif,pdf,doc,docx',
        'type' => 'string',
    ]);
    echo "✓ Created allowed_file_types setting\n";
}
