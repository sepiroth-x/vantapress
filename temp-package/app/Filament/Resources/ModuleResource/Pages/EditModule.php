<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModule extends EditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->after(function ($record) {
                    // Delete the module folder
                    $modulePath = base_path('Modules/' . $record->slug);
                    if (\Illuminate\Support\Facades\File::exists($modulePath)) {
                        \Illuminate\Support\Facades\File::deleteDirectory($modulePath);
                    }
                }),
        ];
    }
}
