<?php

namespace Modules\VPTelemetryServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Installation Theme Model
 * 
 * Tracks enabled themes for each installation
 */
class InstallationTheme extends Model
{
    protected $table = 'telemetry_installation_themes';

    protected $fillable = [
        'installation_id',
        'theme_name',
    ];

    public $timestamps = true;

    /**
     * Get installation this theme belongs to
     */
    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class, 'installation_id');
    }
}
