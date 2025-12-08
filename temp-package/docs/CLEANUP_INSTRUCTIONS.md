# Cleanup Instructions for Production Deployment

## Current Status

✅ **Core System:** Fully operational
- Homepage loads at domain root
- Admin panel at `/tcc-admin` with full styling
- Database has 21 tables ready
- Authentication working with `create-admin.php`
- All assets properly configured

✅ **Documentation Complete:**
- `README.md` - Updated with accurate project information
- `DEPLOYMENT_GUIDE.md` - Step-by-step deployment instructions
- `cleanup.php` - Automated cleanup script ready

---

## Step 1: Run Cleanup Script

Upload and run the cleanup script to remove debug files:

1. **Navigate to:** `https://dev2.thevillainousacademy.it.nf/cleanup.php`
2. **Review output:** Should show ~20 files deleted
3. **Verify:** Essential utilities remain (install.php, create-admin.php, etc.)
4. **Delete cleanup.php itself** after running

### Files That Will Be Removed

Debug/diagnostic scripts created during troubleshooting:
- `access-log.txt`, `response-log.txt`
- `admin-diagnostic.php`, `asset-check.php`
- `check-asset-urls.php`, `complete-fix.php`
- `debug-403.php`, `debug-login.php`
- `fix-admin.php`, `fix-assets.php`
- `test-asset-access.php`, `test-write.php`
- And 10+ more debug files

### Files That Will Be Kept

Essential utilities for maintenance:
- ✅ `install.php` - Keep for fresh installations (delete after use)
- ✅ `create-admin.php` - Keep for user management/password reset
- ✅ `clear-cache.php` - Keep for cache clearing
- ✅ `run-migrations.php` - Keep for future updates
- ✅ `copy-filament-assets.php` - Keep to republish assets if needed

---

## Step 2: Test Fresh Installation

Test the clean deployment process:

### 2.1 Delete All Database Tables

Via phpMyAdmin or hosting control panel:
```sql
DROP TABLE IF EXISTS 
  users, password_reset_tokens, sessions,
  cache, cache_locks,
  jobs, job_batches, failed_jobs,
  academic_years, departments, courses,
  students, teachers, rooms,
  class_schedules, enrollments, grades;
```

Or use phpMyAdmin "Drop All Tables" feature.

### 2.2 Run Fresh Installation

1. **Visit:** `https://dev2.thevillainousacademy.it.nf/install.php`

2. **Step 1 - Requirements Check:**
   - PHP 8.2.29 ✓
   - MySQL Connection ✓
   - File Permissions ✓

3. **Step 2 - Database Verification:**
   - Should show 0 tables initially
   - Confirms connection working

4. **Step 3 - Run Migrations:**
   - Click "Run Migrations" button
   - Should create 21 tables
   - Wait for success message

5. **Step 4 - Create Admin User:**
   - ⚠️ **install.php user creation is BROKEN**
   - Skip this step, use `create-admin.php` instead

6. **Step 5 - Completion:**
   - Click "Go to Admin Panel" button
   - Should redirect to `/tcc-admin/login`

### 2.3 Create Admin Account

1. **Visit:** `https://dev2.thevillainousacademy.it.nf/create-admin.php`
2. **Fill form:**
   - Name: Administrator
   - Email: admin@example.com (or your real email)
   - Password: (create strong password)
3. **Submit:** Should show "Admin user created successfully!"
4. **Click:** "Go to Admin Panel" link

### 2.4 Test Admin Login

1. **Login page:** Should load with full yellow/brown FilamentPHP styling
2. **Enter credentials:** Use email/password from step 2.3
3. **Dashboard:** Should load successfully
4. **Check:** Navigation shows all menu items

### 2.5 Verify Assets

Check that these URLs return 200 OK:
- `/css/filament/forms/forms.css`
- `/css/filament/filament/app.css`
- `/js/filament/app.js`
- `/js/filament/support/support.js`

---

## Step 3: Production Security

After successful testing:

### 3.1 Delete Installer Files

⚠️ **CRITICAL:** Delete these files from server:
```
install.php
create-admin.php
cleanup.php (if not already deleted)
```

### 3.2 Secure Environment

1. **Edit `.env`:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Save and run:** `clear-cache.php` to apply changes

3. **Delete `clear-cache.php`** after running

### 3.3 Change Admin Password

1. Login to admin panel
2. Navigate to profile settings
3. Change password to strong, unique password
4. Logout and login again to verify

### 3.4 Verify Permissions

Check `storage/` directory permissions:
```
storage/ -> 775
storage/framework/ -> 775
storage/logs/ -> 775
```

---

## Step 4: Create Deployment Package (Optional)

If everything works perfectly, create a clean deployment ZIP:

