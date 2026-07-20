<?php

namespace App\Filament\Resources\Allotments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Colors\Color;
use App\Models\TipoUnid;

class AllotmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unidad')
                    ->searchable(),
                TextColumn::make('entra')
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('sale')
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('JUNTO')
                    ->searchable(),
                TextColumn::make('NOMBRE')
                    ->searchable(),
                TextColumn::make('CONFIRMA')
                    ->searchable(),
                TextColumn::make('USUARIO')
                    ->searchable(),
                TextColumn::make('lugaruso.DESUSO')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('PTS')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): array => match ($state) {
                        'DISPONIBLE' => Color::Green,
                        'ASIGNADA' => Color::Blue,
                        'UNIFICADA' => Color::Sky,
                        'DEVUELTA' => Color::Gray,
                        'BLOQUEADA' => Color::hex('#000000'),
                        'CEDIDA' => Color::Yellow,
                        default => Color::Gray,
                    })
                    ->searchable(),
                TextColumn::make('nombre_antes')
                    ->searchable(),
                TextColumn::make('tipo')
                    ->searchable(),
                TextColumn::make('visible_web')
                    ->searchable()
            ])
            ->filters([
                // FILTRO SELECCIONABLE DE HOTEL
                SelectFilter::make('hotel')
                    ->label('Hotel')
                    ->relationship('lugaruso', 'DESUSO')
                    ->searchable()
                    ->placeholder('Selecciona un hotel'),
                // FILTRO DE TIPO
                SelectFilter::make('pts')
                    ->label('Puntos')
                    ->options([
                        'II' => 'Interval',
                        'RCI' => 'RCI',
                        'PTS' => 'Puntos',
                        'GETAWAY' => 'Getaway',
                        'II RSV' => 'Interval Reservado'
                    ])
                    ->placeholder('Selecciona un tipo'),
                // FILTRO DE ESTADO
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'DISPONIBLE' => 'Disponible',
                        'BLOQUEADA' => 'Bloqueada',
                        'ASIGNADA' => 'Asignada',
                        'UNIFICADA' => 'Unificada',
                        'CEDIDA' => 'Cedida',
                        'DEVUELTA' => 'Devuelta',
                        'HOLD' => 'Hold',
                    ])
                    ->placeholder('Selecciona un estado'),
                // FILTRO DE ENTRADA
                Filter::make('entra')
                    ->form([
                        Select::make('entra')
                            ->label('Mes de entrada')
                            ->options([
                                '01' => 'Enero',
                                '02' => 'Febrero',
                                '03' => 'Marzo',
                                '04' => 'Abril',
                                '05' => 'Mayo',
                                '06' => 'Junio',
                                '07' => 'Julio',
                                '08' => 'Agosto',
                                '09' => 'Septiembre',   
                                '10' => 'Octubre',
                                '11' => 'Noviembre',
                                '12' => 'Diciembre',
                            ])
                            ->placeholder('Selecciona un mes'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['entra']) {
                            $query->whereMonth('entra', $data['entra']);
                        }
                    }),
                // FILTRO DE UNIDAD
                Filter::make('unidad')
                    ->form([
                        Select::make('unidad')
                            ->label('Unidad')
                            ->options(function (Get $get) {
                                $hotelId = $get('../hotel.value');

                                return TipoUnid::query()
                                    ->when($hotelId, fn ($query) => $query->where('hotel', $hotelId))
                                    ->pluck('nombre', 'unidad')
                                    ->toArray();
                            })
                            ->searchable()
                            ->placeholder('Selecciona una unidad'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['unidad'] ?? null) {
                            $query->where('unidad', $data['unidad']);
                        }
                    }),
            ])
            ->deferFilters(false)

            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
