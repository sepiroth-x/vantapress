# Deployment Fixes - December 6, 2025

## Issues Fixed

### 1. Layout Templates 500 Error (`/admin/layout-templates`)
**Problem**: Foreign key constraint referencing non-existent `themes` table.

**Root Cause**: VantaPress uses filesystem-based themes (not database), but LayoutTemplate model had a foreign key to a `themes` table.

**Solution**: 
- Removed `theme_id` foreign key constraint
- Added `theme_slug` string column instead
- Updated model and resource to use theme slug

### 2. Page Editing Not Working
**Problem**: Pages couldn't be edited on deployed environment.

**Root Cause**: Missing `author_id` preservation during edit operations.

**Solution**:
- Added `mutateFormDataBeforeSave()` to preserve author_id
- Added `mutateFormDataBeforeFill()` to ensure author_id exists

---

## Deployment Instructions

### Step 1: Pull Latest Code
```bash
cd /path/to/vantapress
git pull origin development
```

### Step 2: Run Migrations
```bash
php artisan migrate
```

This will run the following migration:
- `2025_12_06_175855_update_layout_templates_table_remove_theme_id.php`

The migration will:
1. ✅ Drop the `theme_id` foreign key constraint (if exists)
2. ✅ Remove the `theme_id` column
3. ✅ Add `theme_slug` varchar column

### Step 3: Clear Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Step 4: Verify Fixes

#### Test Layout Templates:
1. Visit: `https://yourdomain.com/admin/layout-templates`
2. Expected: Page loads without 500 error
3. Try creating a new layout template with theme slug "BasicTheme"

#### Test Page Editing:
1. Visit: `https://yourdomain.com/admin/pages`
2. Click edit on any page
3. Make changes and save
4. Expected: Page saves successfully

---

## Files Changed

### Modified Files:
1. **app/Models/LayoutTemplate.php**
   - Removed `theme()` relationship
   - Changed `theme_id` to `theme_slug` in fillable
   - Added `getThemeSlug()` method

2. **app/Filament/Resources/LayoutTemplateResource.php**
   - Changed theme selector to text input for `theme_slug`
   - Updated table columns to display `theme_slug`
   - Updated filters to use `theme_slug`

3. **database/migrations/2025_12_06_162738_create_layout_templates_table.php**
   - Removed foreign key constraint
   - Changed `theme_id` to `theme_slug`

4. **app/Filament/Resources/PageResource/Pages/EditPage.php**
   - Added `mutateFormDataBeforeFill()`
   - Added `mutateFormDataBeforeSave()`
   - Ensures `author_id` is preserved during edits

5. **app/Filament/Resources/PageResource.php**
   - Added `use Illuminate\Support\Facades\Auth;`

### New Migration:
- **2025_12_06_175855_update_layout_templates_table_remove_theme_id.php**

---

## Rollback (If Needed)

If something goes wrong, rollback the migration:

```bash
php artisan migrate:rollback --step=1
```

Then restore the original files from git:
```bash
git checkout HEAD~1 -- app/Models/LayoutTemplate.php
git checkout HEAD~1 -- app/Filament/Resources/LayoutTemplateResource.php
php artisan config:clear
```

---

## Technical Details

### Why Theme Slug Instead of Foreign Key?

VantaPress uses a **hybrid architecture**:
- **Database**: Core CMS data (pages, media, users)
- **Filesystem**: Themes and Modules (for easy FTP deployment)

This is intentional for WordPress-like ease of deployment:
- Upload theme folder via FTP
- No database migrations needed
- Instant availability

Therefore, `theme_slug` references the folder name in `/themes/` directory, not a database ID.

### Migration Safety

The update migration includes safety checks:
```php
// Check if column exists before dropping
if (Schema::hasColumn('layout_templates', 'theme_id')) {
    try {
        $table->dropForeign(['theme_id']);
    } catch (\Exception $e) {
        // Foreign key might not exist, continue
    }
    $table->dropColumn('theme_id');
}
```

This ensures it works whether the table:
- Has foreign key constraint
- Has only the column
- Is in any intermediate state

---

## Verification Checklist

After deployment, verify:

- [ ] `/admin/layout-templates` loads without error
- [ ] Can create new layout template with theme slug
- [ ] Can view existing layout templates
- [ ] Can edit layout templates
- [ ] Can delete layout templates
- [ ] Can edit pages without errors
- [ ] Page edits save successfully
- [ ] Author attribution is preserved

---

## Contact

**Developer**: Sepiroth X Villainous (Richard Cebel Cupal, LPT)  
**Email**: chardy.tsadiq02@gmail.com  
**Mobile**: +63 915 0388 448  

For deployment issues, check:
1. Error logs: `storage/logs/laravel.log`
2. Web server error logs (Apache/Nginx)
3. Database connection status
4. File permissions (775 for directories, 664 for files)
