<?php

namespace App\Filament\Pages;

use App\Services\BuscadorCombinaciones;
use App\Services\DisponibilidadAllotment;
use BackedEnum;
use Carbon\CarbonImmutable;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Buscador extends Page
{
    protected string $view = 'filament.pages.buscador';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $title = 'Buscador';

    private const DIAS = [1 => 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];

    private const MESES_CORTOS = [1 => 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

    private const MESES_LARGOS = [1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    public int $hotel = 2;

    public string $mes = '';

    public int $noches = 1;

    public int $adultos = 2;

    public int $ninos = 0;

    public ?array $resultados = null;

    public function mount(): void
    {
        $this->mes = now()->format('Y-m');
    }

    public function getDestinosProperty(): array
    {
        return BuscadorCombinaciones::DESTINOS;
    }

    /**
     * Mes actual y los 5 siguientes, con etiqueta en español.
     */
    public function getMesesProperty(): array
    {
        $meses = [];
        $fecha = now()->startOfMonth();

        for ($i = 0; $i < 6; $i++) {
            $meses[$fecha->format('Y-m')] = [
                'nombre' => self::MESES_LARGOS[$fecha->month],
                'anio' => $fecha->year,
            ];
            $fecha = $fecha->addMonth();
        }

        return $meses;
    }

    public function buscar(): void
    {
        // Los controles son client-side: se revalida todo en el servidor
        $this->noches = max(1, min(7, $this->noches));
        $this->adultos = max(1, min(8, $this->adultos));
        $this->ninos = max(0, min(7, $this->ninos));

        if ($this->adultos + $this->ninos > 8) {
            $this->ninos = 8 - $this->adultos;
        }

        if (! array_key_exists($this->mes, $this->meses)) {
            $this->mes = now()->format('Y-m');
        }

        if (! array_key_exists($this->hotel, $this->destinos)) {
            $this->hotel = 2;
        }

        $paquetes = app(DisponibilidadAllotment::class)->paquetes(
            hotel: $this->hotel,
            mes: $this->mes,
            noches: $this->noches,
            adultos: $this->adultos,
            ninos: $this->ninos,
        );

        $this->resultados = array_map(fn (array $paquete) => [
            'entra' => $this->formatearFecha($paquete['entra']),
            'sale' => $this->formatearFecha($paquete['sale']),
            'combinaciones' => array_map(fn (array $combinacion) => [
                'prioridad' => $combinacion['prioridad'],
                'descripcion' => $combinacion['descripcion'],
                'disponibles' => $combinacion['disponibles'],
                'unidades' => array_map(fn (array $unidad) => [
                    'tipo' => $unidad['tipo'],
                    'tramos' => array_map(
                        fn (array $tramo) => $this->formatearFecha($tramo['entra'])
                            . ' → ' . $this->formatearFecha($tramo['sale'])
                            . ' · ' . $this->nochesDelTramo($tramo)
                            . ' · Conf. ' . ($tramo['confirma'] !== '' ? $tramo['confirma'] : 's/n'),
                        $unidad['tramos'],
                    ),
                ], $combinacion['unidades']),
            ], $paquete['combinaciones']),
        ], $paquetes);
    }

    private function formatearFecha(string $fecha): string
    {
        $f = CarbonImmutable::createFromFormat('Y-m-d', $fecha);

        return self::DIAS[$f->dayOfWeekIso] . ' ' . $f->format('d') . ' ' . self::MESES_CORTOS[$f->month];
    }

    private function nochesDelTramo(array $tramo): string
    {
        $noches = (int) CarbonImmutable::createFromFormat('Y-m-d', $tramo['entra'])
            ->diffInDays(CarbonImmutable::createFromFormat('Y-m-d', $tramo['sale']));

        return $noches . ($noches === 1 ? ' noche' : ' noches');
    }
}
