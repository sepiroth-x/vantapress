<?php

namespace Modules\TheVillainTerminal\Commands;

use Illuminate\Support\Facades\File;
use ZipArchive;

/**
 * The Villain Terminal - Theme Layout Commands
 * 
 * Generate and export theme layouts.
 */
class ThemeLayoutCommand
{
    /**
     * Create a new theme layout
     * 
     * @param array $args
     * @return array
     */
    public function makeLayout(array $args): array
    {
        $output = [];

        if (empty($args[0])) {
            $output[] = "<span style='color: #ff0000;'>ERROR: Layout name is required</span>";
            $output[] = "";
            $output[] = "Usage: vanta-make-theme-layout {layoutName}";
            return ['output' => implode("\n", $output), 'success' => false];
        }

        $layoutName = $args[0];
        $layoutPath = resource_path("views/layouts/{$layoutName}");

        // Check if layout already exists
        if (File::exists($layoutPath)) {
            $output[] = "<span style='color: #ff0000;'>ERROR: Layout '{$layoutName}' already exists</span>";
            return ['output' => implode("\n", $output), 'success' => false];
        }

        $output[] = "<span style='color: #00ff00;'>Creating theme layout: {$layoutName}</span>";
        $output[] = "";

        try {
            // Create directory structure
            File::makeDirectory($layoutPath, 0755, true);
            File::makeDirectory($layoutPath . '/components', 0755, true);
            File::makeDirectory($layoutPath . '/partials', 0755, true);

            // Create main layout file
            $mainLayout = $this->getMainLayoutTemplate($layoutName);
            File::put($layoutPath . '/app.blade.php', $mainLayout);
            $output[] = "  ✓ Created: app.blade.php";

            // Create header partial
            $header = $this->getHeaderTemplate();
            File::put($layoutPath . '/partials/header.blade.php', $header);
            $output[] = "  ✓ Created: partials/header.blade.php";

            // Create footer partial
            $footer = $this->getFooterTemplate();
            File::put($layoutPath . '/partials/footer.blade.php', $footer);
            $output[] = "  ✓ Created: partials/footer.blade.php";

            // Create sample component
            $component = $this->getComponentTemplate();
            File::put($layoutPath . '/components/card.blade.php', $component);
            $output[] = "  ✓ Created: components/card.blade.php";

            // Create theme.json
            $themeJson = $this->getThemeJsonTemplate($layoutName);
            File::put($layoutPath . '/theme.json', $themeJson);
            $output[] = "  ✓ Created: theme.json";

            // Create README
            $readme = $this->getReadmeTemplate($layoutName);
            File::put($layoutPath . '/README.md', $readme);
            $output[] = "  ✓ Created: README.md";

            $output[] = "";
            $output[] = "<span style='color: #00ff00; font-weight: bold;'>✓ Theme layout '{$layoutName}' created successfully!</span>";
            $output[] = "";
            $output[] = "Location: resources/views/layouts/{$layoutName}";
            $output[] = "";
            $output[] = "Next steps:";
            $output[] = "  1. Edit the layout files in resources/views/layouts/{$layoutName}";
            $output[] = "  2. Add custom styles and scripts";
            $output[] = "  3. Export using: vanta-export-layout {$layoutName}";

            return ['output' => implode("\n", $output), 'success' => true];

        } catch (\Exception $e) {
            $output[] = "<span style='color: #ff0000;'>ERROR: " . $e->getMessage() . "</span>";
            return ['output' => implode("\n", $output), 'success' => false];
        }
    }

