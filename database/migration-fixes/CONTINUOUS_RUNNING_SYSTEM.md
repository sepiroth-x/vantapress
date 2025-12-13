# Migration Fix Scripts - Continuous Running System

## 🔄 Overview

The migration-fixes system now uses a **continuous running strategy** that automatically organizes scripts based on execution results.

## How It Works

### Before (Old System)
- Scripts remained in root directory after execution
- Manual cleanup required
- Hard to tell which scripts already ran
- Risk of duplicate execution

### After (New System - CONTINUOUS RUNNING)
- ✅ **Successful scripts** → Automatically moved to `successfully-ran/` folder
- ❌ **Failed scripts** → Automatically moved to `failed/` folder  
- ⏭️ **Skipped scripts** (already ran) → Moved to `successfully-ran/` folder
- 🔄 **Pending scripts** → Remain in root folder for next run

## Folder Structure

```
database/migration-fixes/
├── README.md                                    # Documentation
├── CONTINUOUS_RUNNING_SYSTEM.md                # This file
├── 007_your_new_fix.php                        # Pending script
├── 008_another_pending_fix.php                 # Pending script
├── successfully-ran/                           # Archive of successful fixes
│   ├── 000_emergency_drop_all_menu_tables.php
│   ├── 001_drop_legacy_menu_tables.php
│   ├── 002_clean_orphaned_menu_migrations.php
│   ├── 003_fix_migration_order.php
│   ├── 004_drop_legacy_module_tables.php
│   ├── 005_fix_vptelemetryserver_autoload.php
│   └── 006_seed_vantapress_roles.php
└── failed/                                      # Scripts that encountered errors
    └── 999_problematic_script.php
```

## Execution Flow

```
┌─────────────────────────────────────┐
│  User clicks "Update Database"      │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  System scans root directory        │
│  for *.php files                    │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  For each script:                   │
│  1. Include the script              │
│  2. Call shouldRun()                │
└──────────────┬──────────────────────┘
               │
         ┌─────┴─────┐
         │           │
         ▼           ▼
   ┌─────────┐  ┌─────────┐
   │ Returns │  │ Returns │
   │  TRUE   │  │  FALSE  │
   └────┬────┘  └────┬────┘
        │            │
        ▼            ▼
   ┌─────────┐  ┌──────────────────┐
   │ Execute │  │ Skip (already    │
   │ script  │  │ ran or not       │
   └────┬────┘  │ needed)          │
        │       └────┬─────────────┘
        │            │
        ▼            ▼
   ┌─────────┐  ┌──────────────────┐
   │ Success?│  │ Move to          │
   └────┬────┘  │ successfully-ran/│
        │       └──────────────────┘
   ┌────┴────┐
   │         │
   ▼         ▼
┌───────┐ ┌────────┐
│ YES   │ │  NO    │
└───┬───┘ └───┬────┘
    │         │
    ▼         ▼
┌──────────────────┐ ┌──────────────────┐
│ Move to          │ │ Move to          │
│ successfully-ran/│ │ failed/          │
└──────────────────┘ └──────────────────┘
```

## Benefits

### 1. **Self-Organizing System**
- Root directory only shows pending work
- Completed scripts automatically archived
- Failed scripts isolated for debugging

### 2. **No Duplicate Execution**
- Scripts that already ran are moved out
- `shouldRun()` prevents re-execution
- Clean execution history

### 3. **Fault Tolerance**
- One failed script doesn't block others
- Failed scripts moved to `failed/` folder
- Easy to identify and fix problems

### 4. **Easy Debugging**
- Check `failed/` folder for errors
- Review Laravel logs for stack traces
- Move fixed scripts back to root to retry

### 5. **Audit Trail**
- `successfully-ran/` shows execution history
- Timestamp via file modification date
- Clear record of what fixes were applied

## Writing Fix Scripts

### Basic Template

