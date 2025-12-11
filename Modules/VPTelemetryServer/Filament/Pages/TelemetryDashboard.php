<?php

namespace Modules\VPTelemetryServer\Filament\Pages;

use Filament\Pages\Page;
use Modules\VPTelemetryServer\Filament\Widgets\TelemetryStatsOverview;
use Modules\VPTelemetryServer\Filament\Widgets\ModulesChart;
use Modules\VPTelemetryServer\Filament\Widgets\ThemesChart;
use Modules\VPTelemetryServer\Filament\Widgets\PhpVersionsChart;
use Modules\VPTelemetryServer\Filament\Widgets\InstallationsTimelineChart;

/**
 * Telemetry Dashboard
 * 
 * Overview of all VantaPress installations sending telemetry data
 */
class TelemetryDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static string $view = 'vptelemetryserver::filament.pages.telemetry-dashboard';
    
    protected static ?string $navigationLabel = 'Telemetry Dashboard';
    
    protected static ?string $title = 'Telemetry Dashboard';
    
    protected static ?string $navigationGroup = 'Analytics';
    
    protected static ?int $navigationSort = 1;

    /**
     * Get header widgets
     */
    protected function getHeaderWidgets(): array
    {
        return [
            TelemetryStatsOverview::class,
        ];
    }

    /**
     * Get widgets
     */
    protected function getWidgets(): array
    {
        return [
            ModulesChart::class,
            ThemesChart::class,
            PhpVersionsChart::class,
            InstallationsTimelineChart::class,
        ];
    }
}
