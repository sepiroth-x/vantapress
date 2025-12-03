<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Theme ID 2...\n\n";

$theme = \App\Models\Theme::find(2);

if (!$theme) {
    echo "Theme ID 2 not found!\n";
    exit(1);
}

echo "ID: " . $theme->id . "\n";
echo "Name: " . $theme->name . "\n";
echo "Slug Type: " . gettype($theme->slug) . "\n";
echo "Slug Value: ";
var_dump($theme->slug);
echo "\n";

if (is_array($theme->slug)) {
    echo "ERROR: Slug is an array! This is the problem.\n";
    echo "Array contents: " . print_r($theme->slug, true) . "\n";
} else {
    echo "Slug is correct type: " . $theme->slug . "\n";
}

// Check raw attributes
echo "\nRaw Attributes:\n";
print_r($theme->getAttributes());
