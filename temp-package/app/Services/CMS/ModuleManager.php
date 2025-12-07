<?php
/**
 * TCC School CMS - Module Manager
 * 
 * Manages the modular plugin system for TCC School CMS.
 * Handles module discovery, loading, activation, deactivation, and installation.
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
use Nwidart\Modules\Facades\Module;

class ModuleManager
{
    protected array $modules = [];
    protected array $enabledModules = [];
    protected string $modulesPath;
    protected string $statusFile;

    public function __construct()
    {
        $this->modulesPath = base_path(config('cms.modules.path', 'Modules'));
        $this->statusFile = base_path('modules_statuses.json');
        $this->loadModuleStatuses();
    }

    /**
     * Boot all enabled modules
     */
    public function bootModules(): void
    {
        $this->discoverModules();
        
        foreach ($this->enabledModules as $moduleName) {
            $this->bootModule($moduleName);
        }
    }

    /**
     * Discover all modules
     */
    protected function discoverModules(): void
    {
        if (!File::exists($this->modulesPath)) {
            File::makeDirectory($this->modulesPath, 0755, true);
            return;
        }

        $directories = File::directories($this->modulesPath);

        foreach ($directories as $directory) {
            $moduleName = basename($directory);
            $moduleJsonPath = $directory . '/module.json';

            if (File::exists($moduleJsonPath)) {
                $moduleData = json_decode(File::get($moduleJsonPath), true);
                $this->modules[$moduleName] = [
                    'name' => $moduleName,
                    'path' => $directory,
                    'data' => $moduleData,
                    'enabled' => $this->isEnabled($moduleName),
                ];
            }
        }
    }

    /**
     * Boot a specific module
     */
    protected function bootModule(string $moduleName): void
    {
        if (!isset($this->modules[$moduleName])) {
            return;
        }

        $module = $this->modules[$moduleName];
        
        // Register routes
        $this->registerModuleRoutes($module);
        
        // Register views
        $this->registerModuleViews($module);
        
        // Register migrations
        $this->registerModuleMigrations($module);
        
        // Execute module boot logic
        do_action("module_boot_{$moduleName}", $module);
    }

    /**
     * Register module routes
     */
    protected function registerModuleRoutes(array $module): void
    {
        $routesPath = $module['path'] . '/Routes';
        
        if (File::exists($routesPath . '/web.php')) {
            require $routesPath . '/web.php';
        }
        
        if (File::exists($routesPath . '/api.php')) {
            require $routesPath . '/api.php';
        }
    }

    /**
     * Register module views
     */
    protected function registerModuleViews(array $module): void
    {
        $viewsPath = $module['path'] . '/Resources/views';
        
        if (File::exists($viewsPath)) {
            view()->addNamespace(
                strtolower($module['name']),
                $viewsPath
            );
        }
    }

    /**
     * Register module migrations
     */
    protected function registerModuleMigrations(array $module): void
    {
        $migrationsPath = $module['path'] . '/Database/Migrations';
        
        if (File::exists($migrationsPath)) {
            app('migrator')->path($migrationsPath);
        }
    }

    /**
     * Enable a module
     */
    public function enable(string $moduleName): bool
    {
        if ($this->isEnabled($moduleName)) {
            return true;
        }

        $statuses = $this->loadModuleStatuses();
        $statuses[$moduleName] = true;
        $this->saveModuleStatuses($statuses);
        
        $this->enabledModules[] = $moduleName;
        
        do_action('module_enabled', $moduleName);
        
        Cache::forget(config('cms.modules.cache_key'));
        
        return true;
    }

    /**
     * Disable a module
     */
    public function disable(string $moduleName): bool
    {
        if (!$this->isEnabled($moduleName)) {
            return true;
        }

        $statuses = $this->loadModuleStatuses();
        $statuses[$moduleName] = false;
        $this->saveModuleStatuses($statuses);
        
        $this->enabledModules = array_diff($this->enabledModules, [$moduleName]);
        
        do_action('module_disabled', $moduleName);
        
        Cache::forget(config('cms.modules.cache_key'));
        
        return true;
    }

    /**
     * Check if module is enabled
     */
    public function isEnabled(string $moduleName): bool
    {
        $statuses = $this->loadModuleStatuses();
        return $statuses[$moduleName] ?? false;
    }

    /**
     * Get all modules
     */
    public function all(): array
    {
        return $this->modules;
    }

    /**
     * Get enabled modules
     */
    public function enabled(): array
    {
        return array_filter($this->modules, fn($module) => $module['enabled']);
    }

    /**
     * Get a specific module
     */
    public function get(string $moduleName): ?array
    {
        return $this->modules[$moduleName] ?? null;
    }

    /**
     * Install a module from ZIP
     */
    public function install(string $zipPath): bool
    {
        try {
            $zip = new \ZipArchive();
            
            if ($zip->open($zipPath) !== true) {
                return false;
            }

            // Extract to modules directory
            $zip->extractTo($this->modulesPath);
            $zip->close();

            // Get module name from extracted folder
            $extractedFolder = $this->getExtractedFolderName($zipPath);
            
            if (!$extractedFolder) {
                return false;
            }

            // Run migrations if they exist
            $modulePath = $this->modulesPath . '/' . $extractedFolder;
            $this->runModuleMigrations($modulePath);

            do_action('module_installed', $extractedFolder);

            return true;
        } catch (\Exception $e) {
            \Log::error('Module installation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Uninstall a module
     */
    public function uninstall(string $moduleName): bool
    {
        if (!isset($this->modules[$moduleName])) {
            return false;
        }

        // Disable first
        $this->disable($moduleName);

        // Delete module directory
        $modulePath = $this->modules[$moduleName]['path'];
        File::deleteDirectory($modulePath);

        // Remove from statuses
        $statuses = $this->loadModuleStatuses();
        unset($statuses[$moduleName]);
        $this->saveModuleStatuses($statuses);

        do_action('module_uninstalled', $moduleName);

        Cache::forget(config('cms.modules.cache_key'));

        return true;
    }

    /**
     * Load module statuses from file
     */
    protected function loadModuleStatuses(): array
    {
        if (!File::exists($this->statusFile)) {
            File::put($this->statusFile, json_encode([]));
            return [];
        }

        $statuses = json_decode(File::get($this->statusFile), true);
        $this->enabledModules = array_keys(array_filter($statuses));
        
        return $statuses ?? [];
    }

    /**
     * Save module statuses to file
     */
    protected function saveModuleStatuses(array $statuses): void
    {
        File::put($this->statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
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
     * Run module migrations
     */
    protected function runModuleMigrations(string $modulePath): void
    {
        $migrationsPath = $modulePath . '/Database/Migrations';
        
        if (File::exists($migrationsPath)) {
            \Artisan::call('migrate', [
                '--path' => $migrationsPath,
                '--force' => true,
            ]);
        }
    }

    /**
     * Check module dependencies
     */
    public function checkDependencies(string $moduleName): array
    {
        $module = $this->get($moduleName);
        
        if (!$module || !isset($module['data']['requires'])) {
            return [];
        }

        $missing = [];
        
        foreach ($module['data']['requires'] as $required) {
            if (!$this->isEnabled($required)) {
                $missing[] = $required;
            }
        }

        return $missing;
    }

    /**
     * Get module version
     */
    public function getVersion(string $moduleName): ?string
    {
        $module = $this->get($moduleName);
        return $module['data']['version'] ?? null;
    }
}
