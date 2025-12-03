# VantaPress Module & Theme System

Complete WordPress-inspired module and theme system for Laravel CMS.

## 📋 Overview

VantaPress provides a powerful, WordPress-like extension system that allows:
- **Modules (.vpm files)**: Add functionality via ZIP packages
- **Themes (.vpt files)**: Customize appearance via ZIP packages
- **Auto-Discovery**: Automatic loading from `/Modules` and `/themes` directories
- **Filament Admin**: Beautiful UI for managing extensions
- **Security**: Path traversal protection, dangerous file detection, size limits
- **Shared Hosting**: No Composer or CLI required after installation

## 🏗️ Architecture

### Module System

```
VantaPress/
├── app/Services/
│   ├── ModuleLoader.php      # Discovery & lifecycle management
│   └── ModuleInstaller.php   # ZIP/VPM installation
├── app/Filament/Resources/
│   └── ModuleResource.php    # Admin UI
└── Modules/
    └── ModuleName/
        ├── module.json       # Metadata
        ├── routes.php        # Routes
        ├── controllers/      # Controllers
        └── views/            # Blade templates
```

### Theme System

```
VantaPress/
├── app/Services/
│   ├── ThemeLoader.php       # Discovery & Blade overrides
│   └── ThemeInstaller.php    # ZIP/VPT installation
├── app/Filament/Resources/
│   └── ThemeResource.php     # Admin UI
└── themes/
    └── ThemeName/
        ├── theme.json        # Metadata
        ├── layouts/          # Layout templates
        ├── views/            # Page views
        ├── components/       # Components
        └── assets/           # CSS/JS/images
```

## 🔧 Service API

### ModuleLoader

```php
$loader = app(ModuleLoader::class);

// Discover all modules
$modules = $loader->discoverModules();
// Returns: ['ModuleName' => [...metadata...], ...]

// Get all modules
$modules = $loader->getModules();

// Get specific module
$module = $loader->getModuleMetadata('ModuleName');

// Activate module
$loader->activateModule('ModuleName');

// Deactivate module
$loader->deactivateModule('ModuleName');

// Delete module
$loader->deleteModule('ModuleName');

// Validate module structure
$errors = $loader->validateModule('/path/to/module');
```

### ModuleInstaller

```php
$installer = app(ModuleInstaller::class);

// Install module
$result = $installer->install('/path/to/module.zip', $update = false);
// Returns: ['success' => true, 'message' => '...', 'module' => [...]]

// Set max file size (default: 50MB)
$installer->setMaxFileSize(104857600); // 100MB

// Get max file size
$maxSize = $installer->getMaxFileSize();
```

### ThemeLoader

```php
$loader = app(ThemeLoader::class);

// Discover all themes
$themes = $loader->discoverThemes();

// Get active theme
$activeTheme = $loader->getActiveTheme();

// Get all themes
$themes = $loader->getThemes();

// Activate theme
$loader->activateTheme('ThemeName');

// Delete theme
$loader->deleteTheme('ThemeName');

// Resolve view with theme override
$view = $loader->resolveView('pages.home');
// Returns: 'theme::pages.home' if exists, else 'pages.home'

// Load active theme
$theme = $loader->loadActiveTheme();
```

### ThemeInstaller

```php
$installer = app(ThemeInstaller::class);

// Install theme
$result = $installer->install('/path/to/theme.zip', $update = false);
// Returns: ['success' => true, 'message' => '...', 'theme' => [...]]
```

## 📦 Module Structure

### module.json (Required)

```json
{
    "name": "Module Name",
    "slug": "ModuleName",
    "version": "1.0.0",
    "description": "Module description",
    "author": "Author Name",
    "active": true
}
```

**Required Fields:**
- `name` (string): Display name
- `version` (string): Semantic version
- `description` (string): Brief description
- `active` (boolean): Enable on install

### routes.php (Optional)

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleName\Controllers\ModuleController;

Route::prefix('module-prefix')->group(function () {
    Route::get('/', [ModuleController::class, 'index'])->name('module.index');
});
```

### Controller Example

```php
<?php

namespace Modules\ModuleName\Controllers;

