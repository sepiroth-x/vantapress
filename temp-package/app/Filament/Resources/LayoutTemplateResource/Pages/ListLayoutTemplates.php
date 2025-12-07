<?php

namespace App\Filament\Resources\LayoutTemplateResource\Pages;

use App\Filament\Resources\LayoutTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLayoutTemplates extends ListRecords
{
    protected static string $resource = LayoutTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
