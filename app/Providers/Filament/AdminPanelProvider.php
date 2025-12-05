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
        $modulePages = $this->discoverModulePages();

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => [
                    50 => '#ffe5ea',
                    100 => '#ffc7d2',
                    200 => '#ff8fa5',
                    300 => '#ff5778',
                    400 => '#ff1f4b',
                    500 => '#D40026',
                    600 => '#b0001f',
                    700 => '#8c0018',
                    800 => '#680012',
                    900 => '#44000c',
                    950 => '#200006',
                ],
                'danger' => Color::Red,
                'gray' => [
                    50 => '#f5f5f6',
                    100 => '#e8e9ea',
                    200 => '#d1d2d4',
                    300 => '#babcbf',
                    400 => '#a3a5a9',
                    500 => '#888A8F',
                    600 => '#6d6f73',
                    700 => '#525457',
                    800 => '#36383a',
                    900 => '#1b1c1e',
                    950 => '#0A0A0A',
                ],
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Orange,
            ])
            ->darkMode(true) // Enable dark mode toggle
            ->font('Inter')
            ->brandLogo(asset('images/vantapress-logo.svg'))
            ->favicon(asset('images/favicon.ico'))
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
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): string => '<script src="' . asset('js/filament/filament/app.js') . '?v=3.3.45"></script>'
            )
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => '<script>
                    // Force dark mode for BasicTheme styling
                    localStorage.setItem("theme", "dark");
                    document.documentElement.classList.add("dark");
                </script>'
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): string => view('filament.footer')->render()
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
                'Updates',
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages(array_merge(
                [Pages\Dashboard::class],
                $modulePages
            ))
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
            ])
            ->brandName('VantaPress');
    }

    /**
     * Discover Filament pages from active modules
     */
    protected function discoverModulePages(): array
    {
        $pages = [];
        $moduleLoader = app(\App\Services\ModuleLoader::class);
        $modules = $moduleLoader->getModules();

        foreach ($modules as $moduleName => $metadata) {
            // Only load pages from active modules
            if (!($metadata['active'] ?? false)) {
                continue;
            }

            // Check if module provides Filament pages
            if (isset($metadata['provides']['filament_pages'])) {
                foreach ($metadata['provides']['filament_pages'] as $pageClass) {
                    if (class_exists($pageClass)) {
                        $pages[] = $pageClass;
                    }
                }
            }
        }

        return $pages;
    }
}
