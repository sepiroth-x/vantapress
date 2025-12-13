# VantaPress Version Management

## How Versioning Works

VantaPress uses a **manual versioning system** with the following components:

### Version Storage

1. **config/version.php** - Central version configuration
   ```php
   return [
       'version' => env('APP_VERSION', '1.0.13-complete'),
       'github_repo' => 'sepiroth-x/vantapress',
       'check_updates' => env('CHECK_FOR_UPDATES', true),
   ];
   ```

2. **.env** file - Runtime version override
   ```env
   APP_VERSION=1.0.13-complete
   ```

3. **.env.example** - Default version for new installations
   ```env
   APP_VERSION=1.0.13-complete
   ```

### Update Detection

The **Update System** in the admin panel (`/admin/updates`) checks for new releases by:

1. Reading current version from `config('version.version')`
2. Fetching latest release from GitHub API: `https://api.github.com/repos/sepiroth-x/vantapress/releases/latest`
3. Comparing versions using `version_compare()`
4. Showing notification if newer version available

### Version Format

**Pattern:** `MAJOR.MINOR.PATCH-SUFFIX`

**Examples:**
- `1.0.13-complete` - Production release
- `1.0.12-complete` - Previous release
- `1.1.0-beta` - Beta release
- `2.0.0` - Major version

---

## Updating Version (For Developers)

When releasing a new version, update the following files:

### 1. Core Configuration Files
```bash
# Update config/version.php
config/version.php → 'version' => env('APP_VERSION', '1.0.XX-complete')

# Update .env.example
.env.example → APP_VERSION=1.0.XX-complete
```

### 2. Installer
```bash
# Update install.php (around line 238)
install.php → APP_VERSION=1.0.XX-complete
```

### 3. Documentation Files
```bash
RELEASE_NOTES.md → Update "Current Version" section
README.md → Update version badge
index.html → Update footer version
```

### 4. Git Tagging
```bash
# Create annotated tag
git tag -a v1.0.XX-complete -m "Release v1.0.XX-complete - Description"

# Push tag to GitHub
git push origin v1.0.XX-complete
```

---

## Version Update Checklist

When releasing a new version:

- [ ] Update `config/version.php` fallback version
- [ ] Update `.env.example` APP_VERSION
- [ ] Update `install.php` APP_VERSION setter (line ~238)
- [ ] Update `RELEASE_NOTES.md` current version
- [ ] Update `README.md` version badge
- [ ] Update `index.html` footer version
- [ ] Create git tag: `git tag -a vX.X.X-complete -m "Release notes"`
- [ ] Push tag: `git push origin vX.X.X-complete`
- [ ] Create GitHub Release from tag
- [ ] Test Update System detects new version

---

## Troubleshooting

### "Update System shows old version"

**Cause:** Version not updated in `config/version.php` or `.env` file

**Fix:**
1. Check `.env` file has: `APP_VERSION=1.0.13-complete`
2. If missing, add it after `APP_URL` line
3. Clear config cache: `php artisan config:clear`
4. Refresh admin panel

### "Update System says no update available"

**Cause:** Git tag not pushed to GitHub

**Fix:**
1. Check local tags: `git tag -l`
2. Push tag: `git push origin v1.0.13-complete`
3. Create GitHub Release from the tag
4. Wait 5 minutes for GitHub API cache
5. Click "Check for Updates" in admin panel

### "Version shows in admin but wrong number"

**Cause:** Config cached with old version

**Fix:**
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Version Priority (Precedence)

VantaPress reads version in this order:

1. **`.env` file** (`APP_VERSION=...`) - Highest priority
2. **`config/version.php`** fallback - If .env not set
3. **Default hardcoded** (`1.0.12-complete`) - Last resort

**Recommendation:** Always set `APP_VERSION` in `.env` for production deployments.

---

## For Users (Shared Hosting)

### Check Current Version

1. Login to admin panel
2. Go to **Updates** page (left sidebar)
3. Current version shown at top

### Update to Latest Version

**Manual Update (Recommended for shared hosting):**

1. Visit [VantaPress Releases](https://github.com/sepiroth-x/vantapress/releases)
2. Download latest version ZIP
3. Extract ZIP file
4. Upload files to server via FTP (overwrite existing files)
5. Keep your `.env` file (don't overwrite!)
6. Visit admin panel → should show new version

**Important:** 
- Backup your `.env` file before updating!
- Backup your database before updating!
- Update overwrites all files except `.env` and `storage/`

---

## Auto-Update System (Future)

Currently, VantaPress uses **manual updates**. Auto-update system is planned for v1.5:

**Planned Features:**
- One-click update from admin panel
- Automatic backup before update
- Rollback capability
- Update notification emails
- Scheduled update checks

---

## Version History Location

Full version history and release notes:
- **RELEASE_NOTES.md** - Complete changelog
- **GitHub Releases** - https://github.com/sepiroth-x/vantapress/releases
- **Admin Panel** - Updates page shows latest release info

---

**Last Updated:** v1.0.13-complete (December 4, 2025)
