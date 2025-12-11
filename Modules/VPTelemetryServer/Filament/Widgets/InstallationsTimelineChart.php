<?php

namespace Modules\VPTelemetryServer\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\VPTelemetryServer\Models\Installation;

/**
 * Installations Timeline Chart Widget
 * 
 * Shows new installations over time
 */
class InstallationsTimelineChart extends ChartWidget
{
    protected static ?string $heading = 'New Installations (Last 30 Days)';
    
    protected static ?string $description = 'Installation growth trend';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Get installations per day for last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            $count = Installation::whereDate('installed_at', $date->toDateString())
                ->count();
            
            $data[] = $count;
            $labels[] = $date->format('M d');
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Installations',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
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
