<?php

namespace Modules\VPTelemetryServer\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\VPTelemetryServer\Models\InstallationModule;

/**
 * Modules Chart Widget
 * 
 * Shows most popular modules
 */
class ModulesChart extends ChartWidget
{
    protected static ?string $heading = 'Most Used Modules';
    
    protected static ?string $description = 'Module popularity across installations';

    protected function getData(): array
    {
        // Get top 10 most used modules
        $modules = InstallationModule::selectRaw('module_name, COUNT(*) as count')
            ->groupBy('module_name')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Installations',
                    'data' => $modules->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b',
                        '#10b981', '#6366f1', '#f43f5e', '#14b8a6',
                        '#a855f7', '#ef4444',
                    ],
                ],
            ],
            'labels' => $modules->pluck('module_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
