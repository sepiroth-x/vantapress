<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeResource\Pages;
use App\Models\Theme;
use App\Services\ThemeInstaller;
use App\Services\ThemeLoader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    
    protected static ?string $navigationGroup = 'Appearance';
    
    protected static ?int $navigationGroupSort = 30;
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Theme Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Theme display name (read-only)'),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(100)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Unique theme identifier (read-only)'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Brief description of this theme (read-only)'),
                        
                        Forms\Components\TextInput::make('version')
                            ->maxLength(20)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Theme version number (read-only)'),
                        
                        Forms\Components\TextInput::make('author')
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Theme author name (read-only)'),
                    ])
                    ->description('Theme information is read-only and comes from theme.json file')
                    ->columns(2),
                
                Forms\Components\Section::make('Theme Package')
                    ->schema([
                        Forms\Components\FileUpload::make('package_path')
                            ->label('Upload Theme (.zip)')
                            ->acceptedFileTypes(['application/zip'])
                            ->directory('themes/packages')
                            ->disk('local')
                            ->downloadable()
                            ->maxSize(51200) // 50MB
                            ->helperText('Upload a .zip file containing your theme. Maximum size: 50MB')
                            ->visible(fn ($record) => $record === null),
                    ])
                    ->visible(fn ($record) => $record === null),
                
                Forms\Components\Section::make('Theme Preview')
                    ->schema([
                        Forms\Components\FileUpload::make('screenshot')
                            ->label('Theme Screenshot')
                            ->image()
                            ->directory('themes/screenshots')
                            ->disk('public')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Theme screenshot (read-only, automatically loaded from theme folder)'),
                    ])
                    ->description('Screenshot is automatically detected from the theme folder'),
                
                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\KeyValue::make('config')
                            ->label('Theme Settings')
                            ->keyLabel('Setting Name')
                            ->valueLabel('Setting Value')
                            ->helperText('Custom configuration options for this theme'),
                    ])
                    ->collapsible(),
                
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Theme')
                            ->helperText('Only one theme can be active at a time')
                            ->disabled(fn ($record) => $record?->is_active === true),
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
                    
                    Tables\Columns\IconColumn::make('is_active')
                        ->boolean()
                        ->label('Active')
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('customize')
                    ->label('Customize')
                    ->icon('heroicon-o-paint-brush')
                    ->color('primary')
                    ->url(fn ($record) => route('theme-customizer.show', ['id' => $record->id]))
                    ->openUrlInNewTab()
                    ->tooltip('Customize theme appearance and settings'),
                
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Activate Theme')
                    ->modalDescription('This will deactivate the current theme and make this theme live on your homepage.')
                    ->action(function ($record) {
                        // Use the model's activate method which handles cache clearing
                        $record->activate();
                        
                        Notification::make()
                            ->title('Theme activated')
                            ->body('Visit your homepage to see the new theme in action!')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Deactivate Theme')
                    ->modalDescription('This will deactivate the current theme. The Basic Theme will be activated automatically.')
                    ->action(function ($record) {
                        $record->deactivate();
                        
                        Notification::make()
                            ->title('Theme deactivated')
                            ->body('Basic Theme has been activated.')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\ViewAction::make()
                    ->label('Details')
                    ->modalHeading(fn ($record) => $record->name)
                    ->modalContent(fn ($record) => view('filament.resources.theme-resource.view-theme', ['record' => $record])),
                
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn ($record) => $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Delete Theme')
                    ->modalDescription(function ($record) {
                        if ($record->is_active) {
                            return 'Cannot delete an active theme. Please activate another theme first.';
                        }
                        return 'Are you sure you want to delete this theme? This action cannot be undone.';
                    })
                    ->before(function ($record) {
                        $loader = new ThemeLoader();
                        $loader->deleteTheme($record->slug);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('is_active', 'desc');
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
            'index' => Pages\ListThemes::route('/'),
            'create' => Pages\CreateTheme::route('/create'),
            'customize' => Pages\CustomizeTheme::route('/{record}/customize'),
            'edit' => Pages\EditTheme::route('/{record}/edit'),
        ];
    }
}
