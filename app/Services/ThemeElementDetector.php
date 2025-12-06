<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Theme Element Detector Service
 * Detects customizable elements using standardized approach:
 * 1. Read theme.json customizer schema (primary source)
 * 2. Scan HTML for data-vp-element attributes
 * 3. Auto-detect common patterns (fallback)
 */
class ThemeElementDetector
{
    protected $themePath;
    protected $themeSlug;
    protected $themeConfig = [];
    protected $detectedElements = [];

    public function __construct($themeSlug)
    {
        $this->themeSlug = $themeSlug;
        $this->themePath = base_path("themes/{$themeSlug}");
        $this->loadThemeConfig();
    }

    /**
     * Load theme.json configuration
     */
    protected function loadThemeConfig()
    {
        $configPath = $this->themePath . '/theme.json';
        
        if (File::exists($configPath)) {
            $json = File::get($configPath);
            $this->themeConfig = json_decode($json, true) ?? [];
        }
    }

    /**
     * Detect all elements in the active theme
     * Priority: theme.json > data attributes > auto-detection
     */
    public function detectElements(): array
    {
        // Priority 1: Read from theme.json (highest priority)
        if (isset($this->themeConfig['customizer']['sections'])) {
            return $this->detectFromThemeJson();
        }

        // Priority 2: Fallback to auto-detection
        return $this->detectFromFiles();
    }

    /**
     * Detect elements from theme.json customizer schema
     */
    protected function detectFromThemeJson(): array
    {
        $sections = $this->themeConfig['customizer']['sections'] ?? [];
        $grouped = [];

        foreach ($sections as $sectionId => $sectionData) {
            $elements = $sectionData['elements'] ?? [];
            
            foreach ($elements as $element) {
                // Ensure element has required fields
                if (!isset($element['id']) || !isset($element['label'])) {
                    continue;
                }

                // Add section reference
                $element['section'] = $sectionId;
                $element['editable'] = true;
                
                // Ensure default is set
                if (!isset($element['default'])) {
                    $element['default'] = $this->getDefaultForType($element['type'] ?? 'text');
                }

                $grouped[$sectionId][] = $element;
            }
        }

        return $grouped;
    }

    /**
     * Get default value based on field type
     */
    protected function getDefaultForType(string $type)
    {
        $defaults = [
            'text' => '',
            'textarea' => '',
            'image' => '',
            'color' => '#000000',
            'toggle' => false,
            'select' => '',
            'range' => 0,
        ];

        return $defaults[$type] ?? '';
    }

    /**
     * Fallback: Detect elements from theme files
     */
    protected function detectFromFiles(): array
    {
        $elements = [
            'site' => $this->detectSiteElements(),
            'header' => $this->detectHeaderElements(),
            'hero' => $this->detectHeroElements(),
            'footer' => $this->detectFooterElements(),
            'colors' => $this->detectColorElements(),
            'typography' => $this->detectTypographyElements(),
            'layout' => $this->detectLayoutElements(),
            'components' => $this->detectComponents(),
            'widgets' => $this->detectWidgetAreas(),
        ];

        return $elements;
    }

    /**
     * Detect site-level elements
     */
    protected function detectSiteElements(): array
    {
        return [
            [
                'id' => 'site_title',
                'label' => 'Site Title',
                'type' => 'text',
                'section' => 'site',
                'default' => config('app.name', 'VantaPress'),
                'editable' => true,
            ],
            [
                'id' => 'site_tagline',
                'label' => 'Tagline',
                'type' => 'text',
                'section' => 'site',
                'default' => '',
                'editable' => true,
            ],
            [
                'id' => 'site_logo',
                'label' => 'Logo',
                'type' => 'image',
                'section' => 'site',
                'default' => '',
                'editable' => true,
            ],
            [
                'id' => 'site_favicon',
                'label' => 'Favicon',
                'type' => 'image',
                'section' => 'site',
                'default' => '',
                'editable' => true,
            ],
        ];
    }

