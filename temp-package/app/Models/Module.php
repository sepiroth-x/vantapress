<?php
/**
 * TCC School CMS - Module Model
 * 
 * Eloquent model for managing module metadata and status.
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

class Module extends Model
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
        'is_enabled',
        'installed_at',
        'config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'installed_at' => 'datetime',
        'config' => 'array',
    ];

    /**
     * Check if module is enabled
     */
    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    /**
     * Enable the module
     */
    public function enable(): bool
    {
        return $this->update(['is_enabled' => true]);
    }

    /**
     * Disable the module
     */
    public function disable(): bool
    {
        return $this->update(['is_enabled' => false]);
    }

    /**
     * Get module configuration value
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return data_get($this->config, $key, $default);
    }

    /**
     * Set module configuration value
     */
    public function setConfig(string $key, mixed $value): bool
    {
        $config = $this->config ?? [];
        data_set($config, $key, $value);
        return $this->update(['config' => $config]);
    }

    /**
     * Scope to get only enabled modules
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope to get only disabled modules
     */
    public function scopeDisabled($query)
    {
        return $query->where('is_enabled', false);
    }
}
