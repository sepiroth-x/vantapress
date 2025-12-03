# Theme Activation System - Implementation Guide

## Overview
The VantaPress CMS now fully supports dynamic theme activation. When you activate a theme, the homepage automatically displays that theme's design.

## How It Works

### 1. Database-Driven Theme System
- Themes are stored in the `themes` table with an `is_active` column
- Only one theme can be active at a time
- The active theme is loaded automatically on every frontend request

### 2. Homepage Integration
**Route: `routes/web.php`**
```php
Route::get('/', function () {
    // Check if there's an active theme
    $activeTheme = Theme::where('is_active', true)->first();
    
    if ($activeTheme) {
        // Try to load theme's home page
        $themePath = base_path('themes/' . $activeTheme->slug);
        $homeViewPath = $themePath . '/pages/home.blade.php';
        
        if (file_exists($homeViewPath)) {
            // Theme has a home page, use it
            return view('theme.pages::home');
        }
    }
    
    // Fallback to default welcome page
    return view('welcome');
})->name('home');
```

### 3. Middleware Integration
**ThemeMiddleware** (`app/Http/Middleware/ThemeMiddleware.php`):
- Checks database for active theme
- Loads theme views, layouts, components, and assets
- Supports preview mode for the customizer (`?theme_preview=slug`)

### 4. Automatic Cache Clearing
When a theme is activated via `Theme::activate()`:
- All other themes are deactivated
- View cache is cleared
- Application cache is cleared
- Theme cache is cleared
- Changes take effect immediately

## Theme Structure Requirements

For a theme to work as a homepage, it needs:

```
themes/YourTheme/
├── pages/
│   └── home.blade.php          # Homepage template
├── layouts/
│   └── main.blade.php          # Main layout
├── components/
│   └── hero.blade.php          # Reusable components
├── partials/
│   └── header.blade.php        # Partial views
└── theme.json                   # Theme metadata
```

## Activating a Theme

### Method 1: From Admin Panel (Recommended)
1. Go to **Appearance → Themes**
2. Find your theme
3. Click **"Activate"** button
4. Confirm activation
5. Visit homepage to see changes

### Method 2: From Customizer
1. Go to **Appearance → Themes**
2. Click **"Customize"** on your theme
3. Make your changes
4. Click **"Activate & Publish"** button
5. Theme is activated AND customizations are saved

### Method 3: Programmatically
```php
use App\Models\Theme;

$theme = Theme::where('slug', 'TheVillainArise')->first();
$theme->activate(); // Automatically deactivates others and clears cache
```

## View Namespaces

When a theme is loaded, the following namespaces are registered:

- `theme::` - Root views directory
- `theme.layouts::` - Layouts directory
- `theme.components::` - Components directory
- `theme.partials::` - Partials directory
- `theme.pages::` - Pages directory

### Example Usage in Blade:
```php
// In your theme's home.blade.php
@extends('theme.layouts::main')

@section('content')
    @include('theme.components::hero')
    @include('theme.partials::features')
@endsection
```

## Customizer Integration

The WordPress-like customizer automatically:
- Shows live preview of theme with `?theme_preview=slug`
- Saves customizations to `vp_theme_settings` table
- Can activate theme directly from customizer
- Clears caches on activation

## Testing Theme Activation

Run the test script:
```bash
php test-theme-activation.php
```

This checks:
- ✓ Active theme in database
- ✓ Theme files exist
- ✓ Homepage routing is correct

## Current Status

**Active Theme:** The Villain Arise
- Slug: `TheVillainArise`
- Homepage: `themes/TheVillainArise/pages/home.blade.php`
- Layout: `themes/TheVillainArise/layouts/main.blade.php`

## Troubleshooting

### Homepage still shows default welcome page?
1. Check active theme: `php test-theme-activation.php`
2. Clear caches: `php artisan optimize:clear`
3. Verify theme has `pages/home.blade.php`

### Theme not loading after activation?
1. Check database: `SELECT * FROM themes WHERE is_active = 1`
2. Verify theme files exist in `themes/` directory
3. Check Laravel logs: `storage/logs/laravel.log`

### Changes not appearing?
1. Clear view cache: `php artisan view:clear`
2. Clear application cache: `php artisan cache:clear`
3. Or use: `php artisan optimize:clear` (clears everything)

## Benefits

✅ **WordPress-like Experience** - Activate themes with one click
✅ **Live Preview** - See changes before activating
✅ **Automatic Cache Management** - Changes take effect immediately
✅ **Safe Deactivation** - Only one theme active at a time
✅ **Fallback Support** - Falls back to default view if theme missing
✅ **Database-Driven** - Easy to manage programmatically
✅ **Middleware Integration** - Themes load on every request
✅ **Namespace Support** - Clean view organization

## Next Steps

1. Visit http://127.0.0.1:8000/ to see "The Villain Arise" theme
2. Go to admin panel → Appearance → Themes
3. Try activating/deactivating themes
4. Use the customizer to make design changes
5. Create additional themes following the structure guide

---

**Last Updated:** December 3, 2025
**Status:** ✓ Fully Implemented and Tested
