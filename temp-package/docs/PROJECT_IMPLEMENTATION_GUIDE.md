# TCC School CMS - Laravel Project Structure & Implementation Guide

## 📋 Project Overview

This is a comprehensive Laravel 11-based CMS and School Information System inspired by WordPress architecture, featuring:
- Modular plugin system (using Nwidart/laravel-modules)
- Dynamic theme system with Blade overrides
- FilamentPHP admin dashboard
- Complete school management features (Students, Teachers, Grades, Enrollment, etc.)
- WordPress-like hooks and filters
- Shared hosting compatible

---

## 🏗️ Architecture Overview

### Core Components

1. **Module System** (`app/Services/CMS/ModuleManager.php`)
   - Module discovery and loading
   - Enable/disable functionality
   - ZIP installation/uninstallation
   - Dependency checking
   - Auto-registration of routes, views, migrations

2. **Theme System** (`app/Services/CMS/ThemeManager.php` - TO BUILD)
   - Theme discovery from `/themes` directory
   - Blade template override hierarchy
   - Asset management per theme
   - Theme configuration (colors, logos, layouts)
   - Active theme switching

3. **Hook System** (`app/Services/CMS/HookManager.php` - TO BUILD)
   - WordPress-style `do_action()` and `add_action()`
   - WordPress-style `apply_filters()` and `add_filter()`
   - Priority-based execution
   - Module extensibility

4. **Menu System** (`app/Services/CMS/MenuManager.php` - TO BUILD)
   - Multiple menu locations
   - Hierarchical menu structure
   - Menu item visibility rules
   - Database-driven menus

5. **Settings System** (`app/Services/CMS/SettingsManager.php` - TO BUILD)
   - Key-value settings storage
   - Setting groups (general, school, appearance, etc.)
   - Cached settings for performance
   - FilamentPHP settings UI

---

## 📁 Directory Structure

```
tcc-school-system/
├── app/
│   ├── Filament/          # FilamentPHP admin resources
│   │   ├── Resources/     # Module/Theme management
│   │   ├── Pages/         # Custom admin pages
│   │   └── Widgets/       # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/   # Frontend controllers
│   │   └── Middleware/    # Custom middleware
│   ├── Models/            # Core models
│   │   ├── Module.php
│   │   ├── Theme.php
│   │   ├── Menu.php
│   │   ├── MenuItem.php
│   │   └── Setting.php
│   ├── Services/
│   │   └── CMS/           # CMS core services
│   │       ├── ModuleManager.php ✅ CREATED
│   │       ├── ThemeManager.php
│   │       ├── HookManager.php
│   │       ├── MenuManager.php
│   │       └── SettingsManager.php
│   ├── Helpers/
│   │   └── helpers.php    ✅ CREATED
│   └── Providers/
│       └── CMSServiceProvider.php ✅ CREATED
│
├── Modules/               # Modular plugins
│   ├── Students/
│   ├── Teachers/
│   ├── Departments/
│   ├── Subjects/
│   ├── Enrollment/
│   ├── Grades/
│   ├── Schedules/
│   └── Reports/
│
├── themes/                # Theme system
│   └── default/
│       ├── layouts/
│       ├── views/
│       ├── assets/
│       └── theme.json
│
├── database/
│   └── migrations/        # Core CMS migrations
│
├── config/
│   ├── modules.php        ✅ CREATED
│   └── cms.php            ✅ CREATED
│
├── composer.json          ✅ CREATED
├── .env.example           ✅ CREATED
└── modules_statuses.json
```

---

## 🔨 Implementation Steps

### ✅ COMPLETED (Files Created)

1. **Project Configuration**
   - `composer.json` - Dependencies and autoloading
   - `.env.example` - Environment variables
   - `config/modules.php` - Module system configuration
   - `config/cms.php` - CMS core configuration

2. **Core Services**
   - `app/Providers/CMSServiceProvider.php` - CMS service registration
   - `app/Services/CMS/ModuleManager.php` - Module management logic
   - `app/Helpers/helpers.php` - Helper functions (WordPress-style)

---

### 🔄 NEXT STEPS (To Build)

