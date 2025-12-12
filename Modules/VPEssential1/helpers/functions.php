<?php

/**
 * VP Essential 1 - Helper Functions
 * 
 * Global helper functions for theme integration and CMS functionality.
 * These functions are automatically loaded when VP Essential 1 is active.
 * 
 * @package VPEssential1
 * @version 1.0.0
 */

use Modules\VPEssential1\Models\ThemeSetting;
use Modules\VPEssential1\Models\Menu;
use Modules\VPEssential1\Models\WidgetArea;
use Modules\VPEssential1\Models\UserProfile;

if (!function_exists('vp_get_theme_setting')) {
    /**
     * Get a theme setting value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function vp_get_theme_setting(string $key, $default = null)
    {
        // Don't cache in customizer preview to see changes immediately
        $setting = ThemeSetting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('vp_set_theme_setting')) {
    /**
     * Set a theme setting value
     * 
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return bool
     */
    function vp_set_theme_setting(string $key, $value, string $type = 'string', string $group = 'general'): bool
    {
        try {
            ThemeSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'group' => $group,
                ]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('vp_get_menu')) {
    /**
     * Get menu items by location
     * 
     * @param string $location
     * @return array
     */
    function vp_get_menu(string $location): array
    {
        $menu = Menu::where('location', $location)
            ->where('is_active', true)
            ->first();
        
        if (!$menu) {
            return [];
        }
        
        return $menu->items->map(function($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'target' => $item->target,
                'icon' => $item->icon,
                'parent_id' => $item->parent_id,
            ];
        })->toArray();
    }
}

if (!function_exists('vp_get_widget_area')) {
    /**
     * Get rendered widget area HTML
     * 
     * @param string $slug
     * @return string
     */
    function vp_get_widget_area(string $slug): string
    {
        $widgetArea = WidgetArea::where('slug', $slug)
            ->where('is_active', true)
            ->with(['widgets' => function($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->first();
        
        if (!$widgetArea || $widgetArea->widgets->isEmpty()) {
            return '';
        }
        
        $html = '<div class="widget-area widget-area-' . $slug . '">';
        
        foreach ($widgetArea->widgets as $widget) {
            $html .= '<div class="widget widget-' . $widget->type . '">';
            
            if ($widget->title) {
                $html .= '<h3 class="widget-title">' . e($widget->title) . '</h3>';
            }
            
            $html .= '<div class="widget-content">';
            $html .= vp_render_widget($widget);
            $html .= '</div>';
            
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('vp_render_widget')) {
    /**
     * Render a single widget
     * 
     * @param \Modules\VPEssential1\Models\Widget $widget
     * @return string
     */
    function vp_render_widget($widget): string
    {
        return match($widget->type) {
            'text' => nl2br(e($widget->content)),
            'html' => $widget->content, // Allow HTML for this type
            'menu' => vp_render_menu_widget($widget),
            'recent_posts' => vp_render_recent_posts_widget($widget),
            default => '',
        };
    }
}

if (!function_exists('vp_render_menu_widget')) {
    /**
     * Render menu widget
     * 
     * @param \Modules\VPEssential1\Models\Widget $widget
     * @return string
     */
    function vp_render_menu_widget($widget): string
    {
        $menuId = $widget->settings['menu_id'] ?? null;
        if (!$menuId) return '';
        
        $menu = Menu::find($menuId);
        if (!$menu) return '';
        
        $html = '<ul class="widget-menu">';
        foreach ($menu->items as $item) {
            $html .= '<li>';
            $html .= '<a href="' . $item->url . '">' . e($item->title) . '</a>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }
}

if (!function_exists('vp_render_recent_posts_widget')) {
    /**
     * Render recent posts widget
     * 
     * @param \Modules\VPEssential1\Models\Widget $widget
     * @return string
     */
    function vp_render_recent_posts_widget($widget): string
    {
        $limit = $widget->settings['limit'] ?? 5;
        $posts = \App\Models\Page::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        if ($posts->isEmpty()) {
            return '<p class="text-gray-500">No posts yet.</p>';
        }
        
        $html = '<ul class="widget-posts">';
        foreach ($posts as $post) {
            $html .= '<li>';
            $html .= '<a href="' . url('/pages/' . $post->slug) . '">' . e($post->title) . '</a>';
            $html .= '<span class="post-date">' . $post->created_at->format('M d, Y') . '</span>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }
}

if (!function_exists('vp_get_hero_config')) {
    /**
     * Get hero section configuration
     * 
     * @return array
     */
    function vp_get_hero_config(): array
    {
        return [
            'title' => vp_get_theme_setting('hero_title', 'Rise of the Villain'),
            'subtitle' => vp_get_theme_setting('hero_subtitle', 'Unleash the power of VantaPress CMS'),
            'description' => vp_get_theme_setting('hero_description', 'A modular, themeable, and powerful content management system built for developers who dare to be different.'),
            'cta_primary_text' => vp_get_theme_setting('hero_cta_primary_text', 'Get Started'),
            'cta_primary_url' => vp_get_theme_setting('hero_cta_primary_url', url('/pages')),
            'cta_secondary_text' => vp_get_theme_setting('hero_cta_secondary_text', 'View Docs'),
            'cta_secondary_url' => vp_get_theme_setting('hero_cta_secondary_url', url('/docs')),
            'background_type' => vp_get_theme_setting('hero_background_type', 'gradient'),
            'background_image' => vp_get_theme_setting('hero_background_image', ''),
            'show_cta' => vp_get_theme_setting('hero_show_cta', true),
        ];
    }
}

if (!function_exists('vp_get_current_user_profile')) {
    /**
     * Get the current authenticated user's profile
     * 
     * @return \Modules\VPEssential1\Models\UserProfile|null
     */
    function vp_get_current_user_profile()
    {
        if (!auth()->check()) {
            return null;
        }
        
        return UserProfile::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'display_name' => auth()->user()->name,
            ]
        );
    }
}

if (!function_exists('vp_get_user_profile')) {
    /**
     * Get a user's profile by user ID
     * 
     * @param int $userId
     * @return \Modules\VPEssential1\Models\UserProfile|null
     */
    function vp_get_user_profile(int $userId)
    {
        return UserProfile::where('user_id', $userId)->first();
    }
}

if (!function_exists('vp_get_recent_tweets')) {
    /**
     * Get recent tweets
     * 
     * @param int $limit
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function vp_get_recent_tweets(int $limit = 10, ?int $userId = null)
    {
        $query = \Modules\VPEssential1\Models\Tweet::where('is_published', true)
            ->whereNull('reply_to_id')
            ->with(['user', 'likes'])
            ->orderBy('created_at', 'desc');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->limit($limit)->get();
    }
}
if (!function_exists('vp_user_url')) {
    /**
     * Get user profile URL using username or ID
     * 
     * @param mixed $user User object or ID
     * @param bool $useUsername Whether to use username (true) or ID (false)
     * @return string
     */
    function vp_user_url($user, bool $useUsername = true): string
    {
        if (is_numeric($user)) {
            $user = \App\Models\User::find($user);
        }
        
        if (!$user) {
            return '#';
        }
        
        $identifier = ($useUsername && $user->username) ? $user->username : $user->id;
        return route('social.profile.user', $identifier);
    }
}

if (!function_exists('vp_permalink_setting')) {
    /**
     * Get the permalink format setting
     * 
     * @param string $type Type of permalink (profile, messages, etc.)
     * @return string 'username' or 'id'
     */
    function vp_permalink_setting(string $type = 'profile'): string
    {
        return \Modules\VPEssential1\Models\SocialSetting::getValue("permalink_{$type}", 'username');
    }
}