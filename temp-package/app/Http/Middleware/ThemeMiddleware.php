<?php
/**
 * TCC School CMS - Theme Middleware
 * 
 * Middleware to load and apply the active theme for frontend requests.
 * Registers theme views, assets, and blade overrides.
 * 
 * @package TCC_School_CMS
 * @subpackage Http\Middleware
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * @version 1.0.0
 * @license Commercial / Paid
 * 
 * Copyright (c) 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * All Rights Reserved.
 * 
 * Contact Information:
 * Email: chardy.tsadiq02@gmail.com
 * Mobile: +63 915 0388 448
 * 
 * This software is proprietary and confidential. Unauthorized copying,
 * modification, distribution, or use of this software, via any medium,
 * is strictly prohibited without explicit written permission from the author.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CMS\ThemeManager;
use App\Models\Theme;

class ThemeMiddleware
{
    protected ThemeManager $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip theme loading for admin panel routes
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        try {
            // Check for theme preview mode (for customizer)
            $previewTheme = $request->query('theme_preview');
            
            if ($previewTheme) {
                // Load preview theme instead of active theme
                $this->loadPreviewTheme($previewTheme);
            } else {
                // Get active theme from database
                $activeTheme = Theme::where('is_active', true)->first();
                
                if ($activeTheme) {
                    // Load active theme from database
                    $this->themeManager->loadTheme($activeTheme->slug);
                } else {
                    // Load default theme
                    $this->themeManager->loadTheme();
                }
            }

            // Share theme data with views
            view()->share('active_theme', $this->themeManager->getActiveTheme());
            view()->share('theme_config', $this->themeManager->getThemeConfig($this->themeManager->getActiveTheme()));
        } catch (\Exception $e) {
            // If themes table doesn't exist or any other error, load default theme
            try {
                $this->themeManager->loadTheme();
                view()->share('active_theme', $this->themeManager->getActiveTheme());
                view()->share('theme_config', []);
            } catch (\Exception $fallbackError) {
                // Silently fail and continue - theme will use defaults
                view()->share('active_theme', 'default');
                view()->share('theme_config', []);
            }
        }

        return $next($request);
    }
    
    /**
     * Load a theme for preview without activating it
     */
    protected function loadPreviewTheme(string $themeSlug): void
    {
        $themePath = base_path('themes/' . $themeSlug);
        
        if (is_dir($themePath)) {
            // Register theme views
            $viewsPath = $themePath . '/views';
            if (is_dir($viewsPath)) {
                view()->addLocation($viewsPath);
            }
            
            // Share preview theme data
            view()->share('active_theme', $themeSlug);
            view()->share('is_preview', true);
        }
    }
}
