# VantaPress Session Memory

**Last Updated:** December 4, 2025

## Critical Architecture Decisions

### 🗑️ public/ Folder DELETED (December 4, 2025)

**Decision:** Permanently removed the `public/` directory from VantaPress.

**Reasoning:**
1. **Shared Hosting Reality** - Web root is the main folder, NOT `public/`
2. **No Dependencies** - Themes and modules don't reference `public/` at all
3. **Asset Location** - All assets already in ROOT (`/css`, `/js`, `/images`, `/vendor`)
4. **Confusion Source** - The folder was causing constant confusion during development
5. **Laravel Convention vs Reality** - Traditional Laravel uses `public/`, but VantaPress is designed for shared hosting where this doesn't apply

**Files Relocated Before Deletion:**
- `public/index.html` → `index.html` (root)
- `public/index.php` → `index.php` (root, then renamed to `_index.php` for pre-installation)
- `public/.htaccess` → `.htaccess` (root, already exists)
- `public/css/`, `public/js/`, `public/images/` → Already in root as `/css`, `/js`, `/images`

**Config Changes:**
- `config/modules.php`: Changed `'assets' => public_path('modules')` to `'assets' => base_path('modules')`

**Impact:**
- ✅ No impact on themes (never used `public/`)
- ✅ No impact on modules (config updated)
- ✅ Cleaner architecture
- ✅ Less confusion during development
- ✅ Matches actual deployment structure

---

## Pre-Installation Welcome Page Solution (v1.0.10)

**Problem:** Laravel throws MissingAppKeyException before installer runs.

**Solution:** Simple HTML welcome page in root:
- `index.html` - Shows BEFORE `index.php` is processed
- `index.php` - Renamed to `_index.php` (Laravel disabled)
- After installation Step 6: `_index.php` → `index.php`, delete `index.html`

**Key Insight:** Web servers prioritize `index.html` over `index.php`, so pure HTML displays without invoking PHP/Laravel.

---

## Theming System

**Architecture:**
- Themes stored in `/themes/{slug}/`
- Active theme checked via database: `Theme::where('is_active', true)->first()`
- Theme home page: `themes/{slug}/pages/home.blade.php`
- Routes in `routes/web.php` handle dynamic loading
- ThemeManager service registers view namespaces

**Important:** Theme system is 100% independent of `public/` folder. All theme assets use root-relative paths.

---

## Shared Hosting Constraints

1. **No SSH access** - Everything must work via FTP/cPanel
2. **No Composer CLI** - All dependencies pre-installed in `vendor/`
3. **No Node.js** - Assets pre-built and committed
4. **Root = Web Root** - Files served from main directory, not `public/`
5. **Apache with .htaccess** - Uses root `.htaccess` for routing

---

## Version History Context

- **v1.0.6** - APP_KEY auto-generation in installer (didn't solve homepage error)
- **v1.0.7** - Route-level error handling (didn't work, Laravel already booted)
- **v1.0.8** - Pre-boot APP_KEY check in `public/index.php` (wrong location for shared hosting)
- **v1.0.9** - Enhanced APP_KEY validation (still wrong approach)
- **v1.0.10** - Simple HTML solution in root directory (WORKS!)

**Lesson Learned:** Stop fighting Laravel's architecture. Use simple HTML that loads BEFORE PHP.

---

## Development Notes

### AI Assistant Confusion Points (to avoid repeating)

1. **DON'T assume `public/` is the web root** - It's not for shared hosting
2. **DON'T try complex pre-boot checks** - Simple HTML is better
3. **DON'T forget root-level files** - `index.php`, `.htaccess`, assets all in root
4. **DO remember** - Themes work independently of `public/` folder
5. **DO remember** - Module assets can be anywhere via config

### File Structure (Actual Deployment)
```
/                           ← Web root
├── index.html             ← Pre-installation welcome (deleted after install)
├── index.php              ← Laravel entry point (renamed from _index.php after install)
├── install.php            ← 6-step web installer
├── .htaccess              ← Apache routing rules
├── .env                   ← Environment configuration
├── css/                   ← Stylesheets (Filament, custom)
├── js/                    ← JavaScript files
├── images/                ← Image assets
├── vendor/                ← Composer dependencies
├── themes/                ← Theme files
├── Modules/               ← Module files
├── app/                   ← Application code
├── bootstrap/             ← Laravel bootstrap
├── config/                ← Configuration files
├── database/              ← Migrations, seeders
├── resources/             ← Views, assets
├── routes/                ← Route definitions
├── storage/               ← Logs, cache, sessions
└── [NO public/ folder]    ← DELETED, not needed!
```

---

## Future Reference

When creating new features:
1. **Assets** - Place in root `/css`, `/js`, `/images` (NOT `public/`)
2. **Views** - Use `resources/views/` or theme-specific paths
3. **Modules** - Assets go to root `/modules/{name}/` via config
4. **Themes** - Self-contained in `/themes/{slug}/` with own assets

**Never reference `public/` folder - it doesn't exist!**
