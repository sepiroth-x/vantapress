<?php

namespace Modules\TheVillainTerminal\Commands;

use Illuminate\Support\Facades\File;

/**
 * The Villain Terminal - System Info Commands
 * 
 * Provides information about the system, PHP, and VantaPress.
 */
class SystemInfoCommand
{
    /**
     * Get full system information
     * 
     * @param array $args
     * @return array
     */
    public function systemInfo(array $args): array
    {
        $output = [];
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>══════════════════════════════════════════════════</span>";
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>         VANTAPRESS SYSTEM INFORMATION            </span>";
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>══════════════════════════════════════════════════</span>";
        $output[] = "";

        // VantaPress Version
        $vantaVersion = config('version.version', 'Unknown');
        $output[] = "<span style='color: #ffff00;'>VantaPress Version:</span> <span style='color: #00ff00;'>{$vantaVersion}</span>";
        $output[] = "";

        // PHP Information
        $output[] = "<span style='color: #ff00ff; font-weight: bold;'>PHP Information:</span>";
        $output[] = "  Version: " . phpversion();
        $output[] = "  Memory Limit: " . ini_get('memory_limit');
        $output[] = "  Max Execution Time: " . ini_get('max_execution_time') . "s";
        $output[] = "  Upload Max Size: " . ini_get('upload_max_filesize');
        $output[] = "  Post Max Size: " . ini_get('post_max_size');
        $output[] = "";

        // Server Information
        $output[] = "<span style='color: #ff00ff; font-weight: bold;'>Server Information:</span>";
        $output[] = "  OS: " . PHP_OS;
        $output[] = "  Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown');
        $output[] = "  Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown');
        $output[] = "";

        // Database Information
        $output[] = "<span style='color: #ff00ff; font-weight: bold;'>Database:</span>";
        $output[] = "  Driver: " . config('database.default');
        $output[] = "  Database: " . config('database.connections.' . config('database.default') . '.database');
        $output[] = "";

        // Laravel Information
        $output[] = "<span style='color: #ff00ff; font-weight: bold;'>Framework:</span>";
        $output[] = "  Laravel: " . app()->version();
        $output[] = "  Environment: " . app()->environment();
        $output[] = "  Debug Mode: " . (config('app.debug') ? 'ON' : 'OFF');
        $output[] = "";

        // Storage Information
        $storagePath = storage_path();
        $freeSpace = disk_free_space($storagePath);
        $totalSpace = disk_total_space($storagePath);
        $usedSpace = $totalSpace - $freeSpace;
        $usedPercent = round(($usedSpace / $totalSpace) * 100, 2);

        $output[] = "<span style='color: #ff00ff; font-weight: bold;'>Storage:</span>";
        $output[] = "  Total: " . $this->formatBytes($totalSpace);
        $output[] = "  Used: " . $this->formatBytes($usedSpace) . " ({$usedPercent}%)";
        $output[] = "  Free: " . $this->formatBytes($freeSpace);
        $output[] = "";

        // PHP Extensions
        $extensions = get_loaded_extensions();
        $output[] = "<span style='color: #ff00ff; font-weight: bold;'>Loaded Extensions (" . count($extensions) . "):</span>";
        $output[] = "  " . implode(', ', array_slice($extensions, 0, 10)) . "...";

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * Get PHP version
     * 
     * @param array $args
     * @return array
     */
    public function phpVersion(array $args): array
    {
        $output = [];
        $output[] = "<span style='color: #00ff00;'>PHP Version: " . phpversion() . "</span>";
        $output[] = "";
        $output[] = "Loaded Extensions:";
        
        $extensions = get_loaded_extensions();
        sort($extensions);
        
        foreach ($extensions as $ext) {
            $output[] = "  • " . $ext;
        }

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * Get Filament version
     * 
     * @param array $args
     * @return array
     */
    public function filamentVersion(array $args): array
    {
        $output = [];
        
        // Try to detect Filament version
        $composerLockPath = base_path('composer.lock');
        $filamentVersion = 'Unknown';
        
        if (File::exists($composerLockPath)) {
            $composerLock = json_decode(File::get($composerLockPath), true);
            
            foreach ($composerLock['packages'] ?? [] as $package) {
                if ($package['name'] === 'filament/filament') {
                    $filamentVersion = $package['version'];
                    break;
                }
            }
        }

        $output[] = "<span style='color: #ffff00; font-weight: bold;'>══════════════════════════════════════</span>";
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>     FILAMENT INFORMATION             </span>";
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>══════════════════════════════════════</span>";
        $output[] = "";
        $output[] = "<span style='color: #00ff00;'>Version: {$filamentVersion}</span>";
        $output[] = "";
        $output[] = "Supported Features:";
        $output[] = "  • Admin Panel";
        $output[] = "  • Form Builder";
        $output[] = "  • Table Builder";
        $output[] = "  • Notifications";
        $output[] = "  • Actions";
        $output[] = "  • Widgets";

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * Get VantaPress version
     * 
     * @param array $args
     * @return array
     */
    public function vantaVersion(array $args): array
    {
        $version = config('version.version', 'Unknown');
        $output = [];
        
        $output[] = "<span style='color: #ffff00; font-size: 18px; font-weight: bold;'>";
        $output[] = "════════════════════════════════════════";
        $output[] = "           VANTAPRESS                   ";
        $output[] = "     Modern CMS for Laravel             ";
        $output[] = "════════════════════════════════════════";
        $output[] = "</span>";
        $output[] = "";
        $output[] = "<span style='color: #00ff00; font-weight: bold;'>Version: {$version}</span>";
        $output[] = "";
        $output[] = "Built with:";
        $output[] = "  • Laravel " . app()->version();
        $output[] = "  • Filament Admin";
        $output[] = "  • Modular Architecture";
        $output[] = "  • Theme System";

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * Format bytes to human-readable size
     * 
     * @param int $bytes
     * @return string
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < 4) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
