<?php

namespace Modules\VPToDoList;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;

class VPToDoListServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Module registration
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        
        // Register Filament resources using Panels
        \Filament\Facades\Filament::getCurrentPanel()?->resources([
            \Modules\VPToDoList\Filament\Resources\ProjectResource::class,
            \Modules\VPToDoList\Filament\Resources\TaskResource::class,
        ]);
    }
}
