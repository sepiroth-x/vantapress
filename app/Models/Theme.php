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
        $activeTheme = static::where('is_active', true)->first();
        
        if (!$activeTheme) {
            // Try to activate Basic Theme
            $basicTheme = static::where('slug', 'Basic')->first();
            
            if ($basicTheme) {
                $basicTheme->activate();
                \Log::info('Basic Theme auto-activated as no theme was active');
            } else {
                // If Basic Theme doesn't exist, activate the first available theme
                $firstTheme = static::orderBy('created_at', 'asc')->first();
                if ($firstTheme) {
                    $firstTheme->activate();
                    \Log::info("Theme '{$firstTheme->name}' auto-activated as fallback");
                }
            }
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
