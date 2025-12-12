<?php

namespace Modules\VPEssential1\Filament\Pages;

use Filament\Pages\Page;

class VPSocialDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'VP Social Dashboard';
    protected static ?string $title = 'VP Social Control Panel';
    protected static ?string $navigationGroup = 'VP Social Admin';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'vpessential1::filament.pages.vp-social-dashboard';
    
    public static function shouldRegisterNavigation(): bool
    {
        // Only show to admin and super-admin
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'super-admin']);
    }
    
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'super-admin']);
    }
    
    public function getWidgets(): array
    {
        return [
            // Widget classes will go here
        ];
    }
}
