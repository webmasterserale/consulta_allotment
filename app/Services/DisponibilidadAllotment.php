<?php

namespace App\Services;

use App\Models\Allotment;
use Carbon\CarbonImmutable;

class DisponibilidadAllotment
{
    public function __construct(
        private readonly BuscadorCombinaciones $combinaciones,
    ) {}

    /**
     * Paquetes disponibles en un mes: para cada fecha de entrada posible,
     * compone estancias de N noches encadenando bloques del allotment
     * (un bloque vie→dom es indivisible: solo entra en cadenas que lo
     * contengan completo) y verifica qué combinaciones del catálogo
     * tienen suficientes unidades libres en todo el rango. Cada combinación
     * incluye los registros de allotment que la respaldan (entra, sale,
     * confirmación) agrupados por unidad asignada.
     *
     * @return array<int, array{entra: string, sale: string, combinaciones: array}>
     */
    /**
     * @param  ?string  $tipoDias  'entre_semana' (dom-jue), 'fin_semana' (vie-sáb) o null para todos
     */
    public function paquetes(
        int $hotel,
        string $mes, // formato 'Y-m'
        int $noches,
        int $adultos,
        int $ninos,
        ?string $canal = 'PTS',
        ?string $tipoDias = null,
    ): array {
        $combos = $this->combinaciones->buscar($hotel, $adultos, $ninos);

        if ($combos->isEmpty()) {
            return [];
        }

        // Códigos de unidad usados por las combinaciones y máxima cantidad requerida
        $codigos = [];
        $maxCantidad = 1;

        foreach ($combos as $combo) {
            foreach ($combo->detalles as $detalle) {
                $codigos[trim($detalle->tipoUnid->unidad)] = true;
                $maxCantidad = max($maxCantidad, $detalle->cantidad);
            }
        }

        $codigos = array_keys($codigos);

        // Se calcula más flujo del estrictamente necesario para poder
        // mostrar cuántas unidades libres hay (topado para no trabajar de más)
        $maxCantidad = max($maxCantidad, 10);

        $inicioMes = CarbonImmutable::createFromFormat('Y-m-d', $mes . '-01')->startOfDay();
        $finMes = $inicioMes->endOfMonth()->startOfDay();
        $desde = $inicioMes->isPast() ? CarbonImmutable::today() : $inicioMes;

        // Bloques libres del rango (con margen de N noches para cadenas que
        // salen del mes). Se excluyen filas basura con rangos inválidos.
        $bloques = Allotment::query()
            ->where('HOTEL', $hotel)
            ->whereIn('unidad', $codigos)
            ->where('estado', 'DISPONIBLE')
            ->where('tipo', 'ALLO')
            ->where('visible_web', 'SI')
            ->when($canal !== null, fn ($q) => $q->where('PTS', $canal))
            ->where('entra', '>=', $desde->toDateString())
            ->where('entra', '<=', $finMes->addDays($noches)->toDateString())
            ->whereColumn('sale', '>', 'entra')
            ->whereRaw('DATEDIFF(sale, entra) <= 30')
            ->orderBy('corr')
            ->get(['corr', 'unidad', 'entra', 'sale', 'CONFIRMA']);

        // Filas concretas y capacidad por tramo entra→sale de cada tipo
        $filas = [];
        $tramos = [];

        foreach ($bloques as $bloque) {
            $e = $bloque->entra->toDateString();
            $s = $bloque->sale->toDateString();
            $unidad = trim($bloque->unidad);

            $filas[$unidad][$e][$s][] = [
                'corr' => $bloque->corr,
                'entra' => $e,
                'sale' => $s,
                'confirma' => trim($bloque->CONFIRMA ?? ''),
            ];
            $tramos[$unidad][$e][$s] = ($tramos[$unidad][$e][$s] ?? 0) + 1;
        }

        // Días donde arranca un bloque multi-noche (ej. vie→dom): nadie puede
        // SALIR ese día, porque esa fecha vende sus noches en paquete. Se mira
        // el allotment completo (no solo lo disponible) porque es la estructura
        // de la semana, no la ocupación, la que define la regla.
        $inicioBloque = [];

        $iniciosMulti = Allotment::query()
            ->where('HOTEL', $hotel)
            ->whereIn('unidad', $codigos)
            ->where('tipo', 'ALLO')
            ->where('visible_web', 'SI')
            ->when($canal !== null, fn ($q) => $q->where('PTS', $canal))
            ->where('entra', '>=', $desde->toDateString())
            ->where('entra', '<=', $finMes->addDays($noches)->toDateString())
            ->whereRaw('DATEDIFF(sale, entra) BETWEEN 2 AND 30')
            ->distinct()
            ->get(['unidad', 'entra']);

        foreach ($iniciosMulti as $inicio) {
            $inicioBloque[trim($inicio->unidad)][$inicio->entra->toDateString()] = true;
        }

        $resultados = [];

        for ($dia = $desde; $dia->lte($finMes); $dia = $dia->addDay()) {
            if ($tipoDias !== null && ! $this->coincideTipoDia($dia, $tipoDias)) {
                continue;
            }

            $entra = $dia->toDateString();
            $sale = $dia->addDays($noches)->toDateString();

            // Cadenas disponibles por tipo para cubrir entra→sale completo,
            // con las filas de allotment concretas de cada cadena. Si la fecha
            // de salida cae en el arranque de un bloque multi-noche de ese tipo
            // (ej. salir viernes cuando vie→dom va en paquete), la estancia no
            // es válida para ese tipo.
            $cadenas = [];
            foreach ($codigos as $codigo) {
                $cadenas[$codigo] = isset($inicioBloque[$codigo][$sale])
                    ? []
                    : $this->cadenasDisponibles(
                        $tramos[$codigo] ?? [],
                        $filas[$codigo] ?? [],
                        $entra,
                        $sale,
                        $maxCantidad,
                    );
            }

            $disponibles = [];
            foreach ($combos as $combo) {
                $unidades = PHP_INT_MAX;
                foreach ($combo->detalles as $detalle) {
                    $codigo = trim($detalle->tipoUnid->unidad);
                    $unidades = min($unidades, intdiv(count($cadenas[$codigo]), $detalle->cantidad));
                }

                if ($unidades < 1) {
                    continue;
                }

                // Registros de allotment que usaría UNA solicitud de esta
                // combinación: una cadena por cada unidad requerida
                $asignacion = [];
                foreach ($combo->detalles as $detalle) {
                    $codigo = trim($detalle->tipoUnid->unidad);
                    $nombre = trim($detalle->tipoUnid->nombre);

                    foreach (array_slice($cadenas[$codigo], 0, $detalle->cantidad) as $indice => $cadena) {
                        $asignacion[] = [
                            'tipo' => $nombre . ($detalle->cantidad > 1 ? ' #' . ($indice + 1) : ''),
                            'tramos' => $cadena,
                        ];
                    }
                }

                $disponibles[] = [
                    'prioridad' => $combo->prioridad,
                    'descripcion' => $combo->descripcion,
                    'disponibles' => $unidades,
                    'unidades' => $asignacion,
                ];
            }

            if ($disponibles !== []) {
                $resultados[] = [
                    'entra' => $entra,
                    'sale' => $sale,
                    'combinaciones' => $disponibles,
                ];
            }
        }

        return $resultados;
    }

