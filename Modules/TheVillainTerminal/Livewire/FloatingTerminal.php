<?php

namespace Modules\TheVillainTerminal\Livewire;

use Livewire\Component;
use Modules\TheVillainTerminal\Services\TerminalExecutor;
use Illuminate\Support\Facades\Log;

class FloatingTerminal extends Component
{
    public string $command = '';
    public array $history = [];
    public string $username = '';
    public string $prompt = '';
    public bool $isOpen = false;

    public function mount(): void
    {
        $user = auth()->user();
        
        if ($user && $user->name) {
            $this->username = strtolower(str_replace(' ', '', $user->name));
        } elseif ($user && $user->email) {
            $this->username = strtolower(explode('@', $user->email)[0]);
        } else {
            $this->username = 'villain';
        }

        $this->prompt = "{$this->username}@vantapress:~$ ";
        
        $this->history[] = [
            'type' => 'output',
            'content' => "VantaPress Terminal v1.0.0\nType 'vanta-help' for available commands.\n"
        ];
    }

    public function executeCommand(): void
    {
        if (empty(trim($this->command))) {
            return;
        }

        // Add command to history
        $this->history[] = [
            'type' => 'command',
            'content' => $this->prompt . $this->command
        ];

        Log::info('[Floating Terminal] Command received', [
            'command' => $this->command,
            'user_id' => auth()->id()
        ]);

        // Handle special commands
        if ($this->command === 'clear') {
            $this->history = [];
            $this->command = '';
            return;
        }

        if ($this->command === 'help' || $this->command === 'vanta-help') {
            $this->command = 'vanta-help';
        }

        // Execute through the terminal executor
        $executor = new TerminalExecutor();
        $result = $executor->execute($this->command);

        // Add result to history
        if ($result['success']) {
            $this->history[] = [
                'type' => 'output',
                'content' => $result['output']
            ];
        } else {
            $this->history[] = [
                'type' => 'error',
                'content' => $result['output']
            ];
        }

        // Clear command input
        $this->command = '';
        
        // Dispatch event to scroll to bottom
        $this->dispatch('terminal-output-updated');
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        return view('thevillainterrminal::livewire.floating-terminal');
    }
}