#### Phase 1: Core CMS Services (Priority: HIGH)

**1. Theme Manager** (`app/Services/CMS/ThemeManager.php`)
```php
- loadTheme()
- getActiveTheme()
- setActiveTheme($themeName)
- discoverThemes()
- getThemeConfig($theme)
- getThemePath($theme)
- registerThemeViews()
- registerThemeAssets()
```

**2. Hook Manager** (`app/Services/CMS/HookManager.php`)
```php
- addAction($hook, $callback, $priority)
- doAction($hook, ...$args)
- addFilter($filter, $callback, $priority)
- applyFilters($filter, $value, ...$args)
- removeAction($hook, $callback)
- removeFilter($filter, $callback)
```

**3. Menu Manager** (`app/Services/CMS/MenuManager.php`)
```php
- createMenu($location, $name)
- addMenuItem($menuId, $data)
- getMenu($location)
- renderMenu($location, $options)
- deleteMenu($menuId)
```

**4. Settings Manager** (`app/Services/CMS/SettingsManager.php`)
```php
- get($key, $default)
- set($key, $value)
- all($group)
- delete($key)
- flush()
```

---

#### Phase 2: Database & Models (Priority: HIGH)

**Create Migrations:**

1. `create_modules_table.php`
```php
- id, name, slug, description, version, author, enabled, installed_at
```

2. `create_themes_table.php`
```php
- id, name, slug, description, version, author, is_active
```

3. `create_menus_table.php`
```php
- id, name, location, is_active
```

4. `create_menu_items_table.php`
```php
- id, menu_id, parent_id, title, url, target, order, icon
```

5. `create_settings_table.php`
```php
- id, group, key, value, type, autoload
```

**Create Models:**

- `app/Models/Module.php`
- `app/Models/Theme.php`
- `app/Models/Menu.php`
- `app/Models/MenuItem.php`
- `app/Models/Setting.php`

---

#### Phase 3: FilamentPHP Admin (Priority: HIGH)

**1. Install Filament Panel**
```bash
php artisan filament:install --panels
```

**2. Create Filament Resources:**

- `ModuleResource.php` - Module management UI
  - List modules with enable/disable actions
  - Upload ZIP installation
  - View module details
  - Check dependencies

- `ThemeResource.php` - Theme management UI
  - List themes
  - Activate/preview themes
  - Upload theme ZIP
  - Theme customizer

- `MenuResource.php` - Menu builder UI
  - Drag-and-drop menu builder
  - Menu item management
  - Menu locations

- `SettingsPage.php` - Settings management
  - Tabbed settings interface
  - General, School, Appearance, Enrollment, Grading tabs

**3. Create Dashboard Widgets:**
- Active modules count
- Student count
- Teacher count
- Recent enrollments

---

#### Phase 4: School System Modules (Priority: HIGH)

Create 8 independent modules using `php artisan module:make ModuleName`:

**1. Students Module**
- Student registration
- Student profiles
- Student dashboard (view grades, schedule, enrollment)
- Student search and filtering

**2. Teachers Module**
- Teacher profiles
- Teacher credentials
- Hourly rates management
- Teacher dashboard (grade input, class management)

**3. Departments Module**
- Department management
- Department-course assignment
- Department head assignment

**4. Subjects Module**
- Subject catalog
- Subject-course mapping
- Subject prerequisites
- Units and credit hours

**5. Enrollment Module**
- Enrollment periods
- Course enrollment
- Section assignment
- Enrollment status tracking

**6. Grades Module**
- Grade input (Prelim, Midterm, Semifinal, Finals)
- Final grade calculation
- Grade reports
- Grade history and audit logs

**7. Schedules Module**
- Class schedule creation
- Room assignment
- Time slot management
- Schedule conflict detection

**8. Reports Module**
- Student grade reports
- Teacher load reports
- Enrollment statistics
- Department reports

Each module should have:
- `module.json` - Module metadata
- `Routes/web.php` - Web routes
- `Routes/api.php` - API routes
- `Http/Controllers/` - Controllers
- `Models/` - Eloquent models
- `Database/Migrations/` - Migrations
- `Resources/views/` - Blade views
- `Providers/ModuleServiceProvider.php` - Module bootstrap

