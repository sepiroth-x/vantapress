<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

/**
 * VantaPress Auto-Update Service
 * WordPress-style automatic updates
 */
class AutoUpdateService
{
    protected string $tempDir;
    protected string $backupDir;
    protected string $githubRepo = 'sepiroth-x/vantapress';
    protected array $protectedFiles = ['.env', '.htaccess'];
    protected array $protectedDirs = ['storage', 'bootstrap/cache'];

    public function __construct()
    {
        $this->tempDir = storage_path('app/updates');
        $this->backupDir = storage_path('app/backups');
        
        $this->ensureDirectories();
    }

    /**
     * Ensure required directories exist
     */
    protected function ensureDirectories(): void
    {
        File::ensureDirectoryExists($this->tempDir);
        File::ensureDirectoryExists($this->backupDir);
    }

    /**
     * Download update package from GitHub
     */
    public function downloadUpdate(string $version): array
    {
        try {
            $url = "https://github.com/{$this->githubRepo}/archive/refs/tags/{$version}.zip";
            $zipPath = $this->tempDir . '/update-' . $version . '.zip';

            // Download with progress tracking
            $response = Http::timeout(300)
                ->withOptions(['sink' => $zipPath])
                ->get($url);

            if (!$response->successful()) {
                throw new Exception("Failed to download update from GitHub");
            }

            if (!file_exists($zipPath) || filesize($zipPath) === 0) {
                throw new Exception("Downloaded file is empty or does not exist");
            }

            return [
                'success' => true,
                'path' => $zipPath,
                'size' => filesize($zipPath),
                'message' => 'Update package downloaded successfully'
            ];
        } catch (Exception $e) {
            Log::error('Update download failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Extract update package
     */
    public function extractUpdate(string $zipPath, string $version): array
    {
        try {
            $extractPath = $this->tempDir . '/extracted-' . $version;

            // Remove old extraction if exists
            if (File::exists($extractPath)) {
                File::deleteDirectory($extractPath);
            }

            File::makeDirectory($extractPath, 0755, true);

            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                throw new Exception("Failed to open ZIP file");
            }

            $zip->extractTo($extractPath);
            $zip->close();

            // GitHub wraps in folder like: vantapress-1.0.12-complete/
            // Find the actual directory
            $dirs = File::directories($extractPath);
            if (empty($dirs)) {
                throw new Exception("No directories found in extracted package");
            }

            $actualPath = $dirs[0];

            return [
                'success' => true,
                'path' => $actualPath,
                'message' => 'Update package extracted successfully'
            ];
        } catch (Exception $e) {
            Log::error('Update extraction failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Extraction failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create backup of current installation
     */
    public function createBackup(): array
    {
        try {
            $timestamp = now()->format('Y-m-d_His');
            $currentVersion = config('version.version', 'unknown');
            $backupName = "backup-{$currentVersion}-{$timestamp}";
            $backupPath = $this->backupDir . '/' . $backupName;

            File::makeDirectory($backupPath, 0755, true);

            // Files/folders to backup
            $itemsToBackup = [
                'app',
                'bootstrap',
                'config',
                'database',
                'resources',
                'routes',
                '.env',
                'composer.json',
                'composer.lock',
            ];

            foreach ($itemsToBackup as $item) {
                $source = base_path($item);
                $destination = $backupPath . '/' . $item;

                if (File::exists($source)) {
                    if (File::isDirectory($source)) {
                        File::copyDirectory($source, $destination);
                    } else {
                        File::copy($source, $destination);
                    }
                }
            }

            // Create backup info file
            File::put($backupPath . '/backup-info.json', json_encode([
                'version' => $currentVersion,
                'timestamp' => $timestamp,
                'date' => now()->toDateTimeString(),
                'items' => $itemsToBackup
            ], JSON_PRETTY_PRINT));

            return [
                'success' => true,
                'path' => $backupPath,
                'message' => 'Backup created successfully'
            ];
        } catch (Exception $e) {
            Log::error('Backup creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Apply update (copy files from extracted package)
     */
    public function applyUpdate(string $extractedPath): array
    {
        try {
            $basePath = base_path();

            // Get list of files to update (exclude protected files)
            $filesToUpdate = $this->getUpdateableFiles($extractedPath);

            $updated = 0;
            $skipped = 0;
            $errors = [];

            foreach ($filesToUpdate as $relativePath) {
                $source = $extractedPath . '/' . $relativePath;
                $destination = $basePath . '/' . $relativePath;

                try {
                    // Skip if protected
                    if ($this->isProtected($relativePath)) {
                        $skipped++;
                        continue;
                    }

                    // Ensure destination directory exists
                    $destDir = dirname($destination);
                    if (!File::exists($destDir)) {
                        File::makeDirectory($destDir, 0755, true);
                    }

                    // Copy file
                    if (File::exists($source)) {
                        if (File::isDirectory($source)) {
                            File::copyDirectory($source, $destination);
                        } else {
                            File::copy($source, $destination);
                        }
                        $updated++;
                    }
                } catch (Exception $e) {
                    $errors[] = "Failed to update {$relativePath}: " . $e->getMessage();
                }
            }

            return [
                'success' => count($errors) === 0,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'message' => "Updated {$updated} files, skipped {$skipped} protected files"
            ];
        } catch (Exception $e) {
            Log::error('Update application failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to apply update: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get list of updateable files
     */
    protected function getUpdateableFiles(string $extractedPath): array
    {
        $files = [];
        
        $directories = [
            'app',
            'bootstrap',
            'config',
            'database',
            'resources',
            'routes',
            'themes',
            'Modules',
        ];

        foreach ($directories as $dir) {
            $path = $extractedPath . '/' . $dir;
            if (File::exists($path)) {
                $files[] = $dir;
            }
        }

        // Add root files
        $rootFiles = [
            'artisan',
            'composer.json',
            'composer.lock',
            'index.php',
            'install.php',
            'create-admin.php',
        ];

        foreach ($rootFiles as $file) {
            if (File::exists($extractedPath . '/' . $file)) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * Check if file/directory is protected
     */
    protected function isProtected(string $path): bool
    {
        // Check protected files
        foreach ($this->protectedFiles as $protected) {
            if (str_contains($path, $protected)) {
                return true;
            }
        }

        // Check protected directories
        foreach ($this->protectedDirs as $protected) {
            if (str_starts_with($path, $protected)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Run post-update tasks
     */
    public function runPostUpdateTasks(string $newVersion): array
    {
        try {
            $tasks = [];

            // Clear caches
            Artisan::call('config:clear');
            $tasks[] = 'Cleared configuration cache';

            Artisan::call('cache:clear');
            $tasks[] = 'Cleared application cache';

            Artisan::call('view:clear');
            $tasks[] = 'Cleared view cache';

            Artisan::call('route:clear');
            $tasks[] = 'Cleared route cache';

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);
            $tasks[] = 'Ran database migrations';

            // Update version in .env
            $this->updateVersionInEnv($newVersion);
            $tasks[] = 'Updated version in .env file';

            return [
                'success' => true,
                'tasks' => $tasks,
                'message' => 'Post-update tasks completed successfully'
            ];
        } catch (Exception $e) {
            Log::error('Post-update tasks failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Post-update tasks failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update version in .env file
     */
    protected function updateVersionInEnv(string $version): void
    {
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            // Strip 'v' prefix if present (e.g., v1.0.29-complete → 1.0.29-complete)
            $cleanVersion = ltrim($version, 'v');
            
            $envContent = File::get($envPath);
            $oldVersion = env('APP_VERSION', 'unknown');
            
            if (preg_match('/^APP_VERSION=.*$/m', $envContent)) {
                $envContent = preg_replace(
                    '/^APP_VERSION=.*$/m',
                    'APP_VERSION=' . $cleanVersion,
                    $envContent
                );
            } else {
                $envContent .= "\nAPP_VERSION=" . $cleanVersion . "\n";
            }
            
            File::put($envPath, $envContent);
            
            Log::info("Updated .env APP_VERSION: {$oldVersion} → {$cleanVersion}");
        } else {
            Log::warning("Could not update .env version: file not found at {$envPath}");
        }
    }

    /**
     * Clean up temporary files
     */
    public function cleanup(): void
    {
        try {
            if (File::exists($this->tempDir)) {
                File::deleteDirectory($this->tempDir);
                File::makeDirectory($this->tempDir, 0755, true);
            }
        } catch (Exception $e) {
            Log::error('Cleanup failed: ' . $e->getMessage());
        }
    }

    /**
     * Restore from backup
     */
    public function restoreBackup(string $backupPath): array
    {
        try {
            if (!File::exists($backupPath)) {
                throw new Exception("Backup not found: {$backupPath}");
            }

            $basePath = base_path();
            $backupItems = File::allFiles($backupPath);

            foreach ($backupItems as $file) {
                $relativePath = $file->getRelativePathname();
                $destination = $basePath . '/' . $relativePath;

                // Ensure destination directory exists
                $destDir = dirname($destination);
                if (!File::exists($destDir)) {
                    File::makeDirectory($destDir, 0755, true);
                }

                File::copy($file->getPathname(), $destination);
            }

            return [
                'success' => true,
                'message' => 'Backup restored successfully'
            ];
        } catch (Exception $e) {
            Log::error('Backup restoration failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Restoration failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Perform complete update process
     */
    public function performUpdate(string $version): array
    {
        $results = [
            'steps' => [],
            'success' => false,
            'version' => $version
        ];

        try {
            // Step 1: Create backup
            $backup = $this->createBackup();
            $results['steps'][] = $backup;
            if (!$backup['success']) {
                throw new Exception('Backup creation failed');
            }

            // Step 2: Download update
            $download = $this->downloadUpdate($version);
            $results['steps'][] = $download;
            if (!$download['success']) {
                throw new Exception('Download failed');
            }

            // Step 3: Extract update
            $extract = $this->extractUpdate($download['path'], $version);
            $results['steps'][] = $extract;
            if (!$extract['success']) {
                throw new Exception('Extraction failed');
            }

            // Step 4: Apply update
            $apply = $this->applyUpdate($extract['path']);
            $results['steps'][] = $apply;
            if (!$apply['success']) {
                // Attempt to restore backup
                $this->restoreBackup($backup['path']);
                throw new Exception('Update application failed, restored from backup');
            }

            // Step 5: Post-update tasks
            $postUpdate = $this->runPostUpdateTasks($version);
            $results['steps'][] = $postUpdate;
            if (!$postUpdate['success']) {
                Log::warning('Post-update tasks had issues');
            }

            // Step 6: Cleanup
            $this->cleanup();
            $results['steps'][] = [
                'success' => true,
                'message' => 'Cleaned up temporary files'
            ];

            $results['success'] = true;
            $results['message'] = "Successfully updated to version {$version}";

        } catch (Exception $e) {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            Log::error('Update process failed: ' . $e->getMessage());
        }

        return $results;
    }
}
