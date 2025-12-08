<?php

namespace Modules\TheVillainTerminal\Commands;

use Modules\TheVillainTerminal\Services\CommandRegistry;

/**
 * The Villain Terminal - Help Command
 * 
 * Display available commands and their descriptions.
 */
class HelpCommand
{
    /**
     * Display help information
     * 
     * @param array $args
     * @return array
     */
    public function handle(array $args): array
    {
        $output = [];
        
        $output[] = "<span style='color: #ffff00; font-size: 16px; font-weight: bold;'>";
        $output[] = "══════════════════════════════════════════════════════════════";
        $output[] = "                  THE VILLAIN TERMINAL - HELP                       ";
        $output[] = "══════════════════════════════════════════════════════════════";
        $output[] = "</span>";
        $output[] = "";
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>Available Commands:</span>";
        $output[] = "";

        $commands = CommandRegistry::all();
        
        if (empty($commands)) {
            $output[] = "No commands registered.";
        } else {
            foreach ($commands as $name => $command) {
                $description = $command['description'] ?? 'No description';
                $output[] = "  • <span style='color: #00ff41; font-weight: bold;'>{$name}</span> - {$description}";
            }
        }
        
        $output[] = "";
        $output[] = "<span style='color: #ffff00; font-weight: bold;'>Tips:</span>";
        $output[] = "  • Use UP/DOWN arrows to navigate command history";
        $output[] = "  • Use TAB for command autocomplete";
        $output[] = "  • Type 'clear' to clear the terminal";
        $output[] = "  • All commands must start with 'vanta-'";

        return ['output' => implode("\n", $output), 'success' => true];
    }
}
