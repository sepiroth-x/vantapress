# 🚀 VantaPress v1.0.0 - Initial Release

**Release Date:** December 3, 2025  
**Download:** [VantaPress-v1.0.0.zip](https://github.com/sepiroth-x/vantapress/archive/refs/tags/v1.0.0.zip)

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
   Download: vantapress-v1.0.0.zip from GitHub releases
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
│   └── Providers/           # Service providers
├── build-tools/             # Deployment tools
├── config/                  # Configuration files
├── css/                     # Static CSS assets
│   └── filament/           # FilamentPHP stylesheets
├── database/
│   └── migrations/         # 12 migration files
├── docs/                    # 25 documentation files
├── js/                      # Static JavaScript
│   └── filament/           # FilamentPHP scripts
├── public/                  # Public assets
├── resources/               # Views and templates
├── routes/                  # Application routes
├── scripts/                 # Utility scripts
│   └── install.php         # Web installer ⚡
├── storage/                 # Logs, cache, sessions
├── vendor/                  # Composer dependencies
├── .env.example            # Environment template
├── .htaccess               # Apache configuration
├── artisan                 # Laravel CLI
├── composer.json           # PHP dependencies
├── LICENSE                 # MIT License
└── README.md               # Documentation
```

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

VantaPress includes WordPress-inspired utility scripts in the `scripts/` directory:

### `install.php` ⚡
6-step web-based installation wizard. Handles everything from requirements check to admin user creation.

**⚠️ Delete after installation for security!**

### `create-admin-quick.php`
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
- Use `scripts/create-admin-quick.php` to reset admin user
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

- [ ] Delete `scripts/install.php`
- [ ] Delete `scripts/create-admin-quick.php`
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
Powered by VantaPress v1.0.0 - Created by Sepirothx
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

**VantaPress v1.0.0** - *WordPress Philosophy, Laravel Power*

---

## 📥 Download Links

- **Source Code (zip):** https://github.com/sepiroth-x/vantapress/archive/refs/tags/v1.0.0.zip
- **Source Code (tar.gz):** https://github.com/sepiroth-x/vantapress/archive/refs/tags/v1.0.0.tar.gz
- **Repository:** https://github.com/sepiroth-x/vantapress
- **Clone:** `git clone -b v1.0.0 https://github.com/sepiroth-x/vantapress.git`

---

**Happy Building! 🚀**
