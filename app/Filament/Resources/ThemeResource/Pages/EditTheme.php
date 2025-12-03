<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTheme extends EditRecord
{
    protected static string $resource = ThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn ($record) => $record->is_active)
                ->requiresConfirmation(),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure only one active theme
        if (isset($data['is_active']) && $data['is_active'] && !$this->record->is_active) {
            \App\Models\Theme::query()->update(['is_active' => false]);
        }
        
        return $data;
    }
}
