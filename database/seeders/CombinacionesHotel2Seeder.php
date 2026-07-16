<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CombinacionesHotel2Seeder extends Seeder
{
    private const HOTEL = 2;

    public function run(): void
    {
        $tipos = DB::connection('mysql_allotment')->table('tipo_unid')
            ->where('hotel', self::HOTEL)
            ->whereIn('unidad', ['D2DL', 'D2JSE', 'STE', 'STL', 'B1BR', 'B2BR'])
            ->pluck('id', 'unidad');

        $u = [
            'doble' => $tipos['D2DL'],
            'mini_suite' => $tipos['D2JSE'],
            'suite' => $tipos['STE'],
            'suite_lujo' => $tipos['STL'],
            'bungalow4' => $tipos['B1BR'],
            'bungalow6' => $tipos['B2BR'],
        ];

        // Listas de opciones en orden de prioridad. Cada opción: [alias => cantidad].
        $unaUnidadChica = [
            ['doble' => 1],
            ['mini_suite' => 1],
            ['suite' => 1],
            ['suite_lujo' => 1],
            ['bungalow4' => 1],
            ['bungalow6' => 1],
        ];

        $unaUnidadMediana = [
            ['mini_suite' => 1],
            ['suite' => 1],
            ['suite_lujo' => 1],
            ['bungalow4' => 1],
            ['bungalow6' => 1],
            ['doble' => 2],
        ];

        $grandesCinco = [
            ['suite_lujo' => 1],
            ['bungalow6' => 1],
            ['doble' => 1, 'mini_suite' => 1],
            ['doble' => 1, 'suite' => 1],
            ['mini_suite' => 2],
            ['mini_suite' => 1, 'suite' => 1],
            ['suite' => 2],
            ['bungalow4' => 1, 'doble' => 1],
            ['bungalow4' => 1, 'mini_suite' => 1],
            ['bungalow4' => 1, 'suite' => 1],
            ['doble' => 3],
        ];

        $medianasCinco = [
            ['mini_suite' => 1],
            ['suite_lujo' => 1],
            ['bungalow6' => 1],
            ['doble' => 2],
            ['doble' => 1, 'suite' => 1],
            ['suite' => 2],
            ['bungalow4' => 1, 'doble' => 1],
            ['bungalow4' => 1, 'suite' => 1],
        ];

        $soloGrandesUnaUnidad = [
            ['mini_suite' => 1],
            ['suite_lujo' => 1],
            ['bungalow6' => 1],
        ];

        $grandesSiete = [
            ['mini_suite' => 2],
            ['mini_suite' => 1, 'suite' => 1],
            ['suite' => 2],
            ['doble' => 1, 'suite_lujo' => 1],
            ['bungalow6' => 1, 'doble' => 1],
            ['mini_suite' => 1, 'suite_lujo' => 1],
            ['bungalow4' => 1, 'mini_suite' => 1],
            ['bungalow6' => 1, 'mini_suite' => 1],
            ['suite' => 1, 'suite_lujo' => 1],
            ['bungalow4' => 1, 'suite' => 1],
            ['bungalow6' => 1, 'suite' => 1],
            ['doble' => 2, 'mini_suite' => 1],
            ['doble' => 2, 'suite' => 1],
            ['doble' => 2, 'bungalow4' => 1],
            ['doble' => 4],
        ];

        $grandesSieteConNinos = [
            ['suite_lujo' => 1],
            ['bungalow6' => 1],
            ['doble' => 2],
            ['doble' => 1, 'mini_suite' => 1],
            ['doble' => 1, 'suite' => 1],
            ['mini_suite' => 2],
            ['mini_suite' => 1, 'suite' => 1],
            ['suite' => 2],
            ['bungalow4' => 1, 'doble' => 1],
            ['bungalow4' => 1, 'mini_suite' => 1],
            ['bungalow4' => 1, 'suite' => 1],
        ];

        $soloMaximas = [
            ['suite_lujo' => 1],
            ['bungalow6' => 1],
        ];

        // [total, adultos, ninos] => lista de opciones
        $bloques = [
            [1, 1, 0, $unaUnidadChica],
            [2, 2, 0, $unaUnidadChica],
            [2, 1, 1, $unaUnidadChica],
            [3, 3, 0, $unaUnidadMediana],
            [3, 2, 1, $unaUnidadChica],
            [3, 1, 2, $unaUnidadChica],
            [4, 4, 0, $unaUnidadMediana],
            [4, 3, 1, $unaUnidadMediana],
            [4, 2, 2, $unaUnidadChica],
            [4, 1, 3, $unaUnidadChica],
            [5, 5, 0, $grandesCinco],
            [5, 4, 1, $medianasCinco],
            [5, 3, 2, $medianasCinco],
            [5, 2, 3, $medianasCinco],
            [5, 1, 4, $soloGrandesUnaUnidad],
            [6, 6, 0, $grandesCinco],
            [6, 5, 1, $grandesCinco],
            [6, 4, 2, $medianasCinco],
            [6, 3, 3, $medianasCinco],
            [6, 2, 4, $medianasCinco],
            [6, 1, 5, $soloGrandesUnaUnidad],
            [7, 7, 0, $grandesSiete],
            [7, 6, 1, $grandesCinco],
            [7, 5, 2, $grandesCinco],
            [7, 4, 3, $grandesSieteConNinos],
            [7, 3, 4, $grandesSieteConNinos],
            [7, 2, 5, $grandesSieteConNinos],
            [7, 1, 6, $soloMaximas],
            [8, 8, 0, $grandesSiete],
            [8, 7, 1, $grandesSiete],
            [8, 6, 2, $grandesCinco],
            [8, 5, 3, $grandesCinco],
            [8, 4, 4, $grandesSieteConNinos],
            [8, 3, 5, $grandesSieteConNinos],
            [8, 2, 6, $grandesSieteConNinos],
            [8, 1, 7, $soloMaximas],
        ];

        DB::transaction(function () use ($bloques, $u) {
            $conn = DB::connection();
            // Recarga limpia del hotel: el detalle se borra por cascada
            $conn->table('combinaciones')->where('hotel', self::HOTEL)->delete();

            $ahora = now();

            foreach ($bloques as [$total, $adultos, $ninos, $opciones]) {
                foreach ($opciones as $indice => $opcion) {
                    $combinacionId = $conn->table('combinaciones')->insertGetId([
                        'hotel' => self::HOTEL,
                        'adultos' => $adultos,
                        'ninos' => $ninos,
                        'total' => $total,
                        'prioridad' => $indice + 1,
                        'activo' => true,
                        'created_at' => $ahora,
                        'updated_at' => $ahora,
                    ]);

                    $detalle = [];
                    foreach ($opcion as $alias => $cantidad) {
                        $detalle[] = [
                            'combinacion_id' => $combinacionId,
                            'tipo_unid_id' => $u[$alias],
                            'cantidad' => $cantidad,
                        ];
                    }

                    $conn->table('detalle_combinaciones')->insert($detalle);
                }
            }
        });
    }
}
