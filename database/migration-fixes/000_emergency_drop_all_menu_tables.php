<?php

/**
 * EMERGENCY Migration Fix: Aggressively Drop ALL Menu Tables
 * 
 * Version: v1.1.4-complete
 * Priority: 000 (Runs FIRST before all other fixes)
 * Issue: Production environments have menu tables that cause "table already exists" errors
 * Solution: ALWAYS drop ALL menu-related tables regardless of tracking status
 * 
 * This is an EMERGENCY fix that runs BEFORE normal fix scripts.
 * It aggressively drops tables to ensure clean slate for migrations.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class {
    /**
     * Execute the emergency fix
     */
    public function execute(): array
    {
        $result = [
            'executed' => false,
            'tables_dropped' => [],
            'message' => ''
        ];

        try {
            Log::warning('[EMERGENCY FIX 000] ================================================');
            Log::warning('[EMERGENCY FIX 000] AGGRESSIVE MODE: Dropping ALL menu tables');
            Log::warning('[EMERGENCY FIX 000] ================================================');

            // List of ALL possible menu-related tables
            $menuTables = [
                'menu_items',      // Must drop first (has foreign keys)
                'menus',           // Old menu table
                'vp_menu_items',   // VantaPress menu items
                'vp_menus'         // VantaPress menus table
            ];

            foreach ($menuTables as $table) {
                if (Schema::hasTable($table)) {
                    Log::warning("[EMERGENCY FIX 000] Found table: {$table} - DROPPING NOW");
                    
                    // Force drop with cascade
                    try {
                        DB::statement('SET FOREIGN_KEY_CHECKS=0');
                        Schema::dropIfExists($table);
                        DB::statement('SET FOREIGN_KEY_CHECKS=1');
                        
                        $result['tables_dropped'][] = $table;
                        Log::warning("[EMERGENCY FIX 000] ✓✓✓ DROPPED: {$table}");
                    } catch (Exception $dropError) {
                        Log::error("[EMERGENCY FIX 000] Failed to drop {$table}", [
                            'error' => $dropError->getMessage()
                        ]);
                    }
                } else {
                    Log::info("[EMERGENCY FIX 000] Table {$table} doesn't exist - OK");
                }
            }

            // Clean ALL menu-related migration entries regardless of table existence
            Log::warning("[EMERGENCY FIX 000] Cleaning ALL menu migration entries from tracking...");
            
            $menuMigrationPatterns = [
                '%create_menus_table%',
                '%create_menu_items_table%',
                '%create_vp_menus_tables%',
                '%add_page_id_to_menu_items_table%'
            ];

            $entriesRemoved = 0;
            foreach ($menuMigrationPatterns as $pattern) {
                $deleted = DB::table('migrations')
                    ->where('migration', 'like', $pattern)
                    ->delete();
                
                if ($deleted > 0) {
                    $entriesRemoved += $deleted;
                    Log::warning("[EMERGENCY FIX 000] ✓ Removed {$deleted} migration entry(ies) matching: {$pattern}");
                }
            }

            if (count($result['tables_dropped']) > 0 || $entriesRemoved > 0) {
                $result['executed'] = true;
                $result['message'] = sprintf(
                    'Emergency cleanup: Dropped %d table(s), removed %d migration entry(ies)',
                    count($result['tables_dropped']),
                    $entriesRemoved
                );
                
                Log::warning('[EMERGENCY FIX 000] ================================================');
                Log::warning('[EMERGENCY FIX 000] ✓✓✓ EMERGENCY CLEANUP COMPLETE');
                Log::warning('[EMERGENCY FIX 000] Tables dropped: ' . implode(', ', $result['tables_dropped']));
                Log::warning('[EMERGENCY FIX 000] Migration entries removed: ' . $entriesRemoved);
                Log::warning('[EMERGENCY FIX 000] ================================================');
            } else {
                $result['message'] = 'No cleanup needed - all tables clean';
                Log::info('[EMERGENCY FIX 000] No emergency action needed');
            }

            return $result;

        } catch (Exception $e) {
            Log::error('[EMERGENCY FIX 000] ❌❌❌ EMERGENCY FIX FAILED', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return [
                'executed' => false,
                'tables_dropped' => [],
                'message' => 'Emergency fix failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Determine if this emergency fix should run
     * 
     * ALWAYS CHECK - This is an emergency fix for production issues
     * 
     * @return bool
     */
    public function shouldRun(): bool
    {
        Log::warning('[EMERGENCY FIX 000] ================================================');
        Log::warning('[EMERGENCY FIX 000] EMERGENCY CHECK: Looking for menu table conflicts');
        Log::warning('[EMERGENCY FIX 000] ================================================');
        
        // Check if migrations table exists
        if (!Schema::hasTable('migrations')) {
            Log::info('[EMERGENCY FIX 000] Migrations table doesn\'t exist yet - SKIP');
            return false;
        }

        // Check if ANY menu table exists
        $menuTables = ['menu_items', 'menus', 'vp_menu_items', 'vp_menus'];
        $foundTables = [];
        
        foreach ($menuTables as $table) {
            if (Schema::hasTable($table)) {
                $foundTables[] = $table;
            }
        }

        if (count($foundTables) > 0) {
            Log::warning('[EMERGENCY FIX 000] ⚠️⚠️⚠️ MENU TABLES DETECTED');
            Log::warning('[EMERGENCY FIX 000] Found tables: ' . implode(', ', $foundTables));
            Log::warning('[EMERGENCY FIX 000] DECISION: WILL RUN - Emergency cleanup needed!');
            return true;
        }

        // Also check if there are orphaned migration entries
        $menuMigrationPatterns = [
            '%create_menus_table%',
            '%create_menu_items_table%',
            '%create_vp_menus_tables%'
        ];

        $hasOrphanedEntries = false;
        foreach ($menuMigrationPatterns as $pattern) {
            if (DB::table('migrations')->where('migration', 'like', $pattern)->exists()) {
                $hasOrphanedEntries = true;
                Log::warning('[EMERGENCY FIX 000] Found orphaned migration entry matching: ' . $pattern);
            }
        }

        if ($hasOrphanedEntries) {
            Log::warning('[EMERGENCY FIX 000] ⚠️ ORPHANED MIGRATION ENTRIES FOUND');
            Log::warning('[EMERGENCY FIX 000] DECISION: WILL RUN - Cleanup needed!');
            return true;
        }

        Log::info('[EMERGENCY FIX 000] No menu tables or orphaned entries found - SKIP');
        return false;
    }
};
