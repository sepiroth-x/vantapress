<?php

/**
 * Migration Fix: Drop Legacy Menu Tables (v1.0.41 and earlier)
 * 
 * Version: v1.0.42
 * Issue: Tables 'menus' and 'menu_items' exist physically but not tracked in migrations
 * Solution: Drop these tables before running migrations
 * 
 * This fix runs ONCE automatically before migrations.
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
            'tables_dropped' => [],
            'message' => ''
        ];

        try {
            Log::info('[Migration Fix 001] Starting execution - Drop legacy menu tables');
            Log::info('[Migration Fix 001] Checking for legacy tables...', [
                'menu_items_exists' => Schema::hasTable('menu_items'),
                'menus_exists' => Schema::hasTable('menus')
            ]);

            // Check for legacy menus tables (v1.0.41 and earlier)
            // Drop menu_items first (foreign key dependency)
            if (Schema::hasTable('menu_items')) {
                $migrationExists = DB::table('migrations')
                    ->where('migration', 'like', '%create_menu_items_table')
                    ->exists();
                
                Log::info('[Migration Fix 001] menu_items table found', [
                    'migration_tracked' => $migrationExists
                ]);
                
                if (!$migrationExists) {
                    Log::info('[Migration Fix 001] Dropping untracked menu_items table...');
                    Schema::dropIfExists('menu_items');
                    $result['tables_dropped'][] = 'menu_items';
                    Log::info('[Migration Fix 001] ✓ Dropped legacy table: menu_items');
                }
            }

            // Drop menus table
            if (Schema::hasTable('menus')) {
                $migrationExists = DB::table('migrations')
                    ->where('migration', 'like', '%create_menus_table')
                    ->exists();
                
                Log::info('[Migration Fix 001] menus table found', [
                    'migration_tracked' => $migrationExists
                ]);
                
                if (!$migrationExists) {
                    Log::info('[Migration Fix 001] Dropping untracked menus table...');
                    Schema::dropIfExists('menus');
                    $result['tables_dropped'][] = 'menus';
                    Log::info('[Migration Fix 001] ✓ Dropped legacy table: menus');
                }
            }

            if (count($result['tables_dropped']) > 0) {
                $result['executed'] = true;
                $result['message'] = 'Dropped ' . count($result['tables_dropped']) . ' legacy table(s): ' . implode(', ', $result['tables_dropped']);
                Log::info('[Migration Fix 001] ✓ Successfully fixed legacy menu tables', $result);
            } else {
                $result['message'] = 'No legacy tables found - fix not needed';
                Log::info('[Migration Fix 001] No action needed - tables already tracked or don\'t exist');
            }

            return $result;

        } catch (Exception $e) {
            Log::error('[Migration Fix 001] ✗ Failed to execute fix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return [
                'executed' => false,
                'tables_dropped' => [],
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
        Log::info('[Migration Fix 001] Checking if fix should run...');
        
        // Only run if migrations table exists (system is initialized)
        if (!Schema::hasTable('migrations')) {
            Log::info('[Migration Fix 001] Skipping - migrations table does not exist yet');
            return false;
        }

        // Check if legacy tables exist but aren't tracked
        $menuItemsExists = Schema::hasTable('menu_items');
        $menusExists = Schema::hasTable('menus');
        
        Log::info('[Migration Fix 001] Table existence check', [
            'menu_items_exists' => $menuItemsExists,
            'menus_exists' => $menusExists
        ]);
        
        if ($menuItemsExists || $menusExists) {
            $menuItemsMigrationExists = DB::table('migrations')
                ->where('migration', 'like', '%create_menu_items_table')
                ->exists();
            
            $menusMigrationExists = DB::table('migrations')
                ->where('migration', 'like', '%create_menus_table')
                ->exists();

            Log::info('[Migration Fix 001] Migration tracking check', [
                'menu_items_tracked' => $menuItemsMigrationExists,
                'menus_tracked' => $menusMigrationExists
            ]);

            // Run if tables exist but migrations don't
            $shouldRun = !$menuItemsMigrationExists || !$menusMigrationExists;
            
            Log::info('[Migration Fix 001] Decision: ' . ($shouldRun ? 'SHOULD RUN' : 'SKIP'), [
                'reason' => $shouldRun 
                    ? 'Legacy tables exist without migration tracking'
                    : 'All tables are properly tracked'
            ]);
            
            return $shouldRun;
        }

        Log::info('[Migration Fix 001] Skipping - no legacy tables found');
        return false;
    }
};
