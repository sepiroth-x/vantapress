<?php

namespace App\Filament\Resources\LayoutTemplateResource\Pages;

use App\Filament\Resources\LayoutTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLayoutTemplate extends EditRecord
{
    protected static string $resource = LayoutTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
