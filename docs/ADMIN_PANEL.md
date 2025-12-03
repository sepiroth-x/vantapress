# VantaPress Admin Panel

## Overview

VantaPress features a comprehensive WordPress-style admin panel built with FilamentPHP. The admin interface provides full control over content, media, themes, plugins (modules), users, and system settings.

## Admin Panel Features

### 🎯 Dashboard
- **Statistics Overview**: Real-time stats for pages, media files, users, and active modules
- **Recent Pages Widget**: Quick access to recently created/modified pages
- **Quick Actions**: Direct links to create new content

### 📄 Content Management

#### Pages
Complete page management system with:
- Rich text editor with formatting tools
- Parent/child page hierarchy
- Status workflow (Draft → Published → Scheduled)
- Template selection
- Featured image support
- SEO metadata (title, description, keywords, OG image)
- Slug auto-generation
- Soft deletes with restore capability

#### Menus
Flexible navigation menu system:
- Multiple menu locations (primary, footer, sidebar, etc.)
- Drag & drop menu ordering
- Hierarchical menu structure
- Custom URLs or page links
- Icon support
- CSS class customization
- Target options (same window/new window)
- Active/inactive toggle per menu item

### 🎨 Appearance

#### Themes
WordPress-style theme management:
- **Upload themes as .zip files** (up to 50MB)
- Automatic theme extraction to `/themes` directory
- Theme metadata from `theme.json`
- Theme preview screenshots
- One-click theme activation
- Only one active theme at a time
- Custom theme configuration storage
- Version tracking

**Theme Structure:**
```
theme-name.zip
├── theme.json (metadata)
├── screenshot.png (preview image)
├── style.css
├── functions.php
└── templates/
    ├── index.blade.php
    └── page.blade.php
```

### 🧩 Extensions

#### Modules (Plugins)
WordPress-style plugin system:
- **Upload modules as .zip files** (up to 50MB)
- Automatic extraction to `/Modules` directory
- Module metadata from `module.json`
- Enable/disable individual modules
- Bulk enable/disable operations
- Automatic migration running
- Automatic asset publishing
- Version tracking
- Custom module configuration

**Module Structure:**
```
module-name.zip
├── module.json (metadata)
├── Providers/
│   └── ModuleServiceProvider.php
├── Database/
│   └── Migrations/
├── Resources/
│   └── assets/
└── Routes/
    └── web.php
```

### 📸 Media Library
Professional media management:
- Drag & drop file uploads
- Image preview with thumbnails
- Image editor (crop, resize, rotate)
- Multiple aspect ratio presets (16:9, 4:3, 1:1)
- File metadata (size, dimensions, MIME type)
- Alt text for accessibility
- Captions and descriptions
- File type filtering (images, videos, audio, documents)
- Maximum upload size: 10MB (configurable)
- Uploader tracking

### 👥 User Management
Complete user administration:
- User CRUD operations
- Role assignment (multiple roles per user)
- Profile avatars
- User bio/description
- Active/inactive status
- Email verification tracking
- Password management
- Bulk operations
- Activity tracking

### ⚙️ Settings
Centralized system configuration with tabs:

#### General Settings
- Site name and tagline
- Site description
- Admin email
- Timezone selection
- Date/time format customization

#### Reading Settings
- Posts per page
- Homepage display type (latest posts or static page)
- Homepage page selection

#### Media Settings
- Maximum upload size (MB)
- Allowed file types (configurable extensions)

#### SEO Settings
- Enable/disable SEO features
- Custom robots.txt content
- Google Analytics integration

#### Maintenance Mode
- Enable/disable maintenance mode
- Custom maintenance message
- Admin-only access when enabled

## Navigation Structure

The admin panel is organized into logical groups:

### Content
- Pages
- Media Library

### Appearance
- Menus
- Themes

### Extensions
- Modules (Plugins)

### Administration
- Users
- Settings

## Key Features

### ✨ WordPress-Like Experience
- Familiar interface for WordPress users
- Upload and activate themes/plugins via .zip files
- Similar terminology and workflows
- Hierarchical content organization

### 🔒 Security
- Role-based access control
- Activity logging
- Soft deletes with restore capability
- Password hashing
- File type restrictions
- Size limits on uploads

### 🎨 Modern UI
- Dark mode enabled by default
- Custom crimson color scheme (#D40026)
- Responsive design
- Icons from Heroicons
- Drag & drop interfaces
- Real-time notifications

### 🚀 Developer Friendly
- Built with FilamentPHP 3.3
- Laravel 11 backend
- Modular architecture
- Easy to extend
- Well-documented models
- Clean separation of concerns

## Accessing the Admin Panel

1. Navigate to `/admin` on your domain
2. Log in with your admin credentials
3. The dashboard displays upon successful login

## Default Admin Credentials

Created during installation via `install.php` or `create-admin.php`:
- Email: admin@example.com
- Password: (set during installation)

## File Upload Limits

Default limits (configurable in Settings):
- **Media Files**: 10MB
- **Theme Packages**: 50MB
- **Module Packages**: 50MB

## Storage Locations

- **Media Files**: `/storage/app/public/media/`
- **Themes**: `/themes/`
- **Modules**: `/Modules/`
- **Avatars**: `/storage/app/public/avatars/`
- **Theme Screenshots**: `/storage/app/public/themes/screenshots/`

## Theme Development

Create a `theme.json` file in your theme root:

```json
{
    "name": "My Theme",
    "slug": "my-theme",
    "description": "A beautiful theme for VantaPress",
    "version": "1.0.0",
    "author": "Your Name",
    "config": {
        "primary_color": "#D40026",
        "layout": "boxed"
    }
}
```

## Module Development

Create a `module.json` file in your module root:

```json
{
    "name": "My Module",
    "slug": "MyModule",
    "description": "Adds awesome features",
    "version": "1.0.0",
    "author": "Your Name",
    "config": {
        "api_key": "",
        "enabled_features": []
    }
}
```

## Widgets

The dashboard includes:
- **StatsOverview**: Displays key metrics with trend charts
- **RecentPages**: Shows the 5 most recently modified pages

## Customization

### Adding Custom Resources
Place new Filament resources in: `app/Filament/Resources/`

### Adding Custom Pages
Place custom pages in: `app/Filament/Pages/`

### Adding Custom Widgets
Place widgets in: `app/Filament/Widgets/`

FilamentPHP will auto-discover them based on paths configured in `AdminPanelProvider.php`.

## Database Schema

### Pages Table
- Hierarchical structure with parent/child relationships
- Soft deletes
- Status workflow
- SEO metadata
- Featured images

### Media Table
- File metadata (size, dimensions, MIME type)
- User tracking (uploader)
- Alternative text for accessibility
- Captions and descriptions

### Menus & Menu Items
- Multiple menus per site
- Hierarchical menu items
- Custom URLs and icons

### Themes & Modules
- Enable/disable functionality
- Configuration storage
- Version tracking
- Author information

### Settings
- Key-value pairs
- Grouped settings
- Type casting (string, boolean, integer, json)
- Autoload flag

## Support

For issues or questions:
- Email: chardy.tsadiq02@gmail.com
- Mobile: +63 915 0388 448

---

**VantaPress** - A WordPress-inspired CMS built with Laravel & FilamentPHP
