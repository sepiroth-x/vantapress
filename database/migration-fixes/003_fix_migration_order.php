<?php

/**
 * Migration Fix 003: Fix Migration Order Issues
 * 
 * Problem: Migrations with dates 2025_12_12_* ran before 2025_12_03_* migrations,
 * causing foreign key errors because dependent tables don't exist yet.
 * 
 * Solution:
 * 1. Roll back the failed migrations
 * 2. Remove their entries from migrations table
 * 3. They will re-run in correct order after base tables are created
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class
{
    public function __invoke(): bool
    {
        Log::warning('[Migration Fix 003] ========================================');
        Log::warning('[Migration Fix 003] Fixing migration order issues');
        Log::warning('[Migration Fix 003] ========================================');

        try {
            // Failed migrations that need to be re-run
            $failedMigrations = [
                '2025_12_12_000001_create_vp_groups_tables',
                '2025_12_12_000002_add_privacy_to_profiles',
                '2025_12_12_131737_add_url_preview_to_vp_posts_table',
            ];

            $removedCount = 0;

            foreach ($failedMigrations as $migration) {
                // Check if this migration entry exists in the migrations table
                $exists = DB::table('migrations')
                    ->where('migration', $migration)
                    ->exists();

                if ($exists) {
                    // Remove the entry so it can re-run
                    DB::table('migrations')
                        ->where('migration', $migration)
                        ->delete();

                    Log::info('[Migration Fix 003] Removed failed migration entry', [
                        'migration' => $migration
                    ]);

                    $removedCount++;
                }
            }

            // Drop incomplete tables if they exist
            $tablesToDrop = [
                'vp_group_posts',
                'vp_group_members',
                'vp_groups',
            ];

            foreach ($tablesToDrop as $table) {
                if (Schema::hasTable($table)) {
                    Schema::dropIfExists($table);
                    Log::info('[Migration Fix 003] Dropped incomplete table', ['table' => $table]);
                }
            }

            if ($removedCount > 0) {
                Log::warning('[Migration Fix 003] DECISION: Fixed migration order', [
                    'removed_entries' => $removedCount,
                    'message' => 'Migrations will re-run in correct order'
                ]);
                return true;
            }

            Log::info('[Migration Fix 003] DECISION: SKIP - No failed migrations found');
            return false;

        } catch (\Exception $e) {
            Log::error('[Migration Fix 003] Error during fix', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
};
