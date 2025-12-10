<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Module;
use App\Services\ModuleInstaller;
use App\Services\ModuleLoader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    
    protected static ?string $navigationGroup = 'Extensions';
    
    protected static ?int $navigationGroupSort = 40;
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $label = 'Module (Plugin)';
    
    protected static ?string $pluralLabel = 'Modules (Plugins)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Module Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Module display name'),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique module identifier (folder name in Modules/)'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->helperText('Brief description of this module'),
                        
                        Forms\Components\TextInput::make('version')
                            ->maxLength(20)
                            ->default('1.0.0')
                            ->helperText('Module version number'),
                        
                        Forms\Components\TextInput::make('author')
                            ->maxLength(255)
                            ->helperText('Module author name'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Module Package')
                    ->schema([
                        Forms\Components\FileUpload::make('package_path')
                            ->label('Upload Module (.zip)')
                            ->acceptedFileTypes(['application/zip'])
                            ->directory('modules/packages')
                            ->disk('local')
                            ->downloadable()
                            ->maxSize(51200) // 50MB
                            ->helperText('Upload a .zip file containing your module. Maximum size: 50MB')
                            ->visible(fn ($record) => $record === null),
                    ])
                    ->visible(fn ($record) => $record === null),
                
                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\KeyValue::make('config')
                            ->label('Module Settings')
                            ->keyLabel('Setting Name')
                            ->valueLabel('Setting Value')
                            ->helperText('Custom configuration options for this module'),
                    ])
                    ->collapsible(),
                
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_enabled')
                            ->label('Enable Module')
                            ->helperText('Enable or disable this module'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Sync with filesystem before displaying
                try {
                    static::syncWithFilesystem();
                } catch (\Exception $e) {
                    \Log::error('[ModuleResource] Failed to sync with filesystem', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
                return $query;
            })
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('bold')
                            ->size('lg')
                            ->icon(fn ($record) => static::moduleExists($record) 
                                ? 'heroicon-o-puzzle-piece' 
                                : 'heroicon-o-exclamation-triangle')
                            ->color(fn ($record) => static::moduleExists($record) 
                                ? 'primary' 
                                : 'danger')
                            ->grow(false)
                            ->description(fn ($record) => static::moduleExists($record) 
                                ? null 
                                : '⚠️ Module folder not found'),
                        
                        Tables\Columns\TextColumn::make('description')
                            ->color('gray')
                            ->wrap()
                            ->lineClamp(2),
                    ])->space(1),
                    
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('author')
                            ->icon('heroicon-o-user')
                            ->color('gray')
                            ->size('sm'),
                        
                        Tables\Columns\TextColumn::make('version')
                            ->badge()
                            ->color(fn ($record) => static::moduleExists($record) ? 'gray' : 'warning')
                            ->icon('heroicon-o-tag'),
                    ])->space(1)->alignment('end'),
                    
                    Tables\Columns\IconColumn::make('is_enabled')
                        ->boolean()
                        ->label('Status')
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->trueColor('success')
                        ->falseColor('gray')
                        ->alignment('end')
                        ->grow(false),
                ])->from('md'),
            ])
            ->contentGrid([
                'md' => 1,
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_enabled')
                    ->label('Enabled Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle')
                    ->label(fn ($record) => $record->is_enabled ? 'Disable' : 'Enable')
                    ->icon(fn ($record) => $record->is_enabled ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->is_enabled ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => static::moduleExists($record))
                    ->action(function ($record) {
                        $loader = new ModuleLoader();
                        $newStatus = !$record->is_enabled;
                        
                        if ($newStatus) {
                            $loader->activateModule($record->slug);
                        } else {
                            $loader->deactivateModule($record->slug);
                        }
                        
                        $record->update(['is_enabled' => $newStatus]);
                        
                        Notification::make()
                            ->title('Module ' . ($newStatus ? 'enabled' : 'disabled'))
                            ->body('Please refresh the page (F5) to update the navigation menu.')
                            ->success()
                            ->persistent()
                            ->send();
                    })
                    ->successNotificationTitle('Module status updated')
                    ->after(fn () => redirect()->to(request()->header('Referer') ?? '/admin/modules')),
                
                Tables\Actions\Action::make('remove_orphan')
                    ->label('Remove from Database')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove Missing Module')
                    ->modalDescription('This module folder is missing from the filesystem. Remove the database entry?')
                    ->modalSubmitActionLabel('Yes, Remove')
                    ->visible(fn ($record) => !static::moduleExists($record))
                    ->action(function ($record) {
                        $record->delete();
                        
                        Notification::make()
                            ->title('Orphaned module removed')
                            ->body('Database entry for missing module has been removed.')
                            ->success()
                            ->send();
                    }),
                    
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Module')
                    ->modalDescription('This will permanently delete the module files, database tables, and all related data. This action cannot be undone.')
                    ->visible(fn ($record) => static::moduleExists($record))
                    ->before(function ($record) {
                        $loader = new ModuleLoader();
                        
                        // Deactivate if enabled
                        if ($record->is_enabled) {
                            $loader->deactivateModule($record->slug);
                        }
                        
                        // Run module's uninstall migrations if they exist
                        $modulePath = base_path('Modules/' . $record->slug);
                        $migrationsPath = $modulePath . '/migrations';
                        
                        if (File::exists($migrationsPath)) {
                            try {
                                \Artisan::call('migrate:rollback', [
                                    '--path' => 'Modules/' . $record->slug . '/migrations',
                                    '--force' => true
                                ]);
                                \Log::info("Rolled back migrations for module: {$record->slug}");
                            } catch (\Exception $e) {
                                \Log::warning("Failed to rollback migrations for module {$record->slug}: " . $e->getMessage());
                            }
                        }
                        
                        // Delete module files
                        $loader->deleteModule($record->slug);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enable')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $loader = new ModuleLoader();
                            $enabled = 0;
                            $skipped = 0;
                            
                            foreach ($records as $record) {
                                // Only enable if module folder exists
                                if (static::moduleExists($record)) {
                                    $loader->activateModule($record->slug);
                                    $record->update(['is_enabled' => true]);
                                    $enabled++;
                                } else {
                                    $skipped++;
                                }
                            }
                            
                            $message = [];
                            if ($enabled > 0) {
                                $message[] = "{$enabled} module(s) enabled";
                            }
                            if ($skipped > 0) {
                                $message[] = "{$skipped} skipped (missing folders)";
                            }
                            
                            Notification::make()
                                ->title('Bulk Enable Complete')
                                ->body(implode(', ', $message))
                                ->success()
                                ->send();
                        })
                        ->successNotificationTitle('Modules enabled successfully')
                        ->after(fn () => redirect()->to(request()->header('Referer') ?? '/admin/modules')),
                    
                    Tables\Actions\BulkAction::make('disable')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(function ($records) {
                            $loader = new ModuleLoader();
                            $disabled = 0;
                            $skipped = 0;
                            
                            foreach ($records as $record) {
                                // Only disable if module folder exists
                                if (static::moduleExists($record)) {
                                    $loader->deactivateModule($record->slug);
                                    $record->update(['is_enabled' => false]);
                                    $disabled++;
                                } else {
                                    $skipped++;
                                }
                            }
                            
                            $message = [];
                            if ($disabled > 0) {
                                $message[] = "{$disabled} module(s) disabled";
                            }
                            if ($skipped > 0) {
                                $message[] = "{$skipped} skipped (missing folders)";
                            }
                            
                            Notification::make()
                                ->title('Bulk Disable Complete')
                                ->body(implode(', ', $message))
                                ->success()
                                ->send();
                        })
                        ->successNotificationTitle('Modules disabled successfully')
                        ->after(fn () => redirect()->to(request()->header('Referer') ?? '/admin/modules')),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
        ];
    }
    
    /**
     * Check if module folder exists on filesystem
     */
    protected static function moduleExists($record): bool
    {
        if (!$record) return false;
        
        $modulePath = base_path('Modules/' . $record->slug);
        
        // Check exact match first
        if (file_exists($modulePath) && is_dir($modulePath)) {
            return true;
        }
        
        // Check case-insensitive match (for cross-platform compatibility)
        $modulesPath = base_path('Modules');
        if (!file_exists($modulesPath)) {
            return false;
        }
        
        $folders = array_filter(scandir($modulesPath), function($item) use ($modulesPath) {
            return $item !== '.' && $item !== '..' && is_dir($modulesPath . '/' . $item);
        });
        
        foreach ($folders as $folder) {
            if (strtolower($folder) === strtolower($record->slug)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sync database with filesystem
     * WordPress-style: discover new modules and mark missing ones
     */
    protected static function syncWithFilesystem(): void
    {
        // Prevent multiple syncs in the same request
        static $synced = false;
        if ($synced) {
            return;
        }
        $synced = true;
        
        try {
            $modulesPath = base_path('Modules');
            
            if (!file_exists($modulesPath)) {
                return;
            }
            
            $loader = app(ModuleLoader::class);
            $filesystemModules = $loader->discoverModules();
            
            if (!is_array($filesystemModules)) {
                \Log::warning('[ModuleResource] discoverModules() did not return array', [
                    'type' => gettype($filesystemModules)
                ]);
                return;
            }
            
            // Get all module slugs from filesystem
            $filesystemSlugs = array_keys($filesystemModules);
            
            // Get all module slugs from database
            $databaseSlugs = Module::pluck('slug')->toArray();
            
            // Add new modules found on filesystem but not in database
            foreach ($filesystemSlugs as $slug) {
                if (!in_array($slug, $databaseSlugs)) {
                    $metadata = $filesystemModules[$slug];
                    
                    if (!is_array($metadata)) {
                        \Log::warning('[ModuleResource] Invalid metadata for module', [
                            'slug' => $slug,
                            'metadata_type' => gettype($metadata)
                        ]);
                        continue;
                    }
                    
                    Module::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => $metadata['name'] ?? $slug,
                            'description' => $metadata['description'] ?? '',
                            'version' => $metadata['version'] ?? '1.0.0',
                            'author' => $metadata['author'] ?? 'Unknown',
                            'is_enabled' => $metadata['active'] ?? false,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('[ModuleResource] syncWithFilesystem() failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
