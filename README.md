# ⚡ VantaPress

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-11.47-FF2D20?logo=laravel)](https://laravel.com)
[![FilamentPHP](https://img.shields.io/badge/FilamentPHP-3.3-FFB800?logo=php)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://www.php.net)
[![Release](https://img.shields.io/badge/Release-v1.0.34--complete-success)](https://github.com/sepiroth-x/vantapress/releases/tag/v1.0.34)

**A WordPress-Inspired Content Management System Built with Laravel**

VantaPress is a modern, open-source CMS that combines the familiar simplicity of WordPress with the robust architecture of Laravel. Built for developers who want WordPress-style ease-of-use with enterprise-grade code quality.

**📦 Current Version:** v1.0.34-complete  
**📥 Download:** [Latest Release](https://github.com/sepiroth-x/vantapress/releases/latest)

---

## 🌟 Why VantaPress?

### The Best of Both Worlds

| Feature | WordPress | VantaPress | Traditional Laravel |
|---------|-----------|-----------|-------------------|
| **Ease of Use** | ✅ Beginner-friendly | ✅ Simple setup | ❌ Complex setup |
| **Modern PHP** | ❌ Legacy code | ✅ Laravel 11 | ✅ Modern code |
| **Admin Panel** | ✅ Built-in | ✅ FilamentPHP | ❌ Build yourself |
| **Database ORM** | ❌ wpdb | ✅ Eloquent | ✅ Eloquent |
| **Asset Management** | ⚠️ Plugins needed | ✅ Built-in | ⚠️ Vite required |
| **Shared Hosting** | ✅ Works anywhere | ✅ Optimized | ❌ Often restricted |
| **Code Quality** | ⚠️ Mixed | ✅ PSR standards | ✅ PSR standards |

### What Makes VantaPress Different?

- 🎯 **WordPress Philosophy, Laravel Power** - Instant setup with web-based installer, no terminal required
- 🚀 **No Build Tools Required** - Deploy via FTP/cPanel, FilamentPHP handles all assets internally
- 💎 **Beautiful Admin Panel** - FilamentPHP provides a stunning dashboard with zero compilation needed
- 🏗️ **Proper Architecture** - MVC pattern, Eloquent ORM, dependency injection, testable code
- 🌐 **Shared Hosting Ready** - Works on cheap shared hosting like iFastNet, HostGator, Bluehost
- 🔓 **Open Source & Free** - MIT licensed, modify and use however you want

---

## 📋 About VantaPress

VantaPress is a **production-ready content management system** that provides complete control over content, users, and structured data. Initially built as a school management system, it's architected as a flexible CMS suitable for any content-driven application.

### Current Features

- 📊 **FilamentPHP Admin Panel** - Modern admin dashboard with full CRUD operations
- 🏛️ **Structured Content Management** - Manage complex relational data with ease
- 👥 **User Management** - Role-based authentication and profile management
- 📅 **Relational Data** - Complex relationships handled elegantly via Eloquent ORM
- 🎨 **Custom Theming** - Easy color scheme customization
- 🔐 **Secure by Default** - Laravel security features, CSRF protection, password hashing
- 📱 **Responsive Design** - Works seamlessly on desktop, tablet, and mobile
- 🚀 **6-Step Web Installer** - Upload files, visit `/install.php`, done!

---

## 🚀 Quick Start

### Installation (WordPress-Style)

VantaPress is designed for **effortless deployment on any shared hosting** without terminal access!

#### 📥 Installation Steps

1. **📦 Download the Latest Version**  
   Get the zipped release from [GitHub Releases](https://github.com/sepiroth-x/vantapress/releases/latest)

2. **☁️ Upload to Server**  
   Use your hosting control panel's **File Manager** to upload the `.zip` file

3. **📂 Extract the Archive**  
   Right-click the uploaded `.zip` and select **Extract** in File Manager

4. **📁 Navigate to Extracted Folder**  
   Open the extracted folder that contains all the VantaPress files

5. **🔄 Move Files to Root Directory**  
   Select **all files** inside the extracted folder and **move** them to your root directory (`yourdomain.com/` or `public_html/`)

6. **⚙️ Rename Environment File**  
   Rename `.env.example` to `.env`

7. **🌐 Visit the Installer**  
   Open your browser and go to `https://yourdomain.com/install.php`

8. **🚀 Run the Installer**  
   Follow the 6-step installation wizard (requirements check → database setup → migrations → assets → admin creation → done!)

9. **🔐 Login to Admin Panel**  
   Access your admin dashboard at `https://yourdomain.com/admin`

10. **🎉 Enjoy VantaPress!**  
    Start building your site with the power of Laravel and FilamentPHP!

**⚠️ Security Tip:** Delete `install.php` after completing installation!

---

### Quick Summary

- ✅ No terminal/SSH required
- ✅ No Composer or npm needed
- ✅ Works on any shared hosting (cPanel, Plesk, DirectAdmin)
- ✅ Automatic database setup
- ✅ One-click asset publishing
- ✅ Built-in admin user creation

### Admin Panel Access

- **URL:** `https://yourdomain.com/admin`
- **Default Path:** `/admin/login`
- **First User:** Created during Step 5 of installation wizard

⚠️ **Security:** Delete `install.php` and `create-admin.php` after installation!

---

## 👨‍💻 Author & License

**Created by:** Sepiroth X  Villainous (Richard Cebel Cupal, LPT)

**Contact:**
- 📧 Email: chardy.tsadiq02@gmail.com
- 📱 Mobile: +63 915 0388 448

**License:** MIT (Open Source)  
Copyright © 2025 Sepirothx

You are free to use, modify, and distribute VantaPress for any purpose, including commercial projects. See [LICENSE](LICENSE) for full terms.

### Attribution

If you find VantaPress useful, consider giving credit:
```
Powered by VantaPress v1.0.0 - Created by Sepiroth X Villainous
```

---

## 🛠️ Technology Stack

- **Framework:** Laravel 11.47.0
- **PHP Version:** 8.2.29+
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Admin Panel:** FilamentPHP 3.3.45
- **Authentication:** Laravel Breeze
- **Frontend:** Blade Templates
- **Assets:** FilamentPHP (publishes CSS/JS via `php artisan filament:assets`, no Node.js/npm/Vite)
- **Migrations:** Raw SQL (bypasses Laravel's Artisan system for shared hosting compatibility)
- **Hosting:** Shared Hosting Compatible (tested on iFastNet)

---

## 📦 Database Schema

VantaPress includes **21 database tables** for comprehensive content management:

### Core Laravel Tables
- `users` - User authentication and profiles
- `password_reset_tokens` - Password reset functionality
- `sessions` - User session management
- `cache` / `cache_locks` - Application caching
- `jobs` / `job_batches` / `failed_jobs` - Queue system

### Content Management Tables (Example Schema)
- `academic_years` - Period management with active status
- `departments` - Organizational units with hierarchy
- `courses` - Content catalog with relationships
- `students` - User profiles with metadata
- `teachers` - Staff profiles with credentials
- `rooms` - Resource management
- `class_schedules` - Event scheduling with conflict detection
- `enrollments` - User-content associations with status tracking
- `grades` - Performance/metrics tracking with calculations

*Note: This schema reflects the school management origin. You can modify tables for your specific use case (e.g., rename `students` → `members`, `courses` → `products`, etc.)*

---

## 📂 Project Structure

```
vantapress/
├── app/
│   ├── Filament/          # FilamentPHP admin resources
│   ├── Models/            # Eloquent models (9 core models)
│   ├── Providers/         # Service providers (includes AdminPanelProvider)
│   └── Services/          # CMS services (ThemeManager, ModuleLoader)
├── bootstrap/             # Laravel bootstrap
├── config/                # Configuration files
├── css/                   # Static CSS assets (ROOT LEVEL - shared hosting optimized)
│   └── filament/          # FilamentPHP stylesheets (published assets)
├── database/
│   └── migrations/        # 12 migration files creating 21 tables
├── images/                # Static images (ROOT LEVEL)
├── js/                    # Static JavaScript (ROOT LEVEL)
│   └── filament/          # FilamentPHP JavaScript (published assets)
├── Modules/               # Modular plugins (WordPress-style)
├── resources/
│   └── views/             # Blade templates
├── routes/                # Application routes (web, admin)
├── storage/               # Logs, cache, sessions (needs 775 permissions)
├── themes/                # Theme system (controls frontend + admin styling)
│   └── BasicTheme/        # Default theme
│       └── assets/
│           └── css/
│               ├── admin.css   # Admin panel styling ⭐
│               └── theme.css   # Frontend styling
├── vendor/                # Composer dependencies (include in deployment)
├── .env                   # Environment configuration (PROTECTED by .htaccess)
├── .htaccess              # Apache rewrite rules (CRITICAL for routing & security)
├── artisan                # Laravel CLI
├── composer.json          # PHP dependencies
├── index.php              # Application entry point (ROOT LEVEL)
├── install.php            # 6-step web installer ⚡
├── create-admin.php       # Backup admin user creator
└── LICENSE                # MIT License
```

**Note:** VantaPress uses a **root-level architecture** optimized for shared hosting. Unlike traditional Laravel apps, there's no `public/` folder as the document root. All public assets (`css/`, `js/`, `images/`) are at root level, and sensitive files are protected via `.htaccess` rules.

---

## 🔧 Maintenance Tools

VantaPress includes WordPress-inspired utility scripts:

### `install.php` - 6-Step Installation Wizard
- ✅ System requirements check (PHP version, extensions, permissions)
- ✅ Interactive database configuration with .env auto-update
- ✅ Automated database migrations using Laravel Artisan
- ✅ Asset publishing (copies FilamentPHP assets to correct locations)
- ✅ Admin user creation with secure password hashing
- ✅ Completion page with security reminders

**⚠️ Delete after installation for security!**

### `create-admin.php` - Emergency Admin Creator
- Creates or updates admin users directly in database
- Secure bcrypt password hashing (cost factor 12)
- Use if installer Step 5 fails or you're locked out
- Direct database insertion bypassing Laravel

**⚠️ Delete after creating admin account!**

### `clear-cache.php` - Cache Management
- Clears Laravel config, route, and view caches
- Run after `.env` changes
- Fixes routing/configuration issues
- Equivalent to `php artisan cache:clear` without terminal

### `run-migrations.php` - Migration Runner
- Manually runs database migrations via web browser
- Shows detailed migration output with table names
- Use if `php artisan migrate` unavailable (no SSH)
- Step-by-step migration execution

### `copy-filament-assets.php` - Asset Copier
- Copies FilamentPHP assets from vendor to public folder
- Required for admin panel styling on shared hosting
- Copies ~2MB of CSS/JS from 7 Filament packages
- Automatically run by installer Step 4

---

## 📚 Documentation

- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Complete deployment instructions for shared hosting
- **[FRESH_DEPLOYMENT_READY.md](FRESH_DEPLOYMENT_READY.md)** - Production deployment summary
- **[LICENSE](LICENSE)** - MIT License terms

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### ❌ 404 Errors on Admin Panel
**Problem:** Can't access `/admin`, getting 404 errors

**Solutions:**
- Verify `.htaccess` file exists in document root
- Check mod_rewrite enabled on Apache server
- Review hosting control panel settings
- See Apache configuration in [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

#### 🎨 Admin Panel Has No Styling (Unstyled)
**Problem:** Admin panel loads but looks like plain HTML, no colors/icons

**Solutions:**
- Run `copy-filament-assets.php` to copy assets from vendor
- Check that assets exist in `/css/filament/` and `/js/filament/` directories
- Verify `.htaccess` allows static assets (lines 10-13)
- Confirm installer Step 4 completed successfully

#### 🔌 Database Connection Errors
**Problem:** "Could not connect to database" or similar errors

**Solutions:**
- Check `.env` file has correct credentials (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Verify database exists in hosting control panel
- Test connection with different MySQL host (try `localhost` vs IP address)
- Some hosts require specific database prefixes (e.g., `username_dbname`)

#### 🔒 Cannot Login After Installation
**Problem:** Login form shows "invalid credentials" even with correct password

**Solutions:**
- Use `create-admin.php` to manually create/reset admin user
- Check user exists in database: `SELECT * FROM users WHERE email='your@email.com'`
- Verify password hash format (should start with `$2y$`)
- Clear browser cookies/cache

#### 🚫 403 Forbidden Errors
**Problem:** Getting "403 Forbidden" when trying to access pages

**Solutions:**
- Check `storage/` directory has 775 permissions
- Verify `storage/framework/` subdirectories exist (cache, sessions, views)
- Run `clear-cache.php` to reset all caches
- Check `.htaccess` file not corrupted

### Debug Mode

To enable detailed error messages (development only):
1. Open `.env` file
2. Change `APP_DEBUG=false` to `APP_DEBUG=true`
3. Save and refresh browser

⚠️ **Never enable debug mode in production!** Error details can expose sensitive information.

---

## 🔐 Security Checklist

After successful installation:

- [ ] Delete `install.php` from server
- [ ] Delete `create-admin.php` from server
- [ ] Change default admin password (if you used a simple one during setup)
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Verify `storage/` permissions (775 max, never 777)
- [ ] Check `.env` file permissions (644 recommended, never 777)
- [ ] Enable HTTPS if available (highly recommended)
- [ ] Set up regular database backups (weekly minimum)
- [ ] Update `APP_URL` in `.env` to match your domain

---

## 🏗️ Architecture

### Why VantaPress Works Like WordPress

**WordPress Approach:**
- Upload files via FTP
- Visit installer URL in browser
- Fill out database credentials in a form
- Click "Install" and wait
- Login to admin panel

**VantaPress Approach:**
- Upload files via FTP
- Visit `/install.php` in browser
- Follow 6-step wizard (requirements → database → migrations → assets → admin → done)
- Automatic asset management, no manual configuration
- Login at `/admin`

### FilamentPHP Admin Panel

VantaPress uses FilamentPHP 3 for the admin interface:
- **Resources:** CRUD interfaces for all models (extensible)
- **Forms:** Dynamic form building with validation
- **Tables:** Sortable, filterable data tables with bulk actions
- **Widgets:** Dashboard statistics and charts (customizable)
- **Actions:** Bulk operations and custom actions
- **Theming:** Custom color scheme integration

### Database Relationships (Eloquent ORM)

Example relationships from current schema:

```php
// One-to-Many
Department::class -> hasMany(Course::class)
Student::class -> hasMany(Enrollment::class)

// Belongs To
Course::class -> belongsTo(Department::class)
Teacher::class -> belongsTo(Department::class)

// Has One Through
Enrollment::class -> hasOne(Grade::class)

// Many-to-Many (via pivot)
Student::class -> belongsToMany(ClassSchedule::class, 'enrollments')
```

### File Structure Logic

**Why assets are in ROOT `/css` and `/js` instead of `/public/css`:**
- Many shared hosting providers (iFastNet, HostGator, Bluehost) use project root as document root
- Apache serves files from root directory, not `public/` subdirectory
- `.htaccess` includes specific rules to allow static assets before Laravel routing
- This mirrors WordPress structure (`/wp-content/` in root, not in subdirectory)
- Installer Step 4 automatically handles asset placement

**Critical .htaccess Rules:**
```apache
# Allow static assets (lines 10-13)
RewriteCond %{REQUEST_URI} ^/(css|js|images|fonts|vendor)/
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^ - [L]
```

---

## 🌐 Deployment

### Shared Hosting Deployment (Tested Hosts)

VantaPress is fully tested and deployed on:
- **iFastNet** (Free/Premium shared hosting)
- Compatible with: HostGator, Bluehost, GoDaddy, Namecheap shared hosting

**Requirements:**
- PHP 8.2+ (8.1 minimum)
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite
- ~50MB disk space
- 128MB PHP memory_limit (256MB recommended)

**Limitations Handled:**
- ✅ No SSH access needed
- ✅ No Composer CLI needed
- ✅ No Node.js/npm needed
- ✅ Works without `public/` as document root
- ✅ FTP upload works perfectly

**See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) for complete step-by-step instructions.**

### Key Deployment Notes

1. **No Build Process:** FilamentPHP loads assets internally, no Vite build needed
2. **Standard Admin Path:** Admin panel at `/admin` (not `/wp-admin`, but similar idea)
3. **Asset Automation:** Installer Step 4 copies and moves assets automatically
4. **Permissions:** `storage/` directory needs 775 permissions (handled by installer)
5. **Remote Database:** Supports remote MySQL hosts (not just localhost)

---

## 🎯 Roadmap

### Version 1.0 (Current)
- [x] Laravel 11 foundation
- [x] FilamentPHP 3 admin panel
- [x] 21-table database schema
- [x] 9 Eloquent models
- [x] Web-based 6-step installer
- [x] Shared hosting deployment
- [x] Authentication system
- [x] MIT open-source license

### Version 1.1 (Planned - Q1 2025)
- [ ] Complete FilamentPHP Resources (9 CRUD interfaces)
- [ ] Dashboard widgets (stats, charts, recent activity)
- [ ] Calendar view for scheduled events
- [ ] Bulk actions (approve, delete, export)
- [ ] Search and filter improvements
- [ ] Export to CSV/PDF

### Version 1.5 (Planned - Q2 2025)
- [ ] Plugin system (Laravel package-based)
- [ ] Theme system (Blade template swapping)
- [ ] Email notifications
- [ ] Activity logging
- [ ] User role management (beyond admin)
- [ ] API endpoints (Laravel Sanctum)

### Version 2.0 (Vision - Q3 2025)
- [ ] Frontend theme marketplace
- [ ] Plugin marketplace
- [ ] Multi-language support (i18n)
- [ ] Advanced permissions system
- [ ] Revision history (like WordPress post revisions)
- [ ] Media library
- [ ] SEO tools integration

---

## 🤝 Contributing

VantaPress is open source! Contributions are welcome.

### How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup

```bash
# Clone repository
git clone https://github.com/yourusername/vantapress.git
cd vantapress

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env, then migrate
php artisan migrate

# Create admin user
php artisan make:filament-user

# Serve locally
php artisan serve
```

### Code Standards

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation for user-facing changes
- Keep commits atomic and well-described

---

## 💬 Support

### Community Support (Free)

- **GitHub Issues:** Report bugs or request features
- **Discussions:** Ask questions, share ideas
- **Documentation:** Check guides in `/docs` folder

### Professional Support (Paid)

For custom development, consulting, or priority support:

**Contact:** Sepirothx  
**Email:** chardy.tsadiq02@gmail.com  
**Mobile:** +63 915 0388 448

---

## 🙏 Acknowledgments

VantaPress stands on the shoulders of giants:

- **[Laravel](https://laravel.com)** - The PHP framework for web artisans
- **[FilamentPHP](https://filamentphp.com)** - Beautiful admin panel framework
- **[WordPress](https://wordpress.org)** - Inspiration for ease-of-use philosophy
- **Open Source Community** - For countless packages and contributions

Special thanks to early testers and contributors!

---

## 📊 Project Statistics

- **Lines of Code:** ~15,000 (excluding vendor)
- **Database Tables:** 21
- **Eloquent Models:** 9
- **Migrations:** 12
- **FilamentPHP Resources:** 9 (in development)
- **Supported PHP Version:** 8.2+
- **Laravel Version:** 11.47
- **License:** MIT (Open Source)

---

## 📝 Changelog

### Version 1.0.0 (December 3, 2025) - Initial Release

**Core Features:**
- ✅ Laravel 11.47 + FilamentPHP 3.3 foundation
- ✅ Complete 21-table database schema
- ✅ 9 Eloquent models with relationships
- ✅ Authentication system (Laravel Breeze)
- ✅ 6-step web installer (`install.php`)
- ✅ FilamentPHP admin panel at `/admin`
- ✅ Maintenance utilities (cache, migrations, admin user)
- ✅ Shared hosting deployment (iFastNet tested)
- ✅ Complete documentation
- ✅ MIT open-source license

**Technical Improvements:**
- ✅ Asset management automation (installer Step 4)
- ✅ Support for root-level document root hosting
- ✅ .htaccess static asset rules
- ✅ No Node.js/Vite requirement
- ✅ Remote MySQL database support

**In Development:**
- 🔄 FilamentPHP Resources (CRUD interfaces)
- 🔄 Dashboard widgets
- 🔄 Reporting system

---

## 📞 Contact

**Sepirothx** (Richard Cebel Cupal, LPT)

- 📧 Email: chardy.tsadiq02@gmail.com
- 📱 Mobile: +63 915 0388 448
- 🌐 Website: (Coming soon)
- 💼 LinkedIn: (Coming soon)

---

## ⭐ Star This Project

If you find VantaPress useful, please give it a star on GitHub! It helps others discover the project.

---

**Made with ❤️ in the Philippines**

**Copyright © 2025 Sepirothx. Licensed under MIT.**

**VantaPress** - *WordPress Philosophy, Laravel Power*
