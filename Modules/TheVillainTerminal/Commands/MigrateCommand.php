<?php

namespace Modules\TheVillainTerminal\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * The Villain Terminal - Migration Command
 * 
 * Runs database migrations WITHOUT using php artisan.
 * Scans core and module migrations, applies them using Laravel's database layer.
 */
class MigrateCommand
{
    /**
     * Execute the migration command
     * 
     * @param array $args
     * @return array
     */
    public function handle(array $args): array
    {
        $output = [];
        $output[] = "<span style='color: #00ff00;'>Starting migration process...</span>";
        $output[] = "";

        // Ensure migrations table exists
        if (!$this->ensureMigrationsTable()) {
            $output[] = "<span style='color: #ff0000;'>ERROR: Could not create migrations table</span>";
            return ['output' => implode("\n", $output), 'success' => false];
        }

        // Scan for all migrations
        $allMigrations = $this->scanMigrations();
        $output[] = "Found " . count($allMigrations) . " migration file(s)";
        $output[] = "";

        // Get already executed migrations
        $executedMigrations = DB::table('migrations')
            ->pluck('migration')
            ->toArray();

        // Find pending migrations
        $pendingMigrations = array_diff(array_keys($allMigrations), $executedMigrations);

        if (empty($pendingMigrations)) {
            $output[] = "<span style='color: #00ff00;'>✓ Database is up to date</span>";
            $output[] = "Nothing to migrate.";
            return ['output' => implode("\n", $output), 'success' => true];
        }

        $output[] = "<span style='color: #ffff00;'>Found " . count($pendingMigrations) . " pending migration(s):</span>";
        foreach ($pendingMigrations as $migration) {
            $output[] = "  - " . $migration;
        }
        $output[] = "";

        // Get current batch number
        $batch = DB::table('migrations')->max('batch') + 1;

        // Execute pending migrations
        $executed = 0;
        $failed = 0;

        foreach ($pendingMigrations as $migrationName) {
            $migrationPath = $allMigrations[$migrationName];
            
            try {
                $output[] = "Migrating: <span style='color: #00ffff;'>{$migrationName}</span>";
                
                // Include the migration file
                $migration = include $migrationPath;
                
                // Execute the up() method
                if (method_exists($migration, 'up')) {
                    $migration->up();
                }
                
                // Record in migrations table
                DB::table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch' => $batch
                ]);
                
                $output[] = "<span style='color: #00ff00;'>✓ Migrated: {$migrationName}</span>";
                $output[] = "";
                $executed++;
                
            } catch (\Exception $e) {
                $output[] = "<span style='color: #ff0000;'>✗ Failed: {$migrationName}</span>";
                $output[] = "<span style='color: #ff0000;'>Error: " . $e->getMessage() . "</span>";
                $output[] = "";
                $failed++;
                
                Log::error("[Villain Terminal] Migration failed", [
                    'migration' => $migrationName,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Summary
        $output[] = "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━";
        $output[] = "<span style='color: #00ff00;'>Migration Summary:</span>";
        $output[] = "  Executed: <span style='color: #00ff00;'>{$executed}</span>";
        if ($failed > 0) {
            $output[] = "  Failed: <span style='color: #ff0000;'>{$failed}</span>";
        }
        $output[] = "  Batch: #{$batch}";

        return [
            'output' => implode("\n", $output),
            'success' => $failed === 0,
            'executed' => $executed,
            'failed' => $failed
        ];
    }

    /**
     * Ensure migrations table exists
     * 
     * @return bool
     */
    protected function ensureMigrationsTable(): bool
    {
        try {
            if (!Schema::hasTable('migrations')) {
                Schema::create('migrations', function (Blueprint $table) {
                    $table->id();
                    $table->string('migration');
                    $table->integer('batch');
                });
                
                Log::info("[Villain Terminal] Created migrations table");
            }
            return true;
        } catch (\Exception $e) {
            Log::error("[Villain Terminal] Failed to create migrations table", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Scan all migration directories
     * 
     * @return array Array of migration name => file path
     */
    protected function scanMigrations(): array
    {
        $migrations = [];

        // Scan core migrations
        $corePath = database_path('migrations');
        if (is_dir($corePath)) {
            $files = glob($corePath . '/*.php');
            foreach ($files as $file) {
                $name = basename($file, '.php');
                $migrations[$name] = $file;
            }
        }

        // Scan module migrations
        $modulesPath = base_path('Modules');
        if (is_dir($modulesPath)) {
            $moduleDirectories = glob($modulesPath . '/*', GLOB_ONLYDIR);
            
            foreach ($moduleDirectories as $moduleDir) {
                $moduleMigrationPath = $moduleDir . '/migrations';
                
                if (is_dir($moduleMigrationPath)) {
                    $files = glob($moduleMigrationPath . '/*.php');
                    
                    foreach ($files as $file) {
                        $name = basename($file, '.php');
                        $migrations[$name] = $file;
                    }
                }
            }
        }

        // Sort by migration name (which includes timestamp)
        ksort($migrations);

        return $migrations;
    }
}
