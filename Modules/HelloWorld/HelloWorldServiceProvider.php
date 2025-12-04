<?php

namespace Modules\HelloWorld;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class HelloWorldServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Register module routes
        $this->registerRoutes();
        
        // Register module views
        $this->registerViews();
    }

    /**
     * Register module routes
     */
    protected function registerRoutes(): void
    {
        $routesPath = __DIR__ . '/routes.php';
        
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }
    }

    /**
     * Register module views
     */
    protected function registerViews(): void
    {
        $viewsPath = __DIR__ . '/views';
        
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'HelloWorld');
        }
    }
}
