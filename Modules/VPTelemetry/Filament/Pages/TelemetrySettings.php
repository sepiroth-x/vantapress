<?php

namespace Modules\VPTelemetry\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Modules\VPTelemetry\Services\TelemetryService;
use Modules\VPTelemetry\Models\TelemetryLog;

/**
 * Telemetry Settings Page
 * 
 * Allows users to:
 * - Enable/disable telemetry
 * - View what data is collected
 * - See last ping status
 * - View telemetry logs
 * - Test connection
 */
class TelemetrySettings extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-signal';
    
    protected static string $view = 'vptelemetry::filament.pages.telemetry-settings';
    
    protected static ?string $navigationLabel = 'Telemetry';
    
    protected static ?string $title = 'Telemetry Settings';
    
    protected static ?string $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 100;

    /**
     * Determine if the navigation item should be registered
     */
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $module = \DB::table('modules')
                ->where('name', 'VPTelemetry')
                ->first();
            
            return $module && $module->is_enabled;
        } catch (\Exception $e) {
            return false;
        }
    }

    public bool $telemetryEnabled;
    public ?string $lastHeartbeat = null;
    public ?string $installationId = null;
    public ?int $totalPings = 0;
    public ?array $latestLog = null;

    /**
     * Mount the page
     */
    public function mount(): void
    {
        $telemetry = app(TelemetryService::class);
        $status = $telemetry->getStatus();

        $this->telemetryEnabled = $status['enabled'];
        $this->lastHeartbeat = $status['last_heartbeat'] ? $status['last_heartbeat']->diffForHumans() : 'Never';
        $this->installationId = $status['installation_id'];
        $this->totalPings = $status['total_pings'];

        // Get latest log
        $latestLog = TelemetryLog::latest()->first();
        if ($latestLog) {
            $this->latestLog = [
                'event_type' => $latestLog->event_type,
                'sent_at' => $latestLog->sent_at->diffForHumans(),
                'payload' => $latestLog->payload,
            ];
        }

        $this->form->fill([
            'telemetry_enabled' => $this->telemetryEnabled ?? true, // Default to enabled
        ]);
    }

    /**
     * Define form schema
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Telemetry Control')
                    ->description('Anonymous usage data helps improve VantaPress. No personal information is collected.')
                    ->schema([
                        Forms\Components\Toggle::make('telemetry_enabled')
                            ->label('Enable Telemetry')
                            ->helperText('Send anonymous usage statistics to help improve VantaPress')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->updateTelemetrySetting($state);
                            }),
                    ]),

                Forms\Components\Section::make('What Data is Collected')
                    ->description('Complete transparency about data collection')
                    ->schema([
                        Forms\Components\Placeholder::make('collected_data')
                            ->label('')
                            ->content(view('vptelemetry::components.collected-data')),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Telemetry Status')
                    ->schema([
                        Forms\Components\Placeholder::make('installation_id')
                            ->label('Installation ID')
                            ->content($this->installationId ?? 'Not generated yet'),

                        Forms\Components\Placeholder::make('last_heartbeat')
                            ->label('Last Heartbeat')
                            ->content($this->lastHeartbeat ?? 'Never'),

                        Forms\Components\Placeholder::make('total_pings')
                            ->label('Total Pings Sent')
                            ->content($this->totalPings ?? 0),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Latest Telemetry Log')
                    ->description('Last data transmission')
                    ->schema([
                        Forms\Components\Placeholder::make('latest_log')
                            ->label('')
                            ->content($this->latestLog ? view('vptelemetry::components.latest-log', ['log' => $this->latestLog]) : 'No logs yet'),
                    ])
                    ->collapsible()
                    ->collapsed($this->latestLog === null),
            ])
            ->statePath('data');
    }

    /**
     * Update telemetry setting
     */
    protected function updateTelemetrySetting(bool $enabled): void
    {
        try {
            // Update .env file
            $envPath = base_path('.env');
            
            if (!file_exists($envPath)) {
                throw new \Exception('.env file not found');
            }
            
            $envContent = file_get_contents($envPath);

            if (preg_match('/^TELEMETRY_ENABLED=.*$/m', $envContent)) {
                $envContent = preg_replace(
                    '/^TELEMETRY_ENABLED=.*$/m',
                    'TELEMETRY_ENABLED=' . ($enabled ? 'true' : 'false'),
                    $envContent
                );
            } else {
                $envContent .= "\nTELEMETRY_ENABLED=" . ($enabled ? 'true' : 'false') . "\n";
            }

            file_put_contents($envPath, $envContent);

            // Clear all caches to ensure config is reloaded
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            
            // Reload config
            app()->make('config')->set('telemetry.enabled', $enabled);

            $this->telemetryEnabled = $enabled;

            Notification::make()
                ->title($enabled ? 'Telemetry Enabled' : 'Telemetry Disabled')
                ->success()
                ->body('Setting saved successfully')
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error updating telemetry setting')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Test telemetry connection
     */
    public function testConnection(): void
    {
        try {
            $telemetry = app(TelemetryService::class);
            $success = $telemetry->testConnection();

            if ($success) {
                Notification::make()
                    ->title('Connection Successful')
                    ->success()
                    ->body('Telemetry API is reachable')
                    ->send();
            } else {
                Notification::make()
                    ->title('Connection Failed')
                    ->warning()
                    ->body('Could not reach telemetry API')
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection Error')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Send test ping
     */
    public function sendTestPing(): void
    {
        try {
            $telemetry = app(TelemetryService::class);
            $telemetry->sendDailyHeartbeat();

            Notification::make()
                ->title('Test Ping Sent')
                ->success()
                ->body('Check telemetry logs to verify')
                ->send();

            // Refresh page
            $this->mount();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to Send Ping')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Get header actions
     */
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('test_connection')
                ->label('Test Connection')
                ->icon('heroicon-o-wifi')
                ->action('testConnection'),

            \Filament\Actions\Action::make('send_test_ping')
                ->label('Send Test Ping')
                ->icon('heroicon-o-paper-airplane')
                ->action('sendTestPing')
                ->visible($this->telemetryEnabled),

            \Filament\Actions\Action::make('view_privacy')
                ->label('Privacy Policy')
                ->icon('heroicon-o-shield-check')
                ->url(config('telemetry.privacy_url', 'https://vantapress.com/telemetry'))
                ->openUrlInNewTab(),
        ];
    }
}
