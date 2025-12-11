<?php

namespace Modules\VPTelemetryServer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Telemetry Log Model
 * 
 * Logs all telemetry events received from installations
 */
class TelemetryLog extends Model
{
    protected $table = 'telemetry_logs';

    protected $fillable = [
        'installation_id',
        'event_type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public $timestamps = true;

    /**
     * Get installation this log belongs to
     */
    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class, 'installation_id');
    }
}
