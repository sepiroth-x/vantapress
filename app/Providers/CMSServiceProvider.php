<?php
/**
 * TCC School CMS - Service Provider
 * 
 * Main service provider for TCC School CMS core functionality.
 * Registers and bootstraps all CMS services, modules, and themes.
 * 
 * @package TCC_School_CMS
 * @subpackage Providers
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

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CMS\ModuleManager;
use App\Services\CMS\ThemeManager;
use App\Services\CMS\HookManager;
use App\Services\CMS\MenuManager;
use App\Services\CMS\SettingsManager;
use App\Services\ModuleLoader;
use App\Services\ModuleInstaller;
use App\Services\ThemeLoader;
use App\Services\ThemeInstaller;
use Illuminate\Support\Facades\File;

class CMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Load helper functions
        $helpersPath = app_path('Helpers/helpers.php');
        if (File::exists($helpersPath)) {
            require_once $helpersPath;
        }
        
        // Register Module Manager
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager();
        });

        // Register Theme Manager
        $this->app->singleton(ThemeManager::class, function ($app) {
            return new ThemeManager();
        });

        // Register VantaPress Module Loader
        $this->app->singleton(ModuleLoader::class, function ($app) {
            return new ModuleLoader();
        });

        // Register VantaPress Module Installer
        $this->app->singleton(ModuleInstaller::class, function ($app) {
            return new ModuleInstaller();
        });

        // Register VantaPress Theme Loader
        $this->app->singleton(ThemeLoader::class, function ($app) {
            return new ThemeLoader();
        });

        // Register VantaPress Theme Installer
        $this->app->singleton(ThemeInstaller::class, function ($app) {
            return new ThemeInstaller();
        });

        // Register Hook Manager
        $this->app->singleton(HookManager::class, function ($app) {
            return new HookManager();
        });

        // Register Menu Manager
        $this->app->singleton(MenuManager::class, function ($app) {
            return new MenuManager();
        });

        // Register Settings Manager
        $this->app->singleton(SettingsManager::class, function ($app) {
            return new SettingsManager();
        });

        // Merge CMS configuration
        $this->mergeConfigFrom(
            config_path('cms.php'), 'cms'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load VantaPress modules
        $moduleLoader = $this->app->make(ModuleLoader::class);
        $modules = $moduleLoader->discoverModules();
        
        // Register module service providers
        foreach ($modules as $moduleName => $metadata) {
            if ($metadata['active'] ?? false) {
                // Check if module has a service provider
                if (isset($metadata['service_provider'])) {
                    $providerClass = $metadata['service_provider'];
                    if (class_exists($providerClass)) {
                        $this->app->register($providerClass);
                    }
                }
            }
        }
        
        // Load VantaPress active theme
        $themeLoader = $this->app->make(ThemeLoader::class);
        $themeLoader->discoverThemes();
        $themeLoader->loadActiveTheme();

        // Load modules
        $moduleManager = $this->app->make(ModuleManager::class);
        $moduleManager->bootModules();

        // Load active theme
        $themeManager = $this->app->make(ThemeManager::class);
        $themeManager->loadTheme();

        // Register view composers
        $this->registerViewComposers();

        // Publish configuration
        $this->publishes([
            config_path('cms.php') => config_path('cms.php'),
        ], 'cms-config');
    }

    /**
     * Register view composers
     */
    protected function registerViewComposers(): void
    {
        view()->composer('*', function ($view) {
            $view->with('cms_settings', app(SettingsManager::class)->all());
            $view->with('cms_menus', app(MenuManager::class)->all());
        });
    }
}
