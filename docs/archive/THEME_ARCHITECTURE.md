# VantaPress Theme Architecture

## Core Concept: Unified Theme System

In VantaPress, **themes control EVERYTHING** - both the frontend website and the admin panel. This is a fundamental architectural decision that ensures consistency and portability.

### What Themes Control

1. **Frontend (Public Website)**
   - Page layouts (home, about, contact, etc.)
   - Header and footer components
   - Navigation menus
   - Typography and color schemes
   - All visual styling

2. **Admin Panel (Backend Dashboard)**
   - Filament sidebar design
   - Dashboard card styling
   - Form inputs and buttons
   - Tables and data displays
   - Light and dark mode aesthetics
   - All admin UI elements

### Why This Matters

When an admin activates a theme:
- ✅ **Frontend changes** - Website reflects theme's design
- ✅ **Admin panel changes** - Dashboard matches theme's aesthetics
- ✅ **Consistency** - Both areas share the same design language
- ✅ **Portability** - Export theme as ZIP, works anywhere

## Theme Structure

```
themes/
└── BasicTheme/                      # Default theme
    ├── theme.json                   # Theme metadata
    ├── assets/
    │   └── css/
    │       ├── theme.css            # Frontend styles ⭐
    │       └── admin.css            # Admin panel styles ⭐
    ├── layouts/
    │   └── app.blade.php            # Main frontend layout
    ├── views/
    │   └── pages/
    │       └── home.blade.php       # Homepage template
    ├── components/
    │   ├── header.blade.php         # Site header
    │   └── footer.blade.php         # Site footer
    └── README.md                    # Documentation
```

## Key Files Explained

### `theme.json`
Theme metadata and configuration:

```json
{
    "name": "Basic Theme (The Beginning)",
    "slug": "BasicTheme",
    "version": "1.0.0",
    "description": "Default theme controlling frontend and admin",
    "author": "VantaPress",
    "preview": "screenshot.png"
}
```

### `assets/css/theme.css`
**Controls: Frontend website styling**

- Homepage design
- Navigation bars
- Content sections
- Footers and widgets
- Public-facing pages

### `assets/css/admin.css` ⭐
**Controls: Admin panel styling**

- Filament sidebar
- Dashboard cards
- Form elements
- Tables and data grids
- Light/Dark mode
- All backend UI

This file is **automatically loaded** by `AdminPanelProvider` when the theme is active.

## How It Works

### 1. Theme Loading

When VantaPress boots:
1. `ThemeManager` reads active theme from config
2. Frontend routes load theme's `theme.css`
3. `AdminPanelProvider` loads theme's `admin.css`

### 2. Admin Panel Integration

File: `app/Providers/Filament/AdminPanelProvider.php`

```php
->renderHook(
    PanelsRenderHook::STYLES_AFTER,
    function (): string {
        $themeManager = app(\App\Services\CMS\ThemeManager::class);
        $activeTheme = $themeManager->getActiveTheme();
        $adminCss = asset("themes/{$activeTheme}/assets/css/admin.css") . '?v=' . time();
        
        return '<link rel="stylesheet" href="' . asset('css/filament/filament/app.css') . '?v=3.3.45">' .
               '<link rel="stylesheet" href="' . $adminCss . '">';
    }
)
```

This hook:
- Detects the active theme
- Loads `themes/[ActiveTheme]/assets/css/admin.css`
- Applies cache-busting with timestamp
- Injects CSS after Filament's base styles

### 3. Theme Switching

When an admin activates a different theme:
1. `ThemeManager::setActiveTheme()` updates `.env`
2. Both frontend and admin panel CSS reload
3. Entire site reflects new theme's design

## Default Theme: Basic Theme (The Beginning)

The default theme shipped with VantaPress.

### Frontend Design
- Clean, modern layout
- Blue color scheme (`#3b82f6`)
- Responsive grid system
- Inter font family

### Admin Panel Design
- **Dark Mode**: Retro arcade aesthetic
  - Navy backgrounds with pixel grid patterns
  - Neon red, cyan, yellow accents
  - Flat cards with solid shadows
  - Uppercase typography with letter-spacing
  
- **Light Mode**: Retro pastel aesthetic
  - Clean white cards
  - Bright accent colors (red, cyan, yellow)
  - Flat design with NO text shadows
  - Modern, readable typography

## Creating Custom Themes

### Step 1: Copy Basic Theme

```powershell
Copy-Item "themes/BasicTheme" "themes/YourTheme" -Recurse
```

### Step 2: Update theme.json

```json
{
    "name": "Your Awesome Theme",
    "slug": "YourTheme",
    "version": "1.0.0",
    "description": "Custom theme for my site",
    "author": "Your Name"
}
```

### Step 3: Customize Frontend (`theme.css`)

Design your public website:
- Colors
- Typography
- Layouts
- Components

### Step 4: Customize Admin Panel (`admin.css`)

Design your admin dashboard:

```css
/* Dark Mode Sidebar */
.dark .fi-sidebar {
    background: your-gradient !important;
    border-right: 3px solid your-accent !important;
}

/* Light Mode Sidebar */
html:not(.dark) .fi-sidebar {
    background: your-light-color !important;
}

/* Cards */
.dark .fi-card {
    background: your-card-bg !important;
    border: 2px solid your-border !important;
}
```

