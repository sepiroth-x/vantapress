# TCC School CMS - iFastNet Shared Hosting Deployment Guide

**Project:** Talisay City College School Management System  
**Author:** Sepiroth X Villainous (Richard Cebel Cupal, LPT)  
**Target:** iFastNet Free/Premium Shared Hosting  
**Date:** December 2, 2025

---

## 📋 Pre-Deployment Checklist

### iFastNet Account Requirements
- ✅ Active iFastNet hosting account (free or premium)
- ✅ MySQL database created via cPanel
- ✅ FTP/SFTP access credentials
- ✅ Domain or subdomain configured
- ✅ PHP 8.2+ enabled (check in cPanel PHP Selector)

### Local Requirements
- ✅ Project files ready
- ✅ Dependencies installed locally (`composer install` completed)
- ✅ Database tested locally
- ✅ `.env` configured for production

---

## 🚀 Step-by-Step Deployment Process

### Step 1: Prepare Your Local Project

#### 1.1 Install Dependencies (if not already done)
```bash
cd "c:\Users\sepirothx\Documents\3. Laravel Development\tcc-school-system"
composer install --optimize-autoloader --no-dev
```

#### 1.2 Configure Production Environment
Create a production `.env` file:

```bash
cp .env.example .env.production
```

Edit `.env.production`:
```env
APP_NAME="TCC School CMS"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.ifastnet.com

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sql300.ifastnet.com
DB_PORT=3306
DB_DATABASE=ifastnet_tccschool
DB_USERNAME=ifastnet_tccuser
DB_PASSWORD=your_database_password

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=database

FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=smtp.ifastnet.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

CMS_MODULES_PATH=Modules
CMS_THEMES_PATH=themes
CMS_ACTIVE_THEME=default
CMS_ENABLE_MODULE_CACHE=true
CMS_ENABLE_THEME_CACHE=true

SCHOOL_NAME="Talisay City College"
SCHOOL_CODE="TCC"
ACADEMIC_YEAR="2024-2025"

# iFastNet Specific
FILAMENT_PATH=admin
```

#### 1.3 Optimize for Production
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate optimized files
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

#### 1.4 Create ZIP Archive
Exclude unnecessary files:

Create `.zipignore` file (if using WinRAR/7-Zip, select manually):
```
node_modules/
.git/
.env
.env.example
tests/
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
.gitignore
.editorconfig
phpunit.xml
```

**Compress these folders/files:**
- ✅ `app/`
- ✅ `bootstrap/`
- ✅ `config/`
- ✅ `database/`
- ✅ `Modules/`
- ✅ `public/`
- ✅ `resources/`
- ✅ `routes/`
- ✅ `storage/` (empty subdirectories)
- ✅ `themes/`
- ✅ `vendor/`
- ✅ `artisan`
- ✅ `composer.json`
- ✅ `composer.lock`

---

### Step 2: Setup iFastNet Hosting

#### 2.1 Login to iFastNet cPanel
1. Go to your iFastNet control panel
2. URL typically: `https://cpanel.ifastnet.com` or via your hosting email

#### 2.2 Create MySQL Database

**Via cPanel:**
1. Navigate to **MySQL® Databases**
2. **Create New Database:**
   - Database Name: `tccschool` (will become `ifastnet_xxxxx_tccschool`)
   - Click **Create Database**
   - **Note the full database name** (e.g., `ifastnet_12345_tccschool`)

3. **Create Database User:**
   - Username: `tccuser` (will become `ifastnet_xxxxx_tccuser`)
   - Generate strong password or create your own
   - Click **Create User**
   - **Copy and save the username and password**

4. **Add User to Database:**
   - Select the user you created
   - Select the database you created
   - Check **ALL PRIVILEGES**
   - Click **Make Changes**

5. **Note Database Connection Details:**
   ```
   DB_HOST: sql300.ifastnet.com (or as shown in cPanel)
   DB_DATABASE: ifastnet_xxxxx_tccschool
   DB_USERNAME: ifastnet_xxxxx_tccuser
   DB_PASSWORD: your_password
   ```

#### 2.3 Check PHP Version