---

#### Phase 5: Default Theme (Priority: MEDIUM)

**Create `themes/default/` structure:**

```
themes/default/
├── layouts/
│   ├── app.blade.php          # Main layout
│   ├── auth.blade.php         # Authentication layout
│   └── dashboard.blade.php    # Dashboard layout
├── views/
│   ├── home.blade.php
│   ├── about.blade.php
│   ├── contact.blade.php
│   └── partials/
│       ├── header.blade.php
│       ├── footer.blade.php
│       ├── navigation.blade.php
│       └── breadcrumbs.blade.php
├── assets/
│   ├── css/
│   │   └── style.css          # Tailwind compiled CSS
│   ├── js/
│   │   └── main.js
│   └── images/
│       └── logo.png
└── theme.json                  # Theme metadata
```

**theme.json structure:**
```json
{
  "name": "TCC Default Theme",
  "slug": "default",
  "version": "1.0.0",
  "author": "TCC Development Team",
  "description": "Default theme for TCC School CMS",
  "screenshot": "screenshot.png",
  "colors": {
    "primary": "#5D4037",
    "secondary": "#8B4513",
    "accent": "#D4A574"
  },
  "menus": ["primary", "footer", "sidebar"],
  "features": ["logo", "breadcrumbs", "widgets"]
}
```

---

#### Phase 6: Routes & Controllers (Priority: MEDIUM)

**1. Web Routes (`routes/web.php`)**
```php
// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication
Auth::routes();

// Theme-based routes
Route::middleware(['theme'])->group(function () {
    Route::get('/about', [PageController::class, 'about']);
    Route::get('/contact', [PageController::class, 'contact']);
    Route::get('/departments', [PageController::class, 'departments']);
});

// Student Dashboard
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard']);
    Route::get('/grades', [StudentController::class, 'grades']);
    Route::get('/schedule', [StudentController::class, 'schedule']);
});

// Teacher Dashboard
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard']);
    Route::get('/classes', [TeacherController::class, 'classes']);
    Route::post('/grades/save', [TeacherController::class, 'saveGrade']);
});
```

**2. API Routes (`routes/api.php`)**
```php
Route::prefix('v1')->group(function () {
    Route::apiResource('students', StudentApiController::class);
    Route::apiResource('teachers', TeacherApiController::class);
    Route::apiResource('subjects', SubjectApiController::class);
    Route::apiResource('enrollments', EnrollmentApiController::class);
});
```

---

#### Phase 7: Middleware (Priority: MEDIUM)

**1. ThemeMiddleware** (`app/Http/Middleware/ThemeMiddleware.php`)
- Load active theme views
- Register theme assets
- Set theme view namespace

**2. ModuleMiddleware** (`app/Http/Middleware/ModuleMiddleware.php`)
- Check if required module is enabled
- Redirect if module disabled

**3. RoleMiddleware** (using Spatie Permission)
- Check user roles (student, teacher, admin)
- Authorization checks

---

#### Phase 8: Seeders (Priority: LOW)

Create database seeders:

1. **RolesAndPermissionsSeeder**
   - Admin, Teacher, Student roles
   - Permissions for each role

2. **DefaultSettingsSeeder**
   - School name, code, academic year
   - Grading scale settings
   - Default configurations

3. **DemoDataSeeder**
   - Sample students
   - Sample teachers
   - Sample courses
   - Sample subjects

---

## 🚀 Quick Start Commands

### Installation

```bash
# 1. Install dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Create database
# Update .env with your database credentials

# 5. Run migrations
php artisan migrate

# 6. Seed database
php artisan db:seed

# 7. Install Filament
php artisan filament:install --panels

# 8. Create admin user
php artisan make:filament-user

# 9. Install modules
php artisan module:install Students
php artisan module:install Teachers
php artisan module:install Grades
# ... etc

# 10. Enable modules
php artisan module:enable Students
php artisan module:enable Teachers
php artisan module:enable Grades

# 11. Compile assets (if using Vite)
npm install
npm run dev

# 12. Start server
php artisan serve
```

