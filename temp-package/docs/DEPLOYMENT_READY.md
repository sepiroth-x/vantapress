# TCC School System - Deployment Checklist

## ✅ READY FOR DEPLOYMENT

### Files & Folders Verified:
- ✅ vendor/ (52 packages)
- ✅ storage/framework/sessions
- ✅ storage/framework/views
- ✅ storage/framework/cache
- ✅ public/vendor/filament (20 asset files)
- ✅ database/migrations (13 migrations)
- ✅ .env (APP_KEY, DB credentials configured)
- ✅ index.php (root entry point)
- ✅ .htaccess (root redirect)
- ✅ public/index.php
- ✅ public/.htaccess
- ✅ install.php (web installer)
- ✅ diagnostic.php (troubleshooting)

### Configuration:
- APP_KEY: ✅ Generated
- DB_HOST: sv65.ifastnet14.org
- DB_DATABASE: hawkeye1_lara610
- APP_URL: https://dev2.thevillainousacademy.it.nf
- ASSET_URL: https://dev2.thevillainousacademy.it.nf

### Routes Available:
- / → Landing page
- /admin → FilamentPHP login
- /admin/login → Admin login form
- /success-install → Laravel success page
- /diagnostic.php → System diagnostic

## 📦 HOW TO DEPLOY

1. **ZIP the project**:
   - Right-click "tcc-school-system" folder
   - Send to → Compressed (zipped) folder
   - Name: `tcc-school-system.zip`

2. **Upload to iFastNet**:
   - Login to iFastNet File Manager
   - Navigate to your domain root (htdocs or public_html)
   - Upload `tcc-school-system.zip`
   - Extract/Unzip in place

3. **Run installer**:
   - Visit: `https://dev2.thevillainousacademy.it.nf/install.php`
   - Follow 5-step wizard:
     * Step 1: Requirements check
     * Step 2: Database connection test
     * Step 3: Run migrations
     * Step 4: Create admin user
     * Step 5: Complete!

4. **Test the site**:
   - Visit: `https://dev2.thevillainousacademy.it.nf/` (should show landing page)
   - Visit: `https://dev2.thevillainousacademy.it.nf/admin` (should show styled login)
   - Login with credentials from install.php

5. **If admin styling broken**:
   - Visit: `https://dev2.thevillainousacademy.it.nf/diagnostic.php`
   - Check which asset URLs work
   - Adjust .htaccess if needed

## ⚠️ IMPORTANT NOTES

- **DO NOT** upload to `/public/` folder - upload to domain root
- **DELETE** install.php after successful installation
- **BACKUP** .env file before any changes
- **SET** file permissions: 755 for folders, 644 for files
- **STORAGE** folders need 775 permissions (storage/framework/*)

## 🎯 Expected Results

After deployment:
- Homepage loads at root domain (brown/yellow theme)
- Admin panel accessible at /admin with full styling
- Can login with admin credentials
- Dashboard shows (empty until you add data)
- No 403 errors on assets
- No infinite loops

## 🔧 Troubleshooting

If homepage doesn't load:
- Check .htaccess uploaded correctly
- Check index.php in root exists
- Check vendor/ folder uploaded completely

If admin has no styling:
- Visit /diagnostic.php
- Check if /vendor/filament/filament/theme.css returns 200
- Verify ASSET_URL in .env matches your domain

If 403 errors:
- Check .htaccess allows access to /public/ assets
- Check file permissions (folders 755, files 644)
- Check storage/ folders are 775

## 📝 Status: READY TO ZIP AND UPLOAD
