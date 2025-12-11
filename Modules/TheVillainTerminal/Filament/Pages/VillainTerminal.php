<?php

namespace Modules\TheVillainTerminal\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Modules\TheVillainTerminal\Services\TerminalExecutor;
use Illuminate\Support\Facades\Log;

/**
 * The Villain Terminal - Filament Page
 * 
 * Web-based terminal interface for VantaPress.
 */
class VillainTerminal extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static string $view = 'thevillainterrminal::terminal';

    protected static ?string $navigationLabel = 'Villain Terminal';

    protected static ?string $title = 'The Villain Terminal';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;
    
    // Hide from navigation since we use the floating terminal widget instead
    protected static bool $shouldRegisterNavigation = false;

    public string $username = '';
    public string $prompt = '';

    /**
     * Check if module is enabled
     */
    protected static function isModuleEnabled(): bool
    {
        try {
            if (!\Schema::hasTable('modules')) {
                return false;
            }
            
            $module = \App\Models\Module::where('slug', 'TheVillainTerminal')->first();
            return $module && $module->is_enabled;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Override getSlug to prevent route registration when disabled
     */
    public static function getSlug(): string
    {
        if (!static::isModuleEnabled()) {
            // Return a slug that won't conflict but also won't register
            return 'disabled-villain-terminal-' . md5(random_bytes(8));
        }
        
        return 'villain-terminal';
    }

    /**
     * Mount the terminal page
     */
    public function mount(): void
    {
        // Set username for prompt
        $user = auth()->user();
        
        if ($user && $user->name) {
            $this->username = strtolower(str_replace(' ', '', $user->name));
        } elseif ($user && $user->email) {
            $this->username = strtolower(explode('@', $user->email)[0]);
        } else {
            $this->username = 'villain';
        }

        $this->prompt = "{$this->username}@villain-terminal:~$ ";

        Log::info('[Villain Terminal] Terminal accessed', [
            'user_id' => auth()->id(),
            'username' => $this->username
        ]);
    }

    /**
     * Execute a terminal command (called from frontend)
     * 
     * @param string $command
     * @return array
     */
    public function executeCommand(string $command): array
    {
        Log::info('[Villain Terminal] Command received', [
            'command' => $command,
            'user_id' => auth()->id()
        ]);

        // Special commands that don't need registry
        if ($command === 'clear') {
            return [
                'success' => true,
                'clear' => true,
                'output' => ''
            ];
        }

        if ($command === 'help' || $command === 'vanta-help') {
            $command = 'vanta-help';
        }

        // Execute through the terminal executor
        $executor = new TerminalExecutor();
        $result = $executor->execute($command);

        return $result;
    }

    /**
     * Get available commands for autocomplete
     * 
     * @return array
     */
    public function getAvailableCommands(): array
    {
        return \Modules\TheVillainTerminal\Services\CommandRegistry::getCommandNames();
    }

    /**
     * Check if user can access terminal
     * 
     * @return bool
     */
    public static function canAccess(): bool
    {
        // Allow all authenticated users
        return auth()->check();
    }

    /**
     * Prevent navigation registration - we use floating terminal widget instead
     * 
     * @return array
     */
    public static function getNavigationItems(): array
    {
        return [];
    }

    /**
     * Get header actions
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}