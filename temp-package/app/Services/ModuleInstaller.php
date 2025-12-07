<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Exception;

/**
 * VantaPress Module Installer
 * Handles installation of modules from ZIP/VPM files
 */
class ModuleInstaller
{
    protected string $modulesPath;
    protected string $tempPath;
    protected int $maxFileSize = 52428800; // 50MB

    public function __construct()
    {
        $this->modulesPath = base_path('Modules');
        $this->tempPath = storage_path('app/temp');
        $this->ensureDirectories();
    }

    /**
     * Ensure required directories exist
     */
    protected function ensureDirectories(): void
    {
        if (!File::exists($this->modulesPath)) {
            File::makeDirectory($this->modulesPath, 0755, true);
        }
        
        if (!File::exists($this->tempPath)) {
            File::makeDirectory($this->tempPath, 0755, true);
        }
    }

    /**
     * Install module from uploaded file
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
            $tempDir = $this->tempPath . '/' . uniqid('module_');
            $extracted = $this->extractArchive($filePath, $tempDir);
            
            if (!$extracted['success']) {
                return $extracted;
            }

            // Find module root (handle nested structures)
            $moduleRoot = $this->findModuleRoot($tempDir);
            if (!$moduleRoot) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Invalid module structure: module.json not found',
                ];
            }

            // Load and validate metadata
            $metadata = json_decode(File::get($moduleRoot . '/module.json'), true);
            
            if (!$metadata || !isset($metadata['name'])) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Invalid module.json',
                ];
            }

            $moduleName = $this->sanitizeModuleName($metadata['name']);
            $finalPath = $this->modulesPath . '/' . $moduleName;

            // Check if module exists
            if (File::exists($finalPath) && !$update) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Module already exists. Use update option to replace it.',
                ];
            }

            // Validate module structure
            $moduleLoader = new ModuleLoader();
            $errors = $moduleLoader->validateModule($moduleRoot);
            
            if (!empty($errors)) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'Module validation failed: ' . implode(', ', $errors),
                ];
            }

            // Remove old version if updating
            if ($update && File::exists($finalPath)) {
                File::deleteDirectory($finalPath);
            }

            // Move to final location
            File::moveDirectory($moduleRoot, $finalPath);
            
            // Cleanup temp
            File::deleteDirectory($tempDir);

            return [
                'success' => true,
                'message' => $update ? 'Module updated successfully' : 'Module installed successfully',
                'module' => $metadata,
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
        if (!in_array($extension, ['zip', 'vpm'])) {
            return ['valid' => false, 'error' => 'Invalid file type. Only .zip and .vpm allowed'];
        }

        return ['valid' => true];
    }

    /**
     * Extract ZIP/VPM archive
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
     * Find module root directory (handles nested structures)
     */
    protected function findModuleRoot(string $path): ?string
    {
        // Check if module.json exists in root
        if (File::exists($path . '/module.json')) {
            return $path;
        }

        // Check one level deep
        $directories = File::directories($path);
        foreach ($directories as $dir) {
            if (File::exists($dir . '/module.json')) {
                return $dir;
            }
        }

        return null;
    }

    /**
     * Sanitize module name
     */
    protected function sanitizeModuleName(string $name): string
    {
        // Remove special characters, allow only alphanumeric and dashes
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