### Step 5: Add Screenshot

Create `screenshot.png` (1200x900px) showing your theme.

### Step 6: Activate

1. Go to **Appearance > Themes** in admin
2. Activate your custom theme
3. Both frontend and admin update instantly!

## Important Selectors for Admin Styling

### Sidebar
```css
.fi-sidebar                    /* Main sidebar */
.fi-sidebar-nav-item          /* Navigation items */
.fi-sidebar-nav-item:hover    /* Hover state */
.fi-sidebar-item-active       /* Active page */
```

### Cards & Sections
```css
.fi-section                    /* Section containers */
.fi-card                       /* Card components */
.fi-stats-card                 /* Dashboard stat cards */
```

### Forms
```css
.fi-input                      /* Text inputs */
.fi-select                     /* Select dropdowns */
.fi-textarea                   /* Textareas */
.fi-btn                        /* Buttons */
```

### Tables
```css
.fi-table                      /* Table wrapper */
.fi-table-header-cell         /* Column headers */
.fi-table-cell                /* Table cells */
.fi-table-row:hover           /* Row hover */
```

### Dark/Light Mode
```css
.dark .fi-*                    /* Dark mode selector */
html:not(.dark) .fi-*         /* Light mode selector */
```

## Best Practices

### 1. Always Use `!important`

Filament has strong default styles. Override with:
```css
.dark .fi-sidebar {
    background: #000 !important;  /* ✅ Use !important */
}
```

### 2. Support Both Modes

Always style both dark and light modes:
```css
/* Dark Mode */
.dark .fi-card {
    background: #1a1a1a !important;
}

/* Light Mode */
html:not(.dark) .fi-card {
    background: #ffffff !important;
}
```

### 3. Use Cache Busting

The provider automatically adds `?v=timestamp` to CSS URLs, forcing browser refresh.

### 4. Keep Consistency

Frontend and admin should share:
- Color palette
- Typography choices
- Design philosophy
- Brand identity

### 5. Test Both Areas

When creating a theme:
1. ✅ Test frontend pages
2. ✅ Test admin dashboard
3. ✅ Toggle dark/light mode
4. ✅ Test all form elements
5. ✅ Check responsive layouts

## Migration from Root CSS

**OLD APPROACH** (Incorrect):
- Styling in `css/vantapress-admin.css`
- Not theme-specific
- Hard to switch or export

**NEW APPROACH** (Correct):
- Styling in `themes/[ThemeName]/assets/css/admin.css`
- Theme-specific and portable
- Switch themes = switch entire design

### Migration Steps

1. ✅ Move admin CSS to theme folder
2. ✅ Update AdminPanelProvider to load from theme
3. ✅ Remove root-level admin CSS files
4. ✅ Test theme switching

## Architecture Benefits

✅ **Consistency**: Frontend and backend share design language  
✅ **Portability**: Export theme as single ZIP file  
✅ **Flexibility**: Switch entire site design with one click  
✅ **Organization**: All theme assets in one folder  
✅ **Scalability**: Easy to create and maintain multiple themes  
✅ **User-Friendly**: Non-technical users can change site appearance  

## Example: Switching from Default to Custom

1. **Before**: Site uses Basic Theme (retro gaming style)
2. **Admin action**: Activate "Corporate Theme"
3. **After**: 
   - Frontend → Professional corporate design
   - Admin panel → Clean, minimal dashboard
   - Both areas match new theme instantly

## File Checklist for New Themes

When creating a custom theme, ensure you have:

- [ ] `theme.json` (metadata)
- [ ] `assets/css/theme.css` (frontend styles)
- [ ] `assets/css/admin.css` (admin panel styles)
- [ ] `layouts/app.blade.php` (main frontend layout)
- [ ] `views/pages/home.blade.php` (homepage)
- [ ] `components/header.blade.php` (site header)
- [ ] `components/footer.blade.php` (site footer)
- [ ] `screenshot.png` (preview image)
- [ ] `README.md` (documentation)

## Debugging Theme Issues

### Admin CSS Not Loading

1. Check theme is active: `php artisan tinker` → `config('cms.themes.active')`
2. Verify file exists: `themes/[ThemeName]/assets/css/admin.css`
3. Clear cache: `php artisan cache:clear`
4. Hard refresh browser: `Ctrl + Shift + R`

### Styles Not Applying

1. Check selector specificity (use `!important`)
2. Verify dark/light mode selector (`.dark` vs `html:not(.dark)`)
3. Inspect with DevTools to see which CSS is loaded
4. Check for typos in Filament class names

### Theme Not Found

1. Ensure theme folder exists in `themes/` directory
2. Verify `theme.json` is valid JSON
3. Check `slug` matches folder name
4. Run: `php artisan cache:clear` to refresh theme cache

## Summary

VantaPress themes are **comprehensive design systems** that control both frontend and backend appearance. This unified approach ensures consistency, portability, and ease of use for administrators who want to change their entire site's look and feel with a single theme activation.

**Key Takeaway**: Always place admin styling in `themes/[ThemeName]/assets/css/admin.css`, never in root-level CSS files. This is what makes VantaPress themes truly portable and powerful.