    /**
     * Export theme layout as ZIP
     * 
     * @param array $args
     * @return array
     */
    public function exportLayout(array $args): array
    {
        $output = [];

        if (empty($args[0])) {
            $output[] = "<span style='color: #ff0000;'>ERROR: Layout name is required</span>";
            $output[] = "";
            $output[] = "Usage: vanta-export-layout {layoutName}";
            return ['output' => implode("\n", $output), 'success' => false];
        }

        $layoutName = $args[0];
        $layoutPath = resource_path("views/layouts/{$layoutName}");

        // Check if layout exists
        if (!File::exists($layoutPath)) {
            $output[] = "<span style='color: #ff0000;'>ERROR: Layout '{$layoutName}' does not exist</span>";
            $output[] = "";
            $output[] = "Available layouts:";
            $layouts = File::directories(resource_path('views/layouts'));
            foreach ($layouts as $layout) {
                $output[] = "  • " . basename($layout);
            }
            return ['output' => implode("\n", $output), 'success' => false];
        }

        $output[] = "<span style='color: #00ff00;'>Exporting theme layout: {$layoutName}</span>";
        $output[] = "";

        try {
            // Create exports directory
            $exportsPath = storage_path('app/exports');
            if (!File::exists($exportsPath)) {
                File::makeDirectory($exportsPath, 0755, true);
            }

            $zipFileName = "{$layoutName}_" . date('Y-m-d_His') . ".zip";
            $zipPath = $exportsPath . '/' . $zipFileName;

            // Create ZIP archive
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception("Could not create ZIP file");
            }

            // Add all files from layout directory
            $files = File::allFiles($layoutPath);
            $output[] = "Adding files to archive...";
            
            foreach ($files as $file) {
                $relativePath = str_replace($layoutPath . DIRECTORY_SEPARATOR, '', $file->getRealPath());
                $zip->addFile($file->getRealPath(), $layoutName . '/' . $relativePath);
                $output[] = "  ✓ " . $relativePath;
            }

            $zip->close();

            $output[] = "";
            $output[] = "<span style='color: #00ff00; font-weight: bold;'>✓ Export completed successfully!</span>";
            $output[] = "";
            $output[] = "Archive: {$zipFileName}";
            $output[] = "Size: " . $this->formatBytes(File::size($zipPath));
            $output[] = "";
            $output[] = "<span style='color: #00ffff;'>Download URL:</span>";
            $output[] = "<a href='/storage/exports/{$zipFileName}' target='_blank' style='color: #00ff00;'>/storage/exports/{$zipFileName}</a>";

            return [
                'output' => implode("\n", $output),
                'success' => true,
                'downloadUrl' => "/storage/exports/{$zipFileName}",
                'fileName' => $zipFileName
            ];

        } catch (\Exception $e) {
            $output[] = "<span style='color: #ff0000;'>ERROR: " . $e->getMessage() . "</span>";
            return ['output' => implode("\n", $output), 'success' => false];
        }
    }

    /**
     * Get main layout template
     */
    protected function getMainLayoutTemplate(string $name): string
    {
        return <<<'BLADE'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @include('layouts.{name}.partials.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.{name}.partials.footer')
    
    @stack('scripts')
</body>
</html>
BLADE;
    }

    /**
     * Get header template
     */
    protected function getHeaderTemplate(): string
    {
        return <<<'BLADE'
<header>
    <nav>
        <!-- Add your navigation here -->
    </nav>
</header>
BLADE;
    }

    /**
     * Get footer template
     */
    protected function getFooterTemplate(): string
    {
        return <<<'BLADE'
<footer>
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
</footer>
BLADE;
    }

    /**
     * Get component template
     */
    protected function getComponentTemplate(): string
    {
        return <<<'BLADE'
<div class="card">
    <div class="card-header">
        {{ $title ?? 'Card Title' }}
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
BLADE;
    }

    /**
     * Get theme.json template
     */
    protected function getThemeJsonTemplate(string $name): string
    {
        return json_encode([
            'name' => $name,
            'version' => '1.0.0',
            'author' => 'VantaPress',
            'description' => 'Custom theme layout for VantaPress',
            'layouts' => [
                'default' => 'app.blade.php'
            ],
            'assets' => [
                'css' => [],
                'js' => []
            ]
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Get README template
     */
    protected function getReadmeTemplate(string $name): string
    {
        return <<<MD
# {$name} Theme Layout

Generated by The Villain Terminal

## Installation

1. Copy this folder to `resources/views/layouts/{$name}`
2. Use in your views: `@extends('layouts.{$name}.app')`

## Structure

- `app.blade.php` - Main layout file
- `partials/` - Reusable partial views
- `components/` - Blade components
- `theme.json` - Theme configuration

## Customization

Edit the files in this folder to customize your theme layout.

MD;
    }

    /**
     * Format bytes
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < 3) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