### 4.1 Files to Include

```
tcc-school-system/
├── app/
├── bootstrap/
├── config/
├── css/                    # Assets in ROOT (iFastNet requirement)
├── database/
├── js/                     # Assets in ROOT (iFastNet requirement)
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .htaccess              # CRITICAL for routing
├── artisan
├── composer.json
├── composer.lock
├── .env.example           # Template, NOT .env with real credentials
├── install.php            # Include for fresh deployments
├── create-admin.php       # Include for admin management
├── clear-cache.php        # Include for maintenance
├── run-migrations.php     # Include for migrations
├── copy-filament-assets.php
├── README.md
├── DEPLOYMENT_GUIDE.md
├── LICENSE.txt
└── PROJECT_IMPLEMENTATION_GUIDE.md
```

### 4.2 Files to EXCLUDE

```
.env                       # Has real credentials - NEVER include
storage/logs/*.log         # May contain sensitive data
access-log.txt             # Should be deleted by cleanup.php
response-log.txt           # Should be deleted by cleanup.php
node_modules/              # Not needed (if exists)
.git/                      # Version control (if exists)
cleanup.php                # One-time use script
CLEANUP_INSTRUCTIONS.md    # This file - internal documentation
```

### 4.3 Create ZIP

Via file manager or command line:
```powershell
# Create ZIP excluding unwanted files
Compress-Archive -Path "c:\Users\sepirothx\Documents\3. Laravel Development\tcc-school-system\*" `
  -DestinationPath "c:\Users\sepirothx\Documents\tcc-school-system-v1.0.0.zip" `
  -Force
```

---

## Troubleshooting

### If Admin Panel Shows 404

**Problem:** Admin panel not loading after cleanup

**Solution:**
1. Check `.htaccess` file exists in document root
2. Verify mod_rewrite enabled
3. Check storage permissions (775)
4. Run `clear-cache.php`

### If Assets Don't Load (No Styling)

**Problem:** Admin panel loads but has no styling

**Solution:**
1. Run `copy-filament-assets.php`
2. Verify files exist:
   - `/css/filament/forms/forms.css`
   - `/js/filament/app.js`
3. Check .htaccess static asset rules (lines 10-13)

### If Database Connection Fails

**Problem:** "Connection refused" or similar error

**Solution:**
1. Verify `.env` database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=sv65.ifastnet14.org
   DB_PORT=3306
   DB_DATABASE=hawkeye1_lara610
   DB_USERNAME=hawkeye1_lara610
   DB_PASSWORD=your_password
   ```
2. Test connection via phpMyAdmin
3. Check database user permissions

### If Can't Login

**Problem:** "Invalid credentials" even with correct password

**Solution:**
1. Run `create-admin.php` again to reset password
2. Check user exists: `SELECT * FROM users WHERE email='admin@example.com'`
3. Verify password hash format (should start with `$2y$`)
4. Clear browser cache/cookies

---

## Success Checklist

Before considering deployment complete:

- [ ] Cleanup script run successfully
- [ ] All debug files removed
- [ ] Fresh database installation tested
- [ ] Admin user created via `create-admin.php`
- [ ] Admin panel login working
- [ ] Dashboard loads with full styling
- [ ] Assets loading (CSS/JS)
- [ ] Database has 21 tables
- [ ] `install.php` deleted from server
- [ ] `create-admin.php` deleted from server
- [ ] `.env` set to `APP_DEBUG=false`
- [ ] Admin password changed
- [ ] Storage permissions verified (775)
- [ ] Deployment ZIP created (if needed)

---

## Next Development Steps

After production deployment is complete and stable:

### Phase 2: FilamentPHP Resources (Priority)

Build CRUD interfaces for all 9 models:
1. AcademicYearResource
2. DepartmentResource
3. CourseResource
4. StudentResource
5. TeacherResource
6. RoomResource
7. ClassScheduleResource
8. EnrollmentResource
9. GradeResource

### Phase 3: Dashboard Widgets

Create admin dashboard visualizations:
- Stats overview (student count, teacher count, etc.)
- Recent enrollments table
- Grade distribution chart
- Upcoming classes calendar

### Phase 4: Advanced Features

- Email notifications
- PDF report generation
- Student/teacher portals
- Mobile API

---

## Support

If you encounter issues during cleanup or deployment:

1. **Check logs:** `storage/logs/laravel.log`
2. **Review documentation:** `DEPLOYMENT_GUIDE.md`
3. **Test components individually:**
   - Homepage: `/`
   - Admin login: `/tcc-admin/login`
   - Assets: `/css/filament/forms/forms.css`
4. **Contact:** See README.md for author contact information

---

**Document Version:** 1.0
**Last Updated:** January 2025
**Status:** Ready for production cleanup
