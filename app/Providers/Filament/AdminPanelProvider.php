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

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Rgb('rgb(212, 0, 38)'), // Crimson Villain
                'gray' => Color::Rgb('rgb(42, 42, 46)'),    // Ghost Gray
                'success' => Color::Rgb('rgb(50, 210, 124)'), // Success Green
                'danger' => Color::Rgb('rgb(255, 74, 74)'),   // Error Red
                'warning' => Color::Rgb('rgb(239, 179, 54)'), // Warning Gold
                'info' => Color::Rgb('rgb(62, 132, 248)'),    // Info Blue
            ])
            ->darkMode(true)
            ->font('Inter')
            ->brandLogo(asset('images/vantapress-logo.svg'))
            ->favicon(asset('images/favicon.ico'))
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth('full')
            ->viteTheme('resources/css/filament/admin/theme.css')
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
}

