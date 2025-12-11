<?php

namespace Modules\TheVillainTerminal;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Modules\TheVillainTerminal\Services\CommandRegistry;
use Modules\TheVillainTerminal\Commands\MigrateCommand;
use Modules\TheVillainTerminal\Commands\SystemInfoCommand;
use Modules\TheVillainTerminal\Commands\ThemeLayoutCommand;
use Modules\TheVillainTerminal\Commands\HelpCommand;
use Modules\TheVillainTerminal\Commands\FileSystemCommand;

/**
 * The Villain Terminal - Service Provider
 * 
 * Registers the terminal module and all built-in commands.
 */
class TheVillainTerminalServiceProvider extends ServiceProvider
{
    /**
     * Module namespace
     */
    protected string $moduleName = 'TheVillainTerminal';
    protected string $moduleNameLower = 'thevillainterrminal';

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
        $this->registerViews();
        $this->registerCommands();
        $this->registerLivewireComponents();
        $this->registerFilamentPages();

        Log::info('[Villain Terminal] Module loaded successfully');
    }

    /**
     * Register Livewire components
     */
    protected function registerLivewireComponents(): void
    {
        \Livewire\Livewire::component('floating-terminal', \Modules\TheVillainTerminal\Livewire\FloatingTerminal::class);
    }

    /**
     * Register the service provider
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register views
     */
    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = base_path("Modules/{$this->moduleName}/resources/views");

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register built-in terminal commands
     */
    protected function registerCommands(): void
    {
        // Migration command
        CommandRegistry::register(
            'vanta-migrate',
            MigrateCommand::class . '@handle',
            'Run database migrations (core and modules)'
        );

        // System info commands
        CommandRegistry::register(
            'vanta-system-info',
            SystemInfoCommand::class . '@systemInfo',
            'Display complete system information'
        );

        CommandRegistry::register(
            'vanta-php-version',
            SystemInfoCommand::class . '@phpVersion',
            'Display PHP version and loaded extensions'
        );

        CommandRegistry::register(
            'vanta-filament-version',
            SystemInfoCommand::class . '@filamentVersion',
            'Display Filament version and features'
        );

        CommandRegistry::register(
            'vanta-version',
            SystemInfoCommand::class . '@vantaVersion',
            'Display VantaPress version'
        );

        // Theme layout commands
        CommandRegistry::register(
            'vanta-make-theme-layout',
            ThemeLayoutCommand::class . '@makeLayout',
            'Create a new theme layout structure'
        );

        CommandRegistry::register(
            'vanta-export-layout',
            ThemeLayoutCommand::class . '@exportLayout',
            'Export theme layout as ZIP archive'
        );

        // Help command
        CommandRegistry::register(
            'vanta-help',
            HelpCommand::class . '@handle',
            'Display available commands and help information'
        );

        // Unix-like file system commands
        CommandRegistry::register(
            'pwd',
            FileSystemCommand::class . '@pwd',
            'Print working directory'
        );

        CommandRegistry::register(
            'cd',
            FileSystemCommand::class . '@cd',
            'Change directory'
        );

        CommandRegistry::register(
            'ls',
            FileSystemCommand::class . '@ls',
            'List directory contents (-l for long format, -a for all)'
        );

        CommandRegistry::register(
            'mkdir',
            FileSystemCommand::class . '@mkdir',
            'Create directory (simulated)'
        );

        CommandRegistry::register(
            'rmdir',
            FileSystemCommand::class . '@rmdir',
            'Remove directory (simulated)'
        );

        CommandRegistry::register(
            'touch',
            FileSystemCommand::class . '@touch',
            'Create empty file (simulated)'
        );

        CommandRegistry::register(
            'rm',
            FileSystemCommand::class . '@rm',
            'Remove file (simulated)'
        );

        CommandRegistry::register(
            'cat',
            FileSystemCommand::class . '@cat',
            'Display file contents'
        );

        // Aliases
        CommandRegistry::alias('vanta-h', 'vanta-help');
        CommandRegistry::alias('vanta-m', 'vanta-migrate');
        CommandRegistry::alias('vanta-info', 'vanta-system-info');

        Log::info('[Villain Terminal] Registered ' . count(CommandRegistry::all()) . ' commands');
    }

    /**
     * Register Filament pages
     */
    protected function registerFilamentPages(): void
    {
        // Register the terminal page with Filament panel
        try {
            $panel = \Filament\Facades\Filament::getPanel('admin');
            $panel->pages([
                \Modules\TheVillainTerminal\Filament\Pages\VillainTerminal::class
            ]);
            Log::info('[Villain Terminal] Filament page registered with panel');
        } catch (\Exception $e) {
            Log::error('[Villain Terminal] Failed to register Filament page', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get publishable view paths
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach ($this->app['config']->get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    /**
     * Get the services provided by the provider
     */
    public function provides(): array
    {
        return [];
    }
}
