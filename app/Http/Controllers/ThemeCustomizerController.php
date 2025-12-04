<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ThemeCustomizerController extends Controller
{
    /**
     * Display the theme customizer
     */
    public function show($id)
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
        
        // Get current settings
        $settings = [
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
        ];
        
        $previewUrl = url('/?theme_preview=' . urlencode($themeSlug));
        
        return view('customizer.index', compact('theme', 'themeMetadata', 'settings', 'previewUrl'));
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
        
        // Clear cache to ensure changes are reflected
        \Illuminate\Support\Facades\Cache::flush();
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully'
        ]);
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
}