1. Go to **Select PHP Version** in cPanel
2. Ensure **PHP 8.2** or higher is selected
3. Enable required extensions:
   - ✅ bcmath
   - ✅ ctype
   - ✅ fileinfo
   - ✅ json
   - ✅ mbstring
   - ✅ openssl
   - ✅ pdo_mysql
   - ✅ tokenizer
   - ✅ xml
   - ✅ zip
   - ✅ gd
   - ✅ curl

---

### Step 3: Upload Files to iFastNet

#### 3.1 Connect via FTP/File Manager

**Option A: FTP Client (Recommended)**
1. Download FileZilla: https://filezilla-project.org/
2. Connection details:
   - Host: `ftp.yourdomain.ifastnet.com` or `ftpupload.net`
   - Username: Your iFastNet username
   - Password: Your iFastNet password
   - Port: 21 (FTP) or 22 (SFTP if available)

**Option B: cPanel File Manager**
1. Navigate to **File Manager** in cPanel
2. Go to `public_html/` or your domain directory

#### 3.2 Upload Project Files

**Important Directory Structure for iFastNet:**

```
public_html/                    (Your domain root)
├── laravel/                    (Upload all Laravel files here)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── Modules/
│   ├── public/                (Laravel public folder)
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── themes/
│   ├── vendor/
│   ├── artisan
│   ├── composer.json
│   └── .env                   (Upload your .env.production as .env)
├── .htaccess                  (Redirect file - create this)
└── index.php                  (Redirect file - create this)
```

**Upload Steps:**

1. **Create `laravel` folder** in `public_html/`
2. **Upload your ZIP file** to `public_html/laravel/`
3. **Extract the ZIP** using cPanel File Manager:
   - Right-click ZIP file → **Extract**
   - Delete ZIP after extraction
4. **Upload `.env.production`** to `public_html/laravel/.env`

---

### Step 4: Configure Laravel for iFastNet

#### 4.1 Create Root Redirect Files

iFastNet requires your domain to point to `public_html/`, but Laravel's entry point is in `laravel/public/`.

**Create `public_html/index.php`:**
```php
<?php
/**
 * TCC School CMS - Public Entry Point
 * Redirects to Laravel public directory
 * 
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 */

// Redirect to laravel/public directory
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__.'/laravel/public'.$uri)) {
    return false;
}

// Load Laravel application
require_once __DIR__.'/laravel/public/index.php';
```

**Create `public_html/.htaccess`:**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect to Laravel public directory
    RewriteCond %{REQUEST_URI} !^/laravel/public/
    RewriteRule ^(.*)$ /laravel/public/$1 [L,QSA]
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>

# Disable directory browsing
Options -Indexes

# Prevent access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Protect .env file
<FilesMatch "\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### 4.2 Set File Permissions

Using cPanel File Manager or FTP:

```bash
# Directories: 755
laravel/
laravel/storage/               755
laravel/storage/framework/     755
laravel/storage/logs/          755
laravel/bootstrap/cache/       755

# Files: 644
laravel/.env                   644
laravel/artisan                755 (executable)
```

**Via File Manager:**
1. Right-click folder → **Change Permissions**
2. Set as shown above

---

### Step 5: Initialize Database

#### 5.1 Access Terminal (if available)

**If SSH is available:**
```bash
cd public_html/laravel
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

**If NO SSH (Common on free hosting):**

Use the Web-Based Migration Script:

**Create `public_html/install.php`:**
```php
<?php
/**
 * TCC School CMS - Web Installer
 * One-time installation script for shared hosting
 * 
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * 
 * IMPORTANT: Delete this file after installation!
 */

// Check if already installed
if (file_exists(__DIR__.'/laravel/storage/installed.lock')) {
    die('Application already installed. Delete storage/installed.lock to reinstall.');
}

// Load Laravel
require __DIR__.'/laravel/vendor/autoload.php';
$app = require_once __DIR__.'/laravel/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre>";
echo "==============================================\n";
echo "TCC School CMS - Installation Script\n";
echo "==============================================\n\n";

// Run migrations
echo "Running database migrations...\n";
$kernel->call('migrate', ['--force' => true]);
echo "\n✓ Migrations completed\n\n";

// Run seeders
echo "Seeding database...\n";
$kernel->call('db:seed', ['--force' => true]);
echo "\n✓ Database seeded\n\n";

