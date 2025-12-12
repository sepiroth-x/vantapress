<?php

namespace Modules\VPEssential1\Filament\Resources\UserVerificationResource\Pages;

use Modules\VPEssential1\Filament\Resources\UserVerificationResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUserVerification extends EditRecord
{
    protected static string $resource = UserVerificationResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Verification status updated')
            ->body('User verification has been updated successfully.');
    }
}
