<?php
/**
 * TCC School CMS - Theme Manager
 * 
 * Manages the theme system for TCC School CMS.
 * Handles theme discovery, loading, activation, asset management, and view overrides.
 * 
 * @package TCC_School_CMS
 * @subpackage Services\CMS
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * @version 1.0.0
 * @license Commercial / Paid
 * 
 * Copyright (c) 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * All Rights Reserved.
 * 
 * Contact Information:
 * Email: chardy.tsadiq02@gmail.com
 * Mobile: +63 915 0388 448
 * 
 * This software is proprietary and confidential. Unauthorized copying,
 * modification, distribution, or use of this software, via any medium,
 * is strictly prohibited without explicit written permission from the author.
 */

namespace App\Services\CMS;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ThemeManager
{
    protected array $themes = [];
    protected ?string $activeTheme = null;
    protected string $themesPath;
    protected string $cacheKey;

    public function __construct()
    {
        $this->themesPath = base_path(config('cms.themes.path', 'themes'));
        $this->cacheKey = config('cms.themes.cache_key', 'cms_themes');
        $this->activeTheme = config('cms.themes.active', 'default');
    }

    /**
     * Load the active theme
     */
    public function loadTheme(?string $themeName = null): void
    {
        $themeName = $themeName ?? $this->activeTheme;

        if (!$this->themeExists($themeName)) {
            $themeName = config('cms.themes.fallback_theme', 'default');
        }

        $this->activeTheme = $themeName;
        $this->registerThemeViews($themeName);
        $this->registerThemeAssets($themeName);

        do_action('theme_loaded', $themeName);
    }

    /**
     * Discover all available themes
     */
    public function discoverThemes(): array
    {
        if (!File::exists($this->themesPath)) {
            File::makeDirectory($this->themesPath, 0755, true);
            return [];
        }

        $cacheEnabled = config('cms.themes.cache_enabled', true);
        
        if ($cacheEnabled && Cache::has($this->cacheKey)) {
            $this->themes = Cache::get($this->cacheKey);
            return $this->themes;
        }

        $directories = File::directories($this->themesPath);
        $this->themes = [];

        foreach ($directories as $directory) {
            $themeName = basename($directory);
            $themeJsonPath = $directory . '/theme.json';

            if (File::exists($themeJsonPath)) {
                $themeData = json_decode(File::get($themeJsonPath), true);
                
                $this->themes[$themeName] = [
                    'name' => $themeData['name'] ?? $themeName,
                    'slug' => $themeName,
                    'path' => $directory,
                    'version' => $themeData['version'] ?? '1.0.0',
                    'author' => $themeData['author'] ?? 'Unknown',
                    'description' => $themeData['description'] ?? '',
                    'screenshot' => $themeData['screenshot'] ?? null,
                    'colors' => $themeData['colors'] ?? [],
                    'menus' => $themeData['menus'] ?? [],
                    'features' => $themeData['features'] ?? [],
                    'is_active' => $themeName === $this->activeTheme,
                ];
            }
        }

        if ($cacheEnabled) {
            $lifetime = config('cms.themes.cache_lifetime', 3600);
            Cache::put($this->cacheKey, $this->themes, $lifetime);
        }

        return $this->themes;
    }

    /**
     * Register theme views
     */
    protected function registerThemeViews(string $themeName): void
    {
        $themePath = $this->getThemePath($themeName);
        
        logger()->info("ThemeManager: Registering views for theme '{$themeName}' at path: {$themePath}");
        
        // Register all theme directories with proper namespaces
        $directories = [
            'views' => 'theme',
            'layouts' => 'theme.layouts',
            'components' => 'theme.components',
            'partials' => 'theme.partials',
            'pages' => 'theme.pages',
        ];
        
        foreach ($directories as $dir => $namespace) {
            $path = $themePath . '/' . $dir;
            if (File::exists($path)) {
                View::addNamespace($namespace, $path);
                logger()->info("ThemeManager: Registered namespace '{$namespace}' -> {$path}");
            } else {
                logger()->debug("ThemeManager: Directory not found: {$path}");
            }
        }

        // Add theme views to view finder paths (for template hierarchy)
        // This allows Laravel to find views without namespace prefix
        $viewsPath = $themePath . '/views';
        if (File::exists($viewsPath)) {
            View::getFinder()->prependLocation($viewsPath);
            logger()->info("ThemeManager: Added views location: {$viewsPath}");
        }
        
        // Also add pages directory for homepage routing
        $pagesPath = $themePath . '/pages';
        if (File::exists($pagesPath)) {
            View::getFinder()->prependLocation($pagesPath);
            logger()->info("ThemeManager: Added pages location: {$pagesPath}");
        }
    }

    /**
     * Register theme assets
     */
    protected function registerThemeAssets(string $themeName): void
    {
        $assetsPath = 'themes/' . $themeName . '/assets';
        
        // Store assets path in view shared data
        View::share('theme_assets_path', asset($assetsPath));
        View::share('theme_name', $themeName);
        View::share('theme_config', $this->getThemeConfig($themeName));
    }

    /**
     * Get theme configuration
     */
    public function getThemeConfig(string $themeName): array
    {
        $themeJsonPath = $this->getThemePath($themeName) . '/theme.json';

        if (!File::exists($themeJsonPath)) {
            return [];
        }

        return json_decode(File::get($themeJsonPath), true) ?? [];
    }

    /**
     * Get theme path
     */
    public function getThemePath(string $themeName): string
    {
        return $this->themesPath . '/' . $themeName;
    }

