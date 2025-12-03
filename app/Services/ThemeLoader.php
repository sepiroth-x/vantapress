<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * VantaPress Theme Loader
 * Handles theme discovery, loading, and Blade overrides
 */
class ThemeLoader
{
    protected string $themesPath;
    protected array $registry = [];
    protected ?string $activeTheme = null;

    public function __construct()
    {
        $this->themesPath = base_path('themes');
        $this->activeTheme = config('cms.active_theme', null);
        $this->ensureThemesDirectory();
    }

    /**
     * Ensure themes directory exists
     */
    protected function ensureThemesDirectory(): void
    {
        if (!File::exists($this->themesPath)) {
            File::makeDirectory($this->themesPath, 0755, true);
        }
    }

    /**
     * Discover all themes
     */
    public function discoverThemes(): array
    {
        $this->registry = [];
        
        if (!File::exists($this->themesPath)) {
            return [];
        }

        $directories = File::directories($this->themesPath);

        foreach ($directories as $directory) {
            $themeName = basename($directory);
            $metadataPath = $directory . '/theme.json';

            if (!File::exists($metadataPath)) {
                continue;
            }

            try {
                $metadata = json_decode(File::get($metadataPath), true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }

                $this->registry[$themeName] = array_merge($metadata, [
                    'path' => $directory,
                    'slug' => $themeName,
                    'is_active' => $themeName === $this->activeTheme,
                ]);
            } catch (Exception $e) {
                logger()->error("Failed to load theme {$themeName}: " . $e->getMessage());
            }
        }

        return $this->registry;
    }

    /**
     * Load active theme
     */
    public function loadActiveTheme(): ?array
    {
        if (!$this->activeTheme) {
            return null;
        }

        $themePath = $this->themesPath . '/' . $this->activeTheme;
        
        if (!File::exists($themePath)) {
            return null;
        }

        // Register theme views
        $viewsPath = $themePath . '/views';
        if (File::exists($viewsPath)) {
            view()->addNamespace('theme', $viewsPath);
        }

        // Register layouts
        $layoutsPath = $themePath . '/layouts';
        if (File::exists($layoutsPath)) {
            view()->addNamespace('theme.layouts', $layoutsPath);
        }

        // Register components
        $componentsPath = $themePath . '/components';
        if (File::exists($componentsPath)) {
            view()->addNamespace('theme.components', $componentsPath);
        }

        return $this->registry[$this->activeTheme] ?? null;
    }

    /**
     * Resolve view with theme override
     */
    public function resolveView(string $view): string
    {
        if (!$this->activeTheme) {
            return $view;
        }

        // Try theme view first
        $themeView = "theme::{$view}";
        if (view()->exists($themeView)) {
            return $themeView;
        }

        // Fallback to default
        return $view;
    }

    /**
     * Validate theme structure
     */
    public function validateTheme(string $path): array
    {
        $errors = [];

        // Check theme.json exists
        if (!File::exists($path . '/theme.json')) {
            $errors[] = 'theme.json is missing';
            return $errors;
        }

        // Validate theme.json
        try {
            $metadata = json_decode(File::get($path . '/theme.json'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'theme.json is invalid JSON';
                return $errors;
            }

            $validator = Validator::make($metadata, [
                'name' => 'required|string|max:255',
                'version' => 'required|string',
                'author' => 'required|string',
                'preview' => 'string',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors()->all());
            }
        } catch (Exception $e) {
            $errors[] = 'Failed to parse theme.json: ' . $e->getMessage();
        }

        // Check for dangerous files
        $dangerousFiles = $this->scanForDangerousFiles($path);
        if (!empty($dangerousFiles)) {
            $errors[] = 'Theme contains potentially dangerous files: ' . implode(', ', $dangerousFiles);
        }

        return $errors;
    }

    /**
     * Scan for dangerous files
     */
    protected function scanForDangerousFiles(string $path): array
    {
        $dangerous = [];
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'sh', 'dll', 'so'];
        
        $files = File::allFiles($path);
        
        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, $dangerousExtensions)) {
                $dangerous[] = $file->getFilename();
            }
        }

        return $dangerous;
    }

    /**
     * Get all themes
     */
    public function getThemes(): array
    {
        return $this->registry;
    }

    /**
     * Get active theme
     */
    public function getActiveTheme(): ?string
    {
        return $this->activeTheme;
    }

    /**
     * Get theme metadata
     */
    public function getThemeMetadata(string $themeName): ?array
    {
        return $this->registry[$themeName] ?? null;
    }

    /**
     * Activate theme
     */
    public function activateTheme(string $themeName): bool
    {
        $themePath = $this->themesPath . '/' . $themeName;
        
        if (!File::exists($themePath . '/theme.json')) {
            return false;
        }

        try {
            // Update config
            $this->updateConfig('cms.active_theme', $themeName);
            $this->activeTheme = $themeName;
            
            return true;
        } catch (Exception $e) {
            logger()->error("Failed to activate theme {$themeName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete theme
     */
    public function deleteTheme(string $themeName): bool
    {
        $themePath = $this->themesPath . '/' . $themeName;
        
        if (!File::exists($themePath)) {
            return false;
        }

        // Prevent deleting active theme
        if ($themeName === $this->activeTheme) {
            return false;
        }

        try {
            File::deleteDirectory($themePath);
            unset($this->registry[$themeName]);
            
            return true;
        } catch (Exception $e) {
            logger()->error("Failed to delete theme {$themeName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update configuration file
     */
    protected function updateConfig(string $key, $value): void
    {
        $configPath = config_path('cms.php');
        $config = include $configPath;
        
        $keys = explode('.', str_replace('cms.', '', $key));
        $temp = &$config;
        
        foreach ($keys as $k) {
            if (!isset($temp[$k])) {
                $temp[$k] = [];
            }
            $temp = &$temp[$k];
        }
        
        $temp = $value;
        
        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        File::put($configPath, $content);
    }
}
