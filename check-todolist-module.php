<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$module = App\Models\Module::where('slug', 'vp-to-do-list')->first();

if ($module) {
    echo "Module: {$module->name}\n";
    echo "Slug: {$module->slug}\n";
    echo "Enabled: " . ($module->is_enabled ? 'YES' : 'NO') . "\n";
} else {
    echo "Module NOT FOUND in database\n";
}
