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
    public bool $hasPendingMigrations = false;
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

        // Get migration history
        $historyResult = $service->getMigrationHistory();
        $this->migrationHistory = $historyResult['history'] ?? [];

        Log::info('Database update page loaded', [
            'pending_count' => count($this->pendingMigrations),
            'has_pending' => $this->hasPendingMigrations
        ]);
    }

    /**
     * Run pending migrations
     */
    public function runMigrations(): void
    {
        try {
            $this->isRunning = true;

            Log::info('User initiated web-based migration', [
                'user_id' => auth()->id(),
                'pending_count' => count($this->pendingMigrations)
            ]);

            $service = new WebMigrationService();
            $result = $service->runMigrations();

            if ($result['success']) {
                Notification::make()
                    ->title('Database Updated Successfully')
                    ->body($result['message'])
                    ->success()
                    ->duration(10000)
                    ->send();

                Log::info('Web-based migrations completed', [
                    'migrations_run' => $result['migrations_run'],
                    'count' => $result['count']
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
        if ($this->hasPendingMigrations) {
            return 'warning';
        }

        return 'success';
    }

    /**
     * Get status badge text
     */
    public function getStatusText(): string
    {
        if ($this->hasPendingMigrations) {
            return count($this->pendingMigrations) . ' update(s) available';
        }

        return 'Up to date';
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
     * Check if user has permission to run migrations
     */
    public static function canAccess(): bool
    {
        // Allow all authenticated admin users to access database updates
        // In production, you may want to restrict this to specific roles
        return auth()->check();
    }
}
