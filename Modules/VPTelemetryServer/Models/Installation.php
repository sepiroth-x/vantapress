<?php

namespace Modules\VPTelemetryServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Installation Model
 * 
 * Represents a VantaPress installation sending telemetry
 */
class Installation extends Model
{
    protected $table = 'telemetry_installations';

    protected $fillable = [
        'installation_id',
        'domain',
        'ip',
        'version',
        'php_version',
        'server_os',
        'installed_at',
        'last_ping_at',
        'updated_at_version',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'last_ping_at' => 'datetime',
        'updated_at_version' => 'datetime',
    ];

    /**
     * Get modules for this installation
     */
    public function modules(): HasMany
    {
        return $this->hasMany(InstallationModule::class, 'installation_id');
    }

    /**
     * Get themes for this installation
     */
    public function themes(): HasMany
    {
        return $this->hasMany(InstallationTheme::class, 'installation_id');
    }

    /**
     * Get telemetry logs for this installation
     */
    public function logs(): HasMany
    {
        return $this->hasMany(TelemetryLog::class, 'installation_id');
    }

    /**
     * Scope: Active installations (pinged within last 7 days)
     */
    public function scopeActive($query)
    {
        return $query->where('last_ping_at', '>', now()->subDays(7));
    }

    /**
     * Scope: Inactive installations (no ping for 30+ days)
     */
    public function scopeInactive($query)
    {
        return $query->where('last_ping_at', '<', now()->subDays(30));
    }

    /**
     * Check if installation is active
     */
    public function isActive(): bool
    {
        return $this->last_ping_at && $this->last_ping_at->gt(now()->subDays(7));
    }
}
