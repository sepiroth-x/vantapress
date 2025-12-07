<?php

/**
 * Migration Fix: Drop Legacy Module Tables (VPToDoList & TheVillainTerminal)
 * 
 * Version: v1.0.52
 * Issue: Module tables (vp_projects, vp_tasks, villain_terminal_*) may exist from 
 *        previous installations but aren't tracked in migrations table, causing conflicts
 * Solution: Drop these tables if they exist but aren't tracked in migrations
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
            $tablesToCheck = [
                'vp_projects' => '%create_vp_projects_table%',
                'vp_tasks' => '%create_vp_tasks_table%',
                'villain_terminal_commands' => '%create_villain_terminal%',
                'villain_terminal_history' => '%create_villain_terminal%',
            ];

            foreach ($tablesToCheck as $table => $migrationPattern) {
                if (Schema::hasTable($table)) {
                    // Check if migration is tracked
                    $migrationExists = DB::table('migrations')
                        ->where('migration', 'like', $migrationPattern)
                        ->exists();
                    
                    if (!$migrationExists) {
                        // Safe to drop - table exists but migration doesn't
                        Schema::dropIfExists($table);
                        $result['tables_dropped'][] = $table;
                        Log::info('[Migration Fix] Dropped legacy module table', [
                            'table' => $table,
                            'reason' => 'Table exists but migration not tracked'
                        ]);
                    }
                }
            }

            if (count($result['tables_dropped']) > 0) {
                $result['executed'] = true;
                $result['message'] = 'Dropped ' . count($result['tables_dropped']) . ' legacy module table(s): ' . implode(', ', $result['tables_dropped']);
                Log::info('[Migration Fix] Legacy module tables cleanup completed', $result);
            } else {
                $result['message'] = 'No legacy module tables found - fix not needed';
            }

            return $result;

        } catch (Exception $e) {
            Log::error('[Migration Fix] Failed to execute module tables cleanup', [
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

        // Check if any module tables exist without tracked migrations
        $tablesToCheck = [
            'vp_projects' => '%create_vp_projects_table%',
            'vp_tasks' => '%create_vp_tasks_table%',
            'villain_terminal_commands' => '%create_villain_terminal%',
            'villain_terminal_history' => '%create_villain_terminal%',
        ];

        foreach ($tablesToCheck as $table => $migrationPattern) {
            if (Schema::hasTable($table)) {
                $migrationExists = DB::table('migrations')
                    ->where('migration', 'like', $migrationPattern)
                    ->exists();

                // Run if table exists but migration doesn't
                if (!$migrationExists) {
                    return true;
                }
            }
        }

        return false;
    }
};
