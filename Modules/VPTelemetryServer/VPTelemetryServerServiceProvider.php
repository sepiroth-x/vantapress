<?php

namespace Modules\VPTelemetryServer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/**
 * VantaPress Telemetry Server Module
 * 
 * Receives and processes anonymous telemetry data from VantaPress installations.
 * Provides dashboard for developers to view usage statistics.
 * 
 * INSTALL THIS MODULE ONLY ON YOUR PRIVATE DEVELOPER SERVER
 */
class VPTelemetryServerServiceProvider extends ServiceProvider
{
    /**
     * Module namespace
     */
    protected string $moduleName = 'VPTelemetryServer';
    protected string $moduleNameLower = 'vptelemetryserver';

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        
        // Load views
        $viewPath = __DIR__ . '/resources/views';
        $this->loadViewsFrom($viewPath, $this->moduleNameLower);
        
        // Register API routes
        $this->registerApiRoutes();
        
        // Register Filament resources
        $this->registerFilamentResources();
        
        // Register Livewire components
        $this->registerLivewireComponents();
        
        Log::info('[VPTelemetryServer] Module booted successfully');
    }

    /**
     * Register the service provider
     */
    public function register(): void
    {
        // Register configuration
        $this->mergeConfigFrom(
            __DIR__ . '/config/telemetry-server.php', 'telemetry-server'
        );
        
        Log::info('[VPTelemetryServer] Service registered');
    }

    /**
     * Register API routes
     */
    protected function registerApiRoutes(): void
    {
        Route::prefix('api/v1/telemetry')
            ->middleware(['api', 'throttle:60,1']) // Max 60 requests per minute
            ->group(__DIR__ . '/routes/api.php');
    }

    /**
     * Register Filament resources
     */
    protected function registerFilamentResources(): void
    {
        // Filament v3 auto-discovers resources and pages from module directories
        // No manual registration needed - Filament will find them via namespace
        
        Log::info('[VPTelemetryServer] Filament resources registered for auto-discovery');
    }
    
    /**
     * Register Livewire components for Filament widgets
     */
    protected function registerLivewireComponents(): void
    {
        // Register Filament widgets as Livewire components
        Livewire::component('modules.v-p-telemetry-server.filament.widgets.telemetry-stats-overview', 
            \Modules\VPTelemetryServer\Filament\Widgets\TelemetryStatsOverview::class);
        
        Livewire::component('modules.v-p-telemetry-server.filament.widgets.installations-timeline-chart', 
            \Modules\VPTelemetryServer\Filament\Widgets\InstallationsTimelineChart::class);
        
        Livewire::component('modules.v-p-telemetry-server.filament.widgets.modules-chart', 
            \Modules\VPTelemetryServer\Filament\Widgets\ModulesChart::class);
        
        Livewire::component('modules.v-p-telemetry-server.filament.widgets.themes-chart', 
            \Modules\VPTelemetryServer\Filament\Widgets\ThemesChart::class);
        
        Livewire::component('modules.v-p-telemetry-server.filament.widgets.php-versions-chart', 
            \Modules\VPTelemetryServer\Filament\Widgets\PhpVersionsChart::class);
        
        Log::info('[VPTelemetryServer] Livewire components registered');
    }
}
