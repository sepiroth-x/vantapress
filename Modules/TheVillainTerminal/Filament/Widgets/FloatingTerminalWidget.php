<?php

namespace Modules\TheVillainTerminal\Filament\Widgets;

use Filament\Widgets\Widget;

class FloatingTerminalWidget extends Widget
{
    protected static string $view = 'thevillainterrminal::filament.widgets.floating-terminal-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static bool $isLazy = false;
    
    public static function canView(): bool
    {
        return auth()->check();
    }
    
    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }
}
