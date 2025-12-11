<?php

namespace Modules\VPEssential1\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\VPEssential1\Models\Post;
use Modules\VPEssential1\Filament\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $navigationGroup = 'VP Essential 1';
    protected static ?int $navigationSort = 10;
    
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                
                Forms\Components\Select::make('type')
                    ->options([
                        'status' => 'Status',
                        'photo' => 'Photo',
                        'video' => 'Video',
                        'link' => 'Link',
                        'shared' => 'Shared',
                    ])
                    ->default('status')
                    ->required(),
                
                Forms\Components\Select::make('visibility')
                    ->options([
                        'public' => 'Public',
                        'friends' => 'Friends Only',
                        'private' => 'Private',
                    ])
                    ->default('public')
                    ->required(),
                
                Forms\Components\Toggle::make('is_published')
                    ->default(true),
                
                Forms\Components\Toggle::make('is_pinned')
                    ->default(false),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'status',
                        'success' => 'photo',
                        'warning' => 'video',
                        'info' => 'link',
                        'danger' => 'shared',
                    ]),
                
                Tables\Columns\BadgeColumn::make('visibility')
                    ->colors([
                        'success' => 'public',
                        'warning' => 'friends',
                        'danger' => 'private',
                    ]),
                
                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Likes')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('comments_count')
                    ->label('Comments')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'status' => 'Status',
                        'photo' => 'Photo',
                        'video' => 'Video',
                        'link' => 'Link',
                        'shared' => 'Shared',
                    ]),
                
                Tables\Filters\SelectFilter::make('visibility')
                    ->options([
                        'public' => 'Public',
                        'friends' => 'Friends Only',
                        'private' => 'Private',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
