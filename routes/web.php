<?php

use Illuminate\Support\Facades\Route;
use App\Models\Theme;
use App\Services\CMS\ThemeManager;

Route::get('/', function (ThemeManager $themeManager) {
    try {
        // Check if database is configured and accessible
        \DB::connection()->getPdo();
        
        // Check if there's an active theme
        $activeTheme = Theme::where('is_active', true)->first();
        
        if ($activeTheme) {
            // Load the theme to register view namespaces
            $themeManager->loadTheme($activeTheme->slug);
            
            // Try to load theme's home page
            $themePath = base_path('themes/' . $activeTheme->slug);
            $homeViewPath = $themePath . '/pages/home.blade.php';
            
            if (file_exists($homeViewPath)) {
                // Theme has a home page, use it
                return view('theme.pages::home');
            }
        }
        
        // Fallback to default welcome page
        return view('welcome');
    } catch (\Exception $e) {
        // Database not configured yet, show welcome page
        return view('welcome');
    }
})->name('home');

Route::get('/old-landing', function () {
    return view('landing');
})->name('old.landing');

// Additional routes will be registered by modules

Route::get('/livewire-test', function() { return view('livewire-test'); });

// Theme Customizer (Full-page, WordPress-style)
Route::middleware(['auth'])->group(function () {
    Route::get('/theme-customizer/{id}', [App\Http\Controllers\ThemeCustomizerController::class, 'show'])
        ->name('theme-customizer.show');
    Route::post('/theme-customizer/{id}/save', [App\Http\Controllers\ThemeCustomizerController::class, 'save'])
        ->name('theme-customizer.save');
    Route::post('/theme-customizer/{id}/activate', [App\Http\Controllers\ThemeCustomizerController::class, 'activate'])
        ->name('theme-customizer.activate');
});

// Catch-all route for dynamic pages (must be last)
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('slug', '.*')
    ->name('page.show');
