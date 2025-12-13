# VantaPress - Fresh Installation Quick Guide

## 🚀 Installation Steps (Shared Hosting)

### Step 1: Pull Latest Code
```bash
cd /home/hawkeye1/vantapress.com
git pull origin standard-development
```

### Step 2: Generate APP_KEY
**Visit**: `https://vantapress.com/generate-key.php`

This script will:
- ✅ Create `.env` file if it doesn't exist
- ✅ Generate secure `APP_KEY`
- ✅ Update `.env` automatically
- ✅ Work without SSH/artisan access

**⚠️ IMPORTANT**: Delete `generate-key.php` after use for security!

### Step 3: Run Database Installation
**Visit**: `https://vantapress.com/scripts/install.php`

This will guide you through:
- Database configuration
- Running migrations
- Creating admin user

---

## 🔧 What Was Fixed

### Problem 1: Laravel Routing Intercepted Setup Scripts
**Before**: Accessing `/install.php` → Laravel caught it → Required APP_KEY → Error ❌

**After**: 
- Added `.htaccess` rules to bypass Laravel for setup scripts ✅
- Setup scripts execute BEFORE Laravel boots ✅

### Problem 2: No Way to Generate APP_KEY Without SSH
**Before**: Needed `php artisan key:generate` (requires SSH) ❌

**After**: 
- Root-level `generate-key.php` bypasses Laravel ✅
- Works on any shared hosting ✅
- No SSH required ✅

---

## 📋 File Structure

```
vantapress.com/
├── generate-key.php          ← NEW: Root-level key generator (DELETE AFTER USE)
├── .htaccess                  ← UPDATED: Setup script bypass rules
├── scripts/
│   ├── generate-key.php       ← Alternative location
│   └── install.php            ← Database installer
├── .env                       ← Created by generate-key.php
└── .env.example               ← Template file
```

---

## ✅ Verification Checklist

After installation, verify:
- [ ] `generate-key.php` deleted from root
- [ ] `.env` file exists with `APP_KEY` set
- [ ] Database configured and migrations run
- [ ] Admin user created
- [ ] Can access `/tcc-admin` or `/admin`
- [ ] Can view front-end website

---

## 🐛 Troubleshooting

### Still Getting "Missing APP_KEY" Error?
1. Check if `.env` file exists in root
2. Open `.env` and verify `APP_KEY=base64:...` is set
3. Clear browser cache/cookies
4. Try accessing in incognito/private mode

### Can't Access generate-key.php?
1. Verify file exists in root directory
2. Check file permissions (644 or 755)
3. Ensure `.htaccess` is uploaded correctly
4. Contact hosting provider about mod_rewrite

### Database Connection Failed?
1. Verify database exists in cPanel/hosting panel
2. Check database name (often includes prefix like `hawkeye1_dbname`)
3. Confirm database user has permissions
4. Host is often `localhost` but may vary

---

## 📞 Support

Issues? Check:
- `/storage/logs/laravel.log` for error details
- Hosting control panel for database info
- File permissions (755 for directories, 644 for files)

**Status**: ✅ Ready for fresh installation on shared hosting

---

**Last Updated**: December 13, 2025  
**Version**: 1.2.0  
**Branch**: standard-development
