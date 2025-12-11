<?php

namespace Modules\VPEssential1\Filament\Pages;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Modules\VPEssential1\Models\UserProfile;
use App\Models\User;

class ProfileManager extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'User Profiles';
    protected static ?string $navigationGroup = 'VP Essential';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'VPEssential1::filament.pages.profile-manager';

    public function table(Table $table): Table
    {
        return $table
            ->query(UserProfile::query()->with('user'))
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->display_name)),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('display_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bio')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('website')
                    ->url(fn ($record) => $record->website, shouldOpenInNewTab: true)
                    ->icon('heroicon-o-link'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_avatar')
                    ->label('Has Avatar')
                    ->query(fn ($query) => $query->whereNotNull('avatar')),
                Tables\Filters\Filter::make('has_bio')
                    ->label('Has Bio')
                    ->query(fn ($query) => $query->whereNotNull('bio')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form(self::getProfileForm()),
                Tables\Actions\EditAction::make()
                    ->form(self::getProfileForm()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getProfileForm(): array
    {
        return [
            Forms\Components\Select::make('user_id')
                ->label('User')
                ->relationship('user', 'name')
                ->required()
                ->searchable()
                ->disabled(fn ($context) => $context === 'edit'),
            Forms\Components\TextInput::make('display_name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('bio')
                ->rows(4)
                ->maxLength(500),
            Forms\Components\FileUpload::make('avatar')
                ->image()
                ->directory('avatars')
                ->imageEditor()
                ->circleCropper(),
            Forms\Components\TextInput::make('website')
                ->url()
                ->maxLength(255)
                ->prefix('https://'),
            Forms\Components\TextInput::make('location')
                ->maxLength(255),
            Forms\Components\Repeater::make('social_links')
                ->schema([
                    Forms\Components\Select::make('platform')
                        ->options([
                            'twitter' => 'Twitter',
                            'facebook' => 'Facebook',
                            'instagram' => 'Instagram',
                            'linkedin' => 'LinkedIn',
                            'github' => 'GitHub',
                            'youtube' => 'YouTube',
                            'tiktok' => 'TikTok',
                            'twitch' => 'Twitch',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('url')
                        ->label('URL')
                        ->url()
                        ->required(),
                ])
                ->columns(2)
                ->defaultItems(0)
                ->collapsible(),
        ];
    }
}
