<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Exception;

/**
 * VantaPress Module Loader
 * Handles auto-discovery, loading, and management of modules
 */
class ModuleLoader
{
    protected string $modulesPath;
    protected array $loadedModules = [];
    protected array $registry = [];

    public function __construct()
    {
        $this->modulesPath = base_path('Modules');
        $this->ensureModulesDirectory();
    }

    /**
     * Ensure Modules directory exists
     */
    protected function ensureModulesDirectory(): void
    {
        if (!File::exists($this->modulesPath)) {
            File::makeDirectory($this->modulesPath, 0755, true);
        }
    }

    /**
     * Discover and load all active modules
     */
    public function discoverModules(): array
    {
        $this->registry = [];
        
        if (!File::exists($this->modulesPath)) {
            return [];
        }

        $directories = File::directories($this->modulesPath);

        foreach ($directories as $directory) {
            $moduleName = basename($directory);
            $metadataPath = $directory . '/module.json';

            if (!File::exists($metadataPath)) {
                continue;
            }

            try {
                $metadata = json_decode(File::get($metadataPath), true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }

                $this->registry[$moduleName] = array_merge($metadata, [
                    'path' => $directory,
                    'namespace' => 'Modules\\' . $moduleName,
                ]);

                // Load module if active
                if ($metadata['active'] ?? false) {
                    $this->loadModule($moduleName);
                }
            } catch (Exception $e) {
                // Log error but continue with other modules
                logger()->error("Failed to load module {$moduleName}: " . $e->getMessage());
            }
        }

        return $this->registry;
    }