### Module Creation

```bash
# Create a new module
php artisan module:make ModuleName

# Create module components
php artisan module:make-model Student ModuleName
php artisan module:make-controller StudentController ModuleName
php artisan module:make-migration create_students_table ModuleName
php artisan module:make-seeder StudentSeeder ModuleName
```

---

## 📦 Deployment (Shared Hosting)

### Requirements
- PHP 8.2+
- MySQL 5.7+ / MariaDB 10.3+
- Apache with mod_rewrite enabled
- Composer installed

### Steps

1. **Upload files via FTP/SFTP**
   - Upload all files to `public_html/` or subdirectory

2. **Set document root**
   - Point domain to `public/` folder
   - Or create `.htaccess` redirect

3. **Set permissions**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
```

4. **Configure database**
   - Create MySQL database via cPanel
   - Update `.env` file

5. **Run migrations**
```bash
php artisan migrate --force
```

6. **Optimize for production**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

7. **Set up cron job** (for scheduled tasks)
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔐 Security Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate strong `APP_KEY`
- [ ] Use HTTPS (SSL certificate)
- [ ] Restrict file permissions (755 for directories, 644 for files)
- [ ] Configure CSRF protection
- [ ] Implement rate limiting
- [ ] Enable SQL injection protection
- [ ] Sanitize user inputs
- [ ] Implement role-based access control

---

## 📚 Key Features Comparison

### WordPress → Laravel Equivalent

| WordPress | Laravel CMS |
|-----------|-------------|
| Plugins | Modules (Nwidart) |
| Themes | Theme System |
| Hooks (do_action) | Hook Manager |
| Filters (apply_filters) | Filter Manager |
| get_option() | Settings Manager |
| wp_nav_menu() | Menu Manager |
| Shortcodes | Blade Components |
| Admin Dashboard | FilamentPHP |

---

## 🎯 Module Feature Matrix

Each module should implement:

| Feature | Students | Teachers | Departments | Subjects | Enrollment | Grades | Schedules | Reports |
|---------|----------|----------|-------------|----------|------------|--------|-----------|---------|
| CRUD Operations | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Search/Filter | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Export (PDF/Excel) | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Import (CSV/Excel) | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ | ❌ | ❌ |
| Audit Logs | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Permissions | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| API Endpoints | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Frontend Views | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Dashboard Widgets | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 🛠️ Recommended Packages

```json
"require": {
    "barryvdh/laravel-dompdf": "^2.0",
    "maatwebsite/excel": "^3.1",
    "rap2hpoutre/fast-excel": "^5.0",
    "spatie/laravel-backup": "^8.0",
    "spatie/laravel-medialibrary": "^11.0",
    "league/flysystem-aws-s3-v3": "^3.0"
}
```

---

## ✅ Testing Strategy

1. **Unit Tests** - Test individual components
2. **Feature Tests** - Test complete features
3. **Browser Tests** (Laravel Dusk) - Test UI interactions
4. **API Tests** - Test RESTful endpoints

```bash
php artisan test
php artisan dusk
```

---

## 📝 Documentation To Create

1. **Installation Guide** (`docs/installation.md`)
2. **Module Development Guide** (`docs/module-development.md`)
3. **Theme Development Guide** (`docs/theme-development.md`)
4. **API Documentation** (`docs/api.md`)
5. **User Manual** (`docs/user-manual.md`)
6. **Admin Manual** (`docs/admin-manual.md`)

---

## 🎓 Learning Resources

- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [FilamentPHP Documentation](https://filamentphp.com/docs)
- [Nwidart Laravel Modules](https://nwidart.com/laravel-modules/)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/)

---

## 🤝 Contributing

This is a proprietary project for Talisay City College.

---

## 📄 License

Proprietary - All rights reserved by Talisay City College

---

**Estimated Development Time:**
- Phase 1-3 (Core Services): 2-3 weeks
- Phase 4 (Modules): 4-6 weeks
- Phase 5-6 (Theme & Routes): 1-2 weeks
- Phase 7-8 (Middleware & Seeders): 1 week
- Testing & Deployment: 1-2 weeks

**Total: 9-14 weeks**
