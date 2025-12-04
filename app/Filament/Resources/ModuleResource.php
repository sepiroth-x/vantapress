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

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    
    protected static ?string $navigationGroup = 'Extensions';
    
    protected static ?int $navigationGroupSort = 35;
    
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
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('bold')
                            ->size('lg')
                            ->icon('heroicon-o-puzzle-piece')
                            ->grow(false),
                        
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
                            ->color('gray')
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
                            ->success()
                            ->send();
                    })
                    ->successNotificationTitle('Module status updated')
                    ->after(fn () => redirect()->to(request()->header('Referer') ?? '/admin/modules')),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->before(function ($record) {
                        $loader = new ModuleLoader();
                        if ($record->is_enabled) {
                            $loader->deactivateModule($record->slug);
                        }
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
                            foreach ($records as $record) {
                                $loader->activateModule($record->slug);
                                $record->update(['is_enabled' => true]);
                            }
                            
                            Notification::make()
                                ->title('Modules enabled')
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
                            foreach ($records as $record) {
                                $loader->deactivateModule($record->slug);
                                $record->update(['is_enabled' => false]);
                            }
                            
                            Notification::make()
                                ->title('Modules disabled')
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
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
