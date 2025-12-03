<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VP To Do List Module Status ===\n\n";

// Check database
$module = App\Models\Module::where('slug', 'VPToDoList')->first();
if ($module) {
    echo "✓ Module in database: {$module->name}\n";
    echo "  - Slug: {$module->slug}\n";
    echo "  - Enabled: " . ($module->is_enabled ? 'YES' : 'NO') . "\n";
    echo "  - Path: {$module->path}\n\n";
} else {
    echo "✗ Module NOT found in database\n\n";
}

// Check if service provider is loaded
$providers = app()->getLoadedProviders();
if (isset($providers['Modules\\VPToDoList\\VPToDoListServiceProvider'])) {
    echo "✓ VPToDoListServiceProvider is loaded\n\n";
} else {
    echo "✗ VPToDoListServiceProvider is NOT loaded\n\n";
}

// Check if resources exist
$projectResource = 'Modules\\VPToDoList\\Filament\\Resources\\ProjectResource';
$taskResource = 'Modules\\VPToDoList\\Filament\\Resources\\TaskResource';

if (class_exists($projectResource)) {
    echo "✓ ProjectResource class exists\n";
    
    // Check shouldRegisterNavigation
    $shouldRegister = $projectResource::shouldRegisterNavigation();
    echo "  - shouldRegisterNavigation(): " . ($shouldRegister ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "✗ ProjectResource class NOT found\n";
}

if (class_exists($taskResource)) {
    echo "✓ TaskResource class exists\n";
    
    // Check shouldRegisterNavigation
    $shouldRegister = $taskResource::shouldRegisterNavigation();
    echo "  - shouldRegisterNavigation(): " . ($shouldRegister ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "✗ TaskResource class NOT found\n";
}
