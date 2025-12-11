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
        // Discover module Filament pages
        // $modulePages = $this->discoverModulePages();
        $modulePages = []; // Temporarily disabled to fix Page error
        
        \Log::info('[AdminPanelProvider] Module pages to register', [
            'count' => count($modulePages),
            'pages' => $modulePages
        ]);
        
        // Log each page class details
        foreach ($modulePages as $pageClass) {
            try {
                \Log::info('[AdminPanelProvider] Page class details', [
                    'class' => $pageClass,
                    'exists' => class_exists($pageClass),
                    'slug' => class_exists($pageClass) ? $pageClass::getSlug() : 'N/A',
                    'label' => class_exists($pageClass) ? $pageClass::getNavigationLabel() : 'N/A',
                    'group' => class_exists($pageClass) ? $pageClass::getNavigationGroup() : 'N/A',
                ]);
            } catch (\Exception $e) {
                \Log::error('[AdminPanelProvider] Error getting page details', [
                    'class' => $pageClass,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue, // Use Filament's built-in Blue palette
                'danger' => Color::Red,
                'gray' => Color::Slate, // Use Filament's Slate for modern dark mode
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
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
                    
                    // Load root admin CSS first, then theme-specific CSS
                    // Theme assets are copied to css/themes/ directory for root-level access
                    $version = config('version.version', '1.0.21');
                    $rootAdminCss = asset('css/vantapress-admin.css') . '?v=' . $version;
                    $themeAdminCss = asset("css/themes/{$activeTheme}/admin.css") . '?v=' . $version;
                    
                    return '<link rel="stylesheet" href="' . $rootAdminCss . '">' .
                           '<link rel="stylesheet" href="' . $themeAdminCss . '">';
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
                \Modules\VPTelemetryServer\Filament\Resources\InstallationResource::class,
            ])
            ->navigationGroups([
                'To Do List',
                'Analytics',
                'Content',
                'Appearance',
                'Modules',
                'Administration',
                'System',
                'Updates',
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages(array_merge($this->safelyMergePages($modulePages), [
                \Modules\VPTelemetryServer\Filament\Pages\TelemetryDashboard::class,
                \Modules\VPTelemetry\Filament\Pages\TelemetrySettings::class,
            ]))
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
        
        \Log::info('[AdminPanelProvider] Final pages array', [
            'count' => count($pages),
            'pages' => $pages
        ]);
        
        return $pages;
    }
}
