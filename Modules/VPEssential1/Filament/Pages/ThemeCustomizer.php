<?php

namespace Modules\VPEssential1\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class ThemeCustomizer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Theme Customizer';
    protected static ?string $navigationGroup = 'VP Essential';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'VPEssential1::filament.pages.theme-customizer';
    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_title' => vp_get_theme_setting('site_title', config('app.name')),
            'site_tagline' => vp_get_theme_setting('site_tagline', ''),
            'logo' => vp_get_theme_setting('logo', ''),
            'favicon' => vp_get_theme_setting('favicon', ''),
            'primary_color' => vp_get_theme_setting('primary_color', '#dc2626'),
            'accent_color' => vp_get_theme_setting('accent_color', '#991b1b'),
            'hero_title' => vp_get_theme_setting('hero_title', 'The Villain Arise'),
            'hero_subtitle' => vp_get_theme_setting('hero_subtitle', 'A Dark WordPress Alternative'),
            'hero_description' => vp_get_theme_setting('hero_description', 'Build powerful websites with Laravel and Filament.'),
            'hero_primary_button_text' => vp_get_theme_setting('hero_primary_button_text', 'Get Started'),
            'hero_primary_button_url' => vp_get_theme_setting('hero_primary_button_url', '#features'),
            'hero_secondary_button_text' => vp_get_theme_setting('hero_secondary_button_text', 'Learn More'),
            'hero_secondary_button_url' => vp_get_theme_setting('hero_secondary_button_url', '#about'),
            'hero_background_type' => vp_get_theme_setting('hero_background_type', 'gradient'),
            'hero_background_image' => vp_get_theme_setting('hero_background_image', ''),
            'hero_background_gradient_from' => vp_get_theme_setting('hero_background_gradient_from', '#dc2626'),
            'hero_background_gradient_to' => vp_get_theme_setting('hero_background_gradient_to', '#991b1b'),
            'footer_text' => vp_get_theme_setting('footer_text', '© 2024 VantaPress. All rights reserved.'),
            'social_links' => vp_get_theme_setting('social_links', []),
            'enable_dark_mode' => vp_get_theme_setting('enable_dark_mode', true),
            'custom_css' => vp_get_theme_setting('custom_css', ''),
            'custom_js' => vp_get_theme_setting('custom_js', ''),
        ]);
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
                                Forms\Components\TextInput::make('site_title')
                                    ->label('Site Title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('site_tagline')
                                    ->label('Site Tagline')
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->directory('theme')
                                    ->imageEditor(),
                                Forms\Components\FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->directory('theme')
                                    ->acceptedFileTypes(['image/x-icon', 'image/png']),
                                Forms\Components\Toggle::make('enable_dark_mode')
                                    ->label('Enable Dark Mode')
                                    ->default(true),
                            ]),
                        Forms\Components\Tabs\Tab::make('Colors')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Forms\Components\ColorPicker::make('primary_color')
                                    ->label('Primary Color')
                                    ->default('#dc2626'),
                                Forms\Components\ColorPicker::make('accent_color')
                                    ->label('Accent Color')
                                    ->default('#991b1b'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Hero Section')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\TextInput::make('hero_title')
                                    ->label('Hero Title')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('hero_subtitle')
                                    ->label('Hero Subtitle')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('hero_description')
                                    ->label('Hero Description')
                                    ->rows(3)
                                    ->maxLength(500),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_primary_button_text')
                                            ->label('Primary Button Text')
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('hero_primary_button_url')
                                            ->label('Primary Button URL')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_secondary_button_text')
                                            ->label('Secondary Button Text')
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('hero_secondary_button_url')
                                            ->label('Secondary Button URL')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Select::make('hero_background_type')
                                    ->label('Background Type')
                                    ->options([
                                        'gradient' => 'Gradient',
                                        'image' => 'Image',
                                    ])
                                    ->default('gradient')
                                    ->reactive(),
                                Forms\Components\FileUpload::make('hero_background_image')
                                    ->label('Background Image')
                                    ->image()
                                    ->directory('theme/hero')
                                    ->imageEditor()
                                    ->visible(fn ($get) => $get('hero_background_type') === 'image'),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\ColorPicker::make('hero_background_gradient_from')
                                            ->label('Gradient From')
                                            ->default('#dc2626'),
                                        Forms\Components\ColorPicker::make('hero_background_gradient_to')
                                            ->label('Gradient To')
                                            ->default('#991b1b'),
                                    ])
                                    ->visible(fn ($get) => $get('hero_background_type') === 'gradient'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Footer')
                            ->icon('heroicon-o-rectangle-group')
                            ->schema([
                                Forms\Components\Textarea::make('footer_text')
                                    ->label('Footer Text')
                                    ->rows(2)
                                    ->maxLength(500),
                                Forms\Components\Repeater::make('social_links')
                                    ->label('Social Links')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->options([
                                                'twitter' => 'Twitter',
                                                'facebook' => 'Facebook',
                                                'instagram' => 'Instagram',
                                                'linkedin' => 'LinkedIn',
                                                'github' => 'GitHub',
                                                'youtube' => 'YouTube',
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
                            ]),
                        Forms\Components\Tabs\Tab::make('Advanced')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Textarea::make('custom_css')
                                    ->label('Custom CSS')
                                    ->rows(10)
                                    ->helperText('Add custom CSS to override theme styles'),
                                Forms\Components\Textarea::make('custom_js')
                                    ->label('Custom JavaScript')
                                    ->rows(10)
                                    ->helperText('Add custom JavaScript code'),
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
            // Determine type for proper storage
            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_array($value)) {
                $type = 'json';
            } elseif (is_numeric($value)) {
                $type = 'integer';
            }

            vp_set_theme_setting($key, $value, $type, 'theme');
        }

        Notification::make()
            ->title('Theme settings saved')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
