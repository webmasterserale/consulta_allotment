<?php

namespace App\Filament\Resources\Allotments\Pages;

use App\Filament\Resources\Allotments\AllotmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAllotment extends ViewRecord
{
    protected static string $resource = AllotmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
