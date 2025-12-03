<?php

use Illuminate\Support\Facades\Route;
use App\Models\Theme;
use App\Services\CMS\ThemeManager;

Route::get('/', function (ThemeManager $themeManager) {
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
})->name('home');

Route::get('/old-landing', function () {
    return view('landing');
})->name('old.landing');

// Additional routes will be registered by modules

Route::get('/livewire-test', function() { return view('livewire-test'); });
