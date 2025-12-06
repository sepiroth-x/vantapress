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

            // Run migrations with force flag (bypasses production check)
            $exitCode = Artisan::call('migrate', ['--force' => true]);

            // Get migrations that were actually run
            $afterCheck = $this->checkPendingMigrations();
            $migrationsRun = array_diff($pendingMigrations, $afterCheck['migrations']);

            if ($exitCode === 0) {
                Log::info('Web-based migrations completed successfully', [
                    'migrations_run' => $migrationsRun,
                    'count' => count($migrationsRun)
                ]);

                return [
                    'success' => true,
                    'message' => 'Database updated successfully! ' . count($migrationsRun) . ' migration(s) executed.',
                    'migrations_run' => array_values($migrationsRun),
                    'count' => count($migrationsRun),
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
