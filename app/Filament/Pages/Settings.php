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
            
            // Developer Options
            'debug_mode' => (bool) config('app.debug', false),
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
                        
                        Forms\Components\Tabs\Tab::make('Developer')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Section::make('Debug Settings')
                                    ->description('Advanced debugging options for developers')
                                    ->schema([
                                        Forms\Components\Toggle::make('debug_mode')
                                            ->label('Enable Debug Mode')
                                            ->helperText('⚠️ WARNING: Shows detailed error messages. DO NOT enable in production!')
                                            ->live()
                                            ->afterStateUpdated(function ($state) {
                                                $this->updateDebugMode($state);
                                            }),
                                    ])
                                    ->collapsible(),
                                
                                Forms\Components\Section::make('Danger Zone')
                                    ->description('⚠️ WARNING: These actions cannot be undone!')
                                    ->schema([
                                        Forms\Components\Placeholder::make('delete_info')
                                            ->label('Delete Conflicting Data')
                                            ->content('Use these tools to fix database issues and remove duplicate or conflicting data:

• **Fix Duplicate Slugs** - Removes duplicate page slugs (keeps most recent)
• **Clear All Pages** - Deletes all pages from database
• **Clear All Media** - Deletes all media files and records
• **Clear Cache** - Clears application, config, route, and view cache
• **Reset Database** - ⚠️ DANGER: Wipes entire database and runs migrations'),
                                        
                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('fix_duplicates')
                                                ->label('Fix Duplicate Slugs')
                                                ->color('warning')
                                                ->icon('heroicon-o-wrench-screwdriver')
                                                ->requiresConfirmation()
                                                ->modalHeading('Fix Duplicate Page Slugs?')
                                                ->modalDescription('This will find and remove duplicate page slugs, keeping the most recent one.')
                                                ->modalSubmitActionLabel('Yes, fix duplicates')
                                                ->action(function () {
                                                    $duplicates = \Illuminate\Support\Facades\DB::table('pages')
                                                        ->select('slug', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
                                                        ->whereNull('deleted_at')
                                                        ->groupBy('slug')
                                                        ->having('count', '>', 1)
                                                        ->get();
                                                    
                                                    $deleted = 0;
                                                    foreach ($duplicates as $duplicate) {
                                                        // Keep the most recent, delete others
                                                        $pages = \App\Models\Page::where('slug', $duplicate->slug)
                                                            ->orderBy('created_at', 'desc')
                                                            ->get();
                                                        
                                                        foreach ($pages->skip(1) as $page) {
                                                            $page->delete();
                                                            $deleted++;
                                                        }
                                                    }
                                                    
                                                    Notification::make()
                                                        ->success()
                                                        ->title('Duplicates Fixed')
                                                        ->body($deleted > 0 ? "{$deleted} duplicate pages removed." : 'No duplicates found.')
                                                        ->send();
                                                }),
                                            
                                            Forms\Components\Actions\Action::make('clear_cache')
                                                ->label('Clear All Cache')
                                                ->color('info')
                                                ->icon('heroicon-o-arrow-path')
                                                ->requiresConfirmation()
                                                ->action(function () {
                                                    try {
                                                        \Illuminate\Support\Facades\Artisan::call('cache:clear');
                                                        \Illuminate\Support\Facades\Artisan::call('config:clear');
                                                        \Illuminate\Support\Facades\Artisan::call('route:clear');
                                                        \Illuminate\Support\Facades\Artisan::call('view:clear');
                                                        
                                                        Notification::make()
                                                            ->success()
                                                            ->title('Cache Cleared')
                                                            ->body('All cache has been cleared successfully.')
                                                            ->send();
                                                    } catch (\Exception $e) {
                                                        Notification::make()
                                                            ->danger()
                                                            ->title('Error')
                                                            ->body('Failed to clear cache: ' . $e->getMessage())
                                                            ->send();
                                                    }
                                                }),
                                            
                                            Forms\Components\Actions\Action::make('delete_pages')
                                                ->label('Clear All Pages')
                                                ->color('danger')
                                                ->icon('heroicon-o-trash')
                                                ->requiresConfirmation()
                                                ->modalHeading('Delete All Pages?')
                                                ->modalDescription('⚠️ This will permanently delete all pages from the database.')
                                                ->modalSubmitActionLabel('Yes, delete all pages')
                                                ->action(function () {
                                                    try {
                                                        $count = \App\Models\Page::count();
                                                        \App\Models\Page::query()->forceDelete();
                                                        
                                                        Notification::make()
                                                            ->success()
                                                            ->title('Pages Cleared')
                                                            ->body("{$count} pages have been deleted.")
                                                            ->send();
                                                    } catch (\Exception $e) {
                                                        Notification::make()
                                                            ->danger()
                                                            ->title('Error')
                                                            ->body('Failed to clear pages: ' . $e->getMessage())
                                                            ->send();
                                                    }
                                                }),
                                            
                                            Forms\Components\Actions\Action::make('delete_media')
                                                ->label('Clear All Media')
                                                ->color('danger')
                                                ->icon('heroicon-o-photo')
                                                ->requiresConfirmation()
                                                ->modalHeading('Delete All Media?')
                                                ->modalDescription('⚠️ This will delete all media records and files.')
                                                ->modalSubmitActionLabel('Yes, delete all media')
                                                ->action(function () {
                                                    try {
                                                        $count = \App\Models\Media::count();
                                                        
                                                        // Delete media files
                                                        $mediaPath = storage_path('app/public/media');
                                                        if (\Illuminate\Support\Facades\File::exists($mediaPath)) {
                                                            \Illuminate\Support\Facades\File::cleanDirectory($mediaPath);
                                                        }
                                                        
                                                        \App\Models\Media::query()->forceDelete();
                                                        
                                                        Notification::make()
                                                            ->success()
                                                            ->title('Media Cleared')
                                                            ->body("{$count} media files have been deleted.")
                                                            ->send();
                                                    } catch (\Exception $e) {
                                                        Notification::make()
                                                            ->danger()
                                                            ->title('Error')
                                                            ->body('Failed to clear media: ' . $e->getMessage())
                                                            ->send();
                                                    }
                                                }),
                                            
                                            Forms\Components\Actions\Action::make('reset_database')
                                                ->label('Reset Entire Database')
                                                ->color('danger')
                                                ->icon('heroicon-o-exclamation-triangle')
                                                ->requiresConfirmation()
                                                ->modalHeading('⚠️ DANGER: Reset Database?')
                                                ->modalDescription('This will DELETE ALL DATA and run migrations again. This action is IRREVERSIBLE! You will need to reinstall VantaPress.')
                                                ->modalSubmitActionLabel('Yes, I understand - RESET EVERYTHING')
                                                ->action(function () {
                                                    try {
                                                        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
                                                        
                                                        Notification::make()
                                                            ->warning()
                                                            ->title('Database Reset Complete')
                                                            ->body('Database has been reset. Please visit /install.php to reinstall VantaPress.')
                                                            ->persistent()
                                                            ->send();
                                                            
                                                        // Logout user
                                                        auth()->logout();
                                                        redirect('/');
                                                    } catch (\Exception $e) {
                                                        Notification::make()
                                                            ->danger()
                                                            ->title('Reset Failed')
                                                            ->body('Error: ' . $e->getMessage())
                                                            ->persistent()
                                                            ->send();
                                                    }
                                                }),
                                        ]),
                                    ])
                                    ->collapsible()
                                    ->collapsed(),
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
            // Skip debug_mode as it's handled separately
            if ($key === 'debug_mode') {
                continue;
            }
            
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
    
    protected function updateDebugMode(bool $enabled): void
    {
        try {
            $envPath = base_path('.env');
            
            if (!\Illuminate\Support\Facades\File::exists($envPath)) {
                Notification::make()
                    ->danger()
                    ->title('Error')
                    ->body('.env file not found')
                    ->send();
                return;
            }
            
            $envContent = \Illuminate\Support\Facades\File::get($envPath);
            
            // Update APP_DEBUG
            if (preg_match('/^APP_DEBUG=.*$/m', $envContent)) {
                $envContent = preg_replace(
                    '/^APP_DEBUG=.*$/m',
                    'APP_DEBUG=' . ($enabled ? 'true' : 'false'),
                    $envContent
                );
            } else {
                $envContent .= "\nAPP_DEBUG=" . ($enabled ? 'true' : 'false');
            }
            
            \Illuminate\Support\Facades\File::put($envPath, $envContent);
            
            // Clear config cache
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            
            Notification::make()
                ->success()
                ->title('Debug Mode ' . ($enabled ? 'Enabled' : 'Disabled'))
                ->body($enabled ? '⚠️ Remember to disable this in production!' : 'Debug mode has been disabled.')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Failed to update debug mode: ' . $e->getMessage())
                ->send();
        }
    }
}
