<?php

namespace Modules\VPTelemetry;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Modules\VPTelemetry\Services\TelemetryService;

/**
 * VantaPress Telemetry Module - Service Provider
 * 
 * Registers anonymous telemetry collection system.
 * Helps developers understand VantaPress usage patterns.
 * 
 * Privacy: NO personal data collected (no emails, usernames, passwords, or content)
 * Control: Users can disable telemetry anytime in Settings
 */
class VPTelemetryServiceProvider extends ServiceProvider
{
    /**
     * Module namespace
     */
    protected string $moduleName = 'VPTelemetry';
    protected string $moduleNameLower = 'vptelemetry';

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
        $this->loadViewsFrom(__DIR__ . '/resources/views', $this->moduleNameLower);
        
        // Register event listeners
        $this->registerEventListeners();
        
        // Register scheduled tasks
        $this->registerScheduledTasks();
        
        Log::info('[VPTelemetry] Module booted successfully');
    }

    /**
     * Register the service provider
     */
    public function register(): void
    {
        // Register telemetry service as singleton
        $this->app->singleton(TelemetryService::class, function ($app) {
            return new TelemetryService();
        });
        
        Log::info('[VPTelemetry] Service registered');
    }

    /**
     * Register event listeners for telemetry triggers
     */
    protected function registerEventListeners(): void
    {
        // Listen for installation complete event
        \Event::listen('vantapress.installed', function () {
            if (config('telemetry.enabled', true)) {
                app(TelemetryService::class)->sendInstallationPing();
            }
        });

        // Listen for update complete event
        \Event::listen('vantapress.updated', function () {
            if (config('telemetry.enabled', true)) {
                app(TelemetryService::class)->sendUpdatePing();
            }
        });

        // Listen for module enable/disable
        \Event::listen('vantapress.module.toggled', function () {
            if (config('telemetry.enabled', true)) {
                app(TelemetryService::class)->sendModuleChangePing();
            }
        });
    }

    /**
     * Register scheduled tasks
     */
    protected function registerScheduledTasks(): void
    {
        // Daily heartbeat ping
        $this->app->booted(function () {
            $schedule = app(\Illuminate\Console\Scheduling\Schedule::class);
            
            $schedule->call(function () {
                if (config('telemetry.enabled', true)) {
                    app(TelemetryService::class)->sendDailyHeartbeat();
                }
            })->daily()->name('vantapress-telemetry-heartbeat');
        });
    }

    /**
     * Get the services provided by the provider
     */
    public function provides(): array
    {
        return [TelemetryService::class];
    }
}
