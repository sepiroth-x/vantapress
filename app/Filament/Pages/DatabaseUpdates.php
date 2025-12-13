<?php

namespace App\Filament\Pages;

use App\Services\WebMigrationService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Database Updates Page
 * 
 * WordPress-style database update interface for shared hosting environments.
 * Allows running Laravel migrations without terminal/SSH access.
 */
class DatabaseUpdates extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static string $view = 'filament.pages.database-updates';

    protected static ?string $navigationLabel = 'Database Updates';

    protected static ?string $title = 'Database Updates';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 99;

    public array $migrationStatus = [];
    public array $pendingMigrations = [];
    public array $migrationHistory = [];
    public array $availableFixScripts = [];
    public array $failedFixScripts = [];
    public array $recentErrors = [];
    public array $moduleMigrations = [];
    public array $themeMigrations = [];
    public int $fixScriptCount = 0;
    public int $failedScriptCount = 0;
    public int $totalModulePending = 0;
    public int $totalThemePending = 0;
    public bool $hasPendingMigrations = false;
    public bool $hasFixScripts = false;
    public bool $hasFailedScripts = false;
    public bool $hasRecentErrors = false;
    public bool $hasModuleMigrations = false;
    public bool $hasThemeMigrations = false;
    public string $statusMessage = '';
    public bool $isRunning = false;

    public function mount(): void
    {
        $this->checkMigrationStatus();
    }

    /**
     * Check current migration status
     */
    public function checkMigrationStatus(): void
    {
        $service = new WebMigrationService();
        $status = $service->getStatus();

        $this->migrationStatus = $status;
        $this->hasPendingMigrations = $status['pending_migrations']['pending'] ?? false;
        $this->statusMessage = $status['message'] ?? '';
        $this->pendingMigrations = $status['pending_migrations']['migrations'] ?? [];

        // Get fix scripts information
        $fixScripts = $status['fix_scripts'] ?? [];
        $this->hasFixScripts = $fixScripts['available'] ?? false;
        $this->fixScriptCount = $fixScripts['count'] ?? 0;
        $this->availableFixScripts = $fixScripts['scripts'] ?? [];

        // Get failed scripts information
        $failedScripts = $status['failed_scripts'] ?? [];
        $this->hasFailedScripts = $failedScripts['has_failures'] ?? false;
        $this->failedScriptCount = $failedScripts['count'] ?? 0;
        $this->failedFixScripts = $failedScripts['scripts'] ?? [];

        // Get recent errors information
        $recentErrors = $status['recent_errors'] ?? [];
        $this->hasRecentErrors = $recentErrors['has_errors'] ?? false;
        $this->recentErrors = $recentErrors['errors'] ?? [];

        // Get module migrations information
        $moduleMigrations = $status['module_migrations'] ?? [];
        $this->hasModuleMigrations = $moduleMigrations['has_pending'] ?? false;
        $this->totalModulePending = $moduleMigrations['total_pending'] ?? 0;
        $this->moduleMigrations = $moduleMigrations['modules'] ?? [];

        // Get theme migrations information
        $themeMigrations = $status['theme_migrations'] ?? [];
        $this->hasThemeMigrations = $themeMigrations['has_pending'] ?? false;
        $this->totalThemePending = $themeMigrations['total_pending'] ?? 0;
        $this->themeMigrations = $themeMigrations['themes'] ?? [];

        // Get migration history
        $historyResult = $service->getMigrationHistory();
        $this->migrationHistory = $historyResult['history'] ?? [];

        Log::info('Database update page loaded', [
            'pending_count' => count($this->pendingMigrations),
            'has_pending' => $this->hasPendingMigrations,
            'fix_scripts_count' => $this->fixScriptCount,
            'has_fix_scripts' => $this->hasFixScripts,
            'failed_scripts_count' => $this->failedScriptCount,
            'has_failed_scripts' => $this->hasFailedScripts,
            'module_migrations' => $this->totalModulePending,
            'theme_migrations' => $this->totalThemePending
        ]);
    }

    /**
     * Run pending migrations
     */
    public function runMigrations(): void
    {
        try {
            $this->isRunning = true;

            // AGGRESSIVE: Clear all caches before running migrations
            Log::info('Clearing all caches before migration', [
                'user_id' => auth()->id()
            ]);
            
            try {
                \Artisan::call('config:clear');
                \Artisan::call('cache:clear');
                \Artisan::call('view:clear');
                \Artisan::call('route:clear');
                
                // Clear OPcache if available
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                    Log::info('OPcache cleared successfully');
                }
                
                // Clear bootstrap cache files
                $bootstrapCache = base_path('bootstrap/cache');
                if (file_exists($bootstrapCache . '/config.php')) {
                    @unlink($bootstrapCache . '/config.php');
                }
                if (file_exists($bootstrapCache . '/services.php')) {
                    @unlink($bootstrapCache . '/services.php');
                }
                if (file_exists($bootstrapCache . '/packages.php')) {
                    @unlink($bootstrapCache . '/packages.php');
                }
                
                Log::info('All caches cleared successfully');
            } catch (\Exception $cacheError) {
                Log::warning('Cache clearing had errors (continuing)', [
                    'error' => $cacheError->getMessage()
                ]);
            }

            Log::info('User initiated web-based migration', [
                'user_id' => auth()->id(),
                'pending_count' => count($this->pendingMigrations),
                'module_migrations' => $this->totalModulePending,
                'theme_migrations' => $this->totalThemePending
            ]);

            $service = new WebMigrationService();
            $result = $service->runMigrations();
            
            // Also run pending module and theme migrations
            $moduleCount = $this->runModuleMigrations();
            $themeCount = $this->runThemeMigrations();

            if ($result['success']) {
                // Build detailed success message
                $message = $result['message'];
                $fixesApplied = $result['fixes_applied'] ?? [];
                
                // Add module migration results
                if ($moduleCount > 0) {
                    $message .= "\n\n📦 Module Migrations: {$moduleCount} migration(s) executed";
                }
                
                // Add theme migration results
                if ($themeCount > 0) {
                    $message .= "\n\n🎨 Theme Migrations: {$themeCount} migration(s) executed";
                }
                
                // Add fix script details if any were executed
                if (($fixesApplied['total'] ?? 0) > 0) {
                    $fixDetails = [];
                    foreach ($fixesApplied['executed'] as $fix) {
                        $fixDetails[] = '• ' . ($fix['message'] ?? $fix['name']);
                    }
                    if (!empty($fixDetails)) {
                        $message .= "\n\n🔧 Fix Scripts Applied:\n" . implode("\n", $fixDetails);
                    }
                }

                Notification::make()
                    ->title('Database Updated Successfully')
                    ->body($message)
                    ->success()
                    ->duration(15000)
                    ->send();

                Log::info('Web-based migrations completed', [
                    'migrations_run' => $result['migrations_run'],
                    'count' => $result['count'],
                    'fixes_applied' => $fixesApplied['total'] ?? 0
                ]);
            } else {
                Notification::make()
                    ->title('Migration Failed')
                    ->body($result['message'])
                    ->danger()
                    ->duration(15000)
                    ->send();

                Log::error('Web-based migrations failed', [
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            }

            // Refresh status
            $this->checkMigrationStatus();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Running Migrations')
                ->body('An unexpected error occurred: ' . $e->getMessage())
                ->danger()
                ->duration(15000)
                ->send();

            Log::error('Web-based migration exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        } finally {
            $this->isRunning = false;
        }
    }

    /**
     * Refresh migration status
     */
    public function refreshStatus(): void
    {
        $this->checkMigrationStatus();

        Notification::make()
            ->title('Status Refreshed')
            ->body($this->statusMessage)
            ->success()
            ->send();
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        if ($this->hasPendingMigrations || $this->hasFixScripts) {
            return 'warning';
        }

        return 'success';
    }

    /**
     * Get status badge text
     */
    public function getStatusText(): string
    {
        $parts = [];
        
        if ($this->hasPendingMigrations) {
            $parts[] = count($this->pendingMigrations) . ' migration(s)';
        }
        
        if ($this->hasFixScripts) {
            $parts[] = $this->fixScriptCount . ' fix script(s)';
        }
        
        if (empty($parts)) {
            return 'Up to date';
        }
        
        return implode(' + ', $parts) . ' available';
    }

    /**
     * Format migration name for display
     */
    public function formatMigrationName(string $migration): string
    {
        // Remove timestamp prefix (2025_12_06_162738_)
        $name = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migration);
        
        // Convert snake_case to Title Case
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);

        return $name;
    }

    /**
     * Run pending module migrations
     */
    protected function runModuleMigrations(): int
    {
        if (!$this->hasModuleMigrations) {
            return 0;
        }

        $totalExecuted = 0;

        try {
            $modulesPath = base_path('Modules');
            $executedMigrations = \DB::table('migrations')->pluck('migration')->toArray();
            $batch = \DB::table('migrations')->max('batch') + 1;

            foreach ($this->moduleMigrations as $module) {
                $migrationPath = $module['path'];
                
                foreach ($module['pending_migrations'] as $migrationName) {
                    $file = $migrationPath . '/' . $migrationName . '.php';
                    
                    if (!file_exists($file)) {
                        continue;
                    }

                    try {
                        $migration = include $file;
                        
                        if (method_exists($migration, 'up')) {
                            $migration->up();
                            
                            \DB::table('migrations')->insert([
                                'migration' => $migrationName,
                                'batch' => $batch
                            ]);
                            
                            $totalExecuted++;
                            Log::info("Module migration executed: {$migrationName} (from {$module['name']})");
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to run module migration {$migrationName}: " . $e->getMessage());
                    }
                }
            }

            if ($totalExecuted > 0) {
                Log::info("Module migrations completed: {$totalExecuted} executed");
            }

            return $totalExecuted;

        } catch (\Exception $e) {
            Log::error('Error running module migrations: ' . $e->getMessage());
            return $totalExecuted;
        }
    }

    /**
     * Run pending theme migrations
     */
    protected function runThemeMigrations(): int
    {
        if (!$this->hasThemeMigrations) {
            return 0;
        }

        $totalExecuted = 0;

        try {
            $themesPath = base_path('themes');
            $executedMigrations = \DB::table('migrations')->pluck('migration')->toArray();
            $batch = \DB::table('migrations')->max('batch') + 1;

            foreach ($this->themeMigrations as $theme) {
                $migrationPath = $theme['path'];
                
                foreach ($theme['pending_migrations'] as $migrationName) {
                    $file = $migrationPath . '/' . $migrationName . '.php';
                    
                    if (!file_exists($file)) {
                        continue;
                    }

                    try {
                        $migration = include $file;
                        
                        if (method_exists($migration, 'up')) {
                            $migration->up();
                            
                            \DB::table('migrations')->insert([
                                'migration' => $migrationName,
                                'batch' => $batch
                            ]);
                            
                            $totalExecuted++;
                            Log::info("Theme migration executed: {$migrationName} (from {$theme['name']})");
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to run theme migration {$migrationName}: " . $e->getMessage());
                    }
                }
            }

            if ($totalExecuted > 0) {
                Log::info("Theme migrations completed: {$totalExecuted} executed");
            }

            return $totalExecuted;

        } catch (\Exception $e) {
            Log::error('Error running theme migrations: ' . $e->getMessage());
            return $totalExecuted;
        }
    }

    /**
     * Check if user has permission to run migrations
     */
    public static function canAccess(): bool
    {
        // Allow all authenticated admin users to access database updates
        // In production, you may want to restrict this to specific roles
        return auth()->check();
    }
}
