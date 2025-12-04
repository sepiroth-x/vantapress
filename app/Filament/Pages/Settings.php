<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationGroup = 'Administration';
    
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.settings';
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    protected function getSettingsData(): array
    {
        $allowedTypes = Setting::get('allowed_file_types', 'jpg,jpeg,png,gif,pdf,doc,docx');
        
        // Convert to array only if it's a string (TagsInput expects array)
        if (is_string($allowedTypes)) {
            $allowedTypes = array_filter(array_map('trim', explode(',', $allowedTypes)));
        } elseif (!is_array($allowedTypes)) {
            $allowedTypes = [];
        }
        
        return [
            // General Settings
            'site_name' => (string) Setting::get('site_name', 'VantaPress'),
            'site_tagline' => (string) Setting::get('site_tagline', 'A WordPress-inspired CMS'),
            'site_description' => (string) Setting::get('site_description', ''),
            'admin_email' => (string) Setting::get('admin_email', auth()->user()->email ?? ''),
            'timezone' => (string) Setting::get('timezone', 'UTC'),
            'date_format' => (string) Setting::get('date_format', 'Y-m-d'),
            'time_format' => (string) Setting::get('time_format', 'H:i:s'),
            
            // Reading Settings
            'posts_per_page' => (int) Setting::get('posts_per_page', 10),
            'homepage_type' => (string) Setting::get('homepage_type', 'latest'),
            'homepage_page_id' => Setting::get('homepage_page_id', null),
            
            // Media Settings
            'max_upload_size' => (int) Setting::get('max_upload_size', 10),
            'allowed_file_types' => $allowedTypes,
            
            // SEO Settings
            'seo_enabled' => (bool) Setting::get('seo_enabled', true),
            'robots_txt' => (string) Setting::get('robots_txt', ''),
            'google_analytics' => (string) Setting::get('google_analytics', ''),
            
            // Maintenance Mode
            'maintenance_mode' => (bool) Setting::get('maintenance_mode', false),
            'maintenance_message' => (string) Setting::get('maintenance_message', 'Site is under maintenance. Please check back soon.'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->required()
                                    ->default('VantaPress')
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('site_tagline')
                                    ->label('Tagline')
                                    ->default('A WordPress-inspired CMS')
                                    ->maxLength(255),
                                
                                Forms\Components\Textarea::make('site_description')
                                    ->label('Site Description')
                                    ->default('')
                                    ->maxLength(500)
                                    ->rows(3),
                                
                                Forms\Components\TextInput::make('admin_email')
                                    ->label('Admin Email')
                                    ->email()
                                    ->required(),
                                
                                Forms\Components\Select::make('timezone')
                                    ->label('Timezone')
                                    ->default('UTC')
                                    ->options([
                                        'UTC' => 'UTC',
                                        'America/New_York' => 'America/New York',
                                        'America/Chicago' => 'America/Chicago',
                                        'America/Los_Angeles' => 'America/Los Angeles',
                                        'Europe/London' => 'Europe/London',
                                        'Europe/Paris' => 'Europe/Paris',
                                        'Asia/Tokyo' => 'Asia/Tokyo',
                                        'Australia/Sydney' => 'Australia/Sydney',
                                    ])
                                    ->searchable(),
                                
                                Forms\Components\TextInput::make('date_format')
                                    ->label('Date Format')
                                    ->default('Y-m-d')
                                    ->placeholder('Y-m-d'),
                                
                                Forms\Components\TextInput::make('time_format')
                                    ->label('Time Format')
                                    ->default('H:i:s')
                                    ->placeholder('H:i:s'),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Tabs\Tab::make('Reading')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Forms\Components\TextInput::make('posts_per_page')
                                    ->label('Posts Per Page')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->default(10),
                                
                                Forms\Components\Select::make('homepage_type')
                                    ->label('Homepage Display')
                                    ->options([
                                        'latest' => 'Latest Posts',
                                        'static' => 'Static Page',
                                    ])
                                    ->default('latest'),
                                
                                Forms\Components\Select::make('homepage_page_id')
                                    ->label('Homepage Page')
                                    ->options(function () {
                                        return \App\Models\Page::pluck('title', 'id');
                                    })
                                    ->searchable()
                                    ->visible(fn (Forms\Get $get) => $get('homepage_type') === 'static'),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\TextInput::make('max_upload_size')
                                    ->label('Max Upload Size (MB)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->default(10),
                                
                                Forms\Components\TagsInput::make('allowed_file_types')
                                    ->label('Allowed File Types')
                                    ->placeholder('jpg, png, pdf')
                                    ->helperText('Comma-separated list of allowed file extensions')
                                    ->dehydrateStateUsing(fn ($state) => is_array($state) ? implode(',', $state) : $state),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\Toggle::make('seo_enabled')
                                    ->label('Enable SEO Features')
                                    ->default(true),
                                
                                Forms\Components\Textarea::make('robots_txt')
                                    ->label('Robots.txt Content')
                                    ->default('')
                                    ->rows(5)
                                    ->helperText('Custom robots.txt content'),
                                
                                Forms\Components\Textarea::make('google_analytics')
                                    ->label('Google Analytics Code')
                                    ->default('')
                                    ->rows(3)
                                    ->placeholder('UA-XXXXX-X or G-XXXXXXXXXX')
                                    ->helperText('Enter your Google Analytics tracking ID'),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Maintenance')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Forms\Components\Toggle::make('maintenance_mode')
                                    ->label('Enable Maintenance Mode')
                                    ->helperText('When enabled, only admins can access the site'),
                                
                                Forms\Components\Textarea::make('maintenance_message')
                                    ->label('Maintenance Message')
                                    ->rows(3)
                                    ->default('Site is under maintenance. Please check back soon.'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            // Determine the type based on the value
            $type = 'string';
            
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_array($value)) {
                // Convert array to comma-separated string
                $value = implode(',', $value);
                $type = 'string';
            } elseif (is_numeric($value)) {
                $type = is_int($value) ? 'integer' : 'float';
            }
            
            Setting::set($key, $value, $type);
        }
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