use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    public function index()
    {
        return view('ModuleName::index');
    }
}
```

### View Example

```blade
{{-- Modules/ModuleName/views/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Module Page</h1>
@endsection
```

## 🎨 Theme Structure

### theme.json (Required)

```json
{
    "name": "Theme Name",
    "slug": "ThemeName",
    "version": "1.0.0",
    "description": "Theme description",
    "author": "Author Name",
    "preview": "screenshot.png"
}
```

### Layout Example

```blade
{{-- themes/ThemeName/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('themes/ThemeName/assets/css/theme.css') }}">
</head>
<body>
    @include('theme.components::header')
    
    <main>
        @yield('content')
    </main>
    
    @include('theme.components::footer')
</body>
</html>
```

### View Namespaces

Themes register multiple namespaces:
- `theme::` - Main views directory
- `theme.layouts::` - Layouts directory
- `theme.components::` - Components directory

### View Resolution

The ThemeLoader provides automatic view override:

```php
// In controller
return view('pages.home'); // Checks theme first

// Explicitly use theme view
return view('theme::pages.home');
```

## 🔐 Security Features

### Path Traversal Protection

Both installers validate extracted paths:

```php
// Blocked paths
'../'
'../../'
'./../../file.php'
```

### Dangerous File Detection

Scans for executable files:

```php
$dangerousExtensions = ['exe', 'bat', 'cmd', 'sh', 'dll', 'so'];
```

### File Size Limits

Default: 50MB per package

```php
// In ModuleInstaller/ThemeInstaller
protected int $maxFileSize = 52428800; // 50MB
```

### File Type Validation

```php
// Modules: Only .zip and .vpm
// Themes: Only .zip and .vpt
```

## 🎯 Filament Admin Usage

### Installing a Module

1. Navigate to **Extensions > Modules**
2. Click **Install Module** button
3. Upload `.zip` or `.vpm` file
4. Toggle "Update if exists" for replacing
5. Submit

### Managing Modules

- **Enable/Disable**: Toggle button on each row
- **Edit**: Modify metadata
- **Delete**: Remove module and files
- **Bulk Actions**: Enable/disable multiple at once

### Installing a Theme

1. Navigate to **Appearance > Themes**
2. Click **Install Theme** button
3. Upload `.zip` or `.vpt` file
4. Submit

### Managing Themes

- **Activate**: Only one active at a time
- **Edit**: Modify metadata
- **Delete**: Cannot delete active theme

## 📝 Configuration

### config/cms.php

```php
'modules' => [
    'path' => 'Modules',
    'cache_enabled' => true,
    'cache_lifetime' => 3600,
    'auto_discover' => true,
],

'themes' => [
    'path' => 'themes',
    'active_theme' => null,
    'cache_enabled' => true,
    'cache_lifetime' => 3600,
],

'uploads' => [
    'max_size' => 10240, // KB
    'allowed_types' => [
        'modules' => ['zip'],
        'themes' => ['zip'],
    ],
],
```

## 🚀 Service Provider Integration

Services are registered in `CMSServiceProvider`:

```php
// Register services
$this->app->singleton(ModuleLoader::class);
$this->app->singleton(ModuleInstaller::class);
$this->app->singleton(ThemeLoader::class);
$this->app->singleton(ThemeInstaller::class);

// Boot services
$moduleLoader->discoverModules();
$themeLoader->discoverThemes();
$themeLoader->loadActiveTheme();
```

## 📚 Example Projects

### HelloWorld Module

Located in `/Modules/HelloWorld/`:
- Routes: `/hello`, `/hello/welcome`
- Controller: `HelloWorldController`
- Views: Gradient hero pages
- Full module structure demonstration

### BasicTheme

Located in `/themes/BasicTheme/`:
- Clean, modern design
- Responsive layout
- Header/footer components
- CSS custom properties
- Full theme structure demonstration

## 🔄 Installation Flow

### Module Installation

1. **Upload**: User uploads `.zip`/`.vpm` via Filament
2. **Validate File**: Check extension, size
3. **Extract**: Unzip to temp directory
4. **Find Root**: Locate `module.json`
5. **Validate Structure**: Check required fields
6. **Security Scan**: Detect dangerous files
7. **Move**: Copy to `/Modules/ModuleName/`
8. **Discover**: ModuleLoader registers module
9. **Database**: Create/update Module record
10. **Cleanup**: Remove temp files

### Theme Installation

1. **Upload**: User uploads `.zip`/`.vpt` via Filament
2. **Validate File**: Check extension, size
3. **Extract**: Unzip to temp directory
4. **Find Root**: Locate `theme.json`
5. **Validate Structure**: Check required fields
6. **Security Scan**: Detect dangerous files
7. **Move**: Copy to `/themes/ThemeName/`
8. **Discover**: ThemeLoader registers theme
9. **Database**: Create/update Theme record
10. **Cleanup**: Remove temp files

## ⚠️ Error Handling

### Installation Errors

```php
// Invalid file
['success' => false, 'message' => 'Invalid file type']

// File too large
['success' => false, 'message' => 'File too large. Maximum: 50MB']

// Missing metadata
['success' => false, 'message' => 'module.json not found']

// Validation failed
['success' => false, 'message' => 'Theme validation failed: ...']

// Security issue
['success' => false, 'message' => 'Archive contains invalid paths']
```

### Success Response

```php
[
    'success' => true,
    'message' => 'Module installed successfully',
    'module' => [
        'name' => 'Module Name',
        'slug' => 'ModuleName',
        'version' => '1.0.0',
        // ... metadata
    ]
]
```

## 🎓 Creating Custom Modules

### 1. Create Structure

```bash
Modules/
└── MyModule/
    ├── module.json
    ├── routes.php
    ├── controllers/
    │   └── MyController.php
    └── views/
        └── index.blade.php
```

### 2. Define Metadata

```json
{
    "name": "My Module",
    "slug": "MyModule",
    "version": "1.0.0",
    "description": "My custom module",
    "author": "Your Name",
    "active": true
}
```

### 3. Create Routes

```php
Route::prefix('my-module')->group(function () {
    Route::get('/', [MyController::class, 'index']);
});
```

### 4. Package as ZIP

```
MyModule.zip
└── MyModule/
    ├── module.json
    ├── routes.php
    └── ...
```

### 5. Install via Admin

Upload `MyModule.zip` through Filament UI.

## 🎨 Creating Custom Themes

### 1. Create Structure

```bash
themes/
└── MyTheme/
    ├── theme.json
    ├── layouts/
    ├── views/
    ├── components/
    └── assets/
```

### 2. Define Metadata

```json
{
    "name": "My Theme",
    "slug": "MyTheme",
    "version": "1.0.0",
    "author": "Your Name",
    "preview": "screenshot.png"
}
```

### 3. Create Layout

```blade
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('themes/MyTheme/assets/css/theme.css') }}">
</head>
<body>
    @yield('content')
