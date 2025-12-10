<?php

namespace Modules\VPTelemetry\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Telemetry Log Model
 * 
 * Stores local record of telemetry pings sent.
 * Helps users see what data was transmitted.
 */
class TelemetryLog extends Model
{
    /**
     * Table name
     */
    protected $table = 'telemetry_logs';

    /**
     * Fillable fields
     */
    protected $fillable = [
        'event_type',
        'payload',
        'sent_at',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Disable timestamps (we use sent_at)
     */
    public $timestamps = true;
}
