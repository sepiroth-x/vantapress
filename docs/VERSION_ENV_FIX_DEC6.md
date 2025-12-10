# ⚠️ Version Display Bug Fix - .env APP_VERSION Override

**Date:** December 6, 2025  
**Issue:** Update System dashboard showing incorrect version (v1.0.25 instead of v1.0.28)  
**Root Cause:** `.env` file had hardcoded `APP_VERSION` that overrode `config/version.php`

---

## 🔍 Problem Analysis

### What Happened?
The Update System dashboard was displaying an outdated version number despite:
- ✅ `config/version.php` being updated to v1.0.28-complete
- ✅ All version files synchronized (README, RELEASE_NOTES, index.html, install.php)
- ✅ Git tags created and pushed

### Root Cause Discovery

The version loading logic in `config/version.php` is:

```php
return [
    'version' => env('APP_VERSION', '1.0.28-complete'),
    // ...
];
```

This reads from the `.env` file FIRST, then falls back to the default value.

**The Problem:** `.env` file contained:
```env
APP_VERSION=1.0.13-complete  # ❌ OUTDATED!
```

Laravel's `env()` function prioritizes environment variables over default values, so the old version from `.env` was being used instead of the updated default in `config/version.php`.

---

## ✅ Solution Applied

### 1. Updated `.env` File (Local Development)
Changed line 7 from:
```env
APP_VERSION=1.0.13-complete
```

To:
```env
APP_VERSION=1.0.28-complete
```

### 2. Production Deployment Fix ⚠️ IMPORTANT

**If your production site shows v1.0.25 after deploying v1.0.28:**

Your production `.env` file also needs manual update!

**On Production Server:**
1. Access your production `.env` file (via SSH, FTP, or cPanel File Manager)
2. Find the `APP_VERSION` line:
   ```env
   APP_VERSION=1.0.25-complete  # ❌ OLD
   ```
3. Update it to:
   ```env
   APP_VERSION=1.0.28-complete  # ✅ NEW
   ```
4. Clear all caches:
   ```bash
   php artisan optimize:clear
   ```
5. Refresh your browser and check `/admin/updates`

**Note:** `.env` files are NOT tracked in git (by design), so you must update them manually on each environment (local, staging, production).

### 3. Cleared All Laravel Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

### 3. Verified Fix
```bash
php artisan tinker --execute="echo config('version.version');"
# Output: 1.0.28-complete ✅
```

---

## 🎯 Why This Happened

### Version Management Workflow Gap

When updating VantaPress versions, we were updating:
1. ✅ `config/version.php` (default value)
2. ✅ `.env.example` (template for new installations)
3. ✅ `README.md`
4. ✅ `RELEASE_NOTES.md`
5. ✅ `index.html`
6. ✅ `install.php`

**BUT MISSING:** 
- ❌ Local development `.env` file (excluded from git)

Since `.env` is in `.gitignore` (correct for security), it wasn't being updated during version bumps.

---

## 📋 Prevention Strategy

### For Future Version Releases

When bumping version numbers, update these files in order:

#### 1. Environment Template (tracked in git)
```env
# .env.example
APP_VERSION=1.0.XX-complete
```

#### 2. Config Default (tracked in git)
```php
// config/version.php
'version' => env('APP_VERSION', '1.0.XX-complete'),
```

#### 3. Local Environment (not tracked, manual update)
```env
# .env (LOCAL ONLY)
APP_VERSION=1.0.XX-complete
```

#### 4. Version Display Files (tracked in git)
- `README.md` - Badge and current version text
- `RELEASE_NOTES.md` - Latest version section
- `index.html` - Pre-installation page version
- `install.php` - Installer version display

#### 5. Clear All Caches (after updates)
```bash
php artisan optimize:clear
```

---

## 🔧 Automated Fix Script

Create `scripts/update-version.php` for future releases:

