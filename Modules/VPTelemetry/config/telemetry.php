<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telemetry Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable anonymous telemetry collection.
    | Users can change this in Admin > Settings.
    |
    | Default: false (opt-in, not opt-out)
    |
    | Data collected: domain, IP, version, PHP version, modules, themes
    | Data NOT collected: emails, usernames, passwords, user content
    |
    */
    'enabled' => env('TELEMETRY_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Central API Endpoint
    |--------------------------------------------------------------------------
    |
    | URL where telemetry data is sent.
    | Must use HTTPS for security.
    |
    | Default: https://vantapress.com/api/v1/telemetry/collect
    |
    */
    'api_endpoint' => env('TELEMETRY_API_ENDPOINT', 'https://vantapress.com/api/v1/telemetry/collect'),

    /*
    |--------------------------------------------------------------------------
    | Heartbeat Interval
    |--------------------------------------------------------------------------
    |
    | How often to send heartbeat pings (in hours).
    | Default: 24 hours (daily)
    |
    */
    'heartbeat_interval' => env('TELEMETRY_HEARTBEAT_INTERVAL', 24),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Number of retry attempts and delay between retries (milliseconds).
    |
    */
    'retry_attempts' => 3,
    'retry_delay' => 100,

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | HTTP request timeout in seconds.
    |
    */
    'timeout' => 10,

    /*
    |--------------------------------------------------------------------------
    | Privacy Policy URL
    |--------------------------------------------------------------------------
    |
    | Link to telemetry data collection explanation.
    |
    */
    'privacy_url' => 'https://vantapress.com/telemetry',
];
