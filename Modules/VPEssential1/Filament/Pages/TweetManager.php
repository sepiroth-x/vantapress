<?php

namespace Modules\VPEssential1\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Modules\VPEssential1\Models\Tweet;
use Filament\Notifications\Notification;

class TweetManager extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Tweets';
    protected static ?string $navigationGroup = 'VP Essential';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'VPEssential1::filament.pages.tweet-manager';
    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Tweet::query()->with(['user', 'replyTo', 'retweetOf']))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->limit(100)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\IconColumn::make('reply_to_id')
                    ->label('Reply')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-uturn-left')
                    ->falseIcon('heroicon-o-minus'),
                Tables\Columns\IconColumn::make('retweet_of_id')
                    ->label('Retweet')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-path')
                    ->falseIcon('heroicon-o-minus'),
                Tables\Columns\TextColumn::make('likes_count')
                    ->counts('likes')
                    ->label('Likes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('replies_count')
                    ->counts('replies')
                    ->label('Replies')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_reply')
                    ->label('Replies Only')
                    ->query(fn ($query) => $query->whereNotNull('reply_to_id')),
                Tables\Filters\Filter::make('is_retweet')
                    ->label('Retweets Only')
                    ->query(fn ($query) => $query->whereNotNull('retweet_of_id')),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name')
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Author')
                            ->disabled(),
                        Forms\Components\Textarea::make('content')
                            ->rows(4)
                            ->disabled(),
                        Forms\Components\TextInput::make('reply_to.user.name')
                            ->label('Reply To')
                            ->disabled()
                            ->visible(fn ($record) => $record->reply_to_id !== null),
                        Forms\Components\TextInput::make('retweet_of.user.name')
                            ->label('Retweet Of')
                            ->disabled()
                            ->visible(fn ($record) => $record->retweet_of_id !== null),
                        Forms\Components\Placeholder::make('stats')
                            ->label('Statistics')
                            ->content(fn ($record) => "Likes: {$record->likes_count} | Replies: {$record->replies_count}"),
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Posted')
                            ->content(fn ($record) => $record->created_at->diffForHumans()),
                    ]),
                Tables\Actions\Action::make('view_replies')
                    ->label('View Replies')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->url(fn ($record) => static::getUrl() . '?tableFilters[reply_to_id][value]=' . $record->id)
                    ->visible(fn ($record) => $record->replies_count > 0),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Tweet deleted')
                            ->body('The tweet has been moved to trash.')
                    ),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('Author')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->default(auth()->id()),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->rows(4)
                            ->maxLength(280)
                            ->helperText('Maximum 280 characters'),
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function getTweetStats(): array
    {
        return [
            'total' => Tweet::count(),
            'today' => Tweet::whereDate('created_at', today())->count(),
            'this_week' => Tweet::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'replies' => Tweet::whereNotNull('reply_to_id')->count(),
            'retweets' => Tweet::whereNotNull('retweet_of_id')->count(),
        ];
    }
}
