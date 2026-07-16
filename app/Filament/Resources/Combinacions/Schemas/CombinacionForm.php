<?php

namespace App\Filament\Resources\Combinacions\Schemas;

use App\Models\TipoUnid;
use App\Services\BuscadorCombinaciones;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CombinacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('hotel')
                    ->label('Destino')
                    ->options(BuscadorCombinaciones::DESTINOS)
                    ->required()
                    ->live(),
                TextInput::make('adultos')
                    ->label('Adultos')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(8),
                TextInput::make('ninos')
                    ->label('Niños')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(7),
                TextInput::make('total')
                    ->label('Total de personas')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(8),
                TextInput::make('prioridad')
                    ->label('Prioridad')
                    ->helperText('1 = se ofrece primero')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),
                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
                Repeater::make('detalles')
                    ->label('Unidades de la combinación')
                    ->relationship()
                    ->columnSpanFull()
                    ->columns(2)
                    ->minItems(1)
                    ->schema([
                        Select::make('tipo_unid_id')
                            ->label('Tipo de unidad')
                            ->options(function (Get $get): array {
                                $hotel = $get('../../hotel');

                                return TipoUnid::query()
                                    ->when($hotel !== null, fn ($q) => $q->where('hotel', $hotel))
                                    ->orderBy('nombre')
                                    ->get()
                                    ->mapWithKeys(fn (TipoUnid $t) => [$t->id => trim($t->nombre) . ' (' . $t->unidad . ')'])
                                    ->all();
                            })
                            ->required(),
                        TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->default(1),
                    ]),
            ]);
    }
}
