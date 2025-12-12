<?php

namespace Modules\VPEssential1\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Modules\VPEssential1\Models\SocialSetting;

class SocialSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Social Settings';
    protected static ?string $navigationGroup = 'VP Essential 1';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'vpessential1::filament.pages.social-settings';
    
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
    
    public array $data = [];
    
    public function mount(): void
    {
        $this->form->fill([
            'enable_registration' => SocialSetting::get('enable_registration', true),
            'enable_profiles' => SocialSetting::get('enable_profiles', true),
            'enable_friends' => SocialSetting::get('enable_friends', true),
            'enable_followers' => SocialSetting::get('enable_followers', true),
            'enable_pokes' => SocialSetting::get('enable_pokes', true),
            'enable_posts' => SocialSetting::get('enable_posts', true),
            'enable_tweets' => SocialSetting::get('enable_tweets', true),
            'enable_comments' => SocialSetting::get('enable_comments', true),
            'enable_reactions' => SocialSetting::get('enable_reactions', true),
            'enable_sharing' => SocialSetting::get('enable_sharing', true),
            'enable_hashtags' => SocialSetting::get('enable_hashtags', true),
            'enable_messaging' => SocialSetting::get('enable_messaging', true),
            'enable_notifications' => SocialSetting::get('enable_notifications', true),
            'enable_verification' => SocialSetting::get('enable_verification', true),
            'max_post_length' => SocialSetting::get('max_post_length', 5000),
            'max_tweet_length' => SocialSetting::get('max_tweet_length', 280),
            'posts_per_page' => SocialSetting::get('posts_per_page', 20),
            'default_comments_display' => SocialSetting::get('default_comments_display', 5),
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Feature Toggles')
                    ->description('Enable or disable social networking features')
                    ->schema([
                        Forms\Components\Toggle::make('enable_registration')
                            ->label('Enable Registration')
                            ->helperText('Allow new users to register'),
                        
                        Forms\Components\Toggle::make('enable_profiles')
                            ->label('Enable Profiles')
                            ->helperText('Enable user profile pages'),
                        
                        Forms\Components\Toggle::make('enable_friends')
                            ->label('Enable Friends')
                            ->helperText('Enable friend request system'),
                        
                        Forms\Components\Toggle::make('enable_followers')
                            ->label('Enable Followers')
                            ->helperText('Enable follower/following system'),
                        
                        Forms\Components\Toggle::make('enable_pokes')
                            ->label('Enable Pokes')
                            ->helperText('Enable poke feature'),
                        
                        Forms\Components\Toggle::make('enable_posts')
                            ->label('Enable Posts')
                            ->helperText('Enable Facebook-style posts and newsfeed'),
                        
                        Forms\Components\Toggle::make('enable_tweets')
                            ->label('Enable Tweets')
                            ->helperText('Enable Twitter-style micro-blogging'),
                        
                        Forms\Components\Toggle::make('enable_comments')
                            ->label('Enable Comments')
                            ->helperText('Enable comments on posts/tweets'),
                        
                        Forms\Components\Toggle::make('enable_reactions')
                            ->label('Enable Reactions')
                            ->helperText('Enable reactions (like, love, haha, etc.)'),
                        
                        Forms\Components\Toggle::make('enable_sharing')
                            ->label('Enable Sharing')
                            ->helperText('Enable post sharing feature'),
                        
                        Forms\Components\Toggle::make('enable_hashtags')
                            ->label('Enable Hashtags')
                            ->helperText('Enable hashtag system'),
                        
                        Forms\Components\Toggle::make('enable_messaging')
                            ->label('Enable Messaging')
                            ->helperText('Enable private messaging'),
                        
                        Forms\Components\Toggle::make('enable_notifications')
                            ->label('Enable Notifications')
                            ->helperText('Enable notification system'),
                        
                        Forms\Components\Toggle::make('enable_verification')
                            ->label('Enable Verification')
                            ->helperText('Enable verification badges'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Content Limits')
                    ->description('Configure content length and pagination')
                    ->schema([
                        Forms\Components\TextInput::make('max_post_length')
                            ->label('Max Post Length')
                            ->numeric()
                            ->default(5000)
                            ->required(),
                        
                        Forms\Components\TextInput::make('max_tweet_length')
                            ->label('Max Tweet Length')
                            ->numeric()
                            ->default(280)
                            ->required(),
                        
                        Forms\Components\TextInput::make('posts_per_page')
                            ->label('Posts Per Page')
                            ->numeric()
                            ->default(20)
                            ->required(),
                        
                        Forms\Components\TextInput::make('default_comments_display')
                            ->label('Default Comments Display Count')
                            ->helperText('Minimum number of comments to show initially (minimum: 5)')
                            ->numeric()
                            ->minValue(5)
                            ->default(5)
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            SocialSetting::set($key, $value);
        }
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
