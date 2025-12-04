# VantaPress Development Guide

**Version:** 1.0.13  
**Last Updated:** December 4, 2025  
**Author:** Sepiroth X Villainous (Richard Cebel Cupal, LPT)

---

## Table of Contents

1. [Introduction](#introduction)
2. [Philosophy & Core Principles](#philosophy--core-principles)
3. [Project Overview](#project-overview)
4. [Author & Development History](#author--development-history)
5. [Architecture Overview](#architecture-overview)
6. [Development Standards](#development-standards)
7. [Module Development Guide](#module-development-guide)
8. [Theme Development Guide](#theme-development-guide)
   - [Theme-Based Admin Styling](#theme-based-admin-styling)
9. [Deployment Guidelines](#deployment-guidelines)
   - [Architecture & Security Considerations](#️-critical-architecture--security-considerations)
   - [Root-Level vs public/ Folder](#root-level-vs-public-folder-structure)
   - [VPS/Dedicated Server Options](#for-vpsdedicated-server-users)
10. [Future Roadmap](#future-roadmap)

---

## Introduction

VantaPress is a WordPress-inspired Content Management System built on Laravel 11 and FilamentPHP 3, specifically designed for **shared hosting environments**. Unlike traditional Laravel applications, VantaPress is optimized for deployment scenarios where SSH access, command-line tools, and Node.js are unavailable.

### Why VantaPress?

- **Shared Hosting First**: Works perfectly on budget hosting plans without terminal access
- **No Build Tools Required**: Pre-compiled assets, no npm/webpack/vite needed in production
- **FTP Deployment**: Upload files via FTP and it just works
- **Filament Admin Panel**: Beautiful, powerful admin interface out of the box
- **Modular Architecture**: WordPress-style plugins and themes system
- **Scalable**: Start small, grow to enterprise without architectural changes

---

## Philosophy & Core Principles

### 1. **Simplicity First**

VantaPress embraces simplicity at every level:
- Drop files via FTP and they work immediately
- No complex build processes in production
- Intuitive admin interface powered by Filament
- Clear, understandable code structure

### 2. **Shared Hosting Friendly**

**CRITICAL DESIGN CONSTRAINT**: VantaPress must work in environments with:
- ❌ No SSH/terminal access
- ❌ No composer CLI
- ❌ No Node.js/npm
- ❌ No build tools (webpack, vite, etc.)
- ✅ Only FTP upload capability
- ✅ PHP 8.2+ with standard extensions
- ✅ MySQL/MariaDB database

### 3. **Developer Experience**

While production is CLI-free, **development** can leverage modern tools:
- Use Laravel Artisan commands during development
- Use npm/vite for asset compilation during development
- Use Composer for dependency management during development
- **Deploy pre-built artifacts to production**

### 4. **WordPress-Inspired, Laravel-Powered**

Combines the best of both worlds:
- WordPress's modular plugin/theme system
- Laravel's robust architecture and ecosystem
- Filament's modern admin interface
- Hook/filter system for extensibility

### 5. **Enterprise-Ready Foundations**

VantaPress can scale from simple blogs to complex enterprise applications:
- Role-based access control (Spatie Permissions)
- Activity logging (Spatie Activity Log)
- Modular architecture for team development
- Professional admin panel with Filament

---

## Project Overview

### Current Features (v1.0.4)

#### Core CMS Features
- **Content Management**: Posts, pages, categories, tags, media library
- **User Management**: Multi-user support with roles and permissions
- **Module System**: WordPress-style plugins with auto-discovery
- **Theme System**: Customizable themes with blade templating
- **Admin Panel**: Modern Filament-based dashboard

#### Built-in Modules

**VPEssential1** - Core functionality module
- Theme Customizer
- Menu Builder
- Widget Manager
- User Profiles
- Tweeting System (micro-blogging)

**VPToDoList** - Task management system
- Project-based task organization
- User-specific workspaces
- Priority levels and due dates
- Beautiful modern UI

**HelloWorld** - Developer template module
- Comprehensive examples of module structure
- 1,000+ lines of documentation
- Controller, view, and routing examples
- Best practices demonstration

#### Technology Stack
- **Framework**: Laravel 11.x
- **Admin Panel**: FilamentPHP 3.3+
- **PHP**: 8.2+ (8.5.0 compatible)
- **Database**: MySQL/MariaDB
- **Frontend**: Blade templates, TailwindCSS
- **Permissions**: Spatie Laravel Permission
- **Activity Log**: Spatie Laravel Activity Log
- **Image Processing**: Intervention Image

---

## Author & Development History

### About the Author

**Sepiroth X Villainous** (Richard Cebel Cupal, LPT)
- **Email**: chardy.tsadiq02@gmail.com
- **Mobile**: +63 915 0388 448
- **GitHub**: [@sepiroth-x](https://github.com/sepiroth-x)
- **Facebook**: [sepirothx](https://www.facebook.com/sepirothx/)
- **Twitter/X**: [@sepirothx000](https://x.com/sepirothx000)

### Development Timeline

**Early Development (Pre-December 2024)**
- Initial concept: Laravel-based CMS for educational institutions
- Originally named "TCC School CMS" for Talisay City College
- Proof of concept with basic content management

**December 2024 - Rebranding to VantaPress**
- Pivoted from school-specific to general-purpose CMS
- Implemented modular architecture
- Developed theme system
- Created module auto-discovery mechanism

**December 3, 2025 - Version 1.0.3**
- Fixed shared hosting deployment issues
- Created pre-deployment package system
- Resolved storage directory permissions
- Fixed encryption key generation
- Cleaned up duplicate code

**December 4, 2025 - Version 1.0.4**
- Enhanced HelloWorld module as developer template
- Reordered admin navigation (Extensions menu placement)
- Fixed module routing and service provider registration
- Created missing base Controller class
- Improved developer documentation

### Development Philosophy Evolution

The project evolved from a specific institutional need to a general-purpose CMS with a unique selling point: **perfect shared hosting compatibility**. This constraint became a strength, making VantaPress accessible to developers and organizations with limited infrastructure.

---

## Architecture Overview

### Directory Structure

```
vantapress/
├── app/
│   ├── Filament/           # Filament admin panel resources
│   │   ├── Pages/          # Custom admin pages
│   │   └── Resources/      # CRUD resources (Posts, Pages, Users, Modules, etc.)
│   ├── Helpers/            # Helper functions
│   ├── Http/
│   │   ├── Controllers/    # Base controllers
│   │   └── Middleware/     # Custom middleware (Theme, Module)
│   ├── Models/             # Eloquent models
│   ├── Providers/          # Service providers
│   │   ├── CMSServiceProvider.php    # Main CMS provider (NOT auto-loaded)
│   │   └── Filament/
│   │       └── AdminPanelProvider.php # Filament configuration
│   └── Services/           # Business logic services
│       ├── ModuleLoader.php      # Module discovery & loading
│       ├── ModuleInstaller.php   # Module installation
│       ├── ThemeLoader.php       # Theme discovery & loading
│       └── ThemeInstaller.php    # Theme installation
├── bootstrap/
│   └── app.php             # Application bootstrap (module providers registered here)
├── config/
│   ├── cms.php             # VantaPress CMS configuration
│   ├── filament.php        # Filament admin panel configuration
│   └── modules.php         # Module system configuration
├── Modules/                # Modules directory (WordPress "plugins")
│   ├── HelloWorld/
│   │   ├── Controllers/    # Module controllers
│   │   ├── views/          # Module views
│   │   ├── migrations/     # Module database migrations
│   │   ├── routes.php      # Module routes
│   │   ├── module.json     # Module metadata
│   │   ├── HelloWorldServiceProvider.php  # Module service provider
│   │   └── README.md       # Module documentation
│   ├── VPEssential1/
│   └── VPToDoList/
├── vantapress/             # Themes directory
│   └── default/            # Default theme
│       ├── views/          # Theme templates
│       ├── theme.json      # Theme metadata
│       └── functions.php   # Theme functions (optional)
├── public/                 # Public web root
│   ├── index.php           # Application entry point
│   ├── css/                # Compiled CSS (for deployment)
│   └── js/                 # Compiled JS (for deployment)
├── resources/              # Development assets
│   ├── views/              # Core blade templates
│   ├── css/                # Source CSS files
│   └── js/                 # Source JS files
├── routes/
│   ├── web.php             # Web routes
│   ├── api.php             # API routes
│   └── console.php         # Console commands
├── storage/                # Application storage
│   ├── app/                # Application files
│   ├── framework/          # Framework cache/sessions
│   └── logs/               # Log files
└── vendor/                 # Composer dependencies (include in deployment)
```

### Service Provider Architecture

**IMPORTANT**: VantaPress uses a hybrid service provider model:

1. **CMSServiceProvider** - Main CMS provider that discovers and loads modules/themes
   - Located in `app/Providers/CMSServiceProvider.php`
   - **NOT auto-registered** by Laravel
   - Modules must be manually registered in `bootstrap/app.php`

2. **Module Service Providers** - Individual module providers
   - Must be registered in `bootstrap/app.php` `withProviders()` array
   - Example: `\Modules\HelloWorld\HelloWorldServiceProvider::class`
   - Use `loadRoutesFrom()` and `loadViewsFrom()` methods

3. **Filament Admin Panel Provider**
   - Registered in `bootstrap/app.php`
   - Configures admin panel resources and navigation

### Module Loading Flow

```
1. Application Bootstrap (bootstrap/app.php)
   ↓
2. Register Module Service Providers
   ↓
3. Module Service Provider boot()
   ↓
4. loadRoutesFrom() - Registers module routes
   ↓
5. loadViewsFrom() - Registers module views with namespace
   ↓
6. Module routes available at runtime
```

### Autoloading in Production

**Key Insight**: Service providers work in production without CLI because:

```json
// composer.json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Modules\\": "Modules/"
    }
}
```

When you run `composer dump-autoload` during development, it generates:
- `vendor/composer/autoload_psr4.php`
- `vendor/composer/autoload_classmap.php`
- `vendor/composer/autoload_static.php`

These files map `Modules\HelloWorld\HelloWorldServiceProvider` to the file path. When you upload the `vendor/` directory to production, **all module classes are already mapped** and work without running composer.

---

## Development Standards

### ⚠️ Must Know: Filament Asset Management

**CRITICAL:** VantaPress uses a **root-level structure** (no `public/` folder), but Filament's asset publisher (`php artisan filament:assets`) is designed to publish assets to `public/css/` and `public/js/` by default.

#### The Problem
When you run `php artisan filament:assets`, Filament will:
- ❌ Create a `public/` folder in your project root
- ❌ Publish CSS/JS assets to `public/css/filament/` and `public/js/filament/`
- ❌ Break the root-level structure
- ❌ Cause asset loading issues in the admin panel

#### Our Solution
VantaPress overrides Laravel's public path to point to the **root directory** instead of `public/`:

**1. In `index.php`:**
```php
$app = require_once __DIR__.'/bootstrap/app.php';

// Override public path to use base directory (root-level structure)
$app->usePublicPath(__DIR__);
```

**2. In `artisan`:**
```php
$app = require_once __DIR__.'/bootstrap/app.php';

// Override public path to use base directory (root-level structure)
$app->usePublicPath(__DIR__);
```

**3. AppServiceProvider (optional binding):**
```php
public function boot(): void
{
    // Set public path to base directory (root-level structure)
    $this->app->bind('path.public', function() {
        return base_path();
    });
}
```

#### Result
With this configuration:
- ✅ `php artisan filament:assets` publishes directly to `/css/filament/` and `/js/filament/`
- ✅ No `public/` folder is created
- ✅ Assets are accessible at root level (e.g., `/css/filament/filament/app.css`)
- ✅ Works perfectly in both development and production

#### Asset Loading in Admin Panel
Filament's render hooks inject the required CSS/JS files in `AdminPanelProvider.php`:

```php
use Filament\View\PanelsRenderHook;

->renderHook(
    PanelsRenderHook::STYLES_AFTER,
    fn (): string => '<link rel="stylesheet" href="' . asset('css/filament/filament/app.css') . '?v=3.3.45">' .
                     '<link rel="stylesheet" href="' . asset('css/filament-theme.css') . '">' .
                     '<link rel="stylesheet" href="' . asset('css/vantapress-admin.css') . '">'
)
->renderHook(
    PanelsRenderHook::SCRIPTS_AFTER,
    fn (): string => '<script src="' . asset('js/filament/filament/app.js') . '?v=3.3.45"></script>'
)
```

#### Publishing Filament Assets

When you need to update Filament assets (after updating Filament version):

```bash
# This will now publish to root /css/ and /js/ folders (not public/)
php artisan filament:assets
```

#### Helper Script (Optional)
If you ever need to copy assets from `public/` to root (e.g., if public path override fails):

```bash
php sync-filament-assets.php
```

This script copies assets from `public/css/filament/` and `public/js/filament/` to the root directories.

---

### Local Development Server

⚠️ **IMPORTANT:** You cannot use `php artisan serve` with VantaPress because it requires a `public/` folder which we don't have.

Instead, create two PHP files in your project root:

#### 1. Create `serve.php`

```php
<?php
/**
 * VantaPress Development Server
 * 
 * Use this instead of `php artisan serve` for local development
 * Since we don't have a public/ folder structure
 */

$host = $argv[1] ?? '127.0.0.1';
$port = $argv[2] ?? '8000';

echo "VantaPress Development Server\n";
echo "==============================\n";
echo "Server: http://{$host}:{$port}\n";
echo "Document Root: " . __DIR__ . "\n";
echo "Press Ctrl+C to stop\n\n";

// Start PHP built-in server from root directory with router
$command = sprintf(
    'php -S %s:%s -t %s %s',
    escapeshellarg($host),
    escapeshellarg($port),
    escapeshellarg(__DIR__),
    escapeshellarg(__DIR__ . '/server.php')
);

passthru($command);
```

#### 2. Create `server.php`

```php
<?php
/**
 * Laravel development server router
 * Handles static files and routes requests through index.php
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    // Let PHP's built-in server handle static files
    return false;
}

// Route everything else through Laravel's index.php
require_once __DIR__ . '/index.php';
```

#### 3. Usage

```bash
# Start development server (default: http://127.0.0.1:8000)
php serve.php

# Custom host and port
php serve.php 0.0.0.0 8080
```

**Note:** These files are in `.gitignore` and won't be included in production deployments. Production hosting uses Apache/Nginx which handles routing automatically.

---

### General Principles

1. **Think Shared Hosting First**
   - Don't add features that require CLI tools in production
   - Pre-compile assets during development
   - Test FTP deployment scenarios
   - Avoid dependencies that need system libraries

2. **Follow Laravel Conventions**
   - Use PSR-4 autoloading
   - Follow Laravel directory structure
   - Use Eloquent ORM for database
   - Use Blade for templating

3. **Leverage Filament**
   - Build admin interfaces with Filament resources
   - Use Filament form builder for settings
   - Follow Filament navigation patterns
   - Utilize Filament widgets for dashboard

4. **Code Quality**
   - Write PHPDoc comments for all classes and methods
   - Use type hints (PHP 8.2+)
   - Follow PSR-12 coding standards
   - Keep methods focused and single-purpose

### Naming Conventions

#### Files and Directories
- **Controllers**: PascalCase with `Controller` suffix (e.g., `HelloWorldController.php`)
- **Models**: PascalCase, singular (e.g., `Post.php`, `User.php`)
- **Views**: snake_case (e.g., `index.blade.php`, `post_detail.blade.php`)
- **Routes**: Use descriptive names with dots (e.g., `hello.index`, `admin.posts.edit`)
- **Modules**: PascalCase directory (e.g., `HelloWorld`, `VPToDoList`)
- **Themes**: lowercase directory (e.g., `default`, `corporate`)

#### Code
- **Classes**: PascalCase (e.g., `ModuleLoader`, `ThemeManager`)
- **Methods**: camelCase (e.g., `discoverModules()`, `loadActiveTheme()`)
- **Variables**: camelCase (e.g., `$modulePath`, `$themeConfig`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MODULE_DIRECTORY`, `THEME_PATH`)
- **Routes**: kebab-case (e.g., `/hello-world`, `/admin/posts`)

### Database Conventions

- **Table Names**: plural, snake_case (e.g., `posts`, `module_settings`)
- **Pivot Tables**: alphabetical order (e.g., `post_tag`, not `tag_post`)
- **Foreign Keys**: singular_id (e.g., `user_id`, `category_id`)
- **Timestamps**: Use Laravel's `timestamps()` in migrations
- **Soft Deletes**: Use `softDeletes()` for recoverable data

### Version Control

- **Branch Strategy**:
  - `main` - Stable production releases (tagged)
  - `release` - Release candidate branch
  - `develop` - Active development
  - Feature branches: `feature/module-name`

- **Commit Messages**:
  ```
  Type: Brief description (50 chars max)
  
  - Detailed change 1
  - Detailed change 2
  - Detailed change 3
  
  Context or reason for changes
  ```
  
  Types: `Fix`, `Feature`, `Enhancement`, `Refactor`, `Docs`, `Style`

- **Versioning Standard**:
  - **Format**: `vX.Y.Z-complete` (e.g., `v1.0.5-complete`)
  - **Semantic Versioning**:
    - Major (X): Breaking changes, major feature overhauls
    - Minor (Y): New features, backwards-compatible
    - Patch (Z): Bug fixes, minor improvements
  - **Suffix**: Always append `-complete` to release tags
  - **Version Files**:
    - Update `config/version.php` default value
    - Update `.env.example` APP_VERSION
    - Update `DEVELOPMENT_GUIDE.md` version header
  - **Release Process**:
    1. Update version in all relevant files
    2. Commit changes: `Version: Update to vX.Y.Z`
    3. Create annotated tag: `git tag -a vX.Y.Z-complete -m "Release notes"`
    4. Push tag: `git push origin vX.Y.Z-complete`
  - **Example Tags**: v1.0.0-complete, v1.0.5-complete, v1.1.0-complete

- **Never Commit**:
  - `.env` file
  - `node_modules/`
  - `storage/` contents (except `.gitkeep`)
  - `bootstrap/cache/` compiled files
  - IDE-specific files
  - Local development configs

---

## Module Development Guide

### Module Structure

Every VantaPress module must follow this structure:

```
Modules/YourModule/
├── Controllers/           # Module controllers (optional)
│   └── YourModuleController.php
├── Filament/             # Filament resources (optional)
│   ├── Pages/            # Custom admin pages
│   └── Resources/        # CRUD resources
├── Models/               # Module-specific models (optional)
├── views/                # Module views
│   └── index.blade.php
├── migrations/           # Database migrations (optional)
├── helpers/              # Helper functions (optional)
│   └── functions.php
├── routes.php            # Module routes (required if using routes)
├── module.json           # Module metadata (REQUIRED)
├── YourModuleServiceProvider.php  # Service provider (REQUIRED)
└── README.md             # Module documentation (recommended)
```

### Step 1: Create module.json

```json
{
    "name": "Your Module Name",
    "slug": "YourModule",
    "version": "1.0.0",
    "description": "Brief description of what your module does",
    "author": "Your Name",
    "author_email": "your.email@example.com",
    "author_url": "https://yourwebsite.com",
    "license": "Open Source",
    "social_links": {
        "github": "https://github.com/yourusername",
        "email": "your.email@example.com"
    },
    "active": true,
    "service_provider": "Modules\\YourModule\\YourModuleServiceProvider"
}
```

**Required Fields**:
- `name` - Display name
- `slug` - Must match directory name (PascalCase)
- `version` - Semantic version (e.g., "1.0.0")
- `description` - Short description
- `active` - Boolean, should be `true` for new modules
- `service_provider` - Full class name with namespace

### Step 2: Create Service Provider

```php
<?php

namespace Modules\YourModule;

use Illuminate\Support\ServiceProvider;

class YourModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Load helper functions if you have them
        $helpersPath = __DIR__ . '/helpers/functions.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Register module routes
        $this->registerRoutes();
        
        // Register module views
        $this->registerViews();
        
        // Register migrations
        $this->registerMigrations();
    }

    /**
     * Register module routes
     */
    protected function registerRoutes(): void
    {
        $routesPath = __DIR__ . '/routes.php';
        
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }
    }

    /**
     * Register module views
     */
    protected function registerViews(): void
    {
        $viewsPath = __DIR__ . '/views';
        
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'YourModule');
        }
    }

    /**
     * Register migrations
     */
    protected function registerMigrations(): void
    {
        $migrationsPath = __DIR__ . '/migrations';
        
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }
}
```

### Step 3: Create Routes (if needed)

**File**: `routes.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\YourModule\Controllers\YourModuleController;

Route::prefix('your-module')->name('yourmodule.')->group(function () {
    
    // Public routes
    Route::get('/', [YourModuleController::class, 'index'])
        ->name('index');
    
    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [YourModuleController::class, 'dashboard'])
            ->name('dashboard');
    });
});
```

### Step 4: Create Controller (if needed)

```php
<?php

namespace Modules\YourModule\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class YourModuleController extends Controller
{
    /**
     * Display module index page
     */
    public function index()
    {
        return view('YourModule::index', [
            'title' => 'Your Module',
            'data' => $this->getModuleData(),
        ]);
    }

    /**
     * Get module data
     */
    protected function getModuleData(): array
    {
        return [
            // Your data here
        ];
    }
}
```

### Step 5: Create Views

**File**: `views/index.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">{{ $title }}</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <p>Your module content here</p>
        </div>
    </div>
</body>
</html>
```

**Tip**: Use view namespace `YourModule::viewname` to reference views:
```php
view('YourModule::index')  // Loads views/index.blade.php
```

### Step 6: Register in bootstrap/app.php

**CRITICAL**: Modules won't work until registered!

**File**: `bootstrap/app.php`

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\Filament\AdminPanelProvider::class,
        \Modules\VPEssential1\VPEssential1ServiceProvider::class,
        \Modules\VPToDoList\VPToDoListServiceProvider::class,
        \Modules\HelloWorld\HelloWorldServiceProvider::class,
        \Modules\YourModule\YourModuleServiceProvider::class,  // ADD YOUR MODULE
    ])
    // ... rest of configuration
```

### Advanced Module Features

#### Adding Filament Admin Resources

Create admin CRUD interfaces:

```php
<?php

namespace Modules\YourModule\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Modules\YourModule\Models\YourModel;

class YourModelResource extends Resource
{
    protected static ?string $model = YourModel::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Your Module';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ]);
    }
}
```

Then register in service provider:

```php
public function boot(): void
{
    // ... other boot code
    
    // Register Filament resources
    \Filament\Facades\Filament::getCurrentPanel()?->resources([
        \Modules\YourModule\Filament\Resources\YourModelResource::class,
    ]);
}
```

#### Adding Database Migrations

Create migration files in `migrations/`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('your_module_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('your_module_table');
    }
};
```

**Note**: Migrations run automatically when module is loaded.

#### Module Settings

Use Laravel's config system or create a settings table:

```php
// In your service provider
$this->mergeConfigFrom(
    __DIR__ . '/config/yourmodule.php', 'yourmodule'
);

// Access in code
config('yourmodule.setting_name');
```

---

## Theme Development Guide

### Theme Structure

```
vantapress/your-theme/
├── views/                # Theme templates
│   ├── layouts/
│   │   ├── app.blade.php       # Main layout
│   │   ├── header.blade.php    # Header partial
│   │   └── footer.blade.php    # Footer partial
│   ├── pages/
│   │   ├── home.blade.php      # Homepage
│   │   └── page.blade.php      # Default page template
│   ├── posts/
│   │   ├── index.blade.php     # Post listing
│   │   └── show.blade.php      # Single post
│   └── partials/
│       └── sidebar.blade.php   # Reusable components
├── assets/               # Theme assets (optional)
│   ├── css/
│   ├── js/
│   └── images/
├── theme.json           # Theme metadata (REQUIRED)
├── functions.php        # Theme functions (optional)
├── screenshot.png       # Theme preview (optional)
└── README.md            # Theme documentation
```

### Creating a Theme

#### Step 1: Create theme.json

```json
{
    "name": "Your Theme Name",
    "slug": "your-theme",
    "version": "1.0.0",
    "description": "Beautiful theme for VantaPress",
    "author": "Your Name",
    "author_url": "https://yourwebsite.com",
    "screenshot": "screenshot.png",
    "tags": ["modern", "responsive", "blog"],
    "active": false
}
```

#### Step 2: Create Main Layout

**File**: `views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('layouts.header')
    
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
```

#### Step 3: Create Templates

**Homepage**: `views/pages/home.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Welcome to ' . config('app.name'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($posts as $post)
        <article class="bg-white rounded-lg shadow-md overflow-hidden">
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-2">
                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                </h2>
                <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt, 150) }}</p>
                <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 hover:underline">
                    Read more →
                </a>
            </div>
        </article>
    @endforeach
</div>
@endsection
```

#### Step 4: Theme Functions (Optional)

**File**: `functions.php`

```php
<?php

// Theme setup
add_action('init', function() {
    // Register navigation menus
    register_nav_menus([
        'primary' => 'Primary Navigation',
        'footer' => 'Footer Navigation',
    ]);
    
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
});

// Custom helper function
function get_theme_option($key, $default = null)
{
    return config("theme.{$key}", $default);
}
```

### Theme Activation

Themes are activated through the VantaPress admin panel:
1. Navigate to **Appearance > Themes**
2. Find your theme in the list
3. Click **Activate**

The theme loader automatically discovers themes in the `vantapress/` directory.

---

### Theme-Based Admin Styling

**NEW IN v1.0.12**: Admin panel styling is now controlled by the active theme, providing a unified design experience across frontend and backend.

#### Architecture

Each theme controls **both** the frontend website appearance AND the admin panel aesthetics through two CSS files:

```
themes/YourTheme/
├── assets/
│   └── css/
│       ├── theme.css    ← Frontend website styling
│       └── admin.css    ← Admin panel styling ⭐
```

#### How It Works

The `AdminPanelProvider` automatically detects the active theme and loads its `admin.css`:

```php
// app/Providers/Filament/AdminPanelProvider.php
->renderHook(
    PanelsRenderHook::STYLES_AFTER,
    function (): string {
        $themeManager = app(\App\Services\CMS\ThemeManager::class);
        $activeTheme = $themeManager->getActiveTheme();
        $adminCss = asset("themes/{$activeTheme}/assets/css/admin.css") . '?v=' . time();
        
        return '<link rel="stylesheet" href="' . $adminCss . '">';
    }
)
```

#### What Themes Can Style

**✅ Themes Control (Visual Styling Only):**
- Colors, gradients, and color schemes
- Typography (fonts, sizes, weights)
- Borders, shadows, and spacing
- Light and dark mode aesthetics
- Sidebar appearance
- Card designs
- Form input styling
- Button styles
- Table layouts
- Hover and active states

**❌ Themes DON'T Control (Filament Core):**
- Admin panel functionality
- Navigation structure
- Resource/Page behavior
- Form validation logic
- Data models or controllers
- Widgets or actions
- Dashboard widgets

#### Creating Admin Styles

**File**: `themes/YourTheme/assets/css/admin.css`

```css
/* Dark Mode Styling */
.dark .fi-sidebar {
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%) !important;
    border-right: 3px solid #ff0033 !important;
}

.dark .fi-card {
    background: #16213e !important;
    border: 2px solid #4ecdc4 !important;
    box-shadow: 4px 4px 0 #ff0033 !important;
}

/* Light Mode Styling */
html:not(.dark) .fi-sidebar {
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border-right: 3px solid #ff6b6b !important;
}

html:not(.dark) .fi-card {
    background: white !important;
    border: 2px solid #4ecdc4 !important;
}

/* Buttons */
.fi-btn-primary {
    background: #ff0033 !important;
    border: 2px solid #ffd93d !important;
}

.fi-btn-primary:hover {
    background: #ffd93d !important;
    color: #1a1a2e !important;
}
```

#### Important Selectors

Common Filament CSS classes to target:

```css
/* Layout */
.fi-sidebar          /* Main sidebar */
.fi-topbar           /* Top navigation bar */
.fi-main             /* Main content area */

/* Components */
.fi-card             /* Card containers */
.fi-section          /* Section containers */
.fi-stats-card       /* Dashboard statistics */

/* Navigation */
.fi-sidebar-nav-item        /* Sidebar menu items */
.fi-sidebar-item-active     /* Active menu item */

/* Forms */
.fi-input            /* Text inputs */
.fi-select           /* Select dropdowns */
.fi-textarea         /* Text areas */
.fi-btn              /* Buttons */

/* Tables */
.fi-table            /* Table wrapper */
.fi-table-header-cell    /* Column headers */
.fi-table-cell       /* Table cells */
.fi-table-row        /* Table rows */
```

#### Best Practices

1. **Use !important**: Filament has strong default styles, override with `!important`
2. **Support Both Modes**: Always style both `.dark` and `html:not(.dark)`
3. **Preserve Functionality**: Only change visual properties (colors, fonts, spacing)
4. **Test Thoroughly**: Check all admin pages, forms, tables after styling
5. **Cache Busting**: The provider adds automatic `?v=timestamp` to force reloads

#### Example: Corporate Theme

```css
/* Professional, minimal design */
.dark .fi-sidebar {
    background: #2d3748 !important;
    border-right: 1px solid #4a5568 !important;
}

.dark .fi-card {
    background: #1a202c !important;
    border: 1px solid #4a5568 !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.fi-btn-primary {
    background: #3182ce !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
}
```

#### Example: Retro Gaming Theme (Default)

```css
/* Flat colors, pixel-perfect, 16-bit aesthetic */
.dark .fi-sidebar {
    background: linear-gradient(180deg, #16213E 0%, #1A1A2E 100%) !important;
    border-right: 3px solid #FF0033 !important;
}

.dark .fi-card {
    background: #16213E !important;
    border: 4px solid #4ECDC4 !important;
    border-radius: 0px !important; /* Sharp corners */
    box-shadow: 8px 8px 0 #FF0033 !important; /* Solid shadow */
}

.dark h1, .dark h2, .dark h3 {
    color: #FFD93D !important;
    font-weight: 900 !important;
    text-transform: uppercase !important;
    letter-spacing: 3px !important;
    text-shadow: 4px 4px 0 #FF0033 !important;
}
```

#### Documentation

For comprehensive theming architecture documentation, see:
- `THEME_ARCHITECTURE.md` - Complete theme system guide
- `themes/BasicTheme/README.md` - Default theme documentation

---

## Deployment Guidelines

### ⚠️ CRITICAL: Architecture & Security Considerations

#### Root-Level vs public/ Folder Structure

VantaPress uses a **root-level architecture** optimized for shared hosting. This is **different from traditional Laravel applications** and requires understanding:

**Traditional Laravel (VPS/Dedicated Server):**
```
/var/www/myapp/           ← NOT web-accessible
├── .env                   ← Protected by directory structure
├── app/                   ← Protected
├── config/                ← Protected
├── vendor/                ← Protected
└── public/                ← ONLY this folder is web root (document root)
    ├── index.php          ← Entry point
    ├── css/
    └── js/
```

**VantaPress (Shared Hosting):**
```
/public_html/              ← EVERYTHING is in web root
├── .env                   ← Protected by .htaccess
├── app/                   ← Protected by .htaccess
├── config/                ← Protected by .htaccess
├── vendor/                ← Protected by .htaccess
├── index.php              ← Entry point
├── css/                   ← Publicly accessible (intended)
├── js/                    ← Publicly accessible (intended)
└── images/                ← Publicly accessible (intended)
```

#### Why No public/ Folder?

**Shared Hosting Constraints:**
- You CANNOT change the document root (it's controlled by the host)
- The web root IS the main directory (`public_html/`, `www/`, `htdocs/`)
- Traditional Laravel's `public/` folder structure doesn't work
- FTP access is the only deployment method

**Security Through .htaccess:**

VantaPress protects sensitive files via `.htaccess` rules:

```apache
# Block sensitive file extensions
<FilesMatch "\.(env|log|json|lock|yml|yaml|xml|sql)$">
    Deny from all
</FilesMatch>

# Block sensitive directories
RedirectMatch 403 ^/(app|bootstrap|config|database|vendor|storage)/

# Block composer files
<FilesMatch "^(composer\.(json|lock)|artisan)$">
    Deny from all
</FilesMatch>

# Disable directory browsing
Options -Indexes
```

**Protected (Cannot Access):**
- ❌ `/.env` - Environment configuration
- ❌ `/app/` - Application code
- ❌ `/config/` - Configuration files
- ❌ `/vendor/` - Composer dependencies
- ❌ `/composer.json` - Dependency manifest
- ❌ `/artisan` - CLI tool

**Public (Can Access):**
- ✅ `/index.php` - Entry point (required)
- ✅ `/install.php` - Installer (delete after use)
- ✅ `/css/` - Stylesheets (intended)
- ✅ `/js/` - JavaScript files (intended)
- ✅ `/images/` - Image assets (intended)

#### For VPS/Dedicated Server Users

If you're deploying to a VPS or dedicated server where you CAN control the document root:

**Option 1: Use as-is (Recommended for consistency)**
- Point document root to the main directory
- VantaPress's `.htaccess` will protect sensitive files
- Same structure works on both shared hosting and VPS

**Option 2: Create public/ symlink structure (Advanced)**
- Create a `public/` directory
- Move `index.php`, `css/`, `js/`, `images/` into `public/`
- Update paths in `index.php` (`require __DIR__.'/../vendor/autoload.php'`)
- Point document root to `public/` directory
- Update `.htaccess` to remove directory blocking rules
- **Note:** This breaks shared hosting compatibility

**We recommend Option 1** to maintain compatibility across all hosting types.

#### WordPress/Joomla Comparison

Popular CMSs use the same approach:
- **WordPress**: Root-level structure, `.htaccess` protection, powers 43% of the web
- **Joomla**: Root-level structure, `.htaccess` protection, millions of sites
- **Drupal**: Root-level structure (can also use public/ on VPS)

VantaPress follows proven patterns used by industry-leading CMSs.

### Pre-Deployment Checklist

#### Development Environment

1. **Test All Features**
   - ✅ Test all module routes
   - ✅ Verify Filament admin panel works
   - ✅ Check file uploads work correctly
   - ✅ Test theme switching
   - ✅ Verify database migrations run

2. **Compile Assets**
   ```bash
   npm run build              # Compile assets for production
   php artisan route:clear    # Clear route cache
   php artisan config:clear   # Clear config cache
   php artisan view:clear     # Clear view cache
   ```

3. **Update Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

4. **Generate Autoload Files**
   ```bash
   composer dump-autoload -o
   ```

### Deployment Package

Create a deployment-ready package:

```bash
# Include these directories/files:
app/
bootstrap/
config/
database/migrations/
Modules/
public/
resources/views/
routes/
storage/ (with .gitkeep files only)
vantapress/
vendor/ (IMPORTANT: Include this!)
.env.example
artisan
composer.json
composer.lock
index.php
```

**DO NOT include**:
- `.env` (upload separately and configure)
- `node_modules/`
- `storage/` contents (except structure)
- `bootstrap/cache/` compiled files
- `.git/`
- Development tools

### FTP Upload Process

1. **Connect to Hosting**
   - Use FTP client (FileZilla, WinSCP, etc.)
   - Connect to your hosting account

2. **Upload Files**
   - Upload all files to public_html or domain root
   - Ensure `public/` contents go to web root
   - Set permissions: 755 for directories, 644 for files
   - Set `storage/` and `bootstrap/cache/` to 775

3. **Configure Environment**
   - Copy `.env.example` to `.env`
   - Edit `.env` with your database credentials
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Generate `APP_KEY` if not present

4. **Database Setup**
   - Import database via phpMyAdmin or hosting control panel
   - Run migrations if needed (via hosting panel tools or temporary artisan script)

5. **Verify Installation**
   - Visit your domain
   - Check admin panel at `/admin`
   - Test module routes
   - Upload test media file

### Production Environment Requirements

**Minimum Requirements**:
- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- 128MB PHP memory limit (256MB recommended)
- File upload support
- PDO PHP extension
- OpenSSL PHP extension
- Mbstring PHP extension
- JSON PHP extension
- GD or Imagick extension (for image processing)

**Recommended Optimizations**:
- Enable OPcache
- Use PHP 8.3 for better performance
- Enable compression (gzip)
- Configure proper file permissions
- Set up automatic backups

### Post-Deployment

1. **Test Critical Paths**
   - Homepage loads
   - Admin login works
   - Post creation works
   - Media uploads work
   - Module routes accessible

2. **Performance Check**
   - Page load times < 3 seconds
   - Database queries optimized
   - Images compressed

3. **Security**
   - `APP_DEBUG=false` in production
   - Strong database passwords
   - Regular backups configured
   - SSL certificate installed

---

## Future Roadmap

### Version 1.1 - Enhanced Content Management (Q1 2026)

- **Custom Post Types**: Define custom content types beyond posts/pages
- **Advanced Media Library**: Better organization, galleries, CDN support
- **Content Revisions**: Track and restore previous versions
- **Scheduled Publishing**: Future-date post publishing
- **Multi-language Support**: i18n framework integration

### Version 1.2 - E-Commerce Module (Q2 2026)

- **VPShop Module**: Basic e-commerce functionality
  - Product management
  - Shopping cart
  - Payment gateway integration (PayPal, Stripe)
  - Order management
  - Inventory tracking

### Version 1.3 - Advanced Features (Q3 2026)

- **API Framework**: RESTful API for headless CMS
- **GraphQL Support**: Modern API queries
- **Webhook System**: Event-driven integrations
- **Advanced Caching**: Redis/Memcached support
- **CDN Integration**: Automatic asset delivery

### Version 2.0 - Enterprise Edition (Q4 2026)

- **Multi-Site Management**: Manage multiple sites from one install
- **Advanced Permissions**: Fine-grained access control
- **Workflow System**: Content approval workflows
- **Analytics Dashboard**: Built-in analytics
- **White Label**: Rebrandable admin panel

### Community Goals

- **Module Marketplace**: Community-contributed modules
- **Theme Gallery**: Showcase of VantaPress themes
- **Documentation Portal**: Comprehensive online docs
- **Video Tutorials**: Step-by-step guides
- **Developer Forum**: Community support platform

---

## Best Practices & Tips

### Performance

1. **Database Queries**
   - Use eager loading: `$posts = Post::with('author', 'category')->get();`
   - Avoid N+1 queries
   - Index frequently queried columns

2. **Caching**
   - Cache expensive operations
   - Use Laravel's cache facade
   - Clear cache after updates

3. **Asset Optimization**
   - Minify CSS/JS before deployment
   - Optimize images (WebP format)
   - Use CDN for static assets in production

### Security

1. **Input Validation**
   - Always validate user input
   - Use Laravel's validation rules
   - Sanitize HTML content

2. **Authentication**
   - Use Laravel's built-in auth
   - Implement rate limiting
   - Add 2FA for admin accounts

3. **File Uploads**
   - Validate file types
   - Limit file sizes
   - Store uploads outside web root when possible

### Code Organization

1. **Controllers**
   - Keep thin, delegate to services
   - Single responsibility
   - Use form requests for validation

2. **Models**
   - Use accessors/mutators for data transformation
   - Define relationships clearly
   - Use scopes for reusable queries

3. **Views**
   - Use Blade components for reusability
   - Keep logic minimal
   - Extract complex logic to view composers

---

## Getting Help

### Resources

- **GitHub Repository**: [sepiroth-x/vantapress](https://github.com/sepiroth-x/vantapress)
- **Documentation**: See README.md and module-specific docs
- **Example Module**: Study `Modules/HelloWorld/` for reference

### Support Channels

- **Email**: chardy.tsadiq02@gmail.com
- **GitHub Issues**: Report bugs and feature requests
- **Social Media**:
  - Facebook: [@sepirothx](https://www.facebook.com/sepirothx/)
  - Twitter/X: [@sepirothx000](https://x.com/sepirothx000)

### Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write/update tests
5. Submit a pull request

Please follow the coding standards outlined in this guide.

---

## License

VantaPress is open-source software. Specific licensing terms to be determined.

**Commercial Use**: Permitted with attribution
**Modification**: Permitted
**Distribution**: Permitted with attribution

---

## Acknowledgments

### Built With

- [Laravel](https://laravel.com) - The PHP Framework
- [FilamentPHP](https://filamentphp.com) - Admin Panel Builder
- [TailwindCSS](https://tailwindcss.com) - Utility-First CSS
- [Spatie Packages](https://spatie.be) - Laravel Permissions & Activity Log

### Inspiration

VantaPress draws inspiration from:
- **WordPress** - Modular architecture and ease of use
- **Craft CMS** - Developer experience
- **October CMS** - Laravel-based CMS concepts
- **Statamic** - Modern content management approach

---

## Conclusion

VantaPress represents a new approach to content management: combining Laravel's power with WordPress's simplicity, specifically designed for the reality of shared hosting deployments.

By following this guide, you can:
- Build modules that work without CLI tools in production
- Create beautiful themes with Blade templating
- Deploy confidently to any shared hosting provider
- Scale from simple blogs to enterprise applications

**Remember the core philosophy**: Keep it simple, make it work on shared hosting, and build for scalability.

Happy coding! 🚀

---

**VantaPress Development Guide v1.0**  
*Last Updated: December 4, 2025*  
*Author: Sepiroth X Villainous*
