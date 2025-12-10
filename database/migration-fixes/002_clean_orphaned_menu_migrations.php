<?php

/**
 * Migration Fix: Clean Orphaned Menu Migration Entries
 * 
 * Version: v1.0.48
 * Issue: Migration entries exist in 'migrations' table but physical tables don't exist
 * Root Cause: Tables were dropped manually or by previous fix, but migration tracking wasn't cleaned
 * Solution: Remove migration entries for non-existent menu tables
 * 
 * This fix runs automatically before migrations to ensure clean state.
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
            'entries_removed' => [],
            'message' => ''
        ];

        try {
            Log::warning('[Migration Fix 002] ========================================');
            Log::warning('[Migration Fix 002] Starting execution - Clean orphaned menu migration entries');
            Log::warning('[Migration Fix 002] ========================================');

            $entriesRemoved = [];

            // Check each menu-related migration entry
            $menuMigrations = [
                'create_menus_table' => 'menus',
                'create_menu_items_table' => 'menu_items',
                'create_vp_menus_tables' => 'vp_menus',
                'add_page_id_to_menu_items_table' => 'menu_items'
            ];

            foreach ($menuMigrations as $migrationPattern => $tableName) {
                // Check if migration is tracked
                $tracked = DB::table('migrations')
                    ->where('migration', 'like', "%{$migrationPattern}%")
                    ->first();

                if ($tracked) {
                    // Check if the actual table exists
                    $tableExists = Schema::hasTable($tableName);
                    
                    Log::warning("[Migration Fix 002] Checking: {$migrationPattern}", [
                        'tracked_in_db' => 'YES',
                        'table_exists' => $tableExists ? 'YES' : 'NO',
                        'table_name' => $tableName
                    ]);

                    // If tracked but table doesn't exist, remove the tracking entry
                    if (!$tableExists) {
                        Log::warning("[Migration Fix 002] ⚠️ ORPHANED ENTRY FOUND: {$tracked->migration}");
                        Log::warning("[Migration Fix 002] Table '{$tableName}' doesn't exist but migration is tracked");
                        Log::warning("[Migration Fix 002] Removing orphaned migration entry...");
                        
                        DB::table('migrations')
                            ->where('id', $tracked->id)
                            ->delete();
                        
                        $entriesRemoved[] = $tracked->migration;
                        Log::warning("[Migration Fix 002] ✓ Removed orphaned entry: {$tracked->migration}");
                    } else {
                        Log::info("[Migration Fix 002] ✓ OK: Migration tracked and table exists");
                    }
                } else {
                    Log::info("[Migration Fix 002] Migration '{$migrationPattern}' not tracked - OK");
                }
            }

            if (count($entriesRemoved) > 0) {
                $result['executed'] = true;
                $result['entries_removed'] = $entriesRemoved;
                $result['message'] = 'Removed ' . count($entriesRemoved) . ' orphaned migration entry(ies)';
                
                Log::warning('[Migration Fix 002] ========================================');
                Log::warning('[Migration Fix 002] ✓✓✓ SUCCESS: Cleaned orphaned entries', $result);
                Log::warning('[Migration Fix 002] ========================================');
            } else {
                $result['message'] = 'No orphaned migration entries found';
                Log::info('[Migration Fix 002] No action needed - all migration entries are valid');
            }

            return $result;

        } catch (Exception $e) {
            Log::error('[Migration Fix 002] ❌ ERROR executing fix', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'executed' => false,
                'entries_removed' => [],
                'message' => 'Fix failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Determine if this fix should run
     * 
     * @return bool
     */
    public function shouldRun(): bool
    {
        Log::warning('[Migration Fix 002] ========================================');
        Log::warning('[Migration Fix 002] Checking for orphaned migration entries');
        Log::warning('[Migration Fix 002] ========================================');
        
        // Check if migrations table exists
        if (!Schema::hasTable('migrations')) {
            Log::warning('[Migration Fix 002] Skipping - migrations table does not exist yet');
            return false;
        }

        // Check if any menu migration entries exist but tables don't
        $menuMigrations = [
            'create_menus_table' => 'menus',
            'create_menu_items_table' => 'menu_items',
            'create_vp_menus_tables' => 'vp_menus'
        ];

        $foundOrphaned = false;

        foreach ($menuMigrations as $migrationPattern => $tableName) {
            $tracked = DB::table('migrations')
                ->where('migration', 'like', "%{$migrationPattern}%")
                ->exists();

            $tableExists = Schema::hasTable($tableName);

            if ($tracked && !$tableExists) {
                Log::warning("[Migration Fix 002] ⚠️ ORPHANED: '{$migrationPattern}' tracked but '{$tableName}' doesn't exist");
                $foundOrphaned = true;
            }
        }

        if ($foundOrphaned) {
            Log::warning('[Migration Fix 002] ✓✓✓ DECISION: WILL RUN - Orphaned entries detected!');
            return true;
        }

        Log::info('[Migration Fix 002] DECISION: SKIP - No orphaned entries found');
        return false;
    }
};
