# VantaPress - Deployment Guide

## 📋 Pre-Deployment Checklist

### What You Need:
- ✅ Shared hosting account (iFastNet, HostGator, Bluehost, etc.)
- ✅ PHP 8.2+ 
- ✅ MySQL 5.7+ or MariaDB 10.3+
- ✅ Database credentials from cPanel

---

## 🚀 Deployment Steps

### Step 1: Prepare Your Database

1. Login to your hosting control panel (cPanel/DirectAdmin)
2. Create a new MySQL database
3. Create a database user with **ALL PRIVILEGES** (important for migrations)
4. Note down:
   - Database name
   - Database username
   - Database password
   - Database host (usually `localhost` or specific hostname like `sql###.infinityfree.com`)

**Important:** Ensure the database user has full permissions including CREATE, ALTER, DROP, INDEX.

### Step 2: Upload Files

1. **Download the VantaPress ZIP**
2. **Extract locally** (don't upload the ZIP directly)
3. **Upload ALL files** to your hosting account's document root:
   - Use FTP (FileZilla) or hosting File Manager
   - Upload to: `/public_html/` or your domain's root directory
   - This includes: vendor/, app/, database/, public/, etc.

**Important**: Upload the ENTIRE project to the root, not just the public folder!

### Step 3: Configure Environment

1. Find the `.env` file in the uploaded files
2. Edit these lines with your database credentials:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

3. Update your site URL (or leave empty for auto-detection):

```env
APP_URL=
ASSET_URL=
```

4. Ensure production settings:

```env
APP_ENV=production
APP_DEBUG=false
```

### Step 4: Set Permissions

Using File Manager or FTP, set these permissions:

```
storage/                    → 755 (recursive)
storage/framework/          → 755 (recursive)
storage/logs/               → 755 (recursive)
bootstrap/cache/            → 755 (recursive)
```

### Step 5: Run the Installer

1. Visit: `https://yourdomain.com/install.php`
2. Follow the 6-step wizard:
   - ✅ **Step 1**: System requirements check
   - ✅ **Step 2**: Database connection verification
   - ✅ **Step 3**: Database migrations (creates all tables using raw SQL)
   - ✅ **Step 4**: Publish Filament assets (CSS/JS files)
   - ✅ **Step 5**: Create admin account
   - ✅ **Step 6**: Installation complete

3. **IMPORTANT**: After installation completes, delete `install.php` for security

**Note on Migrations:** VantaPress uses **raw SQL migrations** instead of Laravel's Artisan system for maximum shared hosting compatibility. This bypasses restrictions on `information_schema` queries common in shared hosting environments.

### Step 6: Access Admin Panel

1. Visit: `https://yourdomain.com/admin`
2. Login with the credentials you created in Step 5
3. Start managing your content!

---

## 📁 File Structure After Deployment

```
yourdomain.com/
├── .env                    ← Database config (never commit to git!)
├── .htaccess              ← URL rewriting rules
├── index.php              ← Laravel entry point
├── install.php            ← Installation wizard (delete after use)
├── artisan                ← Laravel CLI (not accessible via web)
├── composer.json
├── app/                   ← Application code
├── bootstrap/             ← Laravel bootstrap
├── config/                ← Configuration files
├── database/
│   └── migrations/        ← Database schema
├── public/                ← Public assets
├── resources/             ← Views and frontend
├── routes/                ← Application routes
├── storage/               ← Logs, cache, uploads
└── vendor/                ← Dependencies (never edit)
```

---

## 🔧 Post-Installation Setup

### Optional Utilities (Keep These Files)

These helper scripts are included for maintenance:

1. **`clear-cache.php`** - Clear Laravel caches
   - Visit: `https://yourdomain.com/clear-cache.php`
   - Use when: Settings changes not appearing

2. **`run-migrations.php`** - Run new migrations
   - Visit: `https://yourdomain.com/run-migrations.php`
   - Use when: Adding new database tables

3. **`create-admin.php`** - Create/reset admin user
   - Visit: `https://yourdomain.com/create-admin.php`
   - Use when: Forgot password or need new admin

---

## 🎨 Admin Panel Features

Once logged in at `/admin`, you can manage:

- 📚 **Academic Years** - School year periods
- 🏢 **Departments** - Academic departments
- 📖 **Courses** - Course offerings
- 👨‍🎓 **Students** - Student records
- 👨‍🏫 **Teachers** - Faculty management
- 🏫 **Rooms** - Classroom management
- 📅 **Class Schedules** - Class timetables
- 📝 **Enrollments** - Student enrollments
- 📊 **Grades** - Grade management

---

## 🐛 Troubleshooting

### Homepage shows 404 or 500 error
- **Check**: `.htaccess` file was uploaded
- **Check**: `mod_rewrite` is enabled (contact hosting support)
- **Fix**: Re-upload `.htaccess`

### Admin panel has no styling
- **Check**: `css/` and `js/` folders exist in root
- **Check**: Files inside `css/filament/` and `js/filament/`
- **Fix**: Re-upload the entire project

### Database connection error
- **Check**: `.env` database credentials are correct
- **Check**: Database user has full privileges
- **Fix**: Update `.env` and visit `/clear-cache.php`

### Can't login / credentials not working
- **Visit**: `https://yourdomain.com/create-admin.php`
- Create a new admin account
- Login with new credentials

### 403 Forbidden on admin panel
- **Check**: Storage folder permissions (755)
- **Visit**: `/clear-cache.php`
- **Check**: `.env` has correct `APP_URL`

---

## 🔒 Security Best Practices

After installation:

1. ✅ **Delete `install.php`** - Prevents reinstallation
2. ✅ **Change default admin password** - Use strong password
3. ✅ **Set `APP_DEBUG=false`** - Hide error details
4. ✅ **Verify `.env` permissions** - Should not be web-accessible
5. ✅ **Keep `vendor/` folder** - Required for Laravel to run
6. ✅ **Regular backups** - Database and uploaded files

---

## 📞 Support

If you encounter issues:

1. Check the Troubleshooting section above
2. Use the utility scripts (`clear-cache.php`, `create-admin.php`)
3. Check browser console (F12) for JavaScript errors
4. Check server error logs in hosting control panel

---

## 🎓 About TCC School CMS

**Version**: 1.0.0  
**Built with**: Laravel 11 + FilamentPHP 3  
**License**: MIT  
**School**: Talisay City College

Admin Panel: `/admin`  
Color Theme: Yellow (#eeee22) + Brown (#8B4513)

---

**That's it! Your school management system is now ready to use! 🎉**