```php
<?php

/**
 * Migration Fix: [Brief description]
 * 
 * Resolves: [What problem this fixes]
 * Date: [Creation date]
 * Version: [Target VantaPress version]
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class {
    
    /**
     * Determine if this fix should run
     * 
     * Return TRUE if:
     * - The problem exists
     * - The fix hasn't been applied yet
     * - The target data/tables need repair
     * 
     * Return FALSE if:
     * - The fix already executed successfully
     * - The conditions don't require fixing
     * - The target tables/data don't exist
     * 
     * @return bool
     */
    public function shouldRun(): bool
    {
        // Example: Check if problematic table exists
        if (Schema::hasTable('legacy_table_to_remove')) {
            Log::info('[Fix] Problem detected: legacy_table_to_remove exists');
            return true;
        }
        
        // Already fixed
        Log::info('[Fix] No action needed: legacy_table_to_remove already removed');
        return false;
    }
    
    /**
     * Execute the fix
     * 
     * Perform the actual repair work
     * Always wrap in try-catch for safety
     * 
     * @return array Results array with 'executed' and 'message' keys
     */
    public function execute(): array
    {
        try {
            Log::info('[Fix] Starting: Remove legacy_table_to_remove');
            
            // Perform your fix operations
            Schema::dropIfExists('legacy_table_to_remove');
            
            // Clean up related data
            DB::table('migrations')
                ->where('migration', 'like', '%legacy_table%')
                ->delete();
            
            Log::info('[Fix] SUCCESS: Removed legacy_table_to_remove');
            
            return [
                'executed' => true,
                'message' => 'Successfully removed legacy table and migration records',
                'tables_dropped' => ['legacy_table_to_remove'],
                'records_cleaned' => 3
            ];
            
        } catch (\Exception $e) {
            Log::error('[Fix] FAILED: Could not remove legacy_table_to_remove', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'executed' => false,
                'message' => 'Failed to remove legacy table: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }
};
```

## Naming Convention

Use sequential numbering with descriptive names:

```
000_emergency_drop_all_menu_tables.php        # Critical fixes first
001_drop_legacy_menu_tables.php
002_clean_orphaned_menu_migrations.php
003_fix_migration_order.php
...
007_your_new_fix.php                          # Next available number
```

## Common Patterns

### Pattern 1: Drop Orphaned Tables

```php
public function shouldRun(): bool
{
    return Schema::hasTable('orphaned_table');
}

public function execute(): array
{
    Schema::dropIfExists('orphaned_table');
    return [
        'executed' => true,
        'message' => 'Dropped orphaned_table'
    ];
}
```

### Pattern 2: Clean Migration Records

```php
public function shouldRun(): bool
{
    $count = DB::table('migrations')
        ->where('migration', 'like', '%old_pattern%')
        ->count();
    
    return $count > 0;
}

public function execute(): array
{
    $deleted = DB::table('migrations')
        ->where('migration', 'like', '%old_pattern%')
        ->delete();
    
    return [
        'executed' => true,
        'message' => "Removed {$deleted} orphaned migration records"
    ];
}
```

### Pattern 3: Seed Missing Data

```php
public function shouldRun(): bool
{
    return !DB::table('settings')
        ->where('key', 'required_setting')
        ->exists();
}

public function execute(): array
{
    DB::table('settings')->insert([
        'key' => 'required_setting',
        'value' => 'default_value',
        'created_at' => now()
    ]);
    
    return [
        'executed' => true,
        'message' => 'Seeded required_setting'
    ];
}
```

## Troubleshooting

### Script Not Running?

**Symptoms:**
- Script stays in root directory
- Not moving to `successfully-ran/` or `failed/`

**Solutions:**
1. Check it's in root `database/migration-fixes/` (not in subdirectories)
2. Verify it's a `.php` file with proper structure
3. Ensure both `shouldRun()` and `execute()` methods exist
4. Check Laravel logs: `storage/logs/laravel.log`

### Script Keeps Failing?

**Symptoms:**
- Script moved to `failed/` folder
- Error messages in logs

**Solutions:**
1. Review `storage/logs/laravel.log` for detailed error
2. Fix the code issue
3. Move script back to root directory: `mv failed/007_fix.php .`
4. Click "Update Database" to retry

### Script Not Moving After Execution?

**Symptoms:**
- Script executed but didn't move
- Remains in root directory

**Possible Causes:**
1. **File permissions** - Check write permissions on folders:
   ```bash
   chmod 755 database/migration-fixes/successfully-ran
   chmod 755 database/migration-fixes/failed
   ```

