<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use App\Models\Theme;
use App\Services\ThemeLoader;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;

class CustomizeTheme extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ThemeResource::class;
    protected static string $view = 'filament.resources.theme-resource.pages.customize-theme';
    
    public ?array $data = [];
    public Theme $record;
    public string $previewUrl;
    public array $themeMetadata = [];

    public function mount(int | string | Theme $record): void
    {
        try {
            // Check if VPEssential1 module is enabled
            $vpEssentialModule = \App\Models\Module::where('slug', 'VPEssential1')->first();
            if (!$vpEssentialModule || !$vpEssentialModule->is_enabled) {
                Notification::make()
                    ->title('Theme Customizer Unavailable')
                    ->body('The VPEssential1 module is disabled. Please enable it to use the theme customizer.')
                    ->warning()
                    ->send();
                
                $this->redirect(ThemeResource::getUrl('index'));
                return;
            }
            
            // Handle if $record is already a Theme model instance
            if ($record instanceof Theme) {
                $this->record = $record;
            } else {
                // Otherwise treat it as an ID
                $this->record = Theme::findOrFail((int) $record);
            }
            
            // Safely get slug
            $themeSlug = $this->record->getAttribute('slug');
            if (is_array($themeSlug)) {
                $themeSlug = $themeSlug[0] ?? 'default';
            }
            $themeSlug = (string) $themeSlug;
            
            $this->previewUrl = url('/?theme_preview=' . urlencode($themeSlug));
            
            // Load theme metadata
            $themePath = base_path('themes/' . $themeSlug);
            $metadataPath = $themePath . '/theme.json';
            
            if (File::exists($metadataPath)) {
                $this->themeMetadata = json_decode(File::get($metadataPath), true) ?? [];
            }
            
            // Load current theme settings from VP Essential
            // Use defaults if helper functions aren't available yet
            $this->form->fill([
                'site_title' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('site_title', config('app.name', 'VantaPress'))
                    : config('app.name', 'VantaPress'),
                'site_tagline' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('site_tagline', '') : '',
                'logo' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('logo', '') : '',
                'primary_color' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('primary_color', '#dc2626') : '#dc2626',
                'accent_color' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('accent_color', '#991b1b') : '#991b1b',
                'hero_title' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_title', 'Welcome') : 'Welcome',
                'hero_subtitle' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_subtitle', '') : '',
                'hero_description' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_description', '') : '',
                'hero_primary_button_text' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_primary_button_text', 'Get Started') : 'Get Started',
                'hero_primary_button_url' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_primary_button_url', '#') : '#',
                'hero_secondary_button_text' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_secondary_button_text', 'Learn More') : 'Learn More',
                'hero_secondary_button_url' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('hero_secondary_button_url', '#') : '#',
                'footer_text' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('footer_text', '© 2025 VantaPress') : '© 2025 VantaPress',
                'custom_css' => function_exists('vp_get_theme_setting') 
                    ? vp_get_theme_setting('custom_css', '') : '',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('CustomizeTheme mount error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'record_id' => $record,
            ]);
            
            Notification::make()
                ->title('Error Loading Customizer')
                ->body($e->getMessage())
                ->danger()
                ->send();
                
            throw $e;
        }
    }

    public function form(Form $form): Form
    {
        // Get customizable elements from theme.json
        $themeLoader = app(ThemeLoader::class);
        $themeLoader->discoverThemes();
        
        $themeSlug = is_array($this->record->slug) ? $this->record->slug[0] : $this->record->slug;
        $customizableElements = $themeLoader->getCustomizableElements($themeSlug);
        
        // Build tabs dynamically based on theme customization options
        $tabs = [];
        
        // Always show Site Identity
        $tabs[] = Forms\Components\Tabs\Tab::make('Site Identity')
            ->icon('heroicon-o-identification')
            ->schema([
                Forms\Components\TextInput::make('site_title')
                    ->label('Site Title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn () => $this->refreshPreview()),
                
                Forms\Components\TextInput::make('site_tagline')
                    ->label('Tagline')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn () => $this->refreshPreview()),
                
                Forms\Components\FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('theme')
                    ->imageEditor()
                    ->live()
                    ->visible(fn () => $customizableElements['logo'] ?? false)
                    ->afterStateUpdated(fn () => $this->refreshPreview()),
            ]);
        
        // Colors tab - only if enabled
        if ($customizableElements['colors'] ?? false) {
            $tabs[] = Forms\Components\Tabs\Tab::make('Colors')
                ->icon('heroicon-o-swatch')
                ->schema([
                    Forms\Components\ColorPicker::make('primary_color')
                        ->label('Primary Color')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                    
                    Forms\Components\ColorPicker::make('accent_color')
                        ->label('Accent Color')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                ]);
        }
        
        // Hero Section tab - only if enabled
        if ($customizableElements['hero_section'] ?? false) {
            $tabs[] = Forms\Components\Tabs\Tab::make('Hero Section')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\TextInput::make('hero_title')
                        ->label('Title')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                    
                    Forms\Components\TextInput::make('hero_subtitle')
                        ->label('Subtitle')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                    
                    Forms\Components\Textarea::make('hero_description')
                        ->label('Description')
                        ->rows(3)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                    
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('hero_primary_button_text')
                                ->label('Primary Button Text')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->refreshPreview()),
                            
                            Forms\Components\TextInput::make('hero_primary_button_url')
                                ->label('Primary Button URL')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->refreshPreview()),
                        ]),
                    
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('hero_secondary_button_text')
                                ->label('Secondary Button Text')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->refreshPreview()),
                            
                            Forms\Components\TextInput::make('hero_secondary_button_url')
                                ->label('Secondary Button URL')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->refreshPreview()),
                        ]),
                ]);
        }
        
        // Typography tab - only if enabled
        if ($customizableElements['typography'] ?? false) {
            $tabs[] = Forms\Components\Tabs\Tab::make('Typography')
                ->icon('heroicon-o-language')
                ->schema([
                    Forms\Components\Select::make('font_family')
                        ->label('Font Family')
                        ->options([
                            'system-ui' => 'System Default',
                            'Inter' => 'Inter',
                            'Roboto' => 'Roboto',
                            'Open Sans' => 'Open Sans',
                            'Lato' => 'Lato',
                            'Montserrat' => 'Montserrat',
                        ])
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                    
                    Forms\Components\Select::make('font_size')
                        ->label('Base Font Size')
                        ->options([
                            '14px' => 'Small (14px)',
                            '16px' => 'Medium (16px)',
                            '18px' => 'Large (18px)',
                            '20px' => 'Extra Large (20px)',
                        ])
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                ]);
        }
        
        // Layout tab - only if enabled
        if ($customizableElements['layout'] ?? false) {
            $tabs[] = Forms\Components\Tabs\Tab::make('Layout')
                ->icon('heroicon-o-squares-2x2')
                ->schema([
                    Forms\Components\Select::make('layout_width')
                        ->label('Content Width')
                        ->options([
                            'boxed' => 'Boxed (1200px)',
                            'wide' => 'Wide (1400px)',
                            'full' => 'Full Width',
                        ])
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                    
                    Forms\Components\Toggle::make('sidebar_enabled')
                        ->label('Enable Sidebar')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn () => $this->refreshPreview()),
                ]);
        }
        
        // Footer tab
        $tabs[] = Forms\Components\Tabs\Tab::make('Footer')
            ->icon('heroicon-o-rectangle-group')
            ->schema([
                Forms\Components\Textarea::make('footer_text')
                    ->label('Footer Text')
                    ->rows(2)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn () => $this->refreshPreview()),
            ]);
        
        // Custom CSS tab - only if enabled
        if ($customizableElements['custom_css'] ?? true) {
            $tabs[] = Forms\Components\Tabs\Tab::make('Custom CSS')
                ->icon('heroicon-o-code-bracket')
                ->schema([
                    Forms\Components\Textarea::make('custom_css')
                        ->label('Additional CSS')
                        ->rows(10)
                        ->helperText('Add custom CSS to override theme styles'),
                ]);
        }
        
        return $form
            ->schema([
                Forms\Components\Tabs::make('Customizer')
                    ->tabs($tabs)
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Check if VP Essential helper functions are available
        if (!function_exists('vp_set_theme_setting')) {
            Notification::make()
                ->title('Unable to save')
                ->body('VP Essential 1 module is required. Please ensure it is enabled.')
                ->warning()
                ->send();
            return;
        }

        foreach ($data as $key => $value) {
            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_array($value)) {
                $type = 'json';
            }

            vp_set_theme_setting($key, $value, $type, 'theme');
        }

        Notification::make()
            ->title('Theme customization saved')
            ->success()
            ->send();
            
        $this->refreshPreview();
    }

    protected function refreshPreview(): void
    {
        $this->dispatch('refresh-preview');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('activate')
                ->label('Activate & Publish')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => !$this->record->is_active)
                ->requiresConfirmation()
                ->modalHeading('Activate Theme')
                ->modalDescription('This will deactivate the current theme and make this theme live on your website.')
                ->action(function () {
                    $this->save();
                    
                    // Use the model's activate method which handles cache clearing
                    $this->record->activate();
                    
                    Notification::make()
                        ->title('Theme activated')
                        ->body('Your changes are now live on the frontend! Visit the homepage to see it.')
                        ->success()
                        ->send();
                        
                    return redirect()->route('filament.admin.resources.themes.index');
                }),
            
            \Filament\Actions\Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-check')
                ->action(fn () => $this->save()),
        ];
    }

    public function getTitle(): string
    {
        return 'Customize: ' . $this->record->name;
    }
}
