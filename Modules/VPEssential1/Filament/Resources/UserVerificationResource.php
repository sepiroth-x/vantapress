<?php

namespace Modules\VPEssential1\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Models\User;
use Modules\VPEssential1\Filament\Resources\UserVerificationResource\Pages;

class UserVerificationResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationLabel = 'User Verification';
    protected static ?string $navigationGroup = 'VP Essential 1';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->disabled()
                    ->label('User Name'),
                
                Forms\Components\TextInput::make('email')
                    ->disabled()
                    ->label('Email'),
                
                Forms\Components\Select::make('verification_status')
                    ->label('Verification Status')
                    ->options([
                        'none' => 'None',
                        'verified' => '✓ Verified (Blue Check)',
                        'business' => '🏢 Business',
                        'creator' => '⭐ Creator',
                        'vip' => '👑 VIP',
                    ])
                    ->required()
                    ->default('none'),
                
                Forms\Components\Textarea::make('verification_note')
                    ->label('Verification Note')
                    ->helperText('Internal note about verification status')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('verification_status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'none',
                        'success' => 'verified',
                        'info' => 'business',
                        'warning' => 'creator',
                        'danger' => 'vip',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'verified' => '✓ Verified',
                        'business' => '🏢 Business',
                        'creator' => '⭐ Creator',
                        'vip' => '👑 VIP',
                        default => 'None',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('verification_status')
                    ->options([
                        'none' => 'None',
                        'verified' => 'Verified',
                        'business' => 'Business',
                        'creator' => 'Creator',
                        'vip' => 'VIP',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserVerification::route('/'),
            'edit' => Pages\EditUserVerification::route('/{record}/edit'),
        ];
    }
}
