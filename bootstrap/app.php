<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
        \App\Providers\CMSServiceProvider::class, // Module & Theme loader
        \App\Providers\FilesystemServiceProvider::class,
        \App\Providers\Filament\AdminPanelProvider::class,
        \Modules\VPEssential1\VPEssential1ServiceProvider::class,
        \Modules\VPToDoList\VPToDoListServiceProvider::class,
        \Modules\HelloWorld\HelloWorldServiceProvider::class,
        \Modules\TheVillainTerminal\TheVillainTerminalServiceProvider::class,
        \Modules\VPTelemetryServer\VPTelemetryServerServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleFilamentErrors::class,
            \App\Http\Middleware\ThemeMiddleware::class,
        ]);

        $middleware->alias([
            'module' => \App\Http\Middleware\ModuleMiddleware::class,
            'vpsocial' => \App\Http\Middleware\VPSocialThemeRequired::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Suppress PHP 8.5 deprecation warnings for vendor packages
        $exceptions->dontReport([
            \ErrorException::class,
        ]);
        
        // Report deprecations to log but don't interrupt execution
        $exceptions->reportable(function (\Throwable $e) {
            if (str_contains($e->getMessage(), 'null as an array offset')) {
                // Silently log this common PHP 8.5 deprecation from vendor packages
                return false;
            }
        });
    })
    ->create();
