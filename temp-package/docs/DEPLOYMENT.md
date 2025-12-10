# 📦 VantaPress Deployment Guide

**Version:** 1.0.0  
**Last Updated:** December 2, 2025

This guide covers deploying VantaPress to production servers, including shared hosting environments.

---

## 🎯 Quick Deployment (Recommended)

### **Method 1: Web Installer (Easiest)**

Perfect for shared hosting without SSH access.

1. **Upload Files** - Upload entire project via FTP to your server root
2. **Create Database** - Create MySQL database via cPanel/hosting panel
3. **Run Installer** - Visit `https://yourdomain.com/install.php`
4. **Follow Steps:**
   - ✅ System requirements check
   - ✅ Database configuration
   - ✅ Run migrations
   - ✅ Publish assets (automatic)
   - ✅ Create admin user
   - ✅ Complete!
5. **Delete Installer** - Remove `install.php` for security

**Time:** 5-10 minutes  
**Difficulty:** Beginner-friendly

---

### **Method 2: Post-Deploy Script (SSH Available)**

If you have SSH access:

```bash
# Upload files
cd /path/to/your/server/directory

# Run post-deployment script
php post-deploy.php
```

**What it does:**
- Detects deployment structure (root/subdirectory)
- Publishes Filament assets
- Verifies critical files
- Optimizes Laravel (caches config/routes)
- Checks .htaccess configuration
- Reports any issues

**Time:** 2-3 minutes  
**Difficulty:** Intermediate

---

## 📁 Deployment Structures

VantaPress supports multiple deployment configurations:

### **Structure A: Standard Laravel (Recommended)**

```
public_html/              ← Point domain here
├── .htaccess
├── index.php
├── css/
├── js/
├── images/
└── vendor/
```

**When to use:** You can change document root in cPanel

**Setup:**
1. Upload Laravel project to server
2. Point domain to `/public` folder via cPanel
3. Run installer or post-deploy script

---

### **Structure B: Root Deployment (Common)**

```
public_html/              ← Domain points here
├── .htaccess             ← Routes to Laravel
├── index.php             ← Laravel entry
├── app/
├── bootstrap/
├── config/
├── public/               ← Laravel public folder
│   ├── css/
│   ├── js/
│   └── images/
├── vendor/
└── ...
```

**When to use:** Cannot change document root

**Setup:**
1. Upload entire project to server root
2. Ensure `.htaccess` properly handles assets
3. Assets served from root level

---

### **Structure C: Subdirectory (iFastNet Style)**

```
public_html/              ← Domain points here
├── .htaccess             ← Proxy to laravel/public
├── index.php             ← Proxy script
└── laravel/              ← Laravel project
    ├── app/
    ├── config/
    ├── public/           ← Laravel public
    │   ├── .htaccess
    │   ├── index.php
    │   ├── css/
    │   └── js/
    └── vendor/
```

**When to use:** Shared hosting with restrictions

**Setup:**
1. Create `laravel/` folder in server root
2. Upload Laravel files to `laravel/`
3. Create proxy files in root
4. See `IFASTNET_DEPLOYMENT_GUIDE.md` for details

---

## ⚙️ Critical Configuration

### **1. .htaccess (Must Have)**

Your root `.htaccess` must properly handle assets:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # CRITICAL: Serve static assets FIRST
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^(css|js|images|fonts|vendor)/ - [L]

    # Admin routes
    RewriteRule ^admin(.*)$ index.php [L,QSA]

    # Everything else to Laravel
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L,QSA]
</IfModule>

Options -Indexes

<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(env|log|md)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

**Key line:** Static assets are served directly without going through PHP.

---

### **2. Environment File (.env)**

```env
APP_NAME=VantaPress
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database

FILAMENT_PATH=admin
```

**Important:**
- `APP_DEBUG=false` in production
- Generate `APP_KEY` via installer or `php artisan key:generate`
- Never commit `.env` to version control

---

### **3. File Permissions**

Correct permissions are critical:

```bash
# Directories: 755
chmod 755 storage
chmod 755 storage/framework
chmod 755 storage/logs
chmod 755 bootstrap/cache

# Files: 644
chmod 644 .env
chmod 644 composer.json

# Executables: 755
chmod 755 artisan
```

**Via cPanel:** Right-click → Change Permissions

---

## 🔍 Asset Management

