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
            // Check if tables/columns already exist (successful previous migration)
            $groupsTableExists = Schema::hasTable('vp_groups');
            $groupPostsTableExists = Schema::hasTable('vp_group_posts');
            $privacyColumnExists = Schema::hasTable('vp_user_profiles') && 
                                   Schema::hasColumn('vp_user_profiles', 'privacy');
            $urlPreviewColumnExists = Schema::hasTable('vp_posts') && 
                                      Schema::hasColumn('vp_posts', 'url_preview');

            // If ALL features exist, migrations completed successfully - skip fix
            if ($groupsTableExists && $groupPostsTableExists && $privacyColumnExists && $urlPreviewColumnExists) {
                Log::info('[Migration Fix 003] DECISION: SKIP - All migrations completed successfully', [
                    'vp_groups' => 'exists',
                    'vp_group_posts' => 'exists',
                    'privacy_column' => 'exists',
                    'url_preview_column' => 'exists',
                    'reason' => 'Update scenario - migrations were successful in previous version'
                ]);
                return false;
            }

            // Some migrations failed or partially completed - need to clean up and re-run
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