    /**
     * Detect header elements
     */
    protected function detectHeaderElements(): array
    {
        $elements = [];
        $headerPath = $this->themePath . '/components/header.blade.php';

        if (File::exists($headerPath)) {
            $content = File::get($headerPath);

            // Detect navigation menu
            if (Str::contains($content, ['<nav', 'navigation', 'menu'])) {
                $elements[] = [
                    'id' => 'header_menu_location',
                    'label' => 'Header Menu',
                    'type' => 'menu',
                    'section' => 'header',
                    'default' => 'primary',
                    'editable' => true,
                ];
            }

            // Detect logo in header
            if (Str::contains($content, ['logo', 'site-logo'])) {
                $elements[] = [
                    'id' => 'header_logo_position',
                    'label' => 'Logo Position',
                    'type' => 'select',
                    'section' => 'header',
                    'options' => ['left', 'center', 'right'],
                    'default' => 'left',
                    'editable' => true,
                ];
            }

            // Detect sticky header
            $elements[] = [
                'id' => 'header_sticky',
                'label' => 'Sticky Header',
                'type' => 'toggle',
                'section' => 'header',
                'default' => false,
                'editable' => true,
            ];

            $elements[] = [
                'id' => 'header_background_color',
                'label' => 'Header Background Color',
                'type' => 'color',
                'section' => 'header',
                'default' => '#ffffff',
                'editable' => true,
            ];
        }

        return $elements;
    }

    /**
     * Detect hero section elements
     */
    protected function detectHeroElements(): array
    {
        $elements = [];
        $heroPath = $this->themePath . '/components/hero.blade.php';

        if (File::exists($heroPath)) {
            $content = File::get($heroPath);

            $elements[] = [
                'id' => 'hero_title',
                'label' => 'Hero Title',
                'type' => 'text',
                'section' => 'hero',
                'default' => 'Welcome to VantaPress',
                'editable' => true,
                'preview_selector' => '.hero-title',
            ];

            $elements[] = [
                'id' => 'hero_subtitle',
                'label' => 'Hero Subtitle',
                'type' => 'text',
                'section' => 'hero',
                'default' => '',
                'editable' => true,
                'preview_selector' => '.hero-subtitle',
            ];

            $elements[] = [
                'id' => 'hero_description',
                'label' => 'Hero Description',
                'type' => 'textarea',
                'section' => 'hero',
                'default' => '',
                'editable' => true,
                'preview_selector' => '.hero-description',
            ];

            if (Str::contains($content, ['background', 'bg-image'])) {
                $elements[] = [
                    'id' => 'hero_background_image',
                    'label' => 'Background Image',
                    'type' => 'image',
                    'section' => 'hero',
                    'default' => '',
                    'editable' => true,
                ];
            }

            // Detect buttons
            if (Str::contains($content, ['button', 'btn', 'cta'])) {
                $elements[] = [
                    'id' => 'hero_primary_button_text',
                    'label' => 'Primary Button Text',
                    'type' => 'text',
                    'section' => 'hero',
                    'default' => 'Get Started',
                    'editable' => true,
                    'preview_selector' => '.hero-button-primary',
                ];

                $elements[] = [
                    'id' => 'hero_primary_button_url',
                    'label' => 'Primary Button URL',
                    'type' => 'text',
                    'section' => 'hero',
                    'default' => '#',
                    'editable' => true,
                ];
            }
        }

        return $elements;
    }

    /**
     * Detect footer elements
     */
    protected function detectFooterElements(): array
    {
        $elements = [];
        $footerPath = $this->themePath . '/components/footer.blade.php';

        if (File::exists($footerPath)) {
            $content = File::get($footerPath);

            $elements[] = [
                'id' => 'footer_text',
                'label' => 'Footer Text',
                'type' => 'textarea',
                'section' => 'footer',
                'default' => '© 2025 VantaPress',
                'editable' => true,
                'preview_selector' => '.footer-text',
            ];

            // Detect social links
            if (Str::contains($content, ['social', 'facebook', 'twitter', 'instagram'])) {
                $elements[] = [
                    'id' => 'footer_show_social',
                    'label' => 'Show Social Links',
                    'type' => 'toggle',
                    'section' => 'footer',
                    'default' => true,
                    'editable' => true,
                ];
            }

            $elements[] = [
                'id' => 'footer_background_color',
                'label' => 'Footer Background Color',
                'type' => 'color',
                'section' => 'footer',
                'default' => '#1e293b',
                'editable' => true,
            ];

            $elements[] = [
                'id' => 'footer_text_color',
                'label' => 'Footer Text Color',
                'type' => 'color',
                'section' => 'footer',
                'default' => '#ffffff',
                'editable' => true,
            ];
        }

        return $elements;
    }

    /**
     * Detect color scheme elements
     */
    protected function detectColorElements(): array
    {
        return [
            [
                'id' => 'primary_color',
                'label' => 'Primary Color',
                'type' => 'color',
                'section' => 'colors',
                'default' => '#dc2626',
                'editable' => true,
            ],
            [
                'id' => 'secondary_color',
                'label' => 'Secondary Color',
                'type' => 'color',
                'section' => 'colors',
                'default' => '#991b1b',
                'editable' => true,
            ],
            [
                'id' => 'accent_color',
                'label' => 'Accent Color',
                'type' => 'color',
                'section' => 'colors',
                'default' => '#f59e0b',
                'editable' => true,
            ],
            [
                'id' => 'text_color',
                'label' => 'Text Color',
                'type' => 'color',
                'section' => 'colors',
                'default' => '#1e293b',
                'editable' => true,
            ],
            [
                'id' => 'link_color',
                'label' => 'Link Color',
                'type' => 'color',
                'section' => 'colors',
                'default' => '#3b82f6',
                'editable' => true,
            ],
        ];
    }

