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
            Actions\Action::make('sync')
                ->label('Sync with Filesystem')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->action(function () {
                    $modulesPath = base_path('Modules');
                    $loader = app(ModuleLoader::class);
                    $filesystemModules = $loader->discoverModules();
                    
                    $added = 0;
                    $orphaned = 0;
                    
                    // Find modules on filesystem not in database
                    $filesystemSlugs = array_keys($filesystemModules);
                    $databaseSlugs = Module::pluck('slug')->toArray();
                    
                    foreach ($filesystemSlugs as $slug) {
                        if (!in_array($slug, $databaseSlugs)) {
                            $metadata = $filesystemModules[$slug];
                            Module::create([
                                'slug' => $slug,
                                'name' => $metadata['name'] ?? $slug,
                                'description' => $metadata['description'] ?? '',
                                'version' => $metadata['version'] ?? '1.0.0',
                                'author' => $metadata['author'] ?? 'Unknown',
                                'is_enabled' => $metadata['active'] ?? false,
                            ]);
                            $added++;
                        }
                    }
                    
                    // Count orphaned modules (in database but not on filesystem)
                    foreach ($databaseSlugs as $dbSlug) {
                        if (!in_array($dbSlug, $filesystemSlugs)) {
                            $orphaned++;
                        }
                    }
                    
                    $message = [];
                    if ($added > 0) {
                        $message[] = "{$added} new module(s) added";
                    }
                    if ($orphaned > 0) {
                        $message[] = "{$orphaned} missing module(s) detected";
                    }
                    if (empty($message)) {
                        $message[] = "All modules are synchronized";
                    }
                    
                    Notification::make()
                        ->title('Filesystem Sync Complete')
                        ->body(implode('. ', $message) . '.')
                        ->success()
                        ->send();
                }),
            
            Actions\Action::make('cleanup')
                ->label('Clean Up Missing')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Remove Missing Modules')
                ->modalDescription('Remove all database entries for modules whose folders are missing from the filesystem?')
                ->modalSubmitActionLabel('Yes, Clean Up')
                ->action(function () {
                    $modulesPath = base_path('Modules');
                    $deleted = 0;
                    
                    $modules = Module::all();
                    foreach ($modules as $module) {
                        $path = $modulesPath . '/' . $module->slug;
                        if (!file_exists($path) || !is_dir($path)) {
                            $module->delete();
                            $deleted++;
                        }
                    }
                    
                    Notification::make()
                        ->title('Cleanup Complete')
                        ->body("{$deleted} missing module(s) removed from database.")
                        ->success()
                        ->send();
                }),
            
            Actions\Action::make('install')
                ->label('Install Module')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Module File (.zip or .vpm)')
                        ->required()
                        ->disk('livewire-tmp')
                        ->directory('modules')
                        ->maxSize(51200)
                        ->helperText('Upload a .zip or .vpm module package (max 50MB)'),
                    Forms\Components\Toggle::make('update')
                        ->label('Update if exists')
                        ->default(false)
                        ->helperText('Replace existing module if it already exists'),
                ])
                ->action(function (array $data, Actions\Action $action) {
                    try {
                        $installer = app(ModuleInstaller::class);
                        // Filament FileUpload returns path relative to disk root: 'modules/filename.vpm'
                        $filePath = storage_path('app/livewire-tmp/' . $data['file']);
                        
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
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Installation failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->after(function () {
                    // Prevent Filament from trying to clean up the file
                    // File has already been processed by ModuleInstaller
                }),
        ];
    }
}