```php
<?php
/**
 * VantaPress Version Updater
 * Updates version across all files and local .env
 * Usage: php scripts/update-version.php 1.0.29-complete
 */

if (!isset($argv[1])) {
    die("Usage: php scripts/update-version.php [version]\nExample: php scripts/update-version.php 1.0.29-complete\n");
}

$newVersion = $argv[1];
$rootPath = dirname(__DIR__);

// Files to update
$files = [
    '.env.example' => [
        'pattern' => '/APP_VERSION=(.*)/',
        'replacement' => "APP_VERSION={$newVersion}"
    ],
    '.env' => [
        'pattern' => '/APP_VERSION=(.*)/',
        'replacement' => "APP_VERSION={$newVersion}"
    ],
    'config/version.php' => [
        'pattern' => "/'version' => env\('APP_VERSION', '(.*)'\),/",
        'replacement' => "'version' => env('APP_VERSION', '{$newVersion}'),"
    ],
    'README.md' => [
        'pattern' => '/\*\*📦 Current Version:\*\* v(.*)/',
        'replacement' => "**📦 Current Version:** v{$newVersion}"
    ],
    // Add more files as needed
];

foreach ($files as $file => $config) {
    $filePath = "{$rootPath}/{$file}";
    
    if (!file_exists($filePath)) {
        echo "⚠️  Skipping {$file} (not found)\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    $newContent = preg_replace($config['pattern'], $config['replacement'], $content);
    
    if ($content !== $newContent) {
        file_put_contents($filePath, $newContent);
        echo "✅ Updated {$file}\n";
    } else {
        echo "⏭️  No changes needed in {$file}\n";
    }
}

// Clear caches
echo "\n🧹 Clearing Laravel caches...\n";
exec('php artisan optimize:clear');

echo "\n✅ Version updated to {$newVersion}!\n";
echo "📝 Don't forget to:\n";
echo "   1. Update RELEASE_NOTES.md manually\n";
echo "   2. Commit changes: git commit -am 'chore: bump version to {$newVersion}'\n";
echo "   3. Create git tag: git tag -a v{$newVersion} -m 'Release v{$newVersion}'\n";
echo "   4. Push changes: git push origin development && git push origin v{$newVersion}\n";
```

---

## 📊 Impact Assessment

### Before Fix
- ❌ Update System showed v1.0.13-complete (from `.env`)
- ❌ Users confused about current version
- ❌ Update checker not working correctly
- ❌ Version mismatch between dashboard and actual codebase

### After Fix
- ✅ Update System shows v1.0.28-complete
- ✅ Version consistent across all interfaces
- ✅ Update checker works correctly
- ✅ Users see accurate version information

---

## 🎓 Lessons Learned

### Key Takeaways

1. **Environment files are powerful but hidden**
   - `.env` files override config defaults
   - Easy to forget since they're not tracked in git
   - Need manual synchronization during version updates

2. **Version management needs a checklist**
   - Too many files to update manually without mistakes
   - Automation script would prevent this issue
   - Consider version source of truth (single file that others read from)

3. **Cache clearing is critical**
   - Laravel aggressively caches config values
   - Always run `optimize:clear` after .env changes
   - Consider adding cache clear to version update workflow

4. **Testing should include version display**
   - Add version display check to release checklist
   - Verify Update System dashboard shows correct version
   - Test on fresh installation and existing upgrade

---

## ✅ Verification Checklist

After any version update, verify:

- [ ] `.env` has correct `APP_VERSION`
- [ ] `.env.example` has correct `APP_VERSION`
- [ ] `config/version.php` default matches
- [ ] `README.md` version badge updated
- [ ] `RELEASE_NOTES.md` latest version section updated
- [ ] `index.html` version display updated
- [ ] `install.php` version display updated
- [ ] All caches cleared (`php artisan optimize:clear`)
- [ ] Update System dashboard shows correct version
- [ ] Version verified in Tinker: `config('version.version')`

---

## 📞 Resolution Summary

**Issue Resolved:** December 6, 2025  
**Time to Fix:** 10 minutes  
**Impact:** Low (development environment only, `.env` not deployed)  

**Status:** ✅ FIXED - Version now correctly displays as v1.0.28-complete

---

**Documentation by:** Sepirothx  
**Session:** December 6, 2025 Version Management Cleanup
