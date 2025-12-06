# 🚀 VantaPress - Release Notes

**Current Version:** v1.0.22-complete  
**Release Date:** December 6, 2025  
**Download:** [Latest Release](https://github.com/sepiroth-x/vantapress/releases/latest)

---

## 📌 Latest Version: v1.0.22-complete

### 🎯 What's New in v1.0.22-complete
- **🎨 Dynamic Theme Customization System** - VantaPress-driven theme customization (reads from theme.json)
- **🛡️ Enhanced Danger Zone UX** - Hide Danger Zone when Debug Mode is OFF for better security UX
- **🔧 Fixed Debug Mode Logic** - Corrected inverted button states (buttons now properly disabled when debug OFF)
- **📱 Dynamic Footer Version** - Footer now reads version from config/version.php dynamically
- **👤 Updated Attribution** - Added "a.k.a Xenroth Vantablack" to footer, centered layout
- **🚫 Improved .gitignore** - Excluded sync-*.php files from repository

### 🎨 Theme System Improvements
- **VantaPress-Driven Customization** - Themes define capabilities in theme.json, VantaPress generates admin UI
- **Dynamic Form Generation** - CustomizeTheme page now reads customization object from theme.json
- **Conditional Tabs** - Only show tabs that the theme supports (Colors, Hero Section, Typography, Layout, Custom CSS)
- **New Methods in ThemeLoader:**
  - `getCustomizableElements()` - Reads theme customization options
  - `getWidgetAreas()` - Discovers theme widget areas
  - `getMenuLocations()` - Discovers theme menu locations
- **Reduced Theme Complexity** - Theme developers only define JSON, VantaPress handles admin interface

### 🔐 Security & UX Enhancements
- **Danger Zone Visibility** - Entire Danger Zone section now hidden when Debug Mode is OFF
- **Fixed Logic Error** - Corrected inverted button states (buttons were enabled when debug OFF, disabled when ON)
- **Better Developer Experience** - Clear visual indicator when dangerous operations are available
- **Production Safe** - No confusing disabled buttons in production, section simply doesn't appear

### 🐛 Bug Fixes
- Fixed Danger Zone buttons being enabled when Debug Mode was OFF (logic was inverted)
- Fixed footer version showing hardcoded v1.0.17 instead of reading from config
- Fixed footer layout not centering attribution text properly

### 🔧 Technical Improvements
- **Settings.php:** Danger Zone section now uses `->visible(fn () => $this->isDebugMode())` to hide when debug OFF
- **Settings.php:** Removed redundant `->disabled()` checks from all Danger Zone buttons
- **footer.blade.php:** Changed layout from flex-row (left/right) to centered vertical stack
- **footer.blade.php:** Version now reads from `config('version.version')` dynamically
- **config/version.php:** Updated to v1.0.21-complete
- **.gitignore:** Added `sync-*.php` to exclude sync scripts from repository

### 📚 Documentation Updates
- Attribution now includes full name with alias: "Richard Cebel Cupal, LPT a.k.a Xenroth Vantablack"
- Footer layout improved for better mobile and desktop presentation
- Social links now centered below attribution for cleaner layout

---

## 📌 Previous Version: v1.0.20-complete

### 🎯 What's New in v1.0.20-complete
- **🛡️ Enhanced Error Handling** - Comprehensive global error handling system to prevent crashes
- **🐛 Duplicate Slug Protection** - Fixed page creation errors with duplicate slugs
- **🔧 Developer Settings Panel** - New developer tools in Settings with debug mode toggle
- **🗑️ Data Management Tools** - Delete conflicting data, fix duplicates, clear cache
- **📱 Responsive Update Buttons** - Improved button spacing and mobile responsiveness
- **🚀 Development Server Fixed** - Added missing server.php router file

### 🐛 Bug Fixes
- Fixed duplicate slug error when creating pages with existing slugs
- Fixed media upload error handling with better notifications
- Fixed page creation to detect both active and soft-deleted slug conflicts
- Fixed development server failing due to missing server.php file
- Added proper error messages for database constraint violations

### 🔧 Technical Improvements
- **New Middleware:** `HandleFilamentErrors` - Global error catcher for all Filament operations
- **Enhanced CreatePage:** Pre-creation validation with duplicate detection
- **Enhanced CreateMedia:** Comprehensive error handling with try-catch blocks
- **Smart Error Messages:** Production-safe messages, debug mode shows full details
- **Error Logging:** All errors logged with context (user, URL, SQL query)
- **Settings Panel:** New "Developer" tab with 5 powerful tools:
  - Debug Mode toggle (updates .env automatically)
  - Fix Duplicate Slugs
  - Clear All Pages/Media
  - Clear Cache
  - Reset Database
- **Responsive Design:** Update system buttons now stack on mobile, horizontal on desktop
- **Created server.php:** Router file for PHP built-in development server

### 🎨 UI/UX Improvements
- Update system buttons now responsive (flex-col on mobile, flex-row on desktop)
- All buttons use consistent sizing (lg)
- Better gap spacing with Tailwind's gap-3
- Full-width buttons on mobile for better touch targets
- Centered button text across all screen sizes

---

## 📌 Previous Version: v1.0.19-complete

### 🎯 What's New in v1.0.19-complete
- **🖼️ Media Upload Size Fix** - Fixed SQL error: "Field 'size' doesn't have a default value"
- **📊 Improved File Size Detection** - Enhanced file path detection for uploads
- **🔧 Database Schema Update** - Made media size field nullable

### 🐛 Bug Fixes
- Fixed SQL error when uploading media without size field
- Fixed file size calculation to handle multiple path variations
- Added error suppression for getimagesize() to prevent warnings

### 🔧 Technical Improvements
- Made media `size` field nullable in database schema
- Added `size` to Media model fillable fields
- Enhanced CreateMedia to try multiple file path variations
- Created migration for existing databases (make media size nullable)
- Improved error handling in file size detection

---

## 📌 Previous Version: v1.0.18-complete

### 🎯 What's New in v1.0.18-complete
- **✅ Page Creation Enhanced** - Pages now redirect to list after creation
- **📝 Content Field Optional** - Allow blank pages for theme/developer population
- **🔄 Slug Recreation Fixed** - Can now recreate deleted pages with same slug
- **🖼️ Media Upload Fixed** - Title field no longer required, auto-generates from filename
- **↩️ Media Redirect Added** - Returns to media list after upload
- **🎨 Module Flexibility** - Improved .gitignore to support separate module repositories
- **📚 Developer Manual Created** - Comprehensive eBook-style documentation (private)

### 🐛 Bug Fixes
- Fixed page creation staying on same view instead of redirecting to list
- Fixed slug uniqueness error when recreating deleted pages (now ignores soft-deleted records)
- Fixed SQL error: "Field 'title' doesn't have a default value" on media upload
- Fixed media title auto-generation from filename
- Fixed page content required validation (now optional for blank pages)

### 🔧 Technical Improvements
- Added `withoutTrashed()` modifier to page slug uniqueness validation
- Made media `title` field nullable in database
- Enhanced CreateMedia with better title auto-generation
- Added redirect methods to CreatePage and CreateMedia resources
- Updated Media model fillable fields to include 'title' and 'path'
- Created migration to update existing databases (make media title nullable)

---

## 📌 Previous Version: v1.0.17-complete

### 🎯 What's New in v1.0.17-complete
- **🏆 Admin Footer Added** - Proudly display developer attribution in admin panel
- **📱 Social Links Integrated** - Email, GitHub, Facebook, Twitter/X, and mobile contact
- **✨ Version Display Fixed** - Removed double "v" prefix in UpdateSystem page
- **🔗 Theme Routing Fixed** - Replace route('login') with url('/admin') in TheVillainArise theme
- **🗑️ Index.html Removed** - Properly delete pre-installation landing page for clean routing
- **💪 Developer Pride** - Full name and contact information prominently displayed

### 🐛 Bug Fixes
- Fixed RouteNotFoundException when login route not defined
- Fixed double "vv" prefix showing "VantaPress vv1.0.16-complete"
- Fixed homepage loading static index.html instead of theme
- Fixed admin footer displaying correctly across all admin pages

### 🎯 What's New in v1.0.16-complete
- **🔧 Module Namespace Fixes** - Fixed PSR-4 autoloading for all modules
- **📁 Case-Sensitive Folders** - Renamed `models/` → `Models/`, `controllers/` → `Controllers/`
- **✅ Theme Customizer Fixed** - Resolved "Class ThemeSetting not found" error
- **🏠 Homepage Routing Fixed** - index.html properly deleted after installation
- **🎉 Update System Enhanced** - Congratulatory message when running latest version
- **🗄️ Database Cleanup** - Removed 9 legacy school system migrations
- **🚀 Pure CMS Focus** - Converted from TCC School CMS to pure content management system
- **🎨 Theme Loading Improved** - TheVillainArise theme loads correctly on homepage
- **🛠️ Installation Enhanced** - Better debug comments and activation sequence

### 🐛 Bug Fixes
- Fixed VPEssential1 ThemeSetting model not found when clicking theme customize
- Fixed HelloWorld module controller autoloading error on /hello route
- Fixed homepage showing "Not Installed" instead of admin panel button
- Fixed installer not deleting index.html properly
- Fixed all module namespace case-sensitivity issues

### 🎯 What's New in v1.0.15-complete
- **🛡️ Comprehensive Error Handling** - Added try-catch blocks throughout the codebase
- **🔒 Database Safety** - Prevents crashes when tables don't exist yet
- **🎨 Improved Installer UI** - Fixed action buttons always visible at bottom
- **📊 Widget Protection** - StatsOverview widget handles missing tables gracefully
- **🔧 Middleware Safety** - ThemeMiddleware won't crash on missing themes table
- **✨ Module Protection** - VPToDoList module handles missing tables elegantly

### 🎯 What's New in v1.0.14-complete
- **🎨 Villain-Themed Installer** - Complete UI rework with The Villain Arise aesthetic
- **🔥 Dark Theme Design** - Installer now matches villain theme with animated grid background
- **🛠️ Fixed Seeder Issue** - Resolved ModuleThemeSeeder command type mismatch error
- **📝 Developer Standards** - Added VERSION_HANDLING.md and SESSION_DEV_HANDLING.md
- **✨ Enhanced UX** - Orbitron and Space Mono fonts, red accent colors, improved animations

### 🎯 What's New in v1.0.13-complete
- **🚀 WordPress-Style Auto-Updates** - One-click automatic updates with background download
- **💾 Automatic Backup System** - Complete backup before every update
- **🛡️ Protected Files** - .env, storage/, and critical files never touched
- **↩️ Rollback on Failure** - Automatic restore if update fails
- **⚡ Background Installation** - Download, extract, and install automatically
- **🔄 Auto-Refresh** - Page reloads with new version after successful update

### 🎯 What's New in v1.0.12-complete
- **Theme-Based Admin Styling** - Admin CSS now controlled by active theme
- **Retro Arcade Theme** - Flat colors, sharp corners, neon accents
- **Dynamic Theme Loading** - AdminPanelProvider loads theme-specific CSS automatically
- **Comprehensive Documentation** - New THEME_ARCHITECTURE.md guide
- **Root-Level Structure** - Standardized architecture without public/ folder

### Theme Architecture Revolution
Admin panel styling is now part of the theme system! Each theme can customize the admin interface appearance through `themes/[ThemeName]/assets/css/admin.css`. The default BasicTheme includes a complete retro arcade aesthetic with dark/light mode support.

**Download:** [v1.0.14-complete](https://github.com/sepiroth-x/vantapress/releases/tag/v1.0.14-complete)

---

## 📜 Version History

### v1.0.12-complete (December 4, 2025)
- Theme-based admin styling architecture
- Retro arcade theme design (flat colors, sharp corners, pixel patterns)
- Dynamic CSS loading via AdminPanelProvider
- THEME_ARCHITECTURE.md documentation
- Root-level structure standardization (no public/ folder)
- Updated DEVELOPMENT_GUIDE.md and SESSION_MEMORY.md
- README.md version badge and folder structure update

### v1.0.11 (December 4, 2025)
- Fixed Filament admin panel styling
- Prevented public/ folder creation
- Custom development server (serve.php, server.php)
- Admin panel styling fix documentation

### v1.0.10 (December 4, 2025)
- Simple HTML welcome page solution
- Automatic Laravel activation after install
- Zero PHP complexity for pre-installation

### v1.0.9 (December 4, 2025)
- Enhanced APP_KEY detection with explicit validation
- Removed obsolete diagnostic tools
- Cleaner release package

### v1.0.8-complete (December 4, 2025)
- Pre-boot APP_KEY check in public/index.php
- Standalone pre-installation welcome page
- Complete pre-installation UX solution

### v1.0.7-complete (December 4, 2025)
- Pre-installation UX improvement
- Homepage works before database configuration
- Professional welcome page with installation guide

### v1.0.6-complete (December 4, 2025)
- Critical APP_KEY auto-generation fix
- New diagnostic tools: diagnose.php & fix-app-key.php
- Prevents MissingAppKeyException on deployment

### v1.0.5-complete (December 3, 2025)
- Theme screenshot display system
- Navigation menu reordering
- UX improvements in admin panel

### v1.0.0-complete (December 3, 2025)
- Initial public release
- 6-step web installer
- FilamentPHP admin panel
- Complete CMS foundation

---

## 🚀 VantaPress v1.0.0 - Initial Release (Historical)

**Release Date:** December 3, 2025  
**Status:** Superseded by v1.0.7-complete

---

## 📦 What is VantaPress?

**VantaPress** is a modern, open-source Content Management System that combines the familiar simplicity of WordPress with the robust architecture of Laravel. Built for developers who want WordPress-style ease-of-use with enterprise-grade code quality.

**Tagline:** *WordPress Philosophy, Laravel Power*

---

## ✨ Core Features

### 🎯 Installation & Setup
- ✅ **6-Step Web Installer** - Visit `/install.php` and follow the wizard
- ✅ **No Terminal Required** - Complete installation via web browser
- ✅ **Automatic Asset Management** - FilamentPHP assets handled automatically
- ✅ **Shared Hosting Compatible** - Works on iFastNet, HostGator, Bluehost, etc.

### 💎 Admin Panel
- ✅ **FilamentPHP 3.3** - Beautiful, modern admin interface
- ✅ **Ready-to-Use Dashboard** - Access at `/admin` after installation
- ✅ **No Build Tools Needed** - No Node.js, npm, or Vite required
- ✅ **Responsive Design** - Works on desktop, tablet, and mobile

### 🏗️ Technical Foundation
- ✅ **Laravel 11.47** - Latest stable Laravel framework
- ✅ **PHP 8.2+** - Modern PHP with type safety
- ✅ **Eloquent ORM** - 9 models with elegant relationships
- ✅ **21 Database Tables** - Complete schema for content management
- ✅ **12 Migrations** - Automated database setup

### 🔐 Security & Authentication
- ✅ **Laravel Breeze** - Secure authentication system
- ✅ **Password Hashing** - bcrypt with cost factor 12
- ✅ **CSRF Protection** - Built-in Laravel security
- ✅ **Session Management** - Database-backed sessions

---

## 📋 System Requirements

### Minimum Requirements
- **PHP Version:** 8.2.0 or higher
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **Web Server:** Apache with mod_rewrite
- **PHP Extensions:** 
  - PDO
  - Mbstring
  - OpenSSL
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
- **Disk Space:** ~50MB minimum
- **PHP Memory:** 128MB (256MB recommended)

### Hosting Compatibility
✅ **Works on shared hosting:**
- iFastNet (Free/Premium)
- HostGator
- Bluehost
- GoDaddy
- Namecheap
- Any cPanel/Apache hosting

❌ **No SSH/Terminal access required**  
❌ **No Composer CLI needed**  
❌ **No Node.js/npm needed**

---

## 📥 Installation Instructions

### Quick Start (5 Minutes)

1. **Download VantaPress**
   ```
   Download: vantapress-v1.0.12-complete.zip from GitHub releases
   ```

2. **Upload to Server**
   - Extract the zip file
   - Upload all files to your web hosting via FTP/cPanel File Manager
   - Upload to document root (usually `public_html` or `www`)

3. **Create Database**
   - Login to your hosting control panel (cPanel, Plesk, etc.)
   - Create a new MySQL database
   - Create a database user and grant all privileges
   - Note: database name, username, password, host

4. **Run Web Installer**
   - Visit `https://yourdomain.com/install.php` in your browser
   - Follow the 6-step installation wizard:
     - ✅ **Step 1:** System requirements check
     - ✅ **Step 2:** Database configuration
     - ✅ **Step 3:** Run migrations (creates 21 tables)
     - ✅ **Step 4:** Publish assets (copies FilamentPHP files)
     - ✅ **Step 5:** Create admin user
     - ✅ **Step 6:** Installation complete!

5. **Login to Admin Panel**
   - Visit `https://yourdomain.com/admin`
   - Login with credentials created in Step 5
   - Start managing your content!

6. **Security (Important!)**
   - Delete `install.php` from server
   - Delete `scripts/create-admin-quick.php` from server
   - Change admin password if needed

### Detailed Documentation
See `docs/DEPLOYMENT_GUIDE.md` for complete step-by-step instructions with screenshots.

---

## 📂 What's Included

### Project Structure
```
vantapress/
├── app/                      # Application code
│   ├── Filament/            # Admin panel resources
│   ├── Models/              # 9 Eloquent models
│   ├── Providers/           # Service providers (includes AdminPanelProvider)
│   └── Services/            # CMS services (ThemeManager, ModuleLoader)
├── bootstrap/               # Laravel bootstrap
├── config/                  # Configuration files
├── css/                     # Static CSS assets (ROOT LEVEL - shared hosting optimized)
│   └── filament/           # FilamentPHP stylesheets (published assets)
├── database/
│   └── migrations/         # 12 migration files creating 21 tables
├── images/                  # Static images (ROOT LEVEL)
├── js/                      # Static JavaScript (ROOT LEVEL)
│   └── filament/           # FilamentPHP JavaScript (published assets)
├── Modules/                 # Modular plugins (WordPress-style)
├── resources/
│   └── views/              # Blade templates
├── routes/                  # Application routes (web, admin)
├── storage/                 # Logs, cache, sessions (needs 775 permissions)
├── themes/                  # Theme system (controls frontend + admin styling)
│   └── BasicTheme/         # Default theme
│       └── assets/
│           └── css/
│               ├── admin.css   # Admin panel styling ⭐
│               └── theme.css   # Frontend styling
├── vendor/                  # Composer dependencies (include in deployment)
├── .env                     # Environment configuration (PROTECTED by .htaccess)
├── .htaccess               # Apache rewrite rules (CRITICAL for routing & security)
├── artisan                 # Laravel CLI
├── composer.json           # PHP dependencies
├── index.php               # Application entry point (ROOT LEVEL)
├── install.php             # 6-step web installer ⚡
├── create-admin.php        # Backup admin user creator
└── LICENSE                 # MIT License
```

**Note:** VantaPress uses a **root-level architecture** optimized for shared hosting. Unlike traditional Laravel apps, there's no `public/` folder as the document root. All public assets (`css/`, `js/`, `images/`) are at root level, and sensitive files are protected via `.htaccess` rules.

### Database Schema (21 Tables)

**Core Laravel Tables:**
- `users` - User authentication
- `password_reset_tokens` - Password resets
- `sessions` - Session management
- `cache`, `cache_locks` - Application caching
- `jobs`, `job_batches`, `failed_jobs` - Queue system

**Content Management Tables:**
- `academic_years` - Period management
- `departments` - Organizational units
- `courses` - Content catalog
- `students` - User profiles
- `teachers` - Staff profiles
- `rooms` - Resource management
- `class_schedules` - Event scheduling
- `enrollments` - User-content associations
- `grades` - Performance tracking
- `media` - File management

*Note: Schema reflects school management origin. Tables can be renamed for your use case.*

### Eloquent Models (9 Models)
1. `User.php` - Authentication & profiles
2. `AcademicYear.php` - Period management
3. `Department.php` - Organizational structure
4. `Course.php` - Content items
5. `Student.php` - End-user profiles
6. `Teacher.php` - Staff/author profiles
7. `Room.php` - Resource management
8. `ClassSchedule.php` - Events/scheduling
9. `Enrollment.php` - User-content relationships

---

## 🔧 Maintenance Tools

VantaPress includes WordPress-inspired utility scripts at root level:

### `install.php` ⚡
6-step web-based installation wizard. Handles everything from requirements check to admin user creation.

**⚠️ Delete after installation for security!**

### `create-admin.php`
Emergency admin user creator. Use if locked out or installer fails.

**⚠️ Delete after creating admin account!**

---

## 🐛 Troubleshooting

### Common Issues

**❌ 404 Errors on `/admin`**
- Verify `.htaccess` file exists in document root
- Check mod_rewrite enabled on Apache
- Review hosting control panel for URL rewriting settings

**🎨 Admin Panel Unstyled (No Colors/Icons)**
- Assets may not have published correctly
- Check `/css/filament/` and `/js/filament/` directories exist
- Verify `.htaccess` allows static file access

**🔌 Database Connection Errors**
- Check `.env` file has correct credentials
- Try `localhost` vs actual hostname
- Some hosts require database prefix (e.g., `username_dbname`)

**🔒 Cannot Login After Installation**
- Use `create-admin.php` to reset admin user
- Clear browser cookies/cache
- Check user exists in database

### Debug Mode (Development Only)
In `.env` file:
```env
APP_DEBUG=true
APP_ENV=local
```

⚠️ **Never enable debug mode in production!**

---

## 📚 Documentation

Included documentation files (in `docs/` folder):

- **DEPLOYMENT_GUIDE.md** - Complete deployment instructions
- **IFASTNET_DEPLOYMENT_GUIDE.md** - iFastNet-specific guide
- **SESSION_MEMORY.md** - Development session notes
- **DEBUG_LOG.md** - Issue tracking and solutions
- **ADMIN_PANEL.md** - Admin panel overview
- **THEME_ACTIVATION_GUIDE.md** - Theme system guide
- Plus 19 more documentation files!

---

## 🔐 Security Checklist

After installation, complete these security steps:

- [ ] Delete `install.php` from root
- [ ] Delete `create-admin.php` from root
- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Verify `storage/` permissions (775 max)
- [ ] Check `.env` permissions (644 recommended)
- [ ] Enable HTTPS if available
- [ ] Set up regular database backups
- [ ] Update `APP_URL` in `.env` to match domain

---

## 🎯 Roadmap

### Version 1.1 (Planned - Q1 2025)
- Complete FilamentPHP Resources (CRUD interfaces)
- Dashboard widgets (stats, charts)
- Calendar view for schedules
- Bulk actions and improved filters
- Export to CSV/PDF

### Version 1.5 (Planned - Q2 2025)
- Plugin system (Laravel packages)
- Theme system (Blade templates)
- Email notifications
- Activity logging
- User role management
- API endpoints

### Version 2.0 (Vision - Q3 2025)
- Theme marketplace
- Plugin marketplace
- Multi-language support
- Advanced permissions
- Revision history
- Media library
- SEO tools

---

## 🤝 Contributing

VantaPress is open source! Contributions welcome.

**Repository:** https://github.com/sepiroth-x/vantapress  
**Issues:** https://github.com/sepiroth-x/vantapress/issues  
**Discussions:** https://github.com/sepiroth-x/vantapress/discussions

### How to Contribute
1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

---

## 👨‍💻 Author & License

**Created by:** Sepirothx (Richard Cebel Cupal, LPT)

**Contact:**
- 📧 Email: chardy.tsadiq02@gmail.com
- 📱 Mobile: +63 915 0388 448

**License:** MIT (Open Source)  
Copyright © 2025 Sepirothx

You are free to use, modify, and distribute VantaPress for any purpose, including commercial projects.

### Attribution
If you find VantaPress useful, consider giving credit:
```
Powered by VantaPress v1.0.12 - Created by Sepirothx
```

---

## 🙏 Acknowledgments

VantaPress stands on the shoulders of giants:

- **[Laravel](https://laravel.com)** - The PHP framework for web artisans
- **[FilamentPHP](https://filamentphp.com)** - Beautiful admin panel framework
- **[WordPress](https://wordpress.org)** - Inspiration for ease-of-use philosophy
- **Open Source Community** - For countless packages and contributions

---

## 📊 Project Statistics

- **Total Files:** 472
- **Lines of Code:** ~62,000 (including vendor)
- **Core Code:** ~15,000 lines
- **Database Tables:** 21
- **Eloquent Models:** 9
- **Migrations:** 12
- **Documentation Files:** 25+
- **PHP Version:** 8.2+
- **Laravel Version:** 11.47
- **FilamentPHP Version:** 3.3

---

## 💬 Support

### Community Support (Free)
- **GitHub Issues** - Report bugs or request features
- **GitHub Discussions** - Ask questions, share ideas
- **Documentation** - Check guides in `/docs` folder

### Professional Support (Paid)
For custom development, consulting, or priority support:

**Contact:** Sepirothx  
**Email:** chardy.tsadiq02@gmail.com  
**Mobile:** +63 915 0388 448

---

## ⭐ Star This Project

If you find VantaPress useful, please give it a star on GitHub!  
https://github.com/sepiroth-x/vantapress

---

## 📞 Getting Help

**Found a bug?** Open an issue on GitHub  
**Need help?** Start a discussion on GitHub  
**Want to contribute?** Submit a pull request  
**Commercial support?** Contact Sepirothx directly

---

**Made with ❤️ in the Philippines**

**Copyright © 2025 Sepirothx. Licensed under MIT.**

**VantaPress v1.0.12-complete** - *WordPress Philosophy, Laravel Power*

---

## 📥 Download Links

- **Latest Release:** https://github.com/sepiroth-x/vantapress/releases/latest
- **Source Code (zip):** https://github.com/sepiroth-x/vantapress/archive/refs/tags/v1.0.12-complete.zip
- **Source Code (tar.gz):** https://github.com/sepiroth-x/vantapress/archive/refs/tags/v1.0.12-complete.tar.gz
- **Repository:** https://github.com/sepiroth-x/vantapress
- **Clone:** `git clone -b v1.0.12-complete https://github.com/sepiroth-x/vantapress.git`

---

**Happy Building! 🚀**
