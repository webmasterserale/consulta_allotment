<?php

namespace App\Filament\Pages;

use App\Models\Allotment;
use App\Models\LugarUso;
use App\Models\TipoUnid;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;

class AllotmentsAgrupados extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;

    protected string $view = 'filament.pages.allotments-agrupados';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $title = 'Allotments Agrupados';

    protected static ?string $navigationLabel = 'Allotments Agrupados';

    private const LIMITE_REGISTROS = 1500;

    public ?array $data = [];

    public bool $limiteAlcanzado = false;

    public function mount(): void
    {
        $this->form->fill([
            'hotel' => null,
            'estado' => null,
            'pts' => null,
            'unidad' => null,
            'entraDesde' => now()->startOfMonth()->format('Y-m-d'),
            'entraHasta' => now()->addMonths(2)->endOfMonth()->format('Y-m-d'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)
                    ->components([
                        Select::make('hotel')
                            ->label('Hotel')
                            ->options(fn () => LugarUso::query()->orderBy('DESUSO')->pluck('DESUSO', 'CVELUG')->toArray())
                            ->searchable()
                            ->placeholder('Todos los hoteles')
                            ->live(),
                        Select::make('estado')
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
                            ->placeholder('Todos los estados')
                            ->live(),
                        Select::make('pts')
                            ->label('Puntos')
                            ->options([
                                'II' => 'Interval',
                                'RCI' => 'RCI',
                                'PTS' => 'Puntos',
                                'GETAWAY' => 'Getaway',
                                'II RSV' => 'Interval Reservado',
                            ])
                            ->placeholder('Todos')
                            ->live(),
                        Select::make('unidad')
                            ->label('Unidad')
                            ->options(function (Get $get) {
                                $hotelId = $get('hotel');

                                return TipoUnid::query()
                                    ->when($hotelId, fn ($query) => $query->where('hotel', $hotelId))
                                    ->pluck('nombre', 'unidad')
                                    ->toArray();
                            })
                            ->searchable()
                            ->placeholder('Todas')
                            ->live(),
                        DatePicker::make('entraDesde')
                            ->label('Entra desde')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->live(),
                        DatePicker::make('entraHasta')
                            ->label('Entra hasta')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->live(),
                    ]),
            ])
            ->statePath('data');
    }

    public function getGruposProperty(): Collection
    {
        $filtros = $this->form->getState();

        $registros = Allotment::query()
            ->with('lugaruso')
            ->when($filtros['hotel'] ?? null, fn ($query, $valor) => $query->where('HOTEL', $valor))
            ->when($filtros['estado'] ?? null, fn ($query, $valor) => $query->where('estado', $valor))
            ->when($filtros['pts'] ?? null, fn ($query, $valor) => $query->where('PTS', $valor))
            ->when($filtros['unidad'] ?? null, fn ($query, $valor) => $query->where('unidad', $valor))
            ->when(
                ($filtros['entraDesde'] ?? null) && ($filtros['entraHasta'] ?? null),
                fn ($query) => $query->whereBetween('entra', [$filtros['entraDesde'], $filtros['entraHasta']])
            )
            ->orderBy('PTS')
            ->orderBy('entra')
            ->orderBy('unidad')
            ->limit(self::LIMITE_REGISTROS + 1)
            ->get();

        $this->limiteAlcanzado = $registros->count() > self::LIMITE_REGISTROS;

        return $registros->take(self::LIMITE_REGISTROS)
            ->groupBy(fn ($allotment) => $allotment->PTS ?: 'Sin especificar')
            ->map(fn (Collection $porPts) => $porPts
                ->groupBy(fn ($allotment) => $allotment->entra?->format('Y-m-d') ?: 'Sin fecha')
                ->map(fn (Collection $porEntra) => $porEntra->groupBy(fn ($allotment) => $allotment->unidad ?: 'Sin unidad'))
            );
    }

    public static function colorEstado(?string $estado): array
    {
        return match ($estado) {
            'DISPONIBLE' => ['bg' => '#dcfce7', 'text' => '#166534'],
            'ASIGNADA' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
            'UNIFICADA' => ['bg' => '#e0f2fe', 'text' => '#075985'],
            'DEVUELTA' => ['bg' => '#f3f4f6', 'text' => '#374151'],
            'BLOQUEADA' => ['bg' => '#000000', 'text' => '#ffffff'],
            'CEDIDA' => ['bg' => '#fef9c3', 'text' => '#854d0e'],
            default => ['bg' => '#f3f4f6', 'text' => '#374151'],
        };
    }

    public static function rangoDias(?\Illuminate\Support\Carbon $entra, ?\Illuminate\Support\Carbon $sale): string
    {
        if (! $entra || ! $sale) {
            return '-';
        }

        return ucfirst($entra->translatedFormat('l')) . ' a ' . ucfirst($sale->translatedFormat('l'));
    }

    public static function noches(?\Illuminate\Support\Carbon $entra, ?\Illuminate\Support\Carbon $sale): string
    {
        if (! $entra || ! $sale) {
            return '-';
        }

        $noches = $entra->diffInDays($sale);

        return $noches . ($noches === 1 ? ' noche' : ' noches');
    }
}
