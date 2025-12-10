# VantaPress Theme Customizer Standard
## Official Theme Development Guidelines

## 📋 Overview

The VantaPress Theme Customizer uses a **hybrid detection system** that combines:
1. **Theme Declaration** (theme.json) - Explicitly declare customizable elements
2. **Automatic Detection** (data attributes) - Mark HTML elements as editable
3. **Intelligent Fallbacks** - Auto-discover common patterns

---

## 🎨 Theme Structure Standard

### Required Files:
```
themes/YourTheme/
├── theme.json              # Theme metadata and customizer config ⭐
├── assets/
│   ├── css/
│   │   ├── theme.css       # Frontend styles
│   │   └── admin.css       # Admin panel styles (optional)
│   └── js/
│       └── theme.js        # Frontend scripts (optional)
├── components/
│   ├── header.blade.php    # Header component
│   ├── footer.blade.php    # Footer component
│   └── hero.blade.php      # Hero section (optional)
├── layouts/
│   └── default.blade.php   # Main layout
├── pages/
│   └── home.blade.php      # Homepage template
└── screenshot.png          # Theme preview (400x300px)
```

---

## 📝 theme.json Schema (REQUIRED)

### Complete Example:
```json
{
    "name": "Your Theme Name",
    "slug": "YourTheme",
    "version": "1.0.0",
    "description": "Your theme description",
    "author": "Your Name",
    "author_url": "https://yoursite.com",
    "preview": "screenshot.png",
    
    "customizer": {
        "sections": {
            "site": {
                "label": "Site Identity",
                "priority": 10,
                "elements": [
                    {
                        "id": "site_title",
                        "label": "Site Title",
                        "type": "text",
                        "default": "",
                        "selector": "[data-vp-element='site_title']",
                        "live_update": true
                    },
                    {
                        "id": "site_logo",
                        "label": "Logo",
                        "type": "image",
                        "default": "",
                        "selector": "[data-vp-element='site_logo']"
                    }
                ]
            },
            "header": {
                "label": "Header",
                "priority": 20,
                "elements": [
                    {
                        "id": "header_layout",
                        "label": "Header Layout",
                        "type": "select",
                        "options": {
                            "left": "Logo Left",
                            "center": "Logo Center",
                            "right": "Logo Right"
                        },
                        "default": "left"
                    },
                    {
                        "id": "header_sticky",
                        "label": "Sticky Header",
                        "type": "toggle",
                        "default": false
                    },
                    {
                        "id": "header_bg_color",
                        "label": "Background Color",
                        "type": "color",
                        "default": "#ffffff",
                        "selector": "header",
                        "css_property": "background-color"
                    }
                ]
            },
            "hero": {
                "label": "Hero Section",
                "priority": 30,
                "elements": [
                    {
                        "id": "hero_title",
                        "label": "Hero Title",
                        "type": "text",
                        "default": "Welcome to VantaPress",
                        "selector": "[data-vp-element='hero_title']",
                        "live_update": true
                    },
                    {
                        "id": "hero_subtitle",
                        "label": "Hero Subtitle",
                        "type": "textarea",
                        "default": "",
                        "selector": "[data-vp-element='hero_subtitle']",
                        "live_update": true
                    },
                    {
                        "id": "hero_bg_image",
                        "label": "Background Image",
                        "type": "image",
                        "default": "",
                        "selector": ".hero-section",
                        "css_property": "background-image"
                    }
                ]
            },
            "colors": {
                "label": "Colors",
                "priority": 40,
                "elements": [
                    {
                        "id": "primary_color",
                        "label": "Primary Color",
                        "type": "color",
                        "default": "#dc2626",
                        "css_var": "--color-primary"
                    },
                    {
                        "id": "secondary_color",
                        "label": "Secondary Color",
                        "type": "color",
                        "default": "#991b1b",
                        "css_var": "--color-secondary"
                    }
                ]
            },
            "footer": {
                "label": "Footer",
                "priority": 50,
                "elements": [
                    {
                        "id": "footer_text",
                        "label": "Footer Text",
                        "type": "textarea",
                        "default": "© 2025 VantaPress",
                        "selector": "[data-vp-element='footer_text']",
                        "live_update": true
                    },
                    {
                        "id": "footer_show_social",
                        "label": "Show Social Links",
                        "type": "toggle",
                        "default": true
                    }
                ]
            }
        },
        
        "pages": [
            {
                "slug": "home",
                "label": "Homepage",
                "template": "pages/home.blade.php",
                "preview_url": "/"
            },
            {
                "slug": "single",
                "label": "Single Post",
                "template": "pages/single.blade.php",
                "preview_url": "/post/sample"
            }
        ],
        
        "menus": [
            {
                "location": "primary",
                "label": "Primary Menu",
                "description": "Main navigation menu"
            },
            {
                "location": "footer",
                "label": "Footer Menu",
                "description": "Links in footer"
            }
        ]
    }
}
```

