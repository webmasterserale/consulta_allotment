<?php

namespace App\Filament\Resources\Combinacions\Pages;

use App\Filament\Resources\Combinacions\CombinacionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCombinacion extends EditRecord
{
    protected static string $resource = CombinacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
