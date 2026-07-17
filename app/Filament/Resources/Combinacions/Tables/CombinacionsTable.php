<?php

namespace App\Filament\Resources\Combinacions\Tables;

use App\Services\BuscadorCombinaciones;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CombinacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('detalles.tipoUnid'))
            ->defaultSort(fn (Builder $query) => $query
                ->orderBy('hotel')
                ->orderBy('total')
                ->orderBy('adultos', 'desc')
                ->orderBy('prioridad'))
            ->columns([
                TextColumn::make('hotel')
                    ->label('Destino')
                    ->formatStateUsing(fn (int $state) => BuscadorCombinaciones::DESTINOS[$state] ?? "Hotel $state")
                    ->sortable(),
                TextColumn::make('adultos')
                    ->label('Adultos')
                    ->sortable(),
                TextColumn::make('ninos')
                    ->label('Niños')
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->sortable(),
                TextColumn::make('prioridad')
                    ->label('Prioridad')
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->label('Combinación')
                    ->searchable(
                        query: fn (Builder $query, string $search): Builder => $query->whereHas(
                            'detalles.tipoUnid',
                            fn (Builder $q) => $q->where('nombre', 'like', "%{$search}%"),
                        ),
                        isIndividual: true,
                    ),
                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('hotel')
                    ->label('Destino')
                    ->options(BuscadorCombinaciones::DESTINOS),
                SelectFilter::make('adultos')
                    ->label('Adultos')
                    ->options(array_combine(range(1, 8), range(1, 8))),
                SelectFilter::make('ninos')
                    ->label('Niños')
                    ->options(array_combine(range(0, 7), range(0, 7))),
                SelectFilter::make('total')
                    ->label('Total de personas')
                    ->options(array_combine(range(1, 8), range(1, 8))),
                TernaryFilter::make('activo')
                    ->label('Activo'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
