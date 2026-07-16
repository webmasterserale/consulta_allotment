<?php

namespace App\Filament\Resources\Combinacions;

use App\Filament\Resources\Combinacions\Pages\CreateCombinacion;
use App\Filament\Resources\Combinacions\Pages\EditCombinacion;
use App\Filament\Resources\Combinacions\Pages\ListCombinacions;
use App\Filament\Resources\Combinacions\Schemas\CombinacionForm;
use App\Filament\Resources\Combinacions\Tables\CombinacionsTable;
use App\Models\Combinacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CombinacionResource extends Resource
{
    protected static ?string $model = Combinacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'combinación';

    protected static ?string $pluralModelLabel = 'combinaciones';

    protected static ?string $navigationLabel = 'Combinaciones';

    public static function form(Schema $schema): Schema
    {
        return CombinacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CombinacionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCombinacions::route('/'),
            'create' => CreateCombinacion::route('/create'),
            'edit' => EditCombinacion::route('/{record}/edit'),
        ];
    }
}
