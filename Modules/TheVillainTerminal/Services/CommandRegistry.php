<?php

namespace Modules\TheVillainTerminal\Services;

use Illuminate\Support\Facades\Log;

/**
 * The Villain Terminal - Command Registry
 * 
 * Manages all available terminal commands.
 * Allows modules to register custom commands dynamically.
 */
class CommandRegistry
{
    /**
     * Registered commands
     * 
     * @var array
     */
    protected static array $commands = [];

    /**
     * Command aliases
     * 
     * @var array
     */
    protected static array $aliases = [];

    /**
     * Register a new command
     * 
     * @param string $name Command name
     * @param callable|string $handler Command handler (closure or class@method)
     * @param string $description Command description
     * @param array $options Command options/flags
     * @return void
     */
    public static function register(string $name, $handler, string $description = '', array $options = []): void
    {
        // Allow Unix commands (pwd, cd, ls, etc.) and vanta-* commands
        $isUnixCommand = in_array($name, ['pwd', 'cd', 'ls', 'mkdir', 'rmdir', 'touch', 'rm', 'cat', 'clear']);
        $isVantaCommand = str_starts_with($name, 'vanta-');
        
        if (!$isUnixCommand && !$isVantaCommand) {
            Log::warning("[Villain Terminal] Command '{$name}' rejected - must start with 'vanta-' or be a Unix command");
            return;
        }

        static::$commands[$name] = [
            'handler' => $handler,
            'description' => $description,
            'options' => $options,
            'registered_at' => now(),
        ];

        Log::info("[Villain Terminal] Command registered: {$name}");
    }

    /**
     * Register a command alias
     * 
     * @param string $alias
     * @param string $command
     * @return void
     */
    public static function alias(string $alias, string $command): void
    {
        if (!str_starts_with($alias, 'vanta-')) {
            return;
        }

        static::$aliases[$alias] = $command;
    }

    /**
     * Get all registered commands
     * 
     * @return array
     */
    public static function all(): array
    {
        return static::$commands;
    }

    /**
     * Get command by name (or alias)
     * 
     * @param string $name
     * @return array|null
     */
    public static function get(string $name): ?array
    {
        // Check if it's an alias
        if (isset(static::$aliases[$name])) {
            $name = static::$aliases[$name];
        }

        return static::$commands[$name] ?? null;
    }

    /**
     * Check if command exists
     * 
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset(static::$commands[$name]) || isset(static::$aliases[$name]);
    }

    /**
     * Get all command names (for autocomplete)
     * 
     * @return array
     */
    public static function getCommandNames(): array
    {
        return array_merge(
            array_keys(static::$commands),
            array_keys(static::$aliases)
        );
    }

    /**
     * Search commands by keyword
     * 
     * @param string $keyword
     * @return array
     */
    public static function search(string $keyword): array
    {
        $results = [];

        foreach (static::$commands as $name => $command) {
            if (str_contains($name, $keyword) || str_contains($command['description'], $keyword)) {
                $results[$name] = $command;
            }
        }

        return $results;
    }

    /**
     * Clear all registered commands (for testing)
     * 
     * @return void
     */
    public static function clear(): void
    {
        static::$commands = [];
        static::$aliases = [];
    }
}
