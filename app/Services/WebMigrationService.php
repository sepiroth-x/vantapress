<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

/**
 * Web-Based Migration Service
 * 
 * Allows running Laravel migrations from the browser without terminal access.
 * Essential for shared hosting environments where SSH/CLI is unavailable.
 * 
 * WordPress-inspired: Checks for pending migrations and runs them via admin UI.
 */
class WebMigrationService
{
    /**
     * Check if there are pending migrations that need to run
     * 
     * @return array
     */
    public function checkPendingMigrations(): array
    {
        try {
            // First, ensure migrations table exists
            if (!Schema::hasTable('migrations')) {
                return [
                    'pending' => true,
                    'count' => 'Unknown',
                    'message' => 'Migrations table not found. Database needs initialization.',
                    'migrations' => [],
                    'status' => 'needs_init'
                ];
            }

            // Get all migration files
            $migrationPath = database_path('migrations');
            $migrationFiles = glob($migrationPath . '/*.php');
            
            $allMigrations = [];
            foreach ($migrationFiles as $file) {
                $allMigrations[] = basename($file, '.php');
            }

            // Get executed migrations
            $executedMigrations = DB::table('migrations')
                ->pluck('migration')
                ->toArray();

            // Find pending migrations
            $pendingMigrations = array_diff($allMigrations, $executedMigrations);

            if (count($pendingMigrations) > 0) {
                Log::info('Pending migrations detected', [
                    'count' => count($pendingMigrations),
                    'migrations' => $pendingMigrations
                ]);

                return [
                    'pending' => true,
                    'count' => count($pendingMigrations),
                    'message' => count($pendingMigrations) . ' database update(s) available',
                    'migrations' => array_values($pendingMigrations),
                    'status' => 'pending'
                ];
            }

            return [
                'pending' => false,
                'count' => 0,
                'message' => 'Database is up to date',
                'migrations' => [],
                'status' => 'up_to_date'
            ];

        } catch (Exception $e) {
            Log::error('Failed to check pending migrations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'pending' => true,
                'count' => 'Error',
                'message' => 'Unable to check migration status: ' . $e->getMessage(),
                'migrations' => [],
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Execute migration fix scripts from database/migration-fixes directory
     * 
     * This system allows deploying fix scripts with updates that automatically
     * resolve migration conflicts without requiring manual user intervention.
     * 
     * Each fix script:
     * - Checks if it needs to run (shouldRun method)
     * - Executes fix logic (execute method)
     * - Returns detailed results
     * - Logs all actions
     * 
     * @return array Summary of fixes executed
     */
    protected function executeMigrationFixes(): array
    {
        $fixesPath = database_path('migration-fixes');
        $fixesExecuted = [];
        $fixesSkipped = [];
        
        // AGGRESSIVE LOGGING: Always log entry to this method
        Log::warning('[Migration Fixes] ========================================');
        Log::warning('[Migration Fixes] ENTERED executeMigrationFixes() method');
        Log::warning('[Migration Fixes] Looking for fixes at: ' . $fixesPath);
        Log::warning('[Migration Fixes] ========================================');
        
        try {
            // Check if migration-fixes directory exists
            $dirExists = file_exists($fixesPath);
            $isDir = is_dir($fixesPath);
            
            Log::warning('[Migration Fixes] Directory check:', [
                'path' => $fixesPath,
                'exists' => $dirExists ? 'YES' : 'NO',
                'is_directory' => $isDir ? 'YES' : 'NO'
            ]);
            
            if (!$dirExists || !$isDir) {
                Log::error('[Migration Fixes] ❌ CRITICAL: Directory NOT FOUND!');
                Log::error('[Migration Fixes] This is why fix script cannot run!');
                Log::error('[Migration Fixes] User must upload database/migration-fixes/ directory!');
                return [
                    'executed' => [],
                    'skipped' => [],
                    'total' => 0,
                    'message' => 'No migration fixes available'
                ];
            }
            
            Log::warning('[Migration Fixes] ✓ Directory exists, scanning for scripts...');

            Log::warning('[Migration Fixes] ✓ Directory exists, scanning for scripts...');

            // Get all PHP files in migration-fixes directory (sorted alphabetically)
            $fixFiles = glob($fixesPath . '/*.php');
            
            Log::warning('[Migration Fixes] Glob scan result:', [
                'pattern' => $fixesPath . '/*.php',
                'files_found' => count($fixFiles),
                'files' => $fixFiles ? array_map('basename', $fixFiles) : []
            ]);
            
            if (empty($fixFiles)) {
                Log::error('[Migration Fixes] ❌ No PHP files found in directory!');
                Log::error('[Migration Fixes] Expected: 001_drop_legacy_menu_tables.php');
                return [
                    'executed' => [],
                    'skipped' => [],
                    'total' => 0,
                    'message' => 'No migration fixes available'
                ];
            }

            sort($fixFiles); // Ensure alphabetical execution order

            Log::warning('[Migration Fixes] ✓✓✓ Found ' . count($fixFiles) . ' fix script(s) - WILL EXECUTE', [
                'scripts' => array_map('basename', $fixFiles)
            ]);

            // Execute each fix script
            foreach ($fixFiles as $fixFile) {
                $fixName = basename($fixFile, '.php');
                
                Log::warning("[Migration Fixes] ----------------------------------------");
                Log::warning("[Migration Fixes] Processing: {$fixName}");
                Log::warning("[Migration Fixes] File: {$fixFile}");
                
                try {
                    // Include the fix script (returns an anonymous class instance)
                    Log::warning("[Migration Fixes] Including script file...");
                    $fixInstance = include $fixFile;
                    Log::warning("[Migration Fixes] ✓ Script included successfully");

                    // Check if fix should run
                    Log::warning("[Migration Fixes] Calling shouldRun() method...");
                    if (method_exists($fixInstance, 'shouldRun') && !$fixInstance->shouldRun()) {
                        $fixesSkipped[] = $fixName;
                        Log::warning("[Migration Fixes] Script returned FALSE - skipping");
                        continue;
                    }
                    
                    Log::warning("[Migration Fixes] ✓✓✓ Script returned TRUE - EXECUTING!");

                    // Execute the fix
                    if (method_exists($fixInstance, 'execute')) {
                        Log::warning("[Migration Fixes] Calling execute() method...");
                        $result = $fixInstance->execute();
                        Log::warning("[Migration Fixes] Execute completed", $result);
                        
                        if ($result['executed'] ?? false) {
                            $fixesExecuted[] = [
                                'name' => $fixName,
                                'message' => $result['message'] ?? 'Executed successfully',
                                'details' => $result
                            ];
                            Log::warning("[Migration Fixes] ✓ SUCCESS: {$fixName}", $result);
                        } else {
                            $fixesSkipped[] = $fixName;
                            Log::info("[Migration Fixes] Skipped: {$fixName} - " . ($result['message'] ?? 'No action needed'));
                        }
                    } else {
                        Log::error("[Migration Fixes] ❌ Invalid script: {$fixName} (missing execute method)");
                    }

                } catch (Exception $e) {
                    Log::error("[Migration Fixes] ❌ ERROR executing fix: {$fixName}", [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue with other fixes even if one fails
                }
            }

            $summary = [
                'executed' => $fixesExecuted,
                'skipped' => $fixesSkipped,
                'total' => count($fixesExecuted),
                'message' => count($fixesExecuted) > 0 
                    ? 'Executed ' . count($fixesExecuted) . ' migration fix(es)' 
                    : 'No migration fixes needed'
            ];

            Log::warning('[Migration Fixes] ========================================');
            Log::warning('[Migration Fixes] COMPLETED - Summary:', $summary);
            Log::warning('[Migration Fixes] ========================================');
            
            return $summary;

        } catch (Exception $e) {
            Log::error('[Migration Fixes] Failed to execute fixes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'executed' => $fixesExecuted,
                'skipped' => $fixesSkipped,
                'total' => count($fixesExecuted),
                'message' => 'Fix execution had errors: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Legacy method for backward compatibility
     * Now redirects to the new script-based system
     * 
     * @deprecated Use executeMigrationFixes() instead
     */
    protected function fixConflictingTables(): void
    {
        // This method is now handled by migration fix scripts
        // Kept for backward compatibility
        Log::info('[Migration Fixes] Legacy fixConflictingTables() called - now using script-based system');
    }

    /**
     * Run pending migrations via web interface
     * 
     * @return array
     */
    public function runMigrations(): array
    {
        try {
            // Get pending migrations before running
            $beforeCheck = $this->checkPendingMigrations();
            
            if (!$beforeCheck['pending']) {
                return [
                    'success' => true,
                    'message' => 'Database is already up to date',
                    'migrations_run' => [],
                    'count' => 0
                ];
            }

            $pendingMigrations = $beforeCheck['migrations'];

            Log::info('Running web-based migrations', [
                'pending_count' => count($pendingMigrations),
                'migrations' => $pendingMigrations
            ]);

            // STEP 1: Execute migration fix scripts (automatic conflict resolution)
            // This prevents "table already exists" errors on production deployments
            $fixResults = $this->executeMigrationFixes();

            // STEP 2: Run migrations with force flag (bypasses production check)
            $exitCode = Artisan::call('migrate', ['--force' => true]);

            // Get migrations that were actually run
            $afterCheck = $this->checkPendingMigrations();
            $migrationsRun = array_diff($pendingMigrations, $afterCheck['migrations']);

            if ($exitCode === 0) {
                Log::info('Web-based migrations completed successfully', [
                    'migrations_run' => $migrationsRun,
                    'count' => count($migrationsRun),
                    'fixes_executed' => $fixResults['total'] ?? 0
                ]);

                // Build success message with fix details
                $message = 'Database updated successfully! ' . count($migrationsRun) . ' migration(s) executed.';
                if (($fixResults['total'] ?? 0) > 0) {
                    $message .= ' (' . $fixResults['total'] . ' fix(es) applied automatically)';
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'migrations_run' => array_values($migrationsRun),
                    'count' => count($migrationsRun),
                    'fixes_applied' => $fixResults,
                    'output' => Artisan::output()
                ];
            } else {
                Log::error('Web-based migrations failed', [
                    'exit_code' => $exitCode,
                    'output' => Artisan::output()
                ]);

                return [
                    'success' => false,
                    'message' => 'Migration failed. Check logs for details.',
                    'migrations_run' => [],
                    'count' => 0,
                    'error' => Artisan::output()
                ];
            }

        } catch (Exception $e) {
            Log::error('Web-based migration execution failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Migration error: ' . $e->getMessage(),
                'migrations_run' => [],
                'count' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get migration history
     * 
     * @return array
     */
    public function getMigrationHistory(): array
    {
        try {
            if (!Schema::hasTable('migrations')) {
                return [
                    'success' => false,
                    'message' => 'Migrations table does not exist',
                    'history' => []
                ];
            }

            $history = DB::table('migrations')
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($migration) {
                    return [
                        'id' => $migration->id,
                        'migration' => $migration->migration,
                        'batch' => $migration->batch
                    ];
                })
                ->toArray();

            return [
                'success' => true,
                'message' => 'Retrieved ' . count($history) . ' migration records',
                'history' => $history
            ];

        } catch (Exception $e) {
            Log::error('Failed to retrieve migration history', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error retrieving migration history: ' . $e->getMessage(),
                'history' => []
            ];
        }
    }

    /**
     * Check if migrations table exists
     * 
     * @return bool
     */
    public function migrationsTableExists(): bool
    {
        try {
            return Schema::hasTable('migrations');
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get database migration status summary
     * 
     * @return array
     */
    public function getStatus(): array
    {
        $pendingCheck = $this->checkPendingMigrations();
        $historyCheck = $this->getMigrationHistory();

        return [
            'migrations_table_exists' => $this->migrationsTableExists(),
            'pending_migrations' => $pendingCheck,
            'total_executed' => count($historyCheck['history'] ?? []),
            'status' => $pendingCheck['status'],
            'message' => $pendingCheck['message']
        ];
    }
}