---

## 🏷️ Data Attribute Standard (REQUIRED for live editing)

### Use `data-vp-element` attribute:

```html
<!-- ✅ CORRECT: Mark editable elements -->
<h1 data-vp-element="site_title">{{ vp_get_theme_setting('site_title') }}</h1>

<img data-vp-element="site_logo" 
     src="{{ vp_get_theme_setting('site_logo') }}" 
     alt="Logo">

<div class="hero-section" data-vp-element="hero">
    <h2 data-vp-element="hero_title">
        {{ vp_get_theme_setting('hero_title', 'Welcome') }}
    </h2>
    <p data-vp-element="hero_subtitle">
        {{ vp_get_theme_setting('hero_subtitle') }}
    </p>
</div>

<footer>
    <p data-vp-element="footer_text">
        {{ vp_get_theme_setting('footer_text') }}
    </p>
</footer>
```

### Element Types & Attributes:

| Type | Usage | Live Update |
|------|-------|-------------|
| `text` | Short text content | ✅ Yes |
| `textarea` | Long text/HTML | ✅ Yes |
| `image` | Image URLs | ✅ Yes (src) |
| `color` | Color picker | ✅ Yes (CSS) |
| `toggle` | Boolean switch | ⚠️ No (reload) |
| `select` | Dropdown options | ⚠️ No (reload) |
| `range` | Numeric slider | ✅ Yes |

---

## 🎯 CSS Variable Standard

### Define CSS variables for live color updates:

```css
/* themes/YourTheme/assets/css/theme.css */
:root {
    --color-primary: #dc2626;
    --color-secondary: #991b1b;
    --color-accent: #f59e0b;
    --font-heading: 'Arial', sans-serif;
    --font-body: 'Helvetica', sans-serif;
}

/* Use variables in your styles */
.button-primary {
    background-color: var(--color-primary);
}

h1, h2, h3 {
    color: var(--color-primary);
    font-family: var(--font-heading);
}
```

---

## 📦 Helper Functions (Use these in templates)

### Get Setting Value:
```php
{{ vp_get_theme_setting('site_title', 'Default Title') }}
```

### Check If Setting Exists:
```php
@if(vp_has_theme_setting('hero_title'))
    <h1>{{ vp_get_theme_setting('hero_title') }}</h1>
@endif
```

### Get All Settings:
```php
@php
$themeSettings = vp_get_all_theme_settings();
@endphp
```

---

## 🔍 Detection Priority

The Theme Customizer detects elements in this order:

1. **theme.json declarations** (highest priority)
2. **data-vp-element attributes** (HTML markup)
3. **Auto-detection fallbacks** (common patterns)

### Auto-Detected Patterns (if not declared):

```html
<!-- These are automatically detected -->
<header> → header section
<footer> → footer section
.hero, .hero-section → hero section
<nav>, .navigation → navigation menu
h1.site-title → site title
.tagline → site tagline
```

---

## ✅ Complete Theme Example

### header.blade.php
```php
<header data-vp-element="header" 
        style="background-color: {{ vp_get_theme_setting('header_bg_color', '#ffffff') }}">
    
    <div class="container">
        @if(vp_get_theme_setting('site_logo'))
            <img data-vp-element="site_logo" 
                 src="{{ vp_get_theme_setting('site_logo') }}" 
                 alt="Logo" 
                 class="site-logo">
        @else
            <h1 data-vp-element="site_title" class="site-title">
                {{ vp_get_theme_setting('site_title', config('app.name')) }}
            </h1>
        @endif
        
        <nav data-vp-element="navigation">
            <!-- Menu will be rendered here -->
        </nav>
    </div>
</header>
```

