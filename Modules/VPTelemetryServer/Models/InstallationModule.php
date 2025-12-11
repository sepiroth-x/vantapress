<?php

namespace Modules\VPTelemetryServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Installation Module Model
 * 
 * Tracks enabled modules for each installation
 */
class InstallationModule extends Model
{
    protected $table = 'telemetry_installation_modules';

    protected $fillable = [
        'installation_id',
        'module_name',
    ];

    public $timestamps = true;

    /**
     * Get installation this module belongs to
     */
    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class, 'installation_id');
    }
}
