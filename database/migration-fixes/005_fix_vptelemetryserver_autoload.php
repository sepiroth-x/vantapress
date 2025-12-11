<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Migration Fix 005: Fix VPTelemetryServer Autoloading
 * 
 * Ensures VPTelemetryServer module can be properly loaded and enabled.
 * This fix clears caches and regenerates autoloader if needed.
 * 
 * Issues Fixed:
 * - Module appears in list but won't stay enabled
 * - ServiceProvider class not being autoloaded
 * - Stale cache preventing module from loading
 * 
 * @version 1.1.8-complete
 * @since 2025-12-11
 */

return new class
{
    /**
     * Determine if this fix should run
     */
    public function shouldRun(): bool
    {
        Log::info('[Migration Fix 005] ========================================');
        Log::info('[Migration Fix 005] Checking VPTelemetryServer autoloading');
        Log::info('[Migration Fix 005] ========================================');

        // Check if VPTelemetryServer folder exists
        $modulePath = base_path('Modules/VPTelemetryServer');
        if (!File::isDirectory($modulePath)) {
            Log::info('[Migration Fix 005] VPTelemetryServer folder not found - skipping');
            return false;
        }

        // Check if ServiceProvider class can be autoloaded
        $canAutoload = class_exists('Modules\VPTelemetryServer\VPTelemetryServerServiceProvider');
        
        if (!$canAutoload) {
            Log::warning('[Migration Fix 005] VPTelemetryServerServiceProvider NOT autoloadable');
            Log::warning('[Migration Fix 005] This fix WILL run to regenerate autoloader');
            return true;
        }

        Log::info('[Migration Fix 005] VPTelemetryServerServiceProvider is autoloadable');
        Log::info('[Migration Fix 005] Checking if module is in database...');

        // Check if module exists in database
        try {
            $moduleExists = \DB::table('modules')
                ->where('slug', 'VPTelemetryServer')
                ->exists();

            if (!$moduleExists) {
                Log::warning('[Migration Fix 005] Module not in database - will trigger discovery');
                return true;
            }

            Log::info('[Migration Fix 005] Module exists in database');
        } catch (\Exception $e) {
            Log::error('[Migration Fix 005] Database check failed: ' . $e->getMessage());
        }

        Log::info('[Migration Fix 005] No fixes needed - skipping');
        return false;
    }

    /**
     * Execute the fix
     */
    public function run(): bool
    {
        Log::warning('[Migration Fix 005] ========================================');
        Log::warning('[Migration Fix 005] EXECUTING VPTelemetryServer autoload fix');
        Log::warning('[Migration Fix 005] ========================================');

        $success = true;

        try {
            // Step 1: Clear all caches
            Log::info('[Migration Fix 005] Step 1: Clearing all caches...');
            
            Artisan::call('config:clear');
            Log::info('[Migration Fix 005] ✓ Config cache cleared');

            Artisan::call('cache:clear');
            Log::info('[Migration Fix 005] ✓ Application cache cleared');

            Artisan::call('route:clear');
            Log::info('[Migration Fix 005] ✓ Route cache cleared');

            Artisan::call('view:clear');
            Log::info('[Migration Fix 005] ✓ View cache cleared');

            try {
                Artisan::call('optimize:clear');
                Log::info('[Migration Fix 005] ✓ Optimization cache cleared');
            } catch (\Exception $e) {
                Log::warning('[Migration Fix 005] Could not clear optimization cache: ' . $e->getMessage());
            }

            // Step 2: Verify module folder structure
            Log::info('[Migration Fix 005] Step 2: Verifying module structure...');
            
            $modulePath = base_path('Modules/VPTelemetryServer');
            $requiredFiles = [
                'module.json',
                'VPTelemetryServerServiceProvider.php',
                'routes/web.php',
                'routes/api.php',
                'config/telemetry-server.php',
            ];

            foreach ($requiredFiles as $file) {
                $filePath = $modulePath . '/' . $file;
                if (File::exists($filePath)) {
                    Log::info('[Migration Fix 005] ✓ Found: ' . $file);
                } else {
                    Log::error('[Migration Fix 005] ✗ MISSING: ' . $file);
                    $success = false;
                }
            }

            // Step 3: Attempt to regenerate autoloader
            Log::info('[Migration Fix 005] Step 3: Regenerating autoloader...');
            
            // Try composer dump-autoload via shell_exec if available
            if (function_exists('shell_exec')) {
                $composerPaths = [
                    'composer',
                    '/usr/local/bin/composer',
                    base_path('composer.phar'),
                ];

                $composerFound = false;
                foreach ($composerPaths as $composer) {
                    if (is_file($composer) || @shell_exec("which $composer 2>/dev/null")) {
                        $basePath = base_path();
                        $output = @shell_exec("cd '$basePath' && $composer dump-autoload --no-interaction 2>&1");
                        
                        if ($output !== null) {
                            Log::info('[Migration Fix 005] ✓ Composer autoload regenerated');
                            Log::info('[Migration Fix 005] Output: ' . substr($output, 0, 200));
                            $composerFound = true;
                            break;
                        }
                    }
                }

                if (!$composerFound) {
                    Log::warning('[Migration Fix 005] Composer not found - autoloader regeneration skipped');
                    Log::warning('[Migration Fix 005] Module should still work after cache clear');
                }
            } else {
                Log::warning('[Migration Fix 005] shell_exec disabled - cannot run composer');
            }

            // Step 4: Force re-check class loading
            Log::info('[Migration Fix 005] Step 4: Checking if class is now loadable...');
            
            // Clear OPcache if available
            if (function_exists('opcache_reset')) {
                opcache_reset();
                Log::info('[Migration Fix 005] ✓ OPcache cleared');
            }

            // Test class loading
            $canAutoload = class_exists('Modules\VPTelemetryServer\VPTelemetryServerServiceProvider', true);
            
            if ($canAutoload) {
                Log::info('[Migration Fix 005] ✓ VPTelemetryServerServiceProvider is now autoloadable!');
            } else {
                Log::error('[Migration Fix 005] ✗ Class still not autoloadable after fixes');
                $success = false;
            }

            // Step 5: Trigger module discovery
            Log::info('[Migration Fix 005] Step 5: Triggering module discovery...');
            
            try {
                $loader = app(\App\Services\ModuleLoader::class);
                $modules = $loader->discoverModules();
                
                $found = isset($modules['VPTelemetryServer']);
                if ($found) {
                    Log::info('[Migration Fix 005] ✓ VPTelemetryServer discovered in module scan');
                } else {
                    Log::warning('[Migration Fix 005] Module not found in discovery scan');
                }
            } catch (\Exception $e) {
                Log::error('[Migration Fix 005] Module discovery failed: ' . $e->getMessage());
            }

            if ($success) {
                Log::warning('[Migration Fix 005] ========================================');
                Log::warning('[Migration Fix 005] ✓ FIX COMPLETED SUCCESSFULLY');
                Log::warning('[Migration Fix 005] VPTelemetryServer should now be enableable');
                Log::warning('[Migration Fix 005] ========================================');
            } else {
                Log::error('[Migration Fix 005] ========================================');
                Log::error('[Migration Fix 005] ✗ FIX COMPLETED WITH ERRORS');
                Log::error('[Migration Fix 005] Check logs above for details');
                Log::error('[Migration Fix 005] ========================================');
            }

        } catch (\Exception $e) {
            Log::error('[Migration Fix 005] CRITICAL ERROR: ' . $e->getMessage());
            Log::error('[Migration Fix 005] Stack trace: ' . $e->getTraceAsString());
            $success = false;
        }

        return $success;
    }
};
