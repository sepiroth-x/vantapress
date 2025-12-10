<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use App\Models\Module;
use App\Services\ModuleInstaller;
use App\Services\ModuleLoader;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('install')
                ->label('Install Module')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Module File (.zip or .vpm)')
                        ->required()
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->maxSize(51200)
                        ->directory('temp/modules')
                        ->helperText('Upload a .zip or .vpm module package (max 50MB)'),
                    Forms\Components\Toggle::make('update')
                        ->label('Update if exists')
                        ->default(false)
                        ->helperText('Replace existing module if it already exists'),
                ])
                ->action(function (array $data) {
                    $installer = app(ModuleInstaller::class);
                    $filePath = storage_path('app/' . $data['file']);
                    
                    $result = $installer->install($filePath, $data['update'] ?? false);
                    
                    if ($result['success']) {
                        $loader = app(ModuleLoader::class);
                        $loader->discoverModules();
                        
                        $moduleData = $result['module'];
                        Module::updateOrCreate(
                            ['slug' => $moduleData['slug'] ?? str($moduleData['name'])->slug()],
                            [
                                'name' => $moduleData['name'],
                                'description' => $moduleData['description'] ?? '',
                                'version' => $moduleData['version'],
                                'author' => $moduleData['author'] ?? 'Unknown',
                                'is_enabled' => $moduleData['active'] ?? false,
                            ]
                        );
                        
                        Notification::make()
                            ->title('Module installed successfully')
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