    /**
     * Load a specific module
     */
    public function loadModule(string $moduleName): bool
    {
        if (isset($this->loadedModules[$moduleName])) {
            return true;
        }

        $modulePath = $this->modulesPath . '/' . $moduleName;
        
        if (!File::exists($modulePath)) {
            return false;
        }

        try {
            // Get module metadata
            $metadataPath = $modulePath . '/module.json';
            $metadata = json_decode(File::get($metadataPath), true);

            // Register service providers
            $providersRegistered = false;
            
            // Support both 'providers' array (new format) and 'service_provider' string (legacy)
            if (isset($metadata['providers']) && is_array($metadata['providers'])) {
                foreach ($metadata['providers'] as $providerClass) {
                    if (class_exists($providerClass)) {
                        app()->register($providerClass);
                        $providersRegistered = true;
                    }
                }
            } elseif (isset($metadata['service_provider']) && class_exists($metadata['service_provider'])) {
                app()->register($metadata['service_provider']);
                $providersRegistered = true;
            }
            
            if (!$providersRegistered) {
                // Fallback: Load routes and views directly
                // Load routes if exists
                $routesPath = $modulePath . '/routes.php';
                if (File::exists($routesPath)) {
                    require_once $routesPath;
                }

                // Register views
                $viewsPath = $modulePath . '/views';
                if (File::exists($viewsPath)) {
                    view()->addNamespace($moduleName, $viewsPath);
                }

                // Load migrations path
                $migrationsPath = $modulePath . '/migrations';
                if (File::exists($migrationsPath)) {
                    app('migrator')->path($migrationsPath);
                }
            }

            // Register Filament pages if specified
            if (isset($metadata['provides']['filament_pages'])) {
                foreach ($metadata['provides']['filament_pages'] as $pageClass) {
                    if (class_exists($pageClass)) {
                        // Filament will auto-discover these via panel provider
                    }
                }
            }

            // Mark as loaded
            $this->loadedModules[$moduleName] = true;

            return true;
        } catch (Exception $e) {
            logger()->error("Failed to load module {$moduleName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate module structure
     */
    public function validateModule(string $path): array
    {
        $errors = [];

        // Check module.json exists
        if (!File::exists($path . '/module.json')) {
            $errors[] = 'module.json is missing';
            return $errors;
        }

        // Validate module.json
        try {
            $metadata = json_decode(File::get($path . '/module.json'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'module.json is invalid JSON';
                return $errors;
            }

            $validator = Validator::make($metadata, [
                'name' => 'required|string|max:255',
                'version' => 'required|string',
                'description' => 'required|string',
                'active' => 'boolean',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors()->all());
            }
        } catch (Exception $e) {
            $errors[] = 'Failed to parse module.json: ' . $e->getMessage();
        }

        // Check for dangerous files
        $dangerousFiles = $this->scanForDangerousFiles($path);
        if (!empty($dangerousFiles)) {
            $errors[] = 'Module contains potentially dangerous files: ' . implode(', ', $dangerousFiles);
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
     * Get all registered modules
     */
    public function getModules(): array
    {
        return $this->registry;
    }

    /**
     * Get loaded modules
     */
    public function getLoadedModules(): array
    {
        return $this->loadedModules;
    }

    /**
     * Check if module is loaded
     */
    public function isLoaded(string $moduleName): bool
    {
        return isset($this->loadedModules[$moduleName]);
    }

    /**
     * Get module metadata
     */
    public function getModuleMetadata(string $moduleName): ?array
    {
        return $this->registry[$moduleName] ?? null;
    }

    /**
     * Activate module
     */
    public function activateModule(string $moduleName): bool
    {
        $metadataPath = $this->modulesPath . '/' . $moduleName . '/module.json';
        
        if (!File::exists($metadataPath)) {
            return false;
        }

        try {
            $metadata = json_decode(File::get($metadataPath), true);
            $metadata['active'] = true;
            
            File::put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            // Auto-run module migrations on activation
            $this->runModuleMigrations($moduleName);
            
            return $this->loadModule($moduleName);
        } catch (Exception $e) {
            logger()->error("Failed to activate module {$moduleName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Run migrations for a specific module
     */
    protected function runModuleMigrations(string $moduleName): void
    {
        $modulePath = $this->modulesPath . '/' . $moduleName;
        $migrationsPath = $modulePath . '/migrations';
        
        if (!File::exists($migrationsPath)) {
            return;
        }

        try {
            logger()->info("Running migrations for module: {$moduleName}");
            
            // Get all migration files
            $migrationFiles = glob($migrationsPath . '/*.php');
            
            if (empty($migrationFiles)) {
                return;
            }

            // Check which migrations are already executed
            $executedMigrations = \DB::table('migrations')
                ->pluck('migration')
                ->toArray();

            // Get current batch number
            $batch = \DB::table('migrations')->max('batch') + 1;

            $executed = 0;
            
            foreach ($migrationFiles as $file) {
                $migrationName = basename($file, '.php');
                
                // Skip if already executed
                if (in_array($migrationName, $executedMigrations)) {
                    continue;
                }
                
                try {
                    // Include and run the migration
                    $migration = include $file;
                    
                    if (method_exists($migration, 'up')) {
                        $migration->up();
                        
                        // Record in migrations table
                        \DB::table('migrations')->insert([
                            'migration' => $migrationName,
                            'batch' => $batch
                        ]);
                        
                        logger()->info("Migrated: {$migrationName}");
                        $executed++;
                    }
                } catch (\Exception $e) {
                    logger()->error("Migration failed for {$moduleName}: {$migrationName}", [
                        'error' => $e->getMessage()
                    ]);
                    // Continue with other migrations even if one fails
                }
            }
            
            if ($executed > 0) {
                logger()->info("Module {$moduleName}: Executed {$executed} migration(s)");
            }
        } catch (\Exception $e) {
            logger()->error("Failed to run migrations for module {$moduleName}: " . $e->getMessage());
        }
    }

    /**
     * Deactivate module
     */
    public function deactivateModule(string $moduleName): bool
    {
        $metadataPath = $this->modulesPath . '/' . $moduleName . '/module.json';
        
        if (!File::exists($metadataPath)) {
            return false;
        }

        try {
            $metadata = json_decode(File::get($metadataPath), true);
            $metadata['active'] = false;
            
            File::put($metadataPath, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            unset($this->loadedModules[$moduleName]);
            
            return true;
        } catch (Exception $e) {
            logger()->error("Failed to deactivate module {$moduleName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete module
     */
    public function deleteModule(string $moduleName): bool
    {
        $modulePath = $this->modulesPath . '/' . $moduleName;
        
        if (!File::exists($modulePath)) {
            return false;
        }

        try {
            File::deleteDirectory($modulePath);
            
            unset($this->registry[$moduleName]);
            unset($this->loadedModules[$moduleName]);
            
            return true;
        } catch (Exception $e) {
            logger()->error("Failed to delete module {$moduleName}: " . $e->getMessage());
            return false;
        }
    }
}