// Create storage link
echo "Creating storage symlink...\n";
$kernel->call('storage:link');
echo "\n✓ Storage linked\n\n";

// Optimize application
echo "Optimizing application...\n";
$kernel->call('optimize');
echo "\n✓ Application optimized\n\n";

// Create lock file
file_put_contents(__DIR__.'/laravel/storage/installed.lock', date('Y-m-d H:i:s'));

echo "==============================================\n";
echo "Installation Complete!\n";
echo "==============================================\n\n";
echo "IMPORTANT: DELETE THIS FILE (install.php) NOW!\n\n";
echo "Visit your site: " . $_SERVER['HTTP_HOST'] . "\n";
echo "Admin panel: " . $_SERVER['HTTP_HOST'] . "/admin\n";
echo "</pre>";
```

**Access the installer:**
1. Visit: `https://yourdomain.ifastnet.com/install.php`
2. Wait for installation to complete
3. **Delete `install.php` immediately** after success

---

### Step 6: Create Admin User

#### 6.1 Via SSH (if available)
```bash
cd public_html/laravel
php artisan make:filament-user
```

#### 6.2 Via Web Script (No SSH)

**Create `public_html/create-admin.php`:**
```php
<?php
/**
 * TCC School CMS - Admin User Creator
 * 
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * 
 * IMPORTANT: Delete this file after creating admin!
 */

require __DIR__.'/laravel/vendor/autoload.php';
$app = require_once __DIR__.'/laravel/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class);

// Manual admin creation (adjust these values)
$name = 'Admin User';
$email = 'admin@tcc.edu.ph';
$password = 'ChangeThisPassword123!'; // CHANGE THIS!

try {
    $user = \App\Models\User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt($password),
        'email_verified_at' => now(),
    ]);
    
    // Assign admin role
    $user->assignRole('admin');
    
    echo "<pre>";
    echo "Admin user created successfully!\n\n";
    echo "Email: {$email}\n";
    echo "Password: {$password}\n\n";
    echo "Login at: https://{$_SERVER['HTTP_HOST']}/admin\n\n";
    echo "IMPORTANT: \n";
    echo "1. Change your password immediately after login\n";
    echo "2. Delete this file (create-admin.php)\n";
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

1. Edit the file with your desired admin credentials
2. Visit: `https://yourdomain.ifastnet.com/create-admin.php`
3. Note the credentials shown
4. **Delete `create-admin.php` immediately**

---

### Step 7: Post-Installation Configuration

#### 7.1 Clear and Optimize Caches

Create `public_html/optimize.php`:
```php
<?php
require __DIR__.'/laravel/vendor/autoload.php';
$app = require_once __DIR__.'/laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre>";
echo "Clearing caches...\n";
$kernel->call('cache:clear');
$kernel->call('config:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');

echo "\nOptimizing...\n";
$kernel->call('config:cache');
$kernel->call('route:cache');
$kernel->call('view:cache');
$kernel->call('optimize');

echo "\n✓ Optimization complete!\n";
echo "</pre>";
```

Visit once, then delete the file.

#### 7.2 Test Your Installation

1. **Visit Homepage:** `https://yourdomain.ifastnet.com`
2. **Access Admin:** `https://yourdomain.ifastnet.com/admin`
3. **Test Login:** Use admin credentials
4. **Check Modules:** Verify modules are loading
5. **Check Theme:** Verify default theme is active

---

### Step 8: Security Hardening

#### 8.1 Secure Your `.env` File

Add to `public_html/laravel/.htaccess`:
```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

#### 8.2 Disable Debug Mode

Ensure in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

#### 8.3 Set Up HTTPS (if available)

In cPanel:
1. Go to **SSL/TLS Status**
2. Enable **AutoSSL** or install **Let's Encrypt**
3. Force HTTPS in `.env`:
```env
APP_URL=https://yourdomain.ifastnet.com
```

#### 8.4 Protect Admin Routes

Add to `public_html/laravel/.htaccess`:
```apache
# Protect admin area (optional IP whitelist)
<If "%{REQUEST_URI} =~ m#^/admin#">
    # Require ip xxx.xxx.xxx.xxx
