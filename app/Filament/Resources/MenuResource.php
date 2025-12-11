<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    
    protected static ?string $navigationGroup = 'Appearance';
    
    protected static ?int $navigationGroupSort = 30;
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Internal name for this menu'),
                        
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(100)
                            ->helperText('Theme location (e.g., primary, footer, sidebar)'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive menus won\'t be displayed'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Menu Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('page_id')
                                            ->label('Link to Page')
                                            ->relationship('page', 'title')
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->helperText('Select an existing page (URL will be auto-filled)')
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                if ($state) {
                                                    $page = \App\Models\Page::find($state);
                                                    if ($page) {
                                                        // Auto-fill title if empty
                                                        if (empty($get('title'))) {
                                                            $set('title', $page->title);
                                                        }
                                                        // Auto-fill URL
                                                        $set('url', '/' . ltrim($page->slug, '/'));
                                                    }
                                                }
                                            })
                                            ->columnSpan(1),
                                        
                                        Forms\Components\Placeholder::make('page_info')
                                            ->label('Info')
                                            ->content('Or enter custom title and URL below')
                                            ->columnSpan(1),
                                    ]),
                                
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Menu item label (auto-filled from page if linked)'),
                                
                                Forms\Components\TextInput::make('url')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefix('/')
                                    ->helperText('Relative URL (e.g., /about) or full URL (auto-filled from page if linked)'),
                                
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('target')
                                            ->options([
                                                '_self' => 'Same Window',
                                                '_blank' => 'New Window',
                                            ])
                                            ->default('_self'),
                                        
                                        Forms\Components\TextInput::make('icon')
                                            ->maxLength(100)
                                            ->helperText('Icon class (e.g., heroicon-o-home)'),
                                    ]),
                                
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('css_class')
                                            ->maxLength(255)
                                            ->helperText('Custom CSS classes')
                                            ->columnSpan(2),
                                        
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Visible')
                                            ->default(true)
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->orderColumn('order')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Menu Item')
                            ->collapsed()
                            ->addActionLabel('Add Menu Item')
                            ->defaultItems(0)
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action
                                    ->requiresConfirmation()
                            ),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
