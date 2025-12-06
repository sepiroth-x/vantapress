<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayoutTemplateResource\Pages;
use App\Filament\Resources\LayoutTemplateResource\RelationManagers;
use App\Models\LayoutTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LayoutTemplateResource extends Resource
{
    protected static ?string $model = LayoutTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static ?string $navigationGroup = 'Appearance';
    
    protected static ?int $navigationSort = 5;
    
    protected static ?string $navigationLabel = 'Layout Templates';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('category')
                            ->options([
                                'header' => 'Header',
                                'footer' => 'Footer',
                                'hero' => 'Hero Section',
                                'content' => 'Content',
                                'sidebar' => 'Sidebar',
                                'home' => 'Home Page',
                                'blog' => 'Blog',
                                'about' => 'About',
                                'contact' => 'Contact',
                                'general' => 'General',
                            ])
                            ->default('general')
                            ->required(),
                        
                        Forms\Components\Select::make('theme_id')
                            ->relationship('theme', 'name')
                            ->label('Theme')
                            ->helperText('Leave empty for global templates'),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('is_global')
                            ->label('Global Template')
                            ->helperText('Available to all themes')
                            ->default(false),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Layout Data')
                    ->schema([
                        Forms\Components\KeyValue::make('layout_data')
                            ->label('Elements')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'header',
                        'success' => 'footer',
                        'warning' => 'hero',
                        'info' => 'content',
                        'gray' => 'general',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('theme.name')
                    ->label('Theme')
                    ->sortable()
                    ->default('Global'),
                
                Tables\Columns\IconColumn::make('is_global')
                    ->label('Global')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'hero' => 'Hero Section',
                        'content' => 'Content',
                        'general' => 'General',
                    ]),
                
                Tables\Filters\SelectFilter::make('theme_id')
                    ->relationship('theme', 'name')
                    ->label('Theme'),
                
                Tables\Filters\TernaryFilter::make('is_global')
                    ->label('Global Templates')
                    ->placeholder('All templates')
                    ->trueLabel('Global only')
                    ->falseLabel('Theme-specific only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListLayoutTemplates::route('/'),
            'create' => Pages\CreateLayoutTemplate::route('/create'),
            'edit' => Pages\EditLayoutTemplate::route('/{record}/edit'),
        ];
    }
}
