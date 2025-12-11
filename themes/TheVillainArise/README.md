# The Villain Arise Theme

Official default theme for VantaPress CMS featuring a dark villain aesthetic with customizable components.

## 📋 Overview

**The Villain Arise** is the reference theme for VantaPress, showcasing best practices for theme development and serving as the recommended starting point for developers.

### Theme Information
- **Name:** The Villain Arise
- **Version:** 1.0.0
- **Author:** VantaPress
- **Status:** Inactive by default (reference/template)

## 🎨 Design Features

### Visual Design
- **Dark Villain Aesthetic:** Deep blacks and grays with vibrant red accents
- **Typography:** 
  - Orbitron font for headings (bold, futuristic)
  - Space Mono for body text (monospace, readable)
- **Color Palette:**
  - Primary: Villain Red (#dc2626)
  - Background: Gray-900 to Gray-950
  - Accents: Gradient effects from red-300 to red-700

### Layout Components
- **Global Header:** Sticky navigation with logo and menu
- **Global Footer:** Multi-column footer with links and social media
- **Hero Section:** Customizable hero with animated backgrounds
- **Widget Areas:** Header, Footer, Sidebar
- **Menu Locations:** Primary, Footer

## 🏗️ Structure

```
TheVillainArise/
├── theme.json                  # Theme metadata and configuration
├── layouts/
│   └── main.blade.php         # Master layout template
├── pages/
│   └── home.blade.php         # Landing page template
├── components/
│   └── hero.blade.php         # Hero section component
├── partials/
│   ├── header.blade.php       # Global header
│   └── footer.blade.php       # Global footer
└── assets/
    ├── css/
    │   └── theme.css          # Custom styles
    └── js/
        └── theme.js           # Theme JavaScript
```

## 🔧 Integration with VP Essential 1

This theme is designed to work seamlessly with the **VP Essential 1** module, which provides:

### Helper Functions Used
```php
// Get theme settings
vp_get_theme_setting($key, $default)

// Get menu items
vp_get_menu($location)

// Get widget area content
vp_get_widget_area($name)

// Get hero configuration
vp_get_hero_config()

// Get user profile
vp_get_current_user_profile()
```

### Customization Points
1. **Logo:** Customizable via theme settings
2. **Colors:** Primary and accent colors
3. **Hero Section:**
   - Title
   - Subtitle
   - Description
   - CTA buttons (primary/secondary)
   - Background (gradient/image)
4. **Menus:** Primary and footer menus
5. **Widgets:** Header, footer, and sidebar widget areas

## 📝 Blade Sections

### Master Layout (`layouts/main.blade.php`)

```blade
@extends('theme.layouts::main')

@section('title', 'Page Title')

@section('meta')
    <!-- Additional meta tags -->
@endsection

@section('styles')
    <!-- Additional CSS -->
@endsection

@section('content')
    <!-- Page content -->
@endsection

@section('scripts')
    <!-- Additional JavaScript -->
@endsection
```

### View Namespaces
- `theme.layouts::` - Layouts directory
- `theme.partials::` - Partials directory
- `theme.components::` - Components directory
- `theme::` - Root views directory

## 🎯 Widget Areas

### Header Widget Area
```php
ID: header
Location: Between header and main content
Usage: Announcements, notifications
```

### Footer Widget Area
```php
ID: footer
Location: Before footer component
Usage: Newsletter signup, social links
```

### Sidebar Widget Area
```php
ID: sidebar
Location: Page sidebars (when supported)
Usage: Recent posts, categories, search
```

## 🔗 Menu Locations

### Primary Menu
```php
ID: primary
Location: Header navigation
Usage: Main site navigation
```

### Footer Menu
```php
ID: footer
Location: Footer links section
Usage: Quick links, legal pages
```

## 💅 CSS Classes

### Custom Classes
```css
.villain-card        /* Feature/content cards */
.villain-header      /* Header component */
.villain-footer      /* Footer component */
.villain-hero        /* Hero section */
.widget-area         /* Widget containers */
.tweet-card          /* Tweet display */
.profile-card        /* User profile display */
```

### Utility Classes (Tailwind)
```css
.text-villain-500    /* Villain red text */
.bg-villain-600      /* Villain red background */
.border-villain-600  /* Villain red border */
```

## 📱 Responsive Design

- **Mobile First:** Optimized for mobile devices
- **Breakpoints:** Uses Tailwind's default breakpoints
  - `sm:` 640px
  - `md:` 768px
  - `lg:` 1024px
  - `xl:` 1280px
- **Mobile Menu:** Collapsible navigation for small screens

## ⚡ Performance Features

- **Tailwind CDN:** No build process required
- **Lazy Loading:** Images load on scroll
- **Smooth Scrolling:** Enhanced anchor navigation
- **Debounced Events:** Optimized window resize handling

## 🔐 Security

- **CSRF Protection:** Token included in forms
- **XSS Prevention:** Blade escaping by default
- **Content Security:** Safe helper function usage

## 🎓 Development Guidelines

### Adding New Pages
```blade
{{-- Create: pages/custom.blade.php --}}
@extends('theme.layouts::main')

@section('title', 'Custom Page')

@section('content')
    <div class="container mx-auto px-4 py-20">
        <h1 class="text-4xl font-black font-orbitron text-villain-500 mb-6">
            Custom Content
        </h1>
        <!-- Your content -->
    </div>
@endsection
```

### Adding New Components
```blade
{{-- Create: components/custom-component.blade.php --}}
<div class="custom-component">
    <!-- Component markup -->
</div>

{{-- Use in pages: --}}
@include('theme.components::custom-component')
```

### Customizing Styles
Add custom CSS to `assets/css/theme.css`:
```css
.your-custom-class {
    /* Custom styles */
}
```

### Adding JavaScript
Add custom JS to `assets/js/theme.js`:
```javascript
// Your custom functionality
function yourFunction() {
    console.log('Custom function');
}
```

## 🧪 Testing

### Browser Compatibility
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

### Accessibility
- Keyboard navigation supported
- ARIA attributes included
- Focus indicators visible
- Screen reader friendly

## 📦 Installation

1. Already included in base VantaPress installation
2. Navigate to **Appearance > Themes** in admin
3. Click **Activate** on "The Villain Arise"
4. Configure via **Theme Settings** (requires VP Essential 1)

## 🎨 Customization Guide

### Change Logo
1. Go to **Theme Settings**
2. Upload logo image
3. Logo will replace default text

### Change Colors
1. Edit `assets/css/theme.css`
2. Update color variables or Tailwind config
3. Clear cache

### Modify Hero Section
1. Go to **Theme Settings > Hero**
2. Update title, subtitle, description
3. Change CTA buttons
4. Upload background image or use gradient

### Customize Menus
1. Go to **Menu Builder**
2. Create/edit "Primary" or "Footer" menu
3. Add menu items
4. Assign to menu location

## 🐛 Troubleshooting

### Theme Not Applying
- Clear Laravel cache: `php artisan cache:clear`
- Check theme is activated
- Verify ThemeLoader is discovering the theme

### Styles Not Loading
- Check `assets/css/theme.css` exists
- Verify file permissions
- Check browser console for errors

### Menus Not Showing
- Install and activate VP Essential 1 module
- Create menus in Menu Builder
- Assign menus to locations

## 📄 License

Proprietary - VantaPress CMS

---

**Version:** 1.0.0  
**Last Updated:** December 3, 2025  
**Author:** VantaPress  
**Support:** Via VP Essential 1 module
