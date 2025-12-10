<?php

namespace Modules\TheVillainTerminal\Services;

use Illuminate\Support\Facades\Log;

/**
 * The Villain Terminal - Command Parser & Executor
 * 
 * Parses user input and executes registered commands.
 */
class TerminalExecutor
{
    /**
     * Parse and execute a command
     * 
     * @param string $input Raw user input
     * @return array Command result
     */
    public function execute(string $input): array
    {
        $input = trim($input);

        // Empty input
        if (empty($input)) {
            return $this->emptyResponse();
        }

        // Parse command and arguments
        $parsed = $this->parseInput($input);
        $command = $parsed['command'];
        $args = $parsed['args'];

        Log::info("[Villain Terminal] Executing command", [
            'command' => $command,
            'args' => $args,
            'user_id' => auth()->id()
        ]);

        // Check if command exists
        if (!CommandRegistry::exists($command)) {
            return $this->commandNotFound($command);
        }

        // Get command definition
        $commandDef = CommandRegistry::get($command);

        try {
            // Execute command handler
            $handler = $commandDef['handler'];
            
            if (is_callable($handler)) {
                $output = $handler($args);
            } elseif (is_string($handler) && str_contains($handler, '@')) {
                // Class@method format
                [$class, $method] = explode('@', $handler);
                $instance = app($class);
                $output = $instance->$method($args);
            } else {
                return $this->error("Invalid command handler for '{$command}'");
            }

            // Ensure output is array
            if (is_string($output)) {
                $output = ['output' => $output, 'success' => true];
            }

            // Add current working directory for prompt updates
            $cwd = \Illuminate\Support\Facades\Session::get('villain_terminal_cwd', '/home/villain');

            return array_merge([
                'success' => true,
                'command' => $command,
                'timestamp' => now()->toDateTimeString(),
                'cwd' => $cwd,
            ], $output);

        } catch (\Exception $e) {
            Log::error("[Villain Terminal] Command execution failed", [
                'command' => $command,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->error("Command failed: " . $e->getMessage());
        }
    }

    /**
     * Parse user input into command and arguments
     * 
     * @param string $input
     * @return array
     */
    protected function parseInput(string $input): array
    {
        // Split by spaces, but respect quotes
        preg_match_all('/"[^"]*"|\S+/', $input, $matches);
        $parts = $matches[0];

        // Remove quotes from arguments
        $parts = array_map(function ($part) {
            return trim($part, '"\'');
        }, $parts);

        $command = array_shift($parts);
        $args = $parts;

        return [
            'command' => $command,
            'args' => $args,
        ];
    }

    /**
     * Command not found response
     * 
     * @param string $command
     * @return array
     */
    protected function commandNotFound(string $command): array
    {
        $suggestions = $this->getSuggestions($command);
        $message = "Command not found: {$command}";

        if (!empty($suggestions)) {
            $message .= "\n\nDid you mean?\n  " . implode("\n  ", $suggestions);
        }

        $message .= "\n\nType 'vanta-help' to see all available commands.";

        return [
            'success' => false,
            'error' => true,
            'output' => $message,
            'command' => $command,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Get command suggestions based on similarity
     * 
     * @param string $command
     * @return array
     */
    protected function getSuggestions(string $command): array
    {
        $allCommands = CommandRegistry::getCommandNames();
        $suggestions = [];

        foreach ($allCommands as $availableCommand) {
            $similarity = similar_text($command, $availableCommand, $percent);
            if ($percent > 60) {
                $suggestions[] = $availableCommand;
            }
        }

        return array_slice($suggestions, 0, 3);
    }

    /**
     * Empty input response
     * 
     * @return array
     */
    protected function emptyResponse(): array
    {
        return [
            'success' => true,
            'output' => '',
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Error response
     * 
     * @param string $message
     * @return array
     */
    protected function error(string $message): array
    {
        return [
            'success' => false,
            'error' => true,
            'output' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Success response
     * 
     * @param string $message
     * @return array
     */
    protected function success(string $message): array
    {
        return [
            'success' => true,
            'output' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
