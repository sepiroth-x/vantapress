<?php

namespace Modules\VPTelemetryServer\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\VPTelemetryServer\Models\Installation;
use Modules\VPTelemetryServer\Models\TelemetryLog;

/**
 * Telemetry Stats Overview Widget
 * 
 * Displays key metrics about installations
 */
class TelemetryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalInstallations = Installation::count();
        $activeInstallations = Installation::active()->count();
        $newThisWeek = Installation::where('installed_at', '>', now()->subWeek())->count();
        $totalPingsToday = TelemetryLog::whereDate('created_at', today())->count();

        // Calculate trend for active installations (compared to last week)
        $activeLastWeek = Installation::where('last_ping_at', '>', now()->subWeeks(2))
            ->where('last_ping_at', '<', now()->subWeek())
            ->count();
        
        $activeTrend = $activeLastWeek > 0 
            ? round((($activeInstallations - $activeLastWeek) / $activeLastWeek) * 100, 1)
            : 0;

        return [
            Stat::make('Total Installations', $totalInstallations)
                ->description('All time')
                ->descriptionIcon('heroicon-o-server')
                ->color('primary'),

            Stat::make('Active Installations', $activeInstallations)
                ->description($activeTrend >= 0 ? "+{$activeTrend}% from last week" : "{$activeTrend}% from last week")
                ->descriptionIcon($activeTrend >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($activeTrend >= 0 ? 'success' : 'danger')
                ->chart($this->getActiveInstallationsChart()),

            Stat::make('New This Week', $newThisWeek)
                ->description('Fresh installations')
                ->descriptionIcon('heroicon-o-sparkles')
                ->color('warning'),

            Stat::make('Pings Today', $totalPingsToday)
                ->description('Telemetry events received')
                ->descriptionIcon('heroicon-o-signal')
                ->color('info'),
        ];
    }

    /**
     * Get chart data for active installations over last 7 days
     */
    protected function getActiveInstallationsChart(): array
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Installation::where('last_ping_at', '>', $date->startOfDay())
                ->where('last_ping_at', '<', $date->endOfDay())
                ->count();
            
            $data[] = $count;
        }

        return $data;
    }
}
