<?php

namespace App\Services;

use App\Models\Combinacion;
use Illuminate\Support\Collection;

class BuscadorCombinaciones
{
    /**
     * Destinos disponibles: etiqueta => número de hotel en allotment/tipo_unid.
     */
    public const DESTINOS = [
        1 => 'Antigua Guatemala',
        2 => 'Pacífico',
    ];

    /**
     * Combinaciones válidas para un hotel y una distribución de pax,
     * ordenadas por prioridad. La validación de disponibilidad por
     * noches contra el allotment se agregará como una capa posterior.
     *
     * @return Collection<int, Combinacion>
     */
    public function buscar(int $hotel, int $adultos, int $ninos): Collection
    {
        return Combinacion::query()
            ->where('hotel', $hotel)
            ->where('adultos', $adultos)
            ->where('ninos', $ninos)
            ->where('activo', true)
            ->with('detalles.tipoUnid')
            ->orderBy('prioridad')
            ->get();
    }
}
