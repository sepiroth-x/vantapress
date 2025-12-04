<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Media;
use App\Models\User;
use App\Models\Module;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            return [
                Stat::make('Total Pages', Page::count())
                    ->description('Published: ' . Page::where('status', 'published')->count())
                    ->descriptionIcon('heroicon-o-document-text')
                    ->color('success')
                    ->chart([7, 12, 15, 14, 18, 22, 24]),
                
                Stat::make('Media Files', Media::count())
                    ->description('Total size: ' . $this->formatBytes(Media::sum('size')))
                    ->descriptionIcon('heroicon-o-photo')
                    ->color('info')
                    ->chart([5, 8, 12, 15, 18, 20, 24]),
                
                Stat::make('Users', User::where('is_active', true)->count())
                    ->description('Total: ' . User::count())
                    ->descriptionIcon('heroicon-o-users')
                    ->color('warning')
                    ->chart([10, 12, 15, 14, 16, 18, 20]),
                
                Stat::make('Active Modules', Module::where('is_enabled', true)->count())
                    ->description('Total: ' . Module::count())
                    ->descriptionIcon('heroicon-o-puzzle-piece')
                    ->color('primary')
                    ->chart([2, 3, 3, 4, 5, 6, 7]),
            ];
        } catch (\Exception $e) {
            // Return empty stats if database tables don't exist yet
            return [
                Stat::make('System Status', 'Initializing')
                    ->description('Database setup in progress')
                    ->descriptionIcon('heroicon-o-cog')
                    ->color('warning'),
            ];
        }
    }
    
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