</body>
</html>
```

### 4. Package as ZIP

```
MyTheme.zip
└── MyTheme/
    ├── theme.json
    ├── layouts/
    └── ...
```

### 5. Install & Activate

Upload `MyTheme.zip` and activate through Filament UI.

## 🔍 Debugging

### Check Discovered Modules

```php
$loader = app(ModuleLoader::class);
$modules = $loader->discoverModules();
dd($modules);
```

### Check Active Theme

```php
$loader = app(ThemeLoader::class);
$activeTheme = $loader->getActiveTheme();
dd($activeTheme);
```

### Validate Module

```php
$errors = $loader->validateModule(base_path('Modules/ModuleName'));
dd($errors);
```

### Check View Resolution

```php
$view = app(ThemeLoader::class)->resolveView('pages.home');
dd($view); // 'theme::pages.home' or 'pages.home'
```

## ✅ Implementation Checklist

- [x] ModuleLoader service
- [x] ModuleInstaller service
- [x] ThemeLoader service
- [x] ThemeInstaller service
- [x] ModuleResource Filament admin
- [x] ThemeResource Filament admin
- [x] Service provider registration
- [x] Example HelloWorld module
- [x] Example BasicTheme theme
- [x] Configuration updates
- [x] Security hardening
- [x] Documentation

## 📄 License

Proprietary - VantaPress CMS

---

**Version:** 1.0.0  
**Last Updated:** December 3, 2025  
**Author:** Sepiroth X Villainous
