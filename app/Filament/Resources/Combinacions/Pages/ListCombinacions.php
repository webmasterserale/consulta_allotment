<?php

namespace App\Filament\Resources\Combinacions\Pages;

use App\Filament\Resources\Combinacions\CombinacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCombinacions extends ListRecords
{
    protected static string $resource = CombinacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
