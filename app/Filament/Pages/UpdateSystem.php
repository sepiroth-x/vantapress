<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Services\AutoUpdateService;

class UpdateSystem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.update-system';
    
    protected static ?string $navigationLabel = 'Updates';
    
    protected static ?int $navigationSort = 1;
    
    public ?array $latestRelease = null;
    public ?string $currentVersion = null;
    public bool $updateAvailable = false;
    public bool $checking = false;
    public bool $updating = false;
    public ?array $updateProgress = null;

    public function mount(): void
    {
        // Clear config cache FIRST before syncing
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Silently fail if artisan commands don't work
        }
        
        // Auto-sync .env version with config/version.php if they differ
        $this->syncEnvVersion();
        
        // Clear cache AGAIN after .env sync to reload new version
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Silently fail if artisan commands don't work
        }
        
        // Read version DIRECTLY from .env file (most reliable after sync)
        // This bypasses Laravel's env() caching which may hold old values
        $envPath = base_path('.env');
        if (\File::exists($envPath)) {
            $envContent = \File::get($envPath);
            if (preg_match('/^APP_VERSION=(.*)$/m', $envContent, $matches)) {
                $this->currentVersion = trim($matches[1]);
            }
        }
        
        // Fallback to config file if .env read failed
        if (!$this->currentVersion) {
            $versionConfig = include(base_path('config/version.php'));
            $this->currentVersion = $versionConfig['version'] ?? '1.0.0';
        }
        
        $this->checkForUpdates();
    }
    
    /**
     * Sync .env APP_VERSION with config/version.php default version
     * This ensures version stays in sync after git pull deployments
     */
    protected function syncEnvVersion(): void
    {
        try {
            $envPath = base_path('.env');
            
            if (!\File::exists($envPath)) {
                return;
            }
            
            // Get DEFAULT version from config/version.php by parsing the file
            // We need the hardcoded default, not env('APP_VERSION') which is cached
            $configPath = base_path('config/version.php');
            $configContent = \File::get($configPath);
            
            // Extract the default version: 'version' => env('APP_VERSION', '1.0.37-complete')
            // We want the second parameter (the default)
            if (preg_match("/'version'\s*=>\s*env\([^,]+,\s*['\"]([^'\"]+)['\"]\)/", $configContent, $matches)) {
                $configVersion = $matches[1];
            } else {
                \Log::warning('Could not extract default version from config/version.php');
                return;
            }
            
            // Get current version from .env
            $envContent = \File::get($envPath);
            preg_match('/^APP_VERSION=(.*)$/m', $envContent, $matches);
            $envVersion = $matches[1] ?? null;
            
            // If versions differ, update .env to match config default
            if ($configVersion && $envVersion !== $configVersion) {
                if (preg_match('/^APP_VERSION=.*$/m', $envContent)) {
                    $envContent = preg_replace(
                        '/^APP_VERSION=.*$/m',
                        'APP_VERSION=' . $configVersion,
                        $envContent
                    );
                } else {
                    $envContent .= "\nAPP_VERSION=" . $configVersion . "\n";
                }
                
                \File::put($envPath, $envContent);
                \Log::info("Auto-synced .env APP_VERSION: {$envVersion} → {$configVersion}");
            }
        } catch (\Exception $e) {
            \Log::error('Failed to sync .env version: ' . $e->getMessage());
        }
    }
    
    /**
     * Refresh current version from .env file
     * Call this to reload version after updates
     */
    protected function refreshCurrentVersion(): void
    {
        // Clear caches first
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Silently fail
        }
        
        // Read version DIRECTLY from .env file
        $envPath = base_path('.env');
        if (\File::exists($envPath)) {
            $envContent = \File::get($envPath);
            if (preg_match('/^APP_VERSION=(.*)$/m', $envContent, $matches)) {
                $this->currentVersion = trim($matches[1]);
                \Log::info("Refreshed current version from .env: {$this->currentVersion}");
                return;
            }
        }
        
        // Fallback: Extract default version from config/version.php
        $configPath = base_path('config/version.php');
        if (\File::exists($configPath)) {
            $configContent = \File::get($configPath);
            if (preg_match("/'version'\s*=>\s*env\([^,]+,\s*['\"]([^'\"]+)['\"]\)/", $configContent, $matches)) {
                $this->currentVersion = $matches[1];
                return;
            }
        }
        
        // Last resort fallback
        $this->currentVersion = '1.0.0';
    }

    public function checkForUpdates(): void
    {
        try {
            $this->checking = true;
            
            // Refresh current version from .env (in case it was updated)
            $this->refreshCurrentVersion();
            
            // Fetch latest release from GitHub
            $response = Http::timeout(10)
                ->withHeaders([
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'VantaPress-CMS'
                ])
                ->get('https://api.github.com/repos/sepiroth-x/vantapress/releases/latest');

            if ($response->successful()) {
                $release = $response->json();
                
                $this->latestRelease = [
                    'version' => ltrim($release['tag_name'] ?? 'v1.0.0', 'v'),
                    'name' => $release['name'] ?? 'VantaPress Update',
                    'published_at' => $release['published_at'] ?? now()->toIso8601String(),
                    'body' => $release['body'] ?? 'No release notes available.',
                    'html_url' => $release['html_url'] ?? 'https://github.com/sepiroth-x/vantapress/releases',
                    'zipball_url' => $release['zipball_url'] ?? null,
                ];
                
                // Normalize versions for comparison (strip suffixes like -complete, -beta, etc.)
                $normalizedLatest = preg_replace('/-.*$/', '', $this->latestRelease['version']);
                $normalizedCurrent = preg_replace('/-.*$/', '', $this->currentVersion);
                
                // Compare versions
                $this->updateAvailable = version_compare(
                    $normalizedLatest, 
                    $normalizedCurrent, 
                    '>'
                );
                
                if ($this->updateAvailable) {
                    Notification::make()
                        ->title('Update Available!')
                        ->body("Version {$this->latestRelease['version']} is now available.")
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('You\'re up to date!')
                        ->body("VantaPress v{$this->currentVersion} is the latest version.")
                        ->success()
                        ->send();
                }
            } else {
                throw new \Exception('Unable to fetch release information');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Update Check Failed')
                ->body('Could not connect to GitHub. Please check your internet connection.')
                ->warning()
                ->send();
            
            \Log::error('Update check failed: ' . $e->getMessage());
        } finally {
            $this->checking = false;
        }
    }
    
    public function installUpdate(): void
    {
        try {
            if (!$this->latestRelease || !$this->updateAvailable) {
                throw new \Exception('No update available');
            }

            $this->updating = true;
            $version = $this->latestRelease['version'];

            Notification::make()
                ->title('Update Started')
                ->body("Installing version {$version}... This may take a few minutes.")
                ->info()
                ->send();

            // Perform update using AutoUpdateService
            $updateService = new AutoUpdateService();
            $result = $updateService->performUpdate('v' . $version);

            $this->updateProgress = $result;

            if ($result['success']) {
                // Refresh current version to show the new version immediately
                $this->refreshCurrentVersion();
                
                Notification::make()
                    ->title('Update Successful!')
                    ->body("VantaPress has been updated to v{$this->currentVersion}. Page will refresh in 3 seconds...")
                    ->success()
                    ->duration(5000)
                    ->send();

                // Refresh page after 3 seconds to load new version
                $this->dispatch('refresh-page', delay: 3000);
            } else {
                throw new \Exception($result['message'] ?? 'Update failed');
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Update Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
            
            \Log::error('Update installation failed: ' . $e->getMessage());
        } finally {
            $this->updating = false;
        }
    }
    
    public function viewReleaseNotes(): void
    {
        if ($this->latestRelease && isset($this->latestRelease['html_url'])) {
            redirect($this->latestRelease['html_url']);
        }
    }
}
