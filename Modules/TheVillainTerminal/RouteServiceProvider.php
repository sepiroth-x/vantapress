<?php

namespace Modules\TheVillainTerminal;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * The Villain Terminal - Route Service Provider
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace
     */
    protected string $moduleNamespace = 'Modules\TheVillainTerminal\Http\Controllers';

    /**
     * Called before routes are registered
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application
     */
    public function map(): void
    {
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the module
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(base_path('Modules/TheVillainTerminal/routes/web.php'));
    }
}