    /**
     * Entre semana: domingo a jueves. Fin de semana: viernes y sábado.
     */
    private function coincideTipoDia(CarbonImmutable $dia, string $tipoDias): bool
    {
        $finDeSemana = in_array($dia->dayOfWeek, [CarbonImmutable::FRIDAY, CarbonImmutable::SATURDAY], true);

        return $tipoDias === 'fin_semana' ? $finDeSemana : ! $finDeSemana;
    }

    /**
     * Cadenas de bloques consecutivos de un tipo que cubren el rango completo
     * entra→sale (flujo máximo sobre el grafo de fechas, acotado a $maximo).
     * Devuelve una lista de cadenas; cada cadena es la lista de filas de
     * allotment (corr, entra, sale, confirma) que la componen.
     */
    private function cadenasDisponibles(array $tramos, array $filas, string $entra, string $sale, int $maximo): array
    {
        if ($tramos === []) {
            return [];
        }

        // Capacidades residuales (se copian para no mutar el grafo original)
        $residual = [];
        foreach ($tramos as $desde => $destinos) {
            foreach ($destinos as $hasta => $capacidad) {
                $residual[$desde][$hasta] = $capacidad;
            }
        }

        $flujo = 0;

        while ($flujo < $maximo) {
            // BFS buscando un camino entra→sale con capacidad residual
            $cola = [$entra];
            $padre = [$entra => null];

            while ($cola !== [] && ! array_key_exists($sale, $padre)) {
                $nodo = array_shift($cola);

                foreach ($residual[$nodo] ?? [] as $siguiente => $capacidad) {
                    if ($capacidad > 0 && ! array_key_exists($siguiente, $padre) && $siguiente <= $sale) {
                        $padre[$siguiente] = $nodo;
                        $cola[] = $siguiente;
                    }
                }
            }

            if (! array_key_exists($sale, $padre)) {
                break;
            }

            // Descuenta el camino encontrado y agrega residuales inversos
            for ($nodo = $sale; $padre[$nodo] !== null; $nodo = $padre[$nodo]) {
                $anterior = $padre[$nodo];
                $residual[$anterior][$nodo]--;
                $residual[$nodo][$anterior] = ($residual[$nodo][$anterior] ?? 0) + 1;
            }

            $flujo++;
        }

        if ($flujo === 0) {
            return [];
        }

        // Uso real de cada tramo original = capacidad inicial - residual.
        // Los residuales inversos (fecha mayor → menor) no cuentan.
        $uso = [];
        foreach ($tramos as $desde => $destinos) {
            foreach ($destinos as $hasta => $capacidad) {
                $restante = $residual[$desde][$hasta] ?? 0;
                if ($capacidad - $restante > 0) {
                    $uso[$desde][$hasta] = $capacidad - $restante;
                }
            }
        }

        // Descompone el flujo en cadenas concretas y les asigna filas reales
        $usadasPorTramo = [];
        $cadenas = [];

        for ($i = 0; $i < $flujo; $i++) {
            $cadena = [];
            $nodo = $entra;

            while ($nodo !== $sale) {
                $siguiente = null;
                foreach ($uso[$nodo] ?? [] as $destino => $unidades) {
                    if ($unidades > 0) {
                        $siguiente = $destino;
                        break;
                    }
                }

                if ($siguiente === null) {
                    break; // no debería ocurrir: el flujo garantiza el camino
                }

                $uso[$nodo][$siguiente]--;
                $indiceFila = $usadasPorTramo[$nodo][$siguiente] ?? 0;
                $usadasPorTramo[$nodo][$siguiente] = $indiceFila + 1;

                $cadena[] = $filas[$nodo][$siguiente][$indiceFila];
                $nodo = $siguiente;
            }

            if ($nodo === $sale) {
                $cadenas[] = $cadena;
            }
        }

        return $cadenas;
    }
}
