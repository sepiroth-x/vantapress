# VantaPress Theme System

## Overview

VantaPress uses a **theme-based styling system** where the active theme controls ALL visual styling of the admin panel. The structure stays consistent (provided by Filament), but colors, shadows, borders, and visual aesthetics come from the active theme.

## Available Themes

VantaPress ships with **2 themes**:

1. **BasicTheme** (Default Active Theme)
   - Display Name: "The Basic Theme (The Beginning)"
   - Directory: `themes/BasicTheme/`
   - Style: Clean, professional, modern blue/gray palette
   - Status: **Default and currently active**

2. **TheVillainArise**
   - Display Name: "The Villain Arise"
   - Directory: `themes/TheVillainArise/`
   - Style: Custom themed design
   - Status: Available for activation

## Theme Architecture

### Directory Structure

```
themes/
├── BasicTheme/
│   ├── theme.json              # Theme metadata
│   ├── theme.php               # Theme configuration
│   └── assets/
│       ├── css/
│       │   ├── admin.css      # Admin panel styling (IMPORTANT!)
│       │   └── theme.css      # Public site styling
│       └── js/
│           └── theme.js       # Theme JavaScript
│
└── TheVillainArise/
    └── assets/
        └── css/
            ├── admin.css
            └── theme.css
```

### Asset Syncing

Theme assets MUST be synced to root-level directories for web access:

```
themes/BasicTheme/assets/css/admin.css  →  css/themes/BasicTheme/admin.css
themes/BasicTheme/assets/css/theme.css  →  css/themes/BasicTheme/theme.css
themes/BasicTheme/assets/js/theme.js    →  js/themes/BasicTheme/theme.js
```

**Sync Command:**
```bash
php sync-theme-assets.php
```

Run this command whenever you edit theme CSS/JS files.

## CSS Loading Order

The admin panel loads CSS in this specific order:

1. **Filament Base CSS** (auto-loaded by FilamentPHP)
   - `css/filament/support/support.css`
   - `css/filament/forms/forms.css`
   - `css/filament/filament/app.css`

2. **VantaPress Layout CSS** (structure only)
   - `css/vantapress-admin.css`
   - Contains ONLY layout/structure rules (flexbox, positioning)
   - NO colors, shadows, or visual styling

3. **Active Theme CSS** (all visual styling)
   - `css/themes/{ActiveTheme}/admin.css`
   - Contains ALL visual styling (colors, shadows, borders, etc.)
   - This is where the design comes from!

## Active Theme Configuration

The active theme is set in `config/cms.php`:

```php
'themes' => [
    'path' => 'themes',
    'active' => 'BasicTheme',           // Current active theme
    'active_theme' => 'BasicTheme',     // Alternative config key
    'fallback_theme' => 'BasicTheme',   // Fallback if active fails
    'cache_enabled' => true,
    'cache_key' => 'cms_themes',
    'cache_lifetime' => 3600,
]
```

**IMPORTANT:** There is NO "default" theme! The config previously had `'active' => 'default'` which caused CSS loading failures. Always use actual theme names like `'BasicTheme'` or `'TheVillainArise'`.

## Creating a New Theme

1. Create theme directory: `themes/YourTheme/`
2. Create `assets/css/admin.css` with your custom styling
3. Sync assets: `php sync-theme-assets.php`
4. Update config: `'active_theme' => 'YourTheme'` in `config/cms.php`
5. Clear cache: `php artisan config:clear && php artisan cache:clear`

## Theme CSS Guidelines

### What Goes in `vantapress-admin.css` (Root CSS)

- Flexbox layout rules
- Grid positioning
- Element structure
- Responsive breakpoints
- Z-index layers
- **NO colors, shadows, borders, or visual styling!**

### What Goes in Theme `admin.css`

- ALL colors (backgrounds, text, borders)
- Shadows and elevation
- Border styles and radius
- Typography styling
- Hover effects
- Transitions and animations
- Dark mode and light mode styling
- Login page design
- **Everything the user sees!**

## Troubleshooting

### CSS Not Loading

1. Check active theme in `config/cms.php`
2. Verify theme directory exists: `themes/{ActiveTheme}/`
3. Sync assets: `php sync-theme-assets.php`
4. Verify synced file: `css/themes/{ActiveTheme}/admin.css`
5. Clear cache: `php artisan config:clear && php artisan view:clear`
6. Hard refresh browser: `Ctrl + F5`

### "default" Theme Errors

If you see 404 errors for `css/themes/default/admin.css`, the config is pointing to a non-existent theme. Change it to `'BasicTheme'` or `'TheVillainArise'`.

### Changes Not Appearing

1. Edit theme file: `themes/BasicTheme/assets/css/admin.css`
2. Sync: `php sync-theme-assets.php`
3. Clear caches: `php artisan config:clear && php artisan cache:clear`
4. Hard refresh: `Ctrl + F5`

## Design Philosophy

- **Structure is consistent** - All admin panels use the same Filament structure
- **Design is flexible** - Themes provide unique visual identities
- **No public/ folder** - All assets served from root level
- **Active theme controls everything visual** - One source of truth for styling

## Current State (v1.0.13)

- **Active Theme:** BasicTheme
- **Design Style:** Clean professional with blue/gray palette
- **Admin Panel:** Modern, accessible, with smooth transitions
- **Login Page:** Glass morphism with gradient background
- **Dark Mode:** Full support with slate colors
- **Light Mode:** Full support with white/blue colors
