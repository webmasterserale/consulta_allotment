<?php

namespace App\Filament\Resources\Allotments\Pages;

use App\Filament\Resources\Allotments\AllotmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAllotments extends ListRecords
{
    protected static string $resource = AllotmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
