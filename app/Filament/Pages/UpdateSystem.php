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
        // Clear config cache to ensure fresh version is loaded
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Silently fail if artisan commands don't work
        }
        
        // Load version directly from config file to bypass caching
        $versionConfig = include(base_path('config/version.php'));
        $this->currentVersion = $versionConfig['version'] ?? config('version.version', '1.0.0');
        
        $this->checkForUpdates();
    }

    public function checkForUpdates(): void
    {
        try {
            $this->checking = true;
            
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
                
                // Compare versions
                $this->updateAvailable = version_compare(
                    $this->latestRelease['version'], 
                    $this->currentVersion, 
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
                Notification::make()
                    ->title('Update Successful!')
                    ->body("VantaPress has been updated to v{$version}. Page will refresh in 3 seconds...")
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
