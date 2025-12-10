<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use App\Models\Theme;
use App\Services\ThemeInstaller;
use App\Services\ThemeLoader;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListThemes extends ListRecords
{
    protected static string $resource = ThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('install')
                ->label('Install Theme')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Theme File (.zip or .vpt)')
                        ->required()
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->maxSize(51200)
                        ->directory('temp/themes')
                        ->helperText('Upload a .zip or .vpt theme package (max 50MB)'),
                    Forms\Components\Toggle::make('update')
                        ->label('Update if exists')
                        ->default(false)
                        ->helperText('Replace existing theme if it already exists'),
                ])
                ->action(function (array $data) {
                    $installer = app(ThemeInstaller::class);
                    $filePath = storage_path('app/' . $data['file']);
                    
                    $result = $installer->install($filePath, $data['update'] ?? false);
                    
                    if ($result['success']) {
                        $loader = app(ThemeLoader::class);
                        $loader->discoverThemes();
                        
                        $themeData = $result['theme'];
                        Theme::updateOrCreate(
                            ['slug' => $themeData['slug'] ?? str($themeData['name'])->slug()],
                            [
                                'name' => $themeData['name'],
                                'description' => $themeData['description'] ?? '',
                                'version' => $themeData['version'],
                                'author' => $themeData['author'] ?? 'Unknown',
                                'is_active' => false,
                            ]
                        );
                        
                        Notification::make()
                            ->title('Theme installed successfully')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Installation failed')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
