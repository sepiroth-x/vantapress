<?php

namespace Modules\VPTelemetryServer\Filament\Resources\InstallationResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Modules\VPTelemetryServer\Filament\Resources\InstallationResource;

class ViewInstallation extends ViewRecord
{
    protected static string $resource = InstallationResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Installation Overview')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('installation_id')
                                    ->label('Installation ID')
                                    ->copyable(),
                                
                                Infolists\Components\TextEntry::make('domain')
                                    ->label('Domain')
                                    ->url(fn ($record) => 'https://' . $record->domain, shouldOpenInNewTab: true),
                                
                                Infolists\Components\TextEntry::make('ip')
                                    ->label('IP Address'),
                            ]),
                    ]),

                Infolists\Components\Section::make('System Information')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('version')
                                    ->label('VantaPress Version')
                                    ->badge()
                                    ->color('success'),
                                
                                Infolists\Components\TextEntry::make('php_version')
                                    ->label('PHP Version')
                                    ->badge()
                                    ->color('info'),
                                
                                Infolists\Components\TextEntry::make('server_os')
                                    ->label('Server OS')
                                    ->badge()
                                    ->color('warning'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Enabled Modules')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('modules')
                            ->schema([
                                Infolists\Components\TextEntry::make('module_name')
                                    ->label(false)
                                    ->badge()
                                    ->color('primary'),
                            ])
                            ->columns(4),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Enabled Themes')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('themes')
                            ->schema([
                                Infolists\Components\TextEntry::make('theme_name')
                                    ->label(false)
                                    ->badge()
                                    ->color('secondary'),
                            ])
                            ->columns(4),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Timeline')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('installed_at')
                                    ->label('Installed At')
                                    ->dateTime(),
                                
                                Infolists\Components\TextEntry::make('last_ping_at')
                                    ->label('Last Ping')
                                    ->dateTime()
                                    ->badge()
                                    ->color(fn ($record) => $record->isActive() ? 'success' : 'danger'),
                                
                                Infolists\Components\TextEntry::make('updated_at_version')
                                    ->label('Last Version Update')
                                    ->dateTime()
                                    ->placeholder('Never updated'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Recent Telemetry Logs')
                    ->description('Last 10 telemetry events')
                    ->schema([
                        Infolists\Components\ViewEntry::make('logs')
                            ->view('vptelemetryserver::infolists.components.recent-logs'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
