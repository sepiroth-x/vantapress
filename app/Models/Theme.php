<?php
/**
 * TCC School CMS - Theme Model
 * 
 * Eloquent model for managing theme metadata and activation status.
 * 
 * @package TCC_School_CMS
 * @subpackage Models
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * @version 1.0.0
 * @license Commercial / Paid
 * 
 * Copyright (c) 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * All Rights Reserved.
 * 
 * Contact Information:
 * Email: chardy.tsadiq02@gmail.com
 * Mobile: +63 915 0388 448
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'author',
        'author_email',
        'path',
        'screenshot',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    /**
     * Check if theme is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activate this theme (deactivates all others)
     */
    public function activate(): bool
    {
        // Deactivate all other themes
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        $activated = $this->update(['is_active' => true]);
        
        if ($activated) {
            // Run theme migrations automatically (implicit migration)
            $this->runThemeMigrations();
            
            // Clear theme cache so changes take effect immediately
            \Illuminate\Support\Facades\Cache::forget('cms_themes');
            \Illuminate\Support\Facades\Cache::forget('active_theme');
        }
        
        return $activated;
    }
    
    /**
     * Ensure at least one theme is always active
     * If no theme is active, activate Basic Theme
     */
    public static function ensureActiveTheme(): void
    {
        try {
            $activeTheme = static::where('is_active', true)->first();
            
            if (!$activeTheme) {
                // Try to activate BasicTheme first
                $basicTheme = static::where('slug', 'BasicTheme')->first();
                
                if ($basicTheme) {
                    $basicTheme->update(['is_active' => true]);
                    \Log::info('BasicTheme auto-activated as no theme was active');
                } else {
                    // If BasicTheme doesn't exist, activate the first available theme
                    $firstTheme = static::orderBy('created_at', 'asc')->first();
                    if ($firstTheme) {
                        $firstTheme->update(['is_active' => true]);
                        \Log::info("Theme '{$firstTheme->name}' auto-activated as fallback");
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if there's an error, don't break the installation
            \Log::warning('Could not ensure active theme: ' . $e->getMessage());
        }
    }
    
    /**
     * Ensure VP Essential 1 migrations are run
     */
    protected function ensureVPEssentialMigrations(): void
    {
        try {
            // Check if vp_theme_settings table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('vp_theme_settings')) {
                // Run VP Essential 1 migrations
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--path' => 'Modules/VPEssential1/migrations',
                    '--force' => true,
                ]);
                
                \Log::info('VP Essential 1 migrations executed during theme activation');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to run VP Essential 1 migrations: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate this theme
     * Cannot deactivate if it's the only theme
     */
    public function deactivate(): bool
    {
        // Prevent deactivating if this is the only theme or only active theme
        $themeCount = static::count();
        if ($themeCount <= 1) {
            \Log::warning('Cannot deactivate the only theme in the system');
            return false;
        }
        
        $deactivated = $this->update(['is_active' => false]);
        
        if ($deactivated) {
            // Ensure another theme is activated
            static::ensureActiveTheme();
        }
        
        return $deactivated;
    }
    
    /**
     * Run migrations for this theme (implicit migration on activation)
     * Mirrors ModuleLoader::runModuleMigrations() behavior
     */
    protected function runThemeMigrations(): void
    {
        $themePath = base_path('themes/' . $this->slug);
        $migrationsPath = $themePath . '/migrations';
        
        if (!\Illuminate\Support\Facades\File::exists($migrationsPath)) {
            \Log::debug("No migrations directory for theme: {$this->slug}");
            return;
        }

        try {
            \Log::info("Running migrations for theme: {$this->slug}");
            
            // Get all migration files
            $migrationFiles = glob($migrationsPath . '/*.php');
            
            if (empty($migrationFiles)) {
                \Log::debug("No migration files found for theme: {$this->slug}");
                return;
            }

            // Check which migrations are already executed
            $executedMigrations = \DB::table('migrations')
                ->pluck('migration')
                ->toArray();

            // Get current batch number
            $batch = \DB::table('migrations')->max('batch') + 1;

            $executed = 0;
            
            foreach ($migrationFiles as $file) {
                $migrationName = basename($file, '.php');
                
                // Skip if already executed
                if (in_array($migrationName, $executedMigrations)) {
                    continue;
                }
                
                try {
                    // Include and run the migration
                    $migration = include $file;
                    
                    if (method_exists($migration, 'up')) {
                        $migration->up();
                        
                        // Record in migrations table
                        \DB::table('migrations')->insert([
                            'migration' => $migrationName,
                            'batch' => $batch
                        ]);
                        
                        \Log::info("Migrated: {$migrationName}");
                        $executed++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Migration failed for theme {$this->slug}: {$migrationName}", [
                        'error' => $e->getMessage()
                    ]);
                    // Continue with other migrations even if one fails
                }
            }
            
            if ($executed > 0) {
                \Log::info("Theme {$this->slug}: Executed {$executed} migration(s)");
            }
        } catch (\Exception $e) {
            \Log::error("Failed to run migrations for theme {$this->slug}: " . $e->getMessage());
        }
    }

    /**
     * Get theme configuration value
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Set theme configuration value
     */
    public function setConfig(string $key, mixed $value): bool
    {
        $config = $this->config ?? [];
        data_set($config, $key, $value);
        return $this->update(['config' => $config]);
    }

    /**
     * Get theme colors
     */
    public function getColors(): array
    {
        return $this->getConfig('colors', [
            'primary' => '#5D4037',
            'secondary' => '#8B4513',
            'accent' => '#D4A574',
        ]);
    }

    /**
     * Get theme menus
     */
    public function getMenus(): array
    {
        return $this->getConfig('menus', ['primary', 'footer', 'sidebar']);
    }

    /**
     * Scope to get active theme
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
