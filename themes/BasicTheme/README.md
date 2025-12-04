# Basic Theme (The Beginning)

The default VantaPress theme - A clean and simple theme that controls both frontend and admin panel styling.

## Theme Architecture

In VantaPress, themes control the **entire visual experience** - both the frontend website and the admin panel. This unified approach ensures consistency across your entire CMS.

### What This Theme Controls

1. **Frontend (Website)**
   - Page layouts (home, menu, sections)
   - Header & footer design
   - Typography and colors
   - Component styling

2. **Admin Panel (Backend)**
   - Sidebar navigation design
   - Dashboard card styling
   - Form inputs and buttons
   - Tables and data displays
   - Light/Dark mode aesthetics

When you activate a theme, **both your website and admin panel change** to match that theme's design language.

## Structure

```
BasicTheme/
├── theme.json              # Theme metadata
├── layouts/                # Frontend layout templates
│   └── app.blade.php
├── views/                  # Frontend page views
│   └── pages/
│       └── home.blade.php
├── components/             # Reusable frontend components
│   ├── header.blade.php
│   └── footer.blade.php
├── assets/                 # Theme assets
│   └── css/
│       ├── theme.css       # Frontend styles
│       └── admin.css       # Admin panel styles ⭐
└── README.md
```

## Key Files

### `assets/css/theme.css`
Controls the **frontend** styling - your public website appearance.

### `assets/css/admin.css` ⭐
Controls the **admin panel** styling - your Filament dashboard appearance.

This file is automatically loaded by the AdminPanelProvider when your theme is active.

## Installation

1. Upload the theme ZIP file via Filament admin panel
2. Navigate to **Appearance > Themes**
3. Click **Install Theme**
4. Activate the theme
5. **Both frontend and admin panel will update!**

## Theme Metadata (theme.json)

```json
{
    "name": "Basic Theme (The Beginning)",
    "slug": "BasicTheme",
    "version": "1.0.0",
    "description": "The default VantaPress theme - controls both frontend and admin panel",
    "author": "VantaPress",
    "preview": "screenshot.png"
}
```

## Customization

### Frontend Styling

Edit `assets/css/theme.css` to customize the public website:

```css
:root {
    --primary-color: #3b82f6;
    --secondary-color: #6366f1;
    --text-color: #1f2937;
    --bg-color: #ffffff;
    --border-color: #e5e7eb;
}
```

### Admin Panel Styling

Edit `assets/css/admin.css` to customize the admin dashboard:

```css
/* Dark Mode */
.dark .fi-sidebar {
    background: your-color !important;
}

/* Light Mode */
html:not(.dark) .fi-sidebar {
    background: your-color !important;
}
```

### Layouts

Edit `layouts/app.blade.php` to modify the main frontend layout structure.

### Components

- `components/header.blade.php` - Site header and navigation
- `components/footer.blade.php` - Site footer

## Creating Your Own Theme

1. Copy this theme structure
2. Update `theme.json` with your theme details
3. Customize frontend layouts, components, and `theme.css`
4. **Customize admin panel with `admin.css`** (controls backend appearance)
5. Add a `screenshot.png` (1200x900px recommended)
6. ZIP the theme folder
7. Install via Filament admin

## Important: Theme-Based Styling

**All visual styling should live in themes**, not in root-level CSS files. This ensures:

- ✅ Consistent frontend and backend design
- ✅ Easy theme switching
- ✅ Portable theme packages
- ✅ Clear separation of concerns

### Admin CSS Variables (Available in admin.css)

```css
/* Retro Game Palette */
--retro-red: #FF6B6B;
--retro-orange: #FFA06B;
--retro-yellow: #FFD93D;
--retro-green: #6BCF7F;
--retro-cyan: #4ECDC4;
--retro-blue: #5DADE2;
--retro-purple: #A393EB;

/* Neutral */
--pixel-black: #1A1A2E;
--deep-navy: #16213E;
--electric-cyan: #0F4C75;
```

## License

MIT