VantaPress handles assets automatically during installation. However, here's what happens:

### **What Gets Published:**

```
public/
├── css/
│   ├── vantapress-admin.css
│   └── filament/
│       ├── filament/app.css
│       ├── forms/forms.css
│       ├── support/support.css
│       └── ...
├── js/
│   └── filament/
│       ├── filament/app.js
│       ├── support/support.js
│       ├── notifications/notifications.js
│       └── ...
└── images/
    ├── vantapress-logo.svg
    └── vantapress-icon.svg
```

### **Commands:**

```bash
# Publish Filament assets
php artisan filament:assets

# Or use post-deploy script
php post-deploy.php
```

**Automatic:** The web installer (`install.php`) handles this in Step 4.

---

## ✅ Post-Deployment Checklist

After deploying, verify:

- [ ] Homepage loads at `domain.com/`
- [ ] Admin panel loads at `domain.com/admin`
- [ ] No 404 errors in browser console (F12)
- [ ] CSS/JS files return HTTP 200
- [ ] Images display correctly
- [ ] Can login to admin panel
- [ ] Forms submit successfully
- [ ] FilamentPHP styling appears correctly

---

## 🐛 Troubleshooting

### **404 Errors for Assets**

**Symptoms:** CSS/JS files not loading, admin panel unstyled

**Solutions:**
1. Check `.htaccess` exists and has rewrite rules
2. Verify mod_rewrite is enabled
3. Run `php artisan filament:assets`
4. Check file permissions (755 for dirs, 644 for files)

### **500 Server Errors**

**Symptoms:** White page, Internal Server Error

**Common Causes:**
- Bad `.htaccess` syntax
- Missing `vendor/` folder
- PHP version < 8.2
- Wrong file permissions

**Solutions:**
1. Temporarily rename `.htaccess` to test
2. Check PHP error logs
3. Verify `vendor/` folder is uploaded
4. Ensure PHP 8.2+ is enabled

### **Assets Load But Pages Don't**

**Cause:** Routing issues

**Solution:**
- Check `.htaccess` routing rules
- Verify `index.php` proxy (if using subdirectory)
- Clear route cache: `php artisan route:clear`

---

## 🔒 Security Best Practices

### **After Installation:**

1. **Delete installer files:**
   ```bash
   rm install.php
   rm create-admin.php
   ```

2. **Secure .env file:**
   - Verify not publicly accessible
   - Set permissions to 400 or 644
   - Never commit to Git

3. **Change admin password:**
   - Login immediately after install
   - Change from default password

4. **Enable HTTPS:**
   - Get SSL certificate (Let's Encrypt)
   - Update `APP_URL` in `.env`

5. **Regular updates:**
   - Keep Laravel updated
   - Update Composer dependencies
   - Monitor security advisories

---

## 📊 Performance Optimization

### **Production Optimization:**

```bash
# Clear all caches
php artisan optimize:clear

# Cache everything for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev
```

### **Automatic:** The installer and `post-deploy.php` handle this.

---

## 📖 Additional Resources

- **IFASTNET_DEPLOYMENT_GUIDE.md** - Specific guide for iFastNet shared hosting
- **DEPLOYMENT_GUIDE.md** - Basic deployment steps
- **README.md** - Project overview and features

---

## 🆘 Support

If you encounter issues:

**Gather Information:**
- Hosting provider name
- PHP version (`php -v`)
- Server software (Apache/Nginx)
- Error messages from browser console (F12)
- Server error logs (if accessible)

**Contact:**
- **Email:** chardy.tsadiq02@gmail.com
- **Phone:** +63 915 0388 448
- **GitHub Issues:** https://github.com/sepirothx/vantapress/issues

**Include:**
- What you've tried already
- Screenshots of errors
- Deployment structure used

---

## 🎉 Success Indicators

Your deployment is successful when:

✅ Homepage loads without errors  
✅ Admin panel has full FilamentPHP styling  
✅ Can login with credentials created during install  
✅ No 404/500 errors in browser console  
✅ Forms work (create/edit/delete)  
✅ Images and assets load correctly  
✅ URLs are clean (no `/public` in URL)

---

**Created by:** Sepiroth X Villainous (Richard Cebel Cupal, LPT)  
**Project:** VantaPress CMS  
**License:** MIT  
**Version:** 1.0.0
