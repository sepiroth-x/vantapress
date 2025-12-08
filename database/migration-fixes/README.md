# Migration Fixes Directory

This directory contains **automatic migration fix scripts** that run before database migrations.

## 📌 Purpose

When VantaPress updates include database changes, sometimes production servers have conflicting legacy data. These scripts automatically fix conflicts **without requiring manual intervention**.

## 🔧 How It Works

1. **User deploys new version** (via FTP, git pull, or auto-updater)
2. **User clicks "Update Database Now"** in admin panel
3. **System automatically:**
   - Scans `database/migration-fixes/` directory
   - Executes fix scripts in alphabetical order (001_, 002_, etc.)
   - Each script checks if it needs to run (`shouldRun()` method)
   - Runs only necessary fixes
   - Logs all actions
   - Then runs normal migrations

## 📝 Script Format

Each fix script must follow this structure:

```php
<?php

/**
 * Migration Fix: [Description]
 * 
 * Version: [Target version]
 * Issue: [Problem description]
 * Solution: [What the fix does]
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class {
    /**
     * Execute the migration fix
     */
    public function execute(): array
    {
        $result = [
            'executed' => false,
            'message' => ''
        ];

        try {
            // Your fix logic here
            
            $result['executed'] = true;
            $result['message'] = 'Fix completed successfully';
            return $result;

        } catch (Exception $e) {
            Log::error('[Migration Fix] Failed', ['error' => $e->getMessage()]);
            return [
                'executed' => false,
                'message' => 'Fix failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Determine if this fix should run
     */
    public function shouldRun(): bool
    {
        // Check if fix is needed
        return true; // or false
    }
};
```

## 🎯 Naming Convention

**Format:** `XXX_descriptive_name.php`

- `XXX` = Sequential number (001, 002, 003, etc.)
- Use underscores for spaces
- Be descriptive but concise

**Examples:**
- `001_drop_legacy_menu_tables.php`
- `002_fix_duplicate_slugs.php`
- `003_migrate_old_settings_format.php`

## ✅ Best Practices

1. **Each fix should be idempotent** - Safe to run multiple times
2. **Check before executing** - Use `shouldRun()` to avoid unnecessary operations
3. **Comprehensive logging** - Log every action for debugging
4. **Return detailed results** - Include what was changed
5. **Handle errors gracefully** - Catch exceptions, don't crash migrations
6. **Document thoroughly** - Explain the issue and solution in header

## 📊 Execution Order

Scripts execute in **alphabetical order** by filename:
1. `001_drop_legacy_menu_tables.php`
2. `002_next_fix.php`
3. `003_another_fix.php`

## 🔍 Monitoring

All fix executions are logged to:
- `storage/logs/laravel.log` - Detailed execution logs
- Admin panel shows summary after "Update Database Now"

## 🚀 Benefits

- **Zero manual intervention** - Users just click "Update Database Now"
- **Production-safe** - Fixes only run when needed
- **Scalable** - Add new fixes without modifying core code
- **Transparent** - Full logging of all actions
- **Professional UX** - WordPress-style seamless updates

## 📦 Current Fixes

- `001_drop_legacy_menu_tables.php` - Drops legacy menu tables from v1.0.41 and earlier that conflict with new migrations
- `002_drop_legacy_module_tables.php` - Drops orphaned module tables (VPToDoList, TheVillainTerminal) that conflict with core migrations

---

**Note:** This system was introduced in VantaPress v1.0.42 to provide automatic migration conflict resolution.
