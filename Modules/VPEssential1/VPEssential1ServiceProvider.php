<?php

namespace Modules\VPEssential1;

use Illuminate\Support\ServiceProvider;
use Modules\VPEssential1\Services\HashtagService;
use Modules\VPEssential1\Services\NotificationService;
use Modules\VPEssential1\Services\SocialService;

class VPEssential1ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register services
        $this->app->singleton(HashtagService::class, function ($app) {
            return new HashtagService();
        });
        
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
        
        $this->app->singleton(SocialService::class, function ($app) {
            return new SocialService();
        });
        
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
        
        // Load views with both aliases for compatibility
        $this->loadViewsFrom(__DIR__ . '/views', 'VPEssential1');
        $this->loadViewsFrom(__DIR__ . '/views', 'vpessential1');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        
        // Register Filament resources
        if (class_exists(\Filament\Facades\Filament::class)) {
            \Filament\Facades\Filament::serving(function () {
                // Register Filament pages
                \Filament\Facades\Filament::registerPages([
                    \Modules\VPEssential1\Filament\Pages\SocialSettings::class,
                ]);
                
                // Register Filament resources
                \Filament\Facades\Filament::registerResources([
                    \Modules\VPEssential1\Filament\Resources\PostResource::class,
                ]);
            });
        }
    }
}
