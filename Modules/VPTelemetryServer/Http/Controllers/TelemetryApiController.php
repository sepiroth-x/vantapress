<?php

namespace Modules\VPTelemetryServer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Modules\VPTelemetryServer\Models\Installation;
use Modules\VPTelemetryServer\Models\InstallationModule;
use Modules\VPTelemetryServer\Models\InstallationTheme;
use Modules\VPTelemetryServer\Models\TelemetryLog;

/**
 * Telemetry API Controller
 * 
 * Receives anonymous telemetry data from VantaPress installations
 * Validates, processes, and stores data in database
 */
class TelemetryApiController
{
    /**
     * Collect telemetry data from remote installation
     * 
     * POST /api/v1/telemetry/collect
     */
    public function collect(Request $request): JsonResponse
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'installation_id' => 'required|string|size:36', // UUID
            'event_type' => 'required|string|in:install,update,module_change,heartbeat,test',
            'domain' => 'nullable|string|max:255',
            'ip' => 'nullable|ip',
            'version' => 'nullable|string|max:50',
            'php_version' => 'nullable|string|max:50',
            'server_os' => 'nullable|string|max:100',
            'modules' => 'nullable|array',
            'modules.*' => 'string|max:100',
            'themes' => 'nullable|array',
            'themes.*' => 'string|max:100',
            'installed_at' => 'nullable|date',
            'timestamp' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            Log::warning('[TelemetryServer] Validation failed', [
                'errors' => $validator->errors()->toArray(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            // Check rate limiting per installation
            $recentLogs = TelemetryLog::where('installation_id', $data['installation_id'])
                ->where('created_at', '>', now()->subHour())
                ->count();

            if ($recentLogs > 10) {
                Log::warning('[TelemetryServer] Rate limit exceeded', [
                    'installation_id' => $data['installation_id'],
                    'recent_count' => $recentLogs,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded',
                ], 429);
            }

            // Find or create installation record
            $installation = Installation::firstOrCreate(
                ['installation_id' => $data['installation_id']],
                [
                    'domain' => $data['domain'] ?? 'unknown',
                    'ip' => $data['ip'] ?? $request->ip(),
                    'version' => $data['version'] ?? 'unknown',
                    'php_version' => $data['php_version'] ?? 'unknown',
                    'server_os' => $data['server_os'] ?? 'unknown',
                    'installed_at' => isset($data['installed_at']) ? \Carbon\Carbon::parse($data['installed_at']) : now(),
                ]
            );

            // Update installation data if changed
            $updateData = [];
            if (isset($data['domain']) && $installation->domain !== $data['domain']) {
                $updateData['domain'] = $data['domain'];
            }
            if (isset($data['version']) && $installation->version !== $data['version']) {
                $updateData['version'] = $data['version'];
                $updateData['updated_at_version'] = now();
            }
            if (isset($data['php_version']) && $installation->php_version !== $data['php_version']) {
                $updateData['php_version'] = $data['php_version'];
            }
            if (isset($data['server_os']) && $installation->server_os !== $data['server_os']) {
                $updateData['server_os'] = $data['server_os'];
            }

            $updateData['last_ping_at'] = now();

            $installation->update($updateData);

            // Sync modules
            if (isset($data['modules']) && is_array($data['modules'])) {
                // Remove old modules not in current list
                InstallationModule::where('installation_id', $installation->id)
                    ->whereNotIn('module_name', $data['modules'])
                    ->delete();

                // Add new modules
                foreach ($data['modules'] as $moduleName) {
                    InstallationModule::firstOrCreate([
                        'installation_id' => $installation->id,
                        'module_name' => $moduleName,
                    ]);
                }
            }

            // Sync themes
            if (isset($data['themes']) && is_array($data['themes'])) {
                // Remove old themes not in current list
                InstallationTheme::where('installation_id', $installation->id)
                    ->whereNotIn('theme_name', $data['themes'])
                    ->delete();

                // Add new themes
                foreach ($data['themes'] as $themeName) {
                    InstallationTheme::firstOrCreate([
                        'installation_id' => $installation->id,
                        'theme_name' => $themeName,
                    ]);
                }
            }

            // Log the telemetry event
            TelemetryLog::create([
                'installation_id' => $installation->id,
                'event_type' => $data['event_type'],
                'payload' => $request->all(),
            ]);

            Log::info('[TelemetryServer] Telemetry collected', [
                'installation_id' => $data['installation_id'],
                'event_type' => $data['event_type'],
                'version' => $data['version'] ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Telemetry data received',
            ], 200);

        } catch (\Exception $e) {
            Log::error('[TelemetryServer] Error processing telemetry', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing telemetry data',
            ], 500);
        }
    }

    /**
     * Health check endpoint
     * 
     * GET /api/v1/telemetry/health
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'VantaPress Telemetry Server',
            'version' => '1.0.0',
        ]);
    }
}
