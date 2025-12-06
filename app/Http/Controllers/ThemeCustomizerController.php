<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Services\ThemeElementDetector;
use App\Services\ThemePageDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class ThemeCustomizerController extends Controller
{
    /**
     * Display the theme customizer
     */
    public function show($id, Request $request)
    {
        $theme = Theme::findOrFail($id);
        
        // Get theme metadata
        $themeSlug = (string) $theme->slug;
        $themePath = base_path('themes/' . $themeSlug);
        $metadataPath = $themePath . '/theme.json';
        
        $themeMetadata = [];
        if (File::exists($metadataPath)) {
            $themeMetadata = json_decode(File::get($metadataPath), true) ?? [];
        }

        // Initialize detectors
        $elementDetector = new ThemeElementDetector($themeSlug);
        $pageDetector = new ThemePageDetector($themeSlug);

        // Get detected elements
        $elements = $elementDetector->getGroupedElements();
        $allElements = $elementDetector->getAllElements();

        // Get detected pages
        $pages = $pageDetector->detectPages();
        $pagesByType = $pageDetector->getPagesByType();

        // Get current settings
        $settings = $this->getCurrentSettings($allElements);
        
        // Determine preview mode (frontend or admin)
        $previewMode = $request->get('mode', 'frontend');
        
        // Generate preview URL
        if ($previewMode === 'admin') {
            $previewUrl = route('filament.admin.pages.dashboard') . '?theme_preview=' . urlencode($themeSlug);
        } else {
            $selectedPage = $request->get('page', 'home');
            $pageUrl = $this->getPagePreviewUrl($pages, $selectedPage, $themeSlug);
            $previewUrl = $pageUrl ?? url('/?theme_preview=' . urlencode($themeSlug));
        }
        
        return view('customizer.index', compact(
            'theme',
            'themeMetadata',
            'elements',
            'allElements',
            'pages',
            'pagesByType',
            'settings',
            'previewUrl',
            'previewMode'
        ));
    }

    /**
     * Get current settings for all elements
     */
    protected function getCurrentSettings(array $elements): array
    {
        $settings = [];

        foreach ($elements as $element) {
            $key = $element['id'];
            $default = $element['default'] ?? '';

            if (function_exists('vp_get_theme_setting')) {
                $settings[$key] = vp_get_theme_setting($key, $default);
            } else {
                $settings[$key] = $default;
            }
        }

        // Legacy settings for backward compatibility with old customizer view
        $legacyKeys = [
            'site_title' => config('app.name', 'VantaPress'),
            'site_tagline' => '',
            'logo' => '',
            'primary_color' => '#dc2626',
            'accent_color' => '#991b1b',
            'hero_title' => 'Welcome',
            'hero_subtitle' => '',
            'hero_description' => '',
            'hero_primary_button_text' => 'Get Started',
            'hero_primary_button_url' => '#',
            'hero_secondary_button_text' => 'Learn More',
            'hero_secondary_button_url' => '#',
            'footer_text' => '© 2025 VantaPress',
            'custom_css' => '',
        ];
        
        foreach ($legacyKeys as $key => $default) {
            if (!isset($settings[$key])) {
                if (function_exists('vp_get_theme_setting')) {
                    $settings[$key] = vp_get_theme_setting($key, $default);
                } else {
                    $settings[$key] = $default;
                }
            }
        }

        return $settings;
    }

    /**
     * Get preview URL for specific page
     */
    protected function getPagePreviewUrl(array $pages, string $pageSlug, string $themeSlug): ?string
    {
        foreach ($pages as $page) {
            if ($page['slug'] === $pageSlug) {
                return $page['url'] ?? url("/?preview_page={$pageSlug}&theme_preview={$themeSlug}");
            }
        }

        return null;
    }

    /**
     * Save theme settings via AJAX
     */
    public function save(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        
        // Get all form data except _token
        $data = $request->except(['_token']);
        
        // Check if VP Essential helper functions are available
        if (!function_exists('vp_set_theme_setting')) {
            return response()->json([
                'success' => false,
                'message' => 'VP Essential 1 module is required. Please ensure it is enabled.'
            ], 400);
        }
        
        // Save each setting
        foreach ($data as $key => $value) {
            $type = 'string';
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            }
            
            vp_set_theme_setting($key, $value, $type, 'theme');
        }
        
        // Clear ALL caches to ensure changes are reflected immediately
        Cache::flush();
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully'
        ]);
    }

    /**
     * Reset theme settings to defaults
     */
    public function reset(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        
        try {
            // Load theme metadata
            $themeLoader = app(ThemeLoader::class);
            $themeMetadata = $themeLoader->loadTheme($theme->slug);
            
            if (!$themeMetadata) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not load theme metadata'
                ], 404);
            }
            
            // Get all customizer elements with defaults
            $elements = [];
            if (isset($themeMetadata['customizer']['sections'])) {
                foreach ($themeMetadata['customizer']['sections'] as $section) {
                    if (isset($section['elements'])) {
                        foreach ($section['elements'] as $element) {
                            $elements[$element['id']] = $element['default'] ?? '';
                        }
                    }
                }
            }
            
            // Delete all existing settings for this theme
            if (function_exists('vp_delete_theme_settings')) {
                vp_delete_theme_settings();
            }
            
            // Set default values
            if (function_exists('vp_set_theme_setting')) {
                foreach ($elements as $key => $value) {
                    $type = is_bool($value) ? 'boolean' : 'string';
                    vp_set_theme_setting($key, $value, $type, 'theme');
                }
            }
            
            // Clear cache
            Cache::flush();
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Settings reset to defaults'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resetting settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate theme via AJAX
     */
    public function activate(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        $theme->activate();
        
        return response()->json([
            'success' => true,
            'message' => 'Theme activated successfully',
            'redirect' => route('filament.admin.resources.themes.index')
        ]);
    }

    /**
     * Get theme elements via AJAX
     */
    public function getElements($id)
    {
        $theme = Theme::findOrFail($id);
        $detector = new ThemeElementDetector($theme->slug);
        
        return response()->json([
            'success' => true,
            'elements' => $detector->getGroupedElements(),
        ]);
    }

    /**
     * Get theme pages via AJAX
     */
    public function getPages($id)
    {
        $theme = Theme::findOrFail($id);
        $detector = new ThemePageDetector($theme->slug);
        
        return response()->json([
            'success' => true,
            'pages' => $detector->detectPages(),
            'pagesByType' => $detector->getPagesByType(),
        ]);
    }
}
