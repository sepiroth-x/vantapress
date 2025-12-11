<?php

namespace Modules\VPTelemetryServer\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\VPTelemetryServer\Models\Installation;
use Modules\VPTelemetryServer\Filament\Resources\InstallationResource\Pages;

class InstallationResource extends Resource
{
    protected static ?string $model = Installation::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';

    protected static ?string $navigationLabel = 'Installations';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Installation Details')
                    ->schema([
                        Forms\Components\TextInput::make('installation_id')
                            ->label('Installation ID')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('domain')
                            ->label('Domain')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('ip')
                            ->label('IP Address')
                            ->disabled(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('System Information')
                    ->schema([
                        Forms\Components\TextInput::make('version')
                            ->label('VantaPress Version')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('php_version')
                            ->label('PHP Version')
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('server_os')
                            ->label('Server OS')
                            ->disabled(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Timestamps')
                    ->schema([
                        Forms\Components\DateTimePicker::make('installed_at')
                            ->label('Installed At')
                            ->disabled(),
                        
                        Forms\Components\DateTimePicker::make('last_ping_at')
                            ->label('Last Ping')
                            ->disabled(),
                        
                        Forms\Components\DateTimePicker::make('updated_at_version')
                            ->label('Last Version Update')
                            ->disabled(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain')
                    ->label('Domain')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('php_version')
                    ->label('PHP')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->isActive())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('modules_count')
                    ->label('Modules')
                    ->counts('modules')
                    ->sortable(),

                Tables\Columns\TextColumn::make('themes_count')
                    ->label('Themes')
                    ->counts('themes')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_ping_at')
                    ->label('Last Ping')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('installed_at')
                    ->label('Installed')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('version')
                    ->label('Version')
                    ->options(fn () => Installation::distinct()->pluck('version', 'version')->toArray()),

                Tables\Filters\SelectFilter::make('php_version')
                    ->label('PHP Version')
                    ->options(fn () => Installation::distinct()->pluck('php_version', 'php_version')->toArray()),

                Tables\Filters\Filter::make('active')
                    ->label('Active Only')
                    ->query(fn ($query) => $query->active()),

                Tables\Filters\Filter::make('inactive')
                    ->label('Inactive Only')
                    ->query(fn ($query) => $query->inactive()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('last_ping_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstallations::route('/'),
            'view' => Pages\ViewInstallation::route('/{record}'),
        ];
    }
}
