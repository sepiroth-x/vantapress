<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set public path to base directory (root-level structure)
        // This prevents Filament from creating a public/ folder
        $this->app->bind('path.public', function() {
            return base_path();
        });
    }
}
