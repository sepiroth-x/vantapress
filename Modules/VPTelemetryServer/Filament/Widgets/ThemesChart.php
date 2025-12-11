<?php

namespace Modules\VPTelemetryServer\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\VPTelemetryServer\Models\InstallationTheme;

/**
 * Themes Chart Widget
 * 
 * Shows most popular themes
 */
class ThemesChart extends ChartWidget
{
    protected static ?string $heading = 'Most Used Themes';
    
    protected static ?string $description = 'Theme popularity across installations';

    protected function getData(): array
    {
        // Get top 10 most used themes
        $themes = InstallationTheme::selectRaw('theme_name, COUNT(*) as count')
            ->groupBy('theme_name')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Installations',
                    'data' => $themes->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#8b5cf6', '#ec4899', '#f59e0b', '#10b981',
                        '#3b82f6', '#6366f1', '#f43f5e', '#14b8a6',
                        '#a855f7', '#ef4444',
                    ],
                ],
            ],
            'labels' => $themes->pluck('theme_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