    /**
     * Get active theme name
     */
    public function getActiveTheme(): string
    {
        return $this->activeTheme;
    }

    /**
     * Set active theme
     */
    public function setActiveTheme(string $themeName): bool
    {
        if (!$this->themeExists($themeName)) {
            return false;
        }

        $this->activeTheme = $themeName;

        // Update configuration
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            $envContent = preg_replace(
                '/CMS_ACTIVE_THEME=.*/m',
                'CMS_ACTIVE_THEME=' . $themeName,
                $envContent
            );
            File::put($envPath, $envContent);
        }

        // Clear cache
        Cache::forget($this->cacheKey);

        do_action('theme_activated', $themeName);

        return true;
    }

    /**
     * Check if theme exists
     */
    public function themeExists(string $themeName): bool
    {
        return File::exists($this->getThemePath($themeName));
    }

    /**
     * Get all themes
     */
    public function all(): array
    {
        if (empty($this->themes)) {
            $this->discoverThemes();
        }

        return $this->themes;
    }

    /**
     * Get a specific theme
     */
    public function get(string $themeName): ?array
    {
        if (empty($this->themes)) {
            $this->discoverThemes();
        }

        return $this->themes[$themeName] ?? null;
    }

    /**
     * Install theme from ZIP
     */
    public function install(string $zipPath): bool
    {
        try {
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath) !== true) {
                return false;
            }

            // Extract to themes directory
            $zip->extractTo($this->themesPath);
            $zip->close();

            // Get theme name from extracted folder
            $extractedFolder = $this->getExtractedFolderName($zipPath);
            
            if (!$extractedFolder) {
                return false;
            }

            // Validate theme.json exists
            $themeJsonPath = $this->getThemePath($extractedFolder) . '/theme.json';
            if (!File::exists($themeJsonPath)) {
                // Rollback: delete extracted folder
                File::deleteDirectory($this->getThemePath($extractedFolder));
                return false;
            }

            // Clear cache
            Cache::forget($this->cacheKey);

            do_action('theme_installed', $extractedFolder);

            return true;
        } catch (\Exception $e) {
            \Log::error('Theme installation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Uninstall a theme
     */
    public function uninstall(string $themeName): bool
    {
        // Cannot uninstall active theme
        if ($themeName === $this->activeTheme) {
            return false;
        }

        // Cannot uninstall fallback theme
        if ($themeName === config('cms.themes.fallback_theme', 'default')) {
            return false;
        }

        if (!$this->themeExists($themeName)) {
            return false;
        }

        // Delete theme directory
        File::deleteDirectory($this->getThemePath($themeName));

        // Clear cache
        Cache::forget($this->cacheKey);

        do_action('theme_uninstalled', $themeName);

        return true;
    }

    /**
     * Get extracted folder name from ZIP
     */
    protected function getExtractedFolderName(string $zipPath): ?string
    {
        $zip = new \ZipArchive();
        $zip->open($zipPath);
        
        $name = $zip->getNameIndex(0);
        $zip->close();
        
        return $name ? explode('/', $name)[0] : null;
    }

    /**
     * Get theme asset URL
     */
    public function asset(string $path, ?string $themeName = null): string
    {
        $themeName = $themeName ?? $this->activeTheme;
        return asset("themes/{$themeName}/assets/{$path}");
    }

    /**
     * Get theme view path
     */
    public function view(string $view, ?string $themeName = null): string
    {
        $themeName = $themeName ?? $this->activeTheme;
        return "theme::{$view}";
    }

    /**
     * Check if theme has feature
     */
    public function hasFeature(string $feature, ?string $themeName = null): bool
    {
        $themeName = $themeName ?? $this->activeTheme;
        $config = $this->getThemeConfig($themeName);
        
        return in_array($feature, $config['features'] ?? []);
    }

    /**
     * Get theme color
     */
    public function getColor(string $colorName, ?string $themeName = null): ?string
    {
        $themeName = $themeName ?? $this->activeTheme;
        $config = $this->getThemeConfig($themeName);
        
        return $config['colors'][$colorName] ?? null;
    }

    /**
     * Get theme menus
     */
    public function getMenus(?string $themeName = null): array
    {
        $themeName = $themeName ?? $this->activeTheme;
        $config = $this->getThemeConfig($themeName);
        
        return $config['menus'] ?? [];
    }

    /**
     * Get theme screenshot URL
     */
    public function getScreenshot(?string $themeName = null): ?string
    {
        $themeName = $themeName ?? $this->activeTheme;
        $config = $this->getThemeConfig($themeName);
        
        if (!isset($config['screenshot'])) {
            return null;
        }

        return asset("themes/{$themeName}/{$config['screenshot']}");
    }

    /**
     * Validate theme structure
     */
    public function validateTheme(string $themeName): array
    {
        $errors = [];
        $themePath = $this->getThemePath($themeName);

        // Check theme.json exists
        if (!File::exists($themePath . '/theme.json')) {
            $errors[] = 'theme.json file is missing';
        }

        // Check required directories
        $requiredDirs = ['views', 'layouts', 'assets'];
        foreach ($requiredDirs as $dir) {
            if (!File::exists($themePath . '/' . $dir)) {
                $errors[] = "{$dir} directory is missing";
            }
        }

        // Check for main layout file
        if (!File::exists($themePath . '/layouts/app.blade.php')) {
            $errors[] = 'layouts/app.blade.php file is missing';
        }

        return $errors;
    }

    /**
     * Clear theme cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}
