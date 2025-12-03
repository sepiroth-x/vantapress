# BasicTheme

A clean and simple theme for VantaPress CMS.

## Structure

```
BasicTheme/
├── theme.json              # Theme metadata
├── layouts/                # Layout templates
│   └── app.blade.php
├── views/                  # Page views
│   └── pages/
│       └── home.blade.php
├── components/             # Reusable components
│   ├── header.blade.php
│   └── footer.blade.php
├── assets/                 # Static assets
│   └── css/
│       └── theme.css
└── README.md
```

## Installation

1. Upload the theme ZIP file via Filament admin panel
2. Navigate to **Appearance > Themes**
3. Click **Install Theme**
4. Activate the theme

## Theme Metadata (theme.json)

```json
{
    "name": "Basic Theme",
    "slug": "BasicTheme",
    "version": "1.0.0",
    "description": "A clean and simple theme",
    "author": "VantaPress",
    "preview": "screenshot.png"
}
```

## Customization

### Layouts

Edit `layouts/app.blade.php` to modify the main layout structure.

### Components

- `components/header.blade.php` - Site header and navigation
- `components/footer.blade.php` - Site footer

### Styles

Edit `assets/css/theme.css` to customize colors, fonts, and styles.

### CSS Variables

```css
:root {
    --primary-color: #3b82f6;
    --secondary-color: #6366f1;
    --text-color: #1f2937;
    --bg-color: #ffffff;
    --border-color: #e5e7eb;
}
```

## Creating Your Own Theme

1. Copy this theme structure
2. Update `theme.json` with your theme details
3. Customize layouts, components, and styles
4. Add a `screenshot.png` (1200x900px recommended)
5. ZIP the theme folder
6. Install via Filament admin

## License

MIT
