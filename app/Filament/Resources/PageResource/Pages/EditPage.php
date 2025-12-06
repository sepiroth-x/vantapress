<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure author_id is preserved if not set
        if (empty($data['author_id'])) {
            $data['author_id'] = auth()->id();
        }
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Preserve original author if author_id not in form
        if (!isset($data['author_id']) && $this->record->author_id) {
            $data['author_id'] = $this->record->author_id;
        }
        
        return $data;
    }
}