### hero.blade.php
```php
<section class="hero-section" 
         data-vp-element="hero"
         style="background-image: url('{{ vp_get_theme_setting('hero_bg_image') }}')">
    
    <div class="hero-content">
        <h1 data-vp-element="hero_title">
            {{ vp_get_theme_setting('hero_title', 'Welcome to VantaPress') }}
        </h1>
        
        <p data-vp-element="hero_subtitle" class="hero-subtitle">
            {{ vp_get_theme_setting('hero_subtitle', 'Build amazing websites') }}
        </p>
        
        <div class="hero-buttons">
            <a href="{{ vp_get_theme_setting('hero_button_url', '#') }}" 
               class="btn btn-primary"
               data-vp-element="hero_button_text">
                {{ vp_get_theme_setting('hero_button_text', 'Get Started') }}
            </a>
        </div>
    </div>
</section>
```

### footer.blade.php
```php
<footer data-vp-element="footer"
        style="background-color: {{ vp_get_theme_setting('footer_bg_color', '#1e293b') }}">
    
    <div class="container">
        <p data-vp-element="footer_text" class="footer-text">
            {!! vp_get_theme_setting('footer_text', '© 2025 VantaPress') !!}
        </p>
        
        @if(vp_get_theme_setting('footer_show_social', true))
            <div class="social-links" data-vp-element="social_links">
                <!-- Social links here -->
            </div>
        @endif
    </div>
</footer>
```

---

## 🚀 Testing Your Theme

### 1. Validate theme.json:
```bash
php artisan vp:theme:validate YourTheme
```

### 2. Test customizer detection:
```bash
php artisan vp:theme:scan YourTheme
```

### 3. Open Theme Customizer:
Navigate to: `/admin/themes` → Click "Customize" on your theme

### 4. Check Detection Results:
- All sections from theme.json should appear
- Hover over elements with `data-vp-element` to see edit icons
- Changes should update live in preview

---

## 📚 Field Types Reference

### text
```json
{
    "id": "field_name",
    "label": "Field Label",
    "type": "text",
    "default": "default value",
    "placeholder": "Enter text...",
    "selector": "[data-vp-element='field_name']",
    "live_update": true
}
```

### textarea
```json
{
    "id": "field_name",
    "label": "Field Label",
    "type": "textarea",
    "default": "",
    "rows": 4,
    "selector": "[data-vp-element='field_name']"
}
```

### image
```json
{
    "id": "field_name",
    "label": "Image",
    "type": "image",
    "default": "",
    "selector": "[data-vp-element='field_name']"
}
```

### color
```json
{
    "id": "primary_color",
    "label": "Primary Color",
    "type": "color",
    "default": "#dc2626",
    "css_var": "--color-primary"
}
```

### toggle
```json
{
    "id": "enable_feature",
    "label": "Enable Feature",
    "type": "toggle",
    "default": true
}
```

### select
```json
{
    "id": "layout_type",
    "label": "Layout",
    "type": "select",
    "options": {
        "full": "Full Width",
        "boxed": "Boxed",
        "wide": "Wide"
    },
    "default": "full"
}
```

### range
```json
{
    "id": "font_size",
    "label": "Font Size",
    "type": "range",
    "min": 12,
    "max": 24,
    "step": 1,
    "default": 16,
    "unit": "px"
}
```

---

## ⚠️ Common Mistakes to Avoid

### ❌ DON'T:
```html
<!-- Missing data-vp-element -->
<h1>{{ vp_get_theme_setting('site_title') }}</h1>

<!-- Incorrect attribute name -->
<h1 data-customizer="site_title">Title</h1>

<!-- No theme.json declaration -->
<!-- Element won't be detected properly -->
```

### ✅ DO:
```html
<!-- Proper data attribute -->
<h1 data-vp-element="site_title">{{ vp_get_theme_setting('site_title') }}</h1>

<!-- Declare in theme.json -->
<!-- Add data-vp-element in template -->
<!-- Use vp_get_theme_setting() helper -->
```

---

## 📖 Summary

**For Theme Developers:**
1. Create `theme.json` with customizer schema
2. Mark editable elements with `data-vp-element="element_id"`
3. Use `vp_get_theme_setting()` to retrieve values
4. Define CSS variables for colors
5. Test in Theme Customizer

**For VantaPress Core:**
- Read theme.json first (source of truth)
- Scan HTML for data-vp-element attributes
- Fallback to auto-detection for common patterns
- Provide live preview for compatible field types

---

**Last Updated:** December 6, 2025  
**Version:** 1.0  
**Applies to:** VantaPress v1.0.25+
