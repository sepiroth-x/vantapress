<?php

namespace Modules\VPEssential1;

use Illuminate\Support\ServiceProvider;

class VPEssential1ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Load helper functions
        $helpersPath = __DIR__ . '/helpers/functions.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__ . '/views', 'VPEssential1');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}
