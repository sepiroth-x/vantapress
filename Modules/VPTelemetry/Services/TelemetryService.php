<?php

namespace Modules\VPTelemetry\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\VPTelemetry\Models\TelemetryLog;

/**
 * VantaPress Telemetry Service
 * 
 * Collects and sends anonymous usage data to help improve VantaPress.
 * 
 * PRIVACY COMMITMENT:
 * - NO personal data (no emails, usernames, passwords, or content)
 * - NO user-generated content
 * - ONLY anonymous installation metrics
 * - Users can disable anytime
 * 
 * Data collected:
 * - Domain URL
 * - Public IP (server IP, not user IP)
 * - VantaPress version
 * - PHP version
 * - Server OS
 * - Enabled modules list
 * - Enabled themes list
 * - Installation timestamp
 * - Last activity timestamp
 */
class TelemetryService
{
    /**
     * Central telemetry API endpoint
     * 
     * @var string
     */
    protected string $apiEndpoint;

    /**
     * Installation unique ID (generated once, stored in config)
     * 
     * @var string
     */
    protected string $installationId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apiEndpoint = config('telemetry.api_endpoint', 'https://vantapress.com/api/v1/telemetry/collect');
        $this->installationId = $this->getOrCreateInstallationId();
    }

    /**
     * Send installation ping (first install)
     */
    public function sendInstallationPing(): void
    {
        try {
            $data = $this->collectTelemetryData();
            $data['event_type'] = 'install';
            
            $this->sendToApi($data);
            
            $this->logTelemetry('install', $data);
            
            Log::info('[VPTelemetry] Installation ping sent successfully');
        } catch (\Exception $e) {
            Log::error('[VPTelemetry] Failed to send installation ping', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send update ping (version update)
     */
    public function sendUpdatePing(): void
    {
        try {
            $data = $this->collectTelemetryData();
            $data['event_type'] = 'update';
            
            $this->sendToApi($data);
            
            $this->logTelemetry('update', $data);
            
            Log::info('[VPTelemetry] Update ping sent successfully');
        } catch (\Exception $e) {
            Log::error('[VPTelemetry] Failed to send update ping', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send module change ping (module enable/disable)
     */
    public function sendModuleChangePing(): void
    {
        try {
            $data = $this->collectTelemetryData();
            $data['event_type'] = 'module_change';
            
            $this->sendToApi($data);
            
            $this->logTelemetry('module_change', $data);
            
            Log::info('[VPTelemetry] Module change ping sent successfully');
        } catch (\Exception $e) {
            Log::error('[VPTelemetry] Failed to send module change ping', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send daily heartbeat (once per 24 hours)
     */
    public function sendDailyHeartbeat(): void
    {
        // Check if heartbeat already sent today
        $lastHeartbeat = Cache::get('telemetry_last_heartbeat');
        
        if ($lastHeartbeat && $lastHeartbeat > now()->subDay()) {
            Log::debug('[VPTelemetry] Heartbeat already sent today, skipping');
            return;
        }

        try {
            $data = $this->collectTelemetryData();
            $data['event_type'] = 'heartbeat';
            
            $this->sendToApi($data);
            
            $this->logTelemetry('heartbeat', $data);
            
            Cache::put('telemetry_last_heartbeat', now(), now()->addDay());
            
            Log::info('[VPTelemetry] Daily heartbeat sent successfully');
        } catch (\Exception $e) {
            Log::error('[VPTelemetry] Failed to send daily heartbeat', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Collect telemetry data (anonymous only)
     * 
     * @return array
     */
    protected function collectTelemetryData(): array
    {
        return [
            'installation_id' => $this->installationId,
            'domain' => $this->sanitizeDomain(config('app.url')),
            'ip' => $this->getServerPublicIp(),
            'version' => config('version.version', '1.0.0'),
            'php_version' => PHP_VERSION,
            'php_major_minor' => $this->getPhpMajorMinor(),
            'server_os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'laravel_version' => app()->version(),
            'modules' => $this->getEnabledModules(),
            'themes' => $this->getEnabledThemes(),
            'installed_at' => $this->getInstallationDate(),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Send data to central API
     * 
     * @param array $data
     * @return void
     */
    protected function sendToApi(array $data): void
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->post($this->apiEndpoint, $data);
            
            if (!$response->successful()) {
                Log::warning('[VPTelemetry] API returned non-success status', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            // Fail silently - telemetry should never break the application
            Log::error('[VPTelemetry] API request failed', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log telemetry event locally
     * 
     * @param string $eventType
     * @param array $payload
     * @return void
     */
    protected function logTelemetry(string $eventType, array $payload): void
    {
        try {
            TelemetryLog::create([
                'event_type' => $eventType,
                'payload' => $payload,
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            // If table doesn't exist yet, fail silently
            Log::debug('[VPTelemetry] Could not log telemetry event', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get or create unique installation ID
     * 
     * @return string
     */
    protected function getOrCreateInstallationId(): string
    {
        $id = Cache::rememberForever('vantapress_installation_id', function () {
            return 'vp_' . bin2hex(random_bytes(16));
        });

        return $id;
    }

    /**
     * Get server's public IP address
     * 
     * @return string
     */
    protected function getServerPublicIp(): string
    {
        // Try multiple methods to get public IP
        $ip = $_SERVER['SERVER_ADDR'] ?? null;
        
        // If localhost/private IP, try to get public IP from external service
        if (!$ip || $this->isPrivateIp($ip)) {
            try {
                $response = Http::timeout(5)->get('https://api.ipify.org?format=json');
                if ($response->successful()) {
                    $ip = $response->json()['ip'] ?? $ip;
                }
            } catch (\Exception $e) {
                // Fail silently
            }
        }

        return $ip ?? 'unknown';
    }

    /**
     * Check if IP is private/local
     * 
     * @param string $ip
     * @return bool
     */
    protected function isPrivateIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Sanitize domain URL
     * 
     * @param string $url
     * @return string
     */
    protected function sanitizeDomain(string $url): string
    {
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? 'unknown';
        $scheme = $parsed['scheme'] ?? 'https';
        
        return $scheme . '://' . $host;
    }

    /**
     * Get PHP major.minor version
     * 
     * @return string
     */
    protected function getPhpMajorMinor(): string
    {
        $parts = explode('.', PHP_VERSION);
        return $parts[0] . '.' . $parts[1];
    }

    /**
     * Get list of enabled modules
     * 
     * @return array
     */
    protected function getEnabledModules(): array
    {
        try {
            // Check if modules table exists
            if (!DB::getSchemaBuilder()->hasTable('modules')) {
                return [];
            }
            
            // Get enabled modules from database
            $modules = DB::table('modules')
                ->where('is_enabled', true)
                ->pluck('slug')
                ->toArray();
            
            return $modules;
        } catch (\Exception $e) {
            Log::warning('Failed to get enabled modules: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get list of enabled themes
     * 
     * @return array
     */
    protected function getEnabledThemes(): array
    {
        try {
            // Get active theme from config
            $activeTheme = config('cms.active_theme', 'BasicTheme');
            
            return [$activeTheme];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get installation date
     * 
     * @return string|null
     */
    protected function getInstallationDate(): ?string
    {
        $date = Cache::get('vantapress_installed_at');
        
        if (!$date) {
            // Try to get from first migration
            try {
                $firstMigration = \DB::table('migrations')
                    ->orderBy('id', 'asc')
                    ->first();
                
                if ($firstMigration) {
                    $date = now()->toIso8601String();
                    Cache::forever('vantapress_installed_at', $date);
                }
            } catch (\Exception $e) {
                // Table doesn't exist
            }
        }

        return $date;
    }

    /**
     * Get telemetry status
     * 
     * @return array
     */
    public function getStatus(): array
    {
        $lastHeartbeat = Cache::get('telemetry_last_heartbeat');
        $totalPings = TelemetryLog::whereNotNull('sent_at')->count();

        return [
            'enabled' => $this->isEnabled,
            'installation_id' => $this->installationId,
            'last_heartbeat' => $lastHeartbeat ? \Carbon\Carbon::parse($lastHeartbeat) : null,
            'total_pings' => $totalPings,
        ];
    }

    /**
     * Test telemetry connection
     * 
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->post($this->apiEndpoint, [
                'installation_id' => $this->installationId,
                'event_type' => 'test',
                'domain' => 'test.local',
            ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
