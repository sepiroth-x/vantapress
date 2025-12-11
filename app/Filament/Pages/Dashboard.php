<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    public function getHeading(): string
    {
        return 'Welcome to VantaPress';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add your widgets here
        ];
    }
}
