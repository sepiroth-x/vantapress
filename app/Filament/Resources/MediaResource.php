<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?int $navigationGroupSort = 20;
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $label = 'Media Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('File Upload')
                    ->schema([
                        Forms\Components\FileUpload::make('path')
                            ->label('File')
                            ->required()
                            ->directory('media')
                            ->disk('public')
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(10240)
                            ->helperText('Maximum file size: 10MB'),
                    ])
                    ->visible(fn ($record) => $record === null),
                
                Forms\Components\Section::make('File Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Descriptive title for this media file'),
                        
                        Forms\Components\Textarea::make('alt_text')
                            ->maxLength(500)
                            ->rows(2)
                            ->helperText('Alternative text for accessibility (images only)'),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->helperText('Optional description of this media file'),
                        
                        Forms\Components\TextInput::make('caption')
                            ->maxLength(500)
                            ->helperText('Caption displayed with the media'),
                    ])
                    ->columns(1),
                
                Forms\Components\Section::make('File Details')
                    ->schema([
                        Forms\Components\Placeholder::make('file_name')
                            ->label('File Name')
                            ->content(fn ($record) => $record?->file_name ?? 'N/A'),
                        
                        Forms\Components\Placeholder::make('mime_type')
                            ->label('File Type')
                            ->content(fn ($record) => $record?->mime_type ?? 'N/A'),
                        
                        Forms\Components\Placeholder::make('size')
                            ->label('File Size')
                            ->content(fn ($record) => $record?->getFileSizeFormattedAttribute() ?? 'N/A'),
                        
                        Forms\Components\Placeholder::make('dimensions')
                            ->label('Dimensions')
                            ->content(fn ($record) => $record && $record->width && $record->height 
                                ? "{$record->width} × {$record->height} px" 
                                : 'N/A'),
                        
                        Forms\Components\Placeholder::make('url')
                            ->label('File URL')
                            ->content(fn ($record) => $record?->getUrlAttribute() ?? 'N/A'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/images/file-icon.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('mime_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_starts_with($state, 'image/') => 'success',
                        str_starts_with($state, 'video/') => 'info',
                        str_starts_with($state, 'audio/') => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => explode('/', $state)[0] ?? $state),
                
                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn ($record) => $record->getFileSizeFormattedAttribute())
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Uploaded By')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mime_type')
                    ->label('File Type')
                    ->options([
                        'image' => 'Images',
                        'video' => 'Videos',
                        'audio' => 'Audio',
                        'application' => 'Documents',
                    ])
                    ->query(function ($query, $state) {
                        if ($state['value']) {
                            $query->where('mime_type', 'like', $state['value'] . '/%');
                        }
                    }),
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
