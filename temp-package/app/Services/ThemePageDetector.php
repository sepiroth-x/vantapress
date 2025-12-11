<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Theme Page Detector Service
 * Automatically detects all pages in the active theme
 */
class ThemePageDetector
{
    protected $themePath;
    protected $themeSlug;

    public function __construct($themeSlug)
    {
        $this->themeSlug = $themeSlug;
        $this->themePath = base_path("themes/{$themeSlug}");
    }

    /**
     * Detect all pages in the theme
     */
    public function detectPages(): array
    {
        $pages = [];

        // Check for pages directory
        $pagesPath = $this->themePath . '/pages';
        if (File::isDirectory($pagesPath)) {
            $files = File::files($pagesPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $pageName = $file->getFilenameWithoutExtension();
                    $pages[] = $this->analyzePage($pageName, $file->getPathname());
                }
            }
        }

        // Check for views directory
        $viewsPath = $this->themePath . '/views';
        if (File::isDirectory($viewsPath)) {
            $files = File::files($viewsPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $pageName = $file->getFilenameWithoutExtension();
                    
                    // Skip if already found in pages directory
                    $exists = false;
                    foreach ($pages as $page) {
                        if ($page['slug'] === $pageName) {
                            $exists = true;
                            break;
                        }
                    }
                    
                    if (!$exists) {
                        $pages[] = $this->analyzePage($pageName, $file->getPathname());
                    }
                }
            }
        }

        // Check for layouts directory
        $layoutsPath = $this->themePath . '/layouts';
        if (File::isDirectory($layoutsPath)) {
            $files = File::files($layoutsPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $layoutName = $file->getFilenameWithoutExtension();
                    $pages[] = [
                        'slug' => $layoutName,
                        'name' => $this->formatPageName($layoutName),
                        'path' => $file->getPathname(),
                        'type' => 'layout',
                        'url' => null,
                        'customizable_sections' => $this->detectSections($file->getPathname()),
                    ];
                }
            }
        }

        return $pages;
    }

    /**
     * Analyze a single page file
     */
    protected function analyzePage(string $pageName, string $filePath): array
    {
        $content = File::get($filePath);

        return [
            'slug' => $pageName,
            'name' => $this->formatPageName($pageName),
            'path' => $filePath,
            'type' => $this->detectPageType($pageName, $content),
            'url' => $this->generatePageUrl($pageName),
            'customizable_sections' => $this->detectSections($filePath),
            'editable_areas' => $this->detectEditableAreas($content),
        ];
    }

    /**
     * Format page name for display
     */
    protected function formatPageName(string $slug): string
    {
        // Convert snake_case or kebab-case to Title Case
        $name = str_replace(['-', '_'], ' ', $slug);
        return ucwords($name);
    }

    /**
     * Detect page type based on name and content
     */
    protected function detectPageType(string $pageName, string $content): string
    {
        // Home page
        if (in_array($pageName, ['home', 'index', 'front-page'])) {
            return 'homepage';
        }

        // Single post/page
        if (in_array($pageName, ['single', 'page', 'post'])) {
            return 'single';
        }

        // Archive pages
        if (Str::contains($pageName, ['archive', 'category', 'tag', 'author'])) {
            return 'archive';
        }

        // Search page
        if ($pageName === 'search') {
            return 'search';
        }

        // 404 page
        if (in_array($pageName, ['404', 'error', 'not-found'])) {
            return 'error';
        }

        // Default
        return 'page';
    }

    /**
     * Generate preview URL for page
     */
    protected function generatePageUrl(string $pageName): ?string
    {
        if (in_array($pageName, ['home', 'index', 'front-page'])) {
            return url('/');
        }

        if ($pageName === '404' || $pageName === 'error') {
            return null; // No preview for error pages
        }

        // Try to find corresponding route
        return url("/?preview_page={$pageName}&theme_preview={$this->themeSlug}");
    }

    /**
     * Detect customizable sections in page
     */
    protected function detectSections(string $filePath): array
    {
        $content = File::get($filePath);
        $sections = [];

        // Detect @section directives
        preg_match_all('/@section\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        foreach ($matches[1] as $sectionName) {
            $sections[] = [
                'id' => $sectionName,
                'label' => ucfirst(str_replace(['_', '-'], ' ', $sectionName)),
                'type' => 'section',
            ];
        }

        // Detect @yield directives
        preg_match_all('/@yield\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        foreach ($matches[1] as $yieldName) {
            $exists = false;
            foreach ($sections as $section) {
                if ($section['id'] === $yieldName) {
                    $exists = true;
                    break;
                }
            }
            
            if (!$exists) {
                $sections[] = [
                    'id' => $yieldName,
                    'label' => ucfirst(str_replace(['_', '-'], ' ', $yieldName)),
                    'type' => 'yield',
                ];
            }
        }

        // Detect @component directives
        preg_match_all('/@component\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
        foreach ($matches[1] as $componentName) {
            $sections[] = [
                'id' => "component_" . basename($componentName),
                'label' => ucfirst(str_replace(['_', '-', '.'], ' ', basename($componentName))),
                'type' => 'component',
            ];
        }

        return $sections;
    }

    /**
     * Detect editable areas with data-customizer attributes
     */
    protected function detectEditableAreas(string $content): array
    {
        $areas = [];

        // Look for data-customizer-element attribute
        preg_match_all('/data-customizer-element=[\'"]([^\'"]+)[\'"]/', $content, $matches);
        
        foreach ($matches[1] as $elementId) {
            $areas[] = [
                'id' => $elementId,
                'selector' => "[data-customizer-element='{$elementId}']",
            ];
        }

        // Look for data-editable attribute
        preg_match_all('/data-editable=[\'"]([^\'"]+)[\'"]/', $content, $matches);
        
        foreach ($matches[1] as $elementId) {
            $exists = false;
            foreach ($areas as $area) {
                if ($area['id'] === $elementId) {
                    $exists = true;
                    break;
                }
            }
            
            if (!$exists) {
                $areas[] = [
                    'id' => $elementId,
                    'selector' => "[data-editable='{$elementId}']",
                ];
            }
        }

        return $areas;
    }

    /**
     * Get pages grouped by type
     */
    public function getPagesByType(): array
    {
        $pages = $this->detectPages();
        $grouped = [
            'homepage' => [],
            'single' => [],
            'archive' => [],
            'page' => [],
            'layout' => [],
            'other' => [],
        ];

        foreach ($pages as $page) {
            $type = $page['type'] ?? 'other';
            if (!isset($grouped[$type])) {
                $grouped[$type] = [];
            }
            $grouped[$type][] = $page;
        }

        return $grouped;
    }
}