    /**
     * Detect typography elements
     */
    protected function detectTypographyElements(): array
    {
        return [
            [
                'id' => 'font_family_heading',
                'label' => 'Heading Font',
                'type' => 'select',
                'section' => 'typography',
                'options' => [
                    'inherit' => 'System Default',
                    'Arial, sans-serif' => 'Arial',
                    'Georgia, serif' => 'Georgia',
                    'Helvetica, sans-serif' => 'Helvetica',
                    '"Times New Roman", serif' => 'Times New Roman',
                    'Verdana, sans-serif' => 'Verdana',
                ],
                'default' => 'inherit',
                'editable' => true,
            ],
            [
                'id' => 'font_family_body',
                'label' => 'Body Font',
                'type' => 'select',
                'section' => 'typography',
                'options' => [
                    'inherit' => 'System Default',
                    'Arial, sans-serif' => 'Arial',
                    'Georgia, serif' => 'Georgia',
                    'Helvetica, sans-serif' => 'Helvetica',
                    '"Times New Roman", serif' => 'Times New Roman',
                    'Verdana, sans-serif' => 'Verdana',
                ],
                'default' => 'inherit',
                'editable' => true,
            ],
            [
                'id' => 'font_size_base',
                'label' => 'Base Font Size',
                'type' => 'range',
                'section' => 'typography',
                'min' => 12,
                'max' => 24,
                'step' => 1,
                'default' => 16,
                'unit' => 'px',
                'editable' => true,
            ],
        ];
    }

    /**
     * Detect layout elements
     */
    protected function detectLayoutElements(): array
    {
        return [
            [
                'id' => 'layout_width',
                'label' => 'Content Width',
                'type' => 'select',
                'section' => 'layout',
                'options' => [
                    'contained' => 'Contained (1200px)',
                    'wide' => 'Wide (1400px)',
                    'full' => 'Full Width',
                ],
                'default' => 'contained',
                'editable' => true,
            ],
            [
                'id' => 'layout_sidebar_position',
                'label' => 'Sidebar Position',
                'type' => 'select',
                'section' => 'layout',
                'options' => [
                    'none' => 'No Sidebar',
                    'left' => 'Left',
                    'right' => 'Right',
                ],
                'default' => 'right',
                'editable' => true,
            ],
        ];
    }

    /**
     * Detect theme components
     */
    protected function detectComponents(): array
    {
        $components = [];
        $componentsPath = $this->themePath . '/components';

        if (File::isDirectory($componentsPath)) {
            $files = File::files($componentsPath);

            foreach ($files as $file) {
                $componentName = $file->getFilenameWithoutExtension();
                
                $components[] = [
                    'id' => "component_{$componentName}",
                    'label' => ucfirst(str_replace('-', ' ', $componentName)),
                    'type' => 'component',
                    'section' => 'components',
                    'path' => $file->getPathname(),
                    'editable' => true,
                ];
            }
        }

        return $components;
    }

    /**
     * Detect widget areas
     */
    protected function detectWidgetAreas(): array
    {
        $widgetAreas = [];
        $themePath = $this->themePath;

        // Scan all blade files for widget areas
        $bladeFiles = File::allFiles($themePath);

        foreach ($bladeFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());

                // Look for @widget or widget() calls
                preg_match_all('/@widget\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);
                
                foreach ($matches[1] as $widgetArea) {
                    if (!isset($widgetAreas[$widgetArea])) {
                        $widgetAreas[$widgetArea] = [
                            'id' => "widget_area_{$widgetArea}",
                            'label' => ucfirst(str_replace('_', ' ', $widgetArea)),
                            'type' => 'widget_area',
                            'section' => 'widgets',
                            'name' => $widgetArea,
                            'editable' => true,
                        ];
                    }
                }
            }
        }

        return array_values($widgetAreas);
    }

    /**
     * Get all detected elements as flat array
     */
    public function getAllElements(): array
    {
        $all = $this->detectElements();
        $flat = [];

        foreach ($all as $section => $elements) {
            $flat = array_merge($flat, $elements);
        }

        return $flat;
    }

    /**
     * Get elements grouped by section
     */
    public function getGroupedElements(): array
    {
        return $this->detectElements();
    }
}
