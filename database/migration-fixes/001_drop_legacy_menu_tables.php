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
            // Check for legacy menus tables (v1.0.41 and earlier)
            // Drop menu_items first (foreign key dependency)
            if (Schema::hasTable('menu_items')) {
                $migrationExists = DB::table('migrations')
                    ->where('migration', 'like', '%create_menu_items_table')
                    ->exists();
                
                if (!$migrationExists) {
                    Schema::dropIfExists('menu_items');
                    $result['tables_dropped'][] = 'menu_items';
                    Log::info('[Migration Fix] Dropped legacy table: menu_items');
                }
            }

            // Drop menus table
            if (Schema::hasTable('menus')) {
                $migrationExists = DB::table('migrations')
                    ->where('migration', 'like', '%create_menus_table')
                    ->exists();
                
                if (!$migrationExists) {
                    Schema::dropIfExists('menus');
                    $result['tables_dropped'][] = 'menus';
                    Log::info('[Migration Fix] Dropped legacy table: menus');
                }
            }

            if (count($result['tables_dropped']) > 0) {
                $result['executed'] = true;
                $result['message'] = 'Dropped ' . count($result['tables_dropped']) . ' legacy table(s): ' . implode(', ', $result['tables_dropped']);
                Log::info('[Migration Fix] Successfully fixed legacy menu tables', $result);
            } else {
                $result['message'] = 'No legacy tables found - fix not needed';
            }

            return $result;

        } catch (Exception $e) {
            Log::error('[Migration Fix] Failed to execute fix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
        // Only run if migrations table exists (system is initialized)
        if (!Schema::hasTable('migrations')) {
            return false;
        }

        // Check if legacy tables exist but aren't tracked
        if (Schema::hasTable('menu_items') || Schema::hasTable('menus')) {
            $menuItemsMigrationExists = DB::table('migrations')
                ->where('migration', 'like', '%create_menu_items_table')
                ->exists();
            
            $menusMigrationExists = DB::table('migrations')
                ->where('migration', 'like', '%create_menus_table')
                ->exists();

            // Run if tables exist but migrations don't
            return !$menuItemsMigrationExists || !$menusMigrationExists;
        }

        return false;
    }
};
