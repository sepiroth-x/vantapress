<?php

namespace Modules\VPTelemetryServer\Filament\Resources\InstallationResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\VPTelemetryServer\Filament\Resources\InstallationResource;

class ListInstallations extends ListRecords
{
    protected static string $resource = InstallationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
