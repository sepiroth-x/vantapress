<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Discover module Filament pages (disabled for performance)
        $modulePages = [];

        // Load theme colors dynamically
        $colors = $this->getThemeColors();

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors($colors)
            ->darkMode(true) // Enable dark mode toggle (system, light, dark)
            ->font('Inter')
            ->brandLogo(asset('images/vantapress-logo.svg'))
            ->favicon(asset('images/favicon.ico'))
            ->sidebarCollapsibleOnDesktop() // Allow sidebar collapse on desktop
            ->sidebarWidth('16rem') // Set sidebar width (256px)
            ->maxContentWidth('full') // Full width content area
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                function (): string {
                    $themeManager = app(\App\Services\CMS\ThemeManager::class);
                    $activeTheme = $themeManager->getActiveTheme();
                    
                    // Load theme CSS
                    $version = config('version.version', '1.0.21');
                    $rootAdminCss = asset('css/vantapress-admin.css') . '?v=' . $version;
                    $themeAdminCss = asset("css/themes/{$activeTheme}/admin.css") . '?v=' . $version;
                    
                    // CRITICAL: Generate actual CSS from Filament's registered colors
                    // This makes the colors VISIBLE without Vite/Tailwind compilation
                    $colorCSS = $this->generateColorCSS();
                    
                    return '<link rel="stylesheet" href="' . $rootAdminCss . '">' .
                           '<link rel="stylesheet" href="' . $themeAdminCss . '">' .
                           '<style>' . $colorCSS . '</style>';
                }
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): string => view('filament.footer')->render()
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                function (): string {
                    try {
                        // Only show terminal for authenticated super admin users
                        if (!auth()->check()) {
                            return '';
                        }
                        
                        $user = auth()->user();
                        
                        // Check if user has super-admin role (highest level access)
                        if (!$user->hasRole('super-admin')) {
                            return '';
                        }
                        
                        // Check if TheVillainTerminal module is enabled
                        if (\Schema::hasTable('modules')) {
                            $module = \App\Models\Module::where('slug', 'TheVillainTerminal')->first();
                            if ($module && $module->is_enabled) {
                                // Use inline view to avoid Livewire dependencies
                                $username = $user->name ?? 'admin';
                                $prompt = $username . '@vantapress:~$ ';
                                
                                return view('thevillainterrminal::livewire.floating-terminal', compact('username', 'prompt'))->render();
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('[AdminPanelProvider] Error loading terminal widget: ' . $e->getMessage());
                    }
                    return '';
                }
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                \Modules\VPToDoList\Filament\Resources\ProjectResource::class,
                \Modules\VPToDoList\Filament\Resources\TaskResource::class,
            ])
            ->navigationGroups([
                'To Do List',
                'Content',
                'Appearance',
                'Modules',
                'Administration',
                'System',
                'Updates',
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages($this->safelyMergePages($modulePages))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\AttributionWidget::class,
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\CheckPendingMigrations::class,
            ])
            ->brandName('VantaPress');
    }

    /**
     * Discover Filament pages from active modules
     */
    protected function discoverModulePages(): array
    {
        $pages = [];
        
        try {
            // Check if database is available
            if (!\Schema::hasTable('modules')) {
                \Log::warning('[AdminPanelProvider] Modules table not found, skipping page discovery');
                return [];
            }
            
            // Get enabled modules from database
            $enabledModules = \App\Models\Module::where('is_enabled', true)->get();
            
            \Log::info('[AdminPanelProvider] Discovering module pages', [
                'enabled_modules_count' => $enabledModules->count()
            ]);
            
            foreach ($enabledModules as $module) {
                $modulePath = base_path('Modules/' . $module->slug);
                $metadataPath = $modulePath . '/module.json';
                
                if (!file_exists($metadataPath)) {
                    \Log::warning('[AdminPanelProvider] Module metadata not found', [
                        'module' => $module->slug,
                        'path' => $metadataPath
                    ]);
                    continue;
                }
                
                $metadata = json_decode(file_get_contents($metadataPath), true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('[AdminPanelProvider] Invalid JSON in module metadata', [
                        'module' => $module->slug,
                        'error' => json_last_error_msg()
                    ]);
                    continue;
                }
                
                // Check if module provides Filament pages
                if (isset($metadata['provides']['filament_pages'])) {
                    foreach ($metadata['provides']['filament_pages'] as $pageClass) {
                        if (!class_exists($pageClass)) {
                            \Log::warning('[AdminPanelProvider] Page class not found', [
                                'module' => $module->slug,
                                'class' => $pageClass
                            ]);
                            continue;
                        }
                        
                        $pages[] = $pageClass;
                        \Log::info('[AdminPanelProvider] Registered module page', [
                            'module' => $module->slug,
                            'page' => $pageClass
                        ]);
                    }
                }
            }
            
            \Log::info('[AdminPanelProvider] Total pages registered', [
                'count' => count($pages)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('[AdminPanelProvider] Failed to discover module pages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $pages;
    }
    
    /**
     * Safely merge pages and log any issues
     */
    protected function safelyMergePages(array $modulePages): array
    {
        $pages = [Pages\Dashboard::class];
        
        foreach ($modulePages as $pageClass) {
            try {
                // Test if class can be instantiated
                if (class_exists($pageClass)) {
                    $pages[] = $pageClass;
                    \Log::info('[AdminPanelProvider] Added page to final array', [
                        'class' => $pageClass
                    ]);
                } else {
                    \Log::warning('[AdminPanelProvider] Page class does not exist', [
                        'class' => $pageClass
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('[AdminPanelProvider] Error adding page', [
                    'class' => $pageClass,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $pages;
    }
    
    /**
     * Get theme colors for admin panel
     * Reads from active theme's theme.json admin_colors section
     */
    protected function getThemeColors(): array
    {
        try {
            $themeManager = app(\App\Services\CMS\ThemeManager::class);
            $activeTheme = $themeManager->getActiveTheme();
            $themePath = base_path("themes/{$activeTheme}/theme.json");
            
            if (file_exists($themePath)) {
                $themeData = json_decode(file_get_contents($themePath), true);
                
                if (isset($themeData['admin_colors']) && is_array($themeData['admin_colors'])) {
                    $adminColors = $themeData['admin_colors'];
                    
                    // Map theme color names to Filament Color classes
                    $colorMap = [];
                    foreach ($adminColors as $key => $colorName) {
                        $colorMap[$key] = $this->getFilamentColor($colorName);
                    }
                    
                    return $colorMap;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('[AdminPanelProvider] Failed to load theme colors, using defaults', [
                'error' => $e->getMessage()
            ]);
        }
        
        // Fallback to default colors
        return [
            'primary' => Color::Blue,
            'danger' => Color::Red,
            'gray' => Color::Slate,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
        ];
    }
    
    /**
     * Convert color name to Filament Color class
     */
    protected function getFilamentColor(string $colorName)
    {
        $colorName = strtolower($colorName);
        
        return match($colorName) {
            'slate' => Color::Slate,
            'gray' => Color::Gray,
            'zinc' => Color::Zinc,
            'neutral' => Color::Neutral,
            'stone' => Color::Stone,
            'red' => Color::Red,
            'orange' => Color::Orange,
            'amber' => Color::Amber,
            'yellow' => Color::Yellow,
            'lime' => Color::Lime,
            'green' => Color::Green,
            'emerald' => Color::Emerald,
            'teal' => Color::Teal,
            'cyan' => Color::Cyan,
            'sky' => Color::Sky,
            'blue' => Color::Blue,
            'indigo' => Color::Indigo,
            'violet' => Color::Violet,
            'purple' => Color::Purple,
            'fuchsia' => Color::Fuchsia,
            'pink' => Color::Pink,
            'rose' => Color::Rose,
            default => Color::Blue,
        };
    }
    
    /**
     * Generate actual CSS color definitions from Filament's registered colors
     * This runs on EVERY request and makes colors VISIBLE without Vite
     * 
     * Uses AGGRESSIVE selectors to override Filament's Tailwind classes
     */
    protected function generateColorCSS(): string
    {
        try {
            $panel = \Filament\Facades\Filament::getPanel('admin');
            $colors = $panel->getColors();
            
            if (empty($colors)) {
                return '';
            }
            
            $css = "/* Dynamic Theme Colors - Generated from PHP */\n:root {\n";
            
            // Generate CSS custom properties
            foreach ($colors as $name => $shades) {
                if (!is_array($shades)) continue;
                
                foreach ($shades as $shade => $rgb) {
                    $css .= "    --{$name}-{$shade}: {$rgb};\n";
                    // Also generate color-only variables (needed for Tailwind classes)
                    $css .= "    --color-{$name}-{$shade}: {$rgb};\n";
                }
            }
            
            $css .= "}\n\n";
            
            // AGGRESSIVE overrides for ALL Tailwind/Filament color classes
            foreach ($colors as $name => $shades) {
                if (!is_array($shades)) continue;
                
                // Override all bg-{color}-{shade} classes
                foreach ($shades as $shade => $rgb) {
                    $css .= ".bg-{$name}-{$shade} { background-color: rgb({$rgb}) !important; }\n";
                    $css .= ".hover\\:bg-{$name}-{$shade}:hover { background-color: rgb({$rgb}) !important; }\n";
                    $css .= ".text-{$name}-{$shade} { color: rgb({$rgb}) !important; }\n";
                    $css .= ".border-{$name}-{$shade} { border-color: rgb({$rgb}) !important; }\n";
                    $css .= ".ring-{$name}-{$shade} { --tw-ring-color: rgb({$rgb}) !important; }\n";
                }
                
                // Dark mode variants
                foreach ($shades as $shade => $rgb) {
                    $css .= ".dark .dark\\:bg-{$name}-{$shade} { background-color: rgb({$rgb}) !important; }\n";
                    $css .= ".dark .dark\\:text-{$name}-{$shade} { color: rgb({$rgb}) !important; }\n";
                    $css .= ".dark .dark\\:border-{$name}-{$shade} { border-color: rgb({$rgb}) !important; }\n";
                }
            }
            
            return $css;
            
        } catch (\Exception $e) {
            \Log::error('[AdminPanelProvider] Failed to generate color CSS', [
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }
}

