<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use ZipArchive;
use Exception;

/**
 * VantaPress Theme Installer
 * Handles installation of themes from ZIP/VPT files
 */
class ThemeInstaller
{
    protected string $themesPath;
    protected string $tempPath;
    protected int $maxFileSize = 52428800; // 50MB

    public function __construct()
    {
        $this->themesPath = base_path('themes');
        $this->tempPath = storage_path('app/temp');
        $this->ensureDirectories();
    }

    /**
     * Ensure required directories exist
     */
    protected function ensureDirectories(): void
    {
        if (!File::exists($this->themesPath)) {
            File::makeDirectory($this->themesPath, 0755, true);
        }
        
        if (!File::exists($this->tempPath)) {
            File::makeDirectory($this->tempPath, 0755, true);
        }
    }

    /**
     * Install theme from uploaded file
     */
    public function install(string $filePath, bool $update = false): array
    {
        try {
            // Validate file
            $validation = $this->validateFile($filePath);
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'message' => $validation['error'],
                ];
            }

            // Extract to temp directory
            $tempDir = $this->tempPath . '/' . uniqid('theme_');
            $extracted = $this->extractArchive($filePath, $tempDir);
            
            if (!$extracted['success']) {
                return $extracted;
            }

            // Find theme root
            $themeRoot = $this->findThemeRoot($tempDir);
            if (!$themeRoot) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Invalid theme structure: theme.json not found',
                ];
            }

            // Load and validate metadata
            $metadata = json_decode(File::get($themeRoot . '/theme.json'), true);
            
            if (!$metadata || !isset($metadata['name'])) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Invalid theme.json',
                ];
            }

            $themeName = $this->sanitizeThemeName($metadata['name']);
            $finalPath = $this->themesPath . '/' . $themeName;

            // Check if theme exists
            if (File::exists($finalPath) && !$update) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Theme already exists. Use update option to replace it.',
                ];
            }

            // Validate theme structure
            $themeLoader = new ThemeLoader();
            $errors = $themeLoader->validateTheme($themeRoot);
            
            if (!empty($errors)) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Theme validation failed: ' . implode(', ', $errors),
                ];
            }

            // Remove old version if updating
            if ($update && File::exists($finalPath)) {
                File::deleteDirectory($finalPath);
            }

            // Move to final location
            File::moveDirectory($themeRoot, $finalPath);
            
            // Cleanup temp
            File::deleteDirectory($tempDir);

            return [
                'success' => true,
                'message' => $update ? 'Theme updated successfully' : 'Theme installed successfully',
                'theme' => $metadata,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(string $filePath): array
    {
        if (!File::exists($filePath)) {
            return ['valid' => false, 'error' => 'File does not exist'];
        }

        $fileSize = File::size($filePath);
        if ($fileSize > $this->maxFileSize) {
            return ['valid' => false, 'error' => 'File too large. Maximum size: 50MB'];
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($extension, ['zip', 'vpt'])) {
            return ['valid' => false, 'error' => 'Invalid file type. Only .zip and .vpt allowed'];
        }

        return ['valid' => true];
    }

    /**
     * Extract ZIP/VPT archive
     */
    protected function extractArchive(string $filePath, string $destination): array
    {
        if (!class_exists('ZipArchive')) {
            return [
                'success' => false,
                'message' => 'ZipArchive extension is not installed',
            ];
        }

        $zip = new ZipArchive();
        $result = $zip->open($filePath);

        if ($result !== true) {
            return [
                'success' => false,
                'message' => 'Failed to open archive',
            ];
        }

        // Validate paths for security
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            
            // Prevent path traversal
            if (strpos($filename, '..') !== false || strpos($filename, './') === 0) {
                $zip->close();
                return [
                    'success' => false,
                    'message' => 'Archive contains invalid paths',
                ];
            }
        }

        $zip->extractTo($destination);
        $zip->close();

        return ['success' => true];
    }

    /**
     * Find theme root directory
     */
    protected function findThemeRoot(string $path): ?string
    {
        if (File::exists($path . '/theme.json')) {
            return $path;
        }

        $directories = File::directories($path);
        foreach ($directories as $dir) {
            if (File::exists($dir . '/theme.json')) {
                return $dir;
            }
        }

        return null;
    }

    /**
     * Sanitize theme name
     */
    protected function sanitizeThemeName(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        $name = trim($name, '-_');
        
        return $name;
    }

    /**
     * Get max file size
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * Set max file size
     */
    public function setMaxFileSize(int $bytes): void
    {
        $this->maxFileSize = $bytes;
    }
}