</If>
```

---

## 🔧 Common iFastNet Issues & Solutions

### Issue 1: 500 Internal Server Error

**Solutions:**
1. Check `.htaccess` syntax
2. Verify file permissions (755 for folders, 644 for files)
3. Check PHP version (must be 8.2+)
4. Review error logs in cPanel

### Issue 2: Database Connection Failed

**Solutions:**
1. Verify database credentials in `.env`
2. Use full database names from cPanel (e.g., `ifastnet_12345_dbname`)
3. Ensure user has ALL PRIVILEGES on database
4. Check DB_HOST (usually `sql300.ifastnet.com`)

### Issue 3: Composer Dependencies Missing

**Solutions:**
1. Run `composer install` locally before uploading
2. Upload the entire `vendor/` folder
3. Don't run composer on shared hosting

### Issue 4: Storage/Logs Not Writable

**Solutions:**
```bash
# Set proper permissions
storage/                  755
storage/framework/        755
storage/framework/cache/  755
storage/framework/sessions/ 755
storage/framework/views/  755
storage/logs/            755
bootstrap/cache/         755
```

### Issue 5: Routes Not Working (404 Errors)

**Solutions:**
1. Check `.htaccess` in `public_html/` and `laravel/public/`
2. Verify mod_rewrite is enabled
3. Clear route cache: run `optimize.php`

### Issue 6: Assets Not Loading

**Solutions:**
1. Check `APP_URL` in `.env` matches your domain
2. Run `php artisan storage:link`
3. Verify asset paths in views use `asset()` helper

---

## 📊 iFastNet Performance Optimization

### Enable OPcache

In cPanel PHP Settings:
1. Go to **Select PHP Version**
2. Enable **OPcache** extension
3. Increase OPcache memory to max allowed

### Optimize Database Queries

```php
// Use in .env
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

### Enable Laravel Caching

```bash
# All caching enabled
CMS_ENABLE_MODULE_CACHE=true
CMS_ENABLE_THEME_CACHE=true
CACHE_STORE=file
SESSION_DRIVER=file
```

### Minimize Asset Size

```bash
# Before deployment, minify CSS/JS
npm run build
```

---

## 🔄 Updating the Application

### Via FTP:

1. **Backup current installation**
2. **Upload new/modified files** to `public_html/laravel/`
3. **Run database migrations** (if needed):
   - Via SSH: `php artisan migrate --force`
   - Via web script: Create migration runner
4. **Clear caches**: Visit `optimize.php`

### Maintenance Mode:

Create `public_html/maintenance.php`:
```php
<?php
// Enable: php artisan down
// Disable: php artisan up

require __DIR__.'/laravel/vendor/autoload.php';
$app = require_once __DIR__.'/laravel/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$action = $_GET['action'] ?? 'down';

if ($action === 'up') {
    $kernel->call('up');
    echo "Maintenance mode disabled";
} else {
    $kernel->call('down');
    echo "Maintenance mode enabled";
}
```

---

## 📞 Support & Troubleshooting

### iFastNet Support
- **Website:** https://ifastnet.com
- **Support Forum:** https://forum.infinityfree.net

### Developer Contact
- **Email:** chardy.tsadiq02@gmail.com
- **Mobile:** +63 915 0388 448

### Error Logs Location
- **Laravel Logs:** `laravel/storage/logs/laravel.log`
- **cPanel Error Logs:** Available in cPanel → **Errors**

---

## ✅ Post-Deployment Checklist

- [ ] Application accessible at domain
- [ ] Admin panel accessible at `/admin`
- [ ] Database connected successfully
- [ ] Admin user created and can login
- [ ] Modules loading correctly
- [ ] Theme displaying properly
- [ ] File uploads working
- [ ] HTTPS enabled (if available)
- [ ] Debug mode disabled (`APP_DEBUG=false`)
- [ ] All installation scripts deleted
- [ ] `.env` file secured
- [ ] Proper file permissions set
- [ ] Backup created

---

## 🎯 Quick Command Reference

**Via SSH (if available):**
```bash
# Navigate to project
cd public_html/laravel

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Database
php artisan migrate --force
php artisan db:seed --force

# Create admin
php artisan make:filament-user

# Maintenance
php artisan down
php artisan up

# Storage link
php artisan storage:link
```

---

**Deployment Complete! Your TCC School CMS is now live on iFastNet!** 🎉

**Copyright © 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT). All Rights Reserved.**
