<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telemetry Server Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the telemetry data receiver server.
    | This module should ONLY be installed on your private developer server.
    |
    */

    /**
     * Enable/disable telemetry collection
     * Set to false to stop receiving data
     */
    'enabled' => env('TELEMETRY_SERVER_ENABLED', true),

    /**
     * API rate limiting
     * Maximum requests per installation per hour
     */
    'rate_limit' => [
        'max_per_hour' => env('TELEMETRY_RATE_LIMIT', 10),
    ],

    /**
     * Data retention policy
     * How long to keep telemetry logs
     */
    'retention' => [
        'logs_days' => env('TELEMETRY_LOGS_RETENTION_DAYS', 90), // Keep logs for 90 days
        'installations_days' => env('TELEMETRY_INSTALLATIONS_RETENTION_DAYS', 365), // Keep inactive installations for 1 year
    ],

    /**
     * Dashboard settings
     */
    'dashboard' => [
        'active_threshold_days' => 7, // Consider installation active if pinged within X days
        'inactive_threshold_days' => 30, // Consider installation inactive after X days
    ],

    /**
     * Security
     */
    'security' => [
        'require_https' => env('TELEMETRY_REQUIRE_HTTPS', true),
        'allowed_ips' => env('TELEMETRY_ALLOWED_IPS', null), // Comma-separated IPs, null = allow all
    ],

];
