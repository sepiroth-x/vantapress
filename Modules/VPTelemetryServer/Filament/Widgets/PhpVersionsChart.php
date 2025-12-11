<?php

namespace Modules\VPTelemetryServer\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\VPTelemetryServer\Models\Installation;

/**
 * PHP Versions Chart Widget
 * 
 * Shows PHP version distribution
 */
class PhpVersionsChart extends ChartWidget
{
    protected static ?string $heading = 'PHP Version Distribution';
    
    protected static ?string $description = 'Server PHP versions in use';

    protected function getData(): array
    {
        // Get PHP version distribution
        $versions = Installation::selectRaw('php_version, COUNT(*) as count')
            ->groupBy('php_version')
            ->orderByDesc('count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Installations',
                    'data' => $versions->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#10b981', '#3b82f6', '#f59e0b', '#ef4444',
                        '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6',
                    ],
                ],
            ],
            'labels' => $versions->pluck('php_version')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