2. **Folders don't exist** - They should be created automatically, but verify:
   ```bash
   ls -la database/migration-fixes/
   ```

3. **Script returned invalid result** - Ensure `execute()` returns proper array:
   ```php
   return [
       'executed' => true,  // Must be boolean
       'message' => 'Done'  // Must be string
   ];
   ```

## Deployment Workflow

### 1. Develop New Fix Script

```bash
# Create new script
cd database/migration-fixes/
touch 007_my_new_fix.php

# Write script following template
# Test locally
```

### 2. Deploy to Production

```bash
# Push to repository
git add database/migration-fixes/007_my_new_fix.php
git commit -m "Add fix for issue XYZ"
git push

# Or upload via FTP
# Upload to: /database/migration-fixes/007_my_new_fix.php
```

### 3. User Runs Update

1. User sees "Database updates available" banner
2. User clicks "Update Database Now"
3. System executes new fix script
4. Script moves to `successfully-ran/` if successful
5. Main migrations run
6. User sees success message

### 4. Verify Execution

```bash
# Check logs
tail -f storage/logs/laravel.log

# Verify script moved
ls -l database/migration-fixes/successfully-ran/

# Check for failures
ls -l database/migration-fixes/failed/
```

## Migration vs Fix Scripts

| Feature | Migrations | Fix Scripts |
|---------|-----------|-------------|
| **Purpose** | Schema changes | Emergency repairs |
| **When runs** | `php artisan migrate` | Before migrations (web-based) |
| **Execution** | Once, recorded in DB | Based on `shouldRun()` conditions |
| **Order** | Timestamp-based | Alphabetical (001_, 002_) |
| **Reversible** | Yes (`down()` method) | No (forward-only fixes) |
| **Idempotent** | Should be | Must be |
| **Storage** | `database/migrations/` | `database/migration-fixes/` |
| **Auto-organize** | No | Yes (moved after execution) |

## Best Practices

### 1. Make Scripts Idempotent

Scripts should be safe to run multiple times:

```php
// ✅ GOOD - Safe to run multiple times
Schema::dropIfExists('table');

// ❌ BAD - Fails if already dropped
Schema::drop('table');
```

### 2. Log Everything

Use extensive logging for debugging:

```php
Log::info('[Fix] Checking condition...');
Log::info('[Fix] Found 5 orphaned records');
Log::warning('[Fix] Proceeding with cleanup');
Log::info('[Fix] Deleted 5 records');
```

### 3. Handle Errors Gracefully

Never throw unhandled exceptions:

```php
try {
    // Risky operation
    DB::statement('DROP TABLE IF EXISTS legacy_table');
} catch (\Exception $e) {
    Log::error('[Fix] Error dropping table', [
        'error' => $e->getMessage()
    ]);
    
    return [
        'executed' => false,
        'message' => 'Failed: ' . $e->getMessage()
    ];
}
```

### 4. Return Detailed Results

Include useful information in results:

```php
return [
    'executed' => true,
    'message' => 'Cleaned up 15 orphaned records',
    'tables_affected' => ['users', 'roles'],
    'records_deleted' => 15,
    'duration_seconds' => 2.3
];
```

### 5. Test Before Deploying

Always test fix scripts locally:

```bash
# Run migrations with fix scripts
php artisan migrate

# Check logs
tail storage/logs/laravel.log

# Verify script moved correctly
ls database/migration-fixes/successfully-ran/
```

## History & Evolution

### Version 1.0 (Original System)
- Scripts remained in root directory
- Manual tracking required
- Risk of duplicate execution

### Version 2.0 (Current - Continuous Running)
- Automatic script organization
- Self-cleaning root directory
- Fault-tolerant execution
- Clear audit trail

## Future Enhancements

Possible improvements:

- **Execution history database table** - Track when each script ran
- **Web UI for script management** - View/retry failed scripts in admin panel
- **Rollback capability** - Some scripts could support undo operations
- **Dependency system** - Scripts that depend on others running first
- **Scheduling** - Run certain scripts at specific times

---

**Last Updated:** December 2024  
**System Version:** 2.0 (Continuous Running)  
**Compatibility:** VantaPress 1.1.0+
