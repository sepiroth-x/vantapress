<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AttributionWidget extends Widget
{
    protected static string $view = 'filament.widgets.attribution-widget';
    
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 'full',
        'lg' => 'full',
        'xl' => 'full',
        '2xl' => 'full',
    ];
    
    protected static ?int $sort = 999;
}
