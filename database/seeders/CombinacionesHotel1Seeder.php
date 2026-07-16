<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CombinacionesHotel1Seeder extends Seeder
{
    private const HOTEL = 1;

    public function run(): void
    {
        $tipos = DB::connection('mysql_allotment')->table('tipo_unid')
            ->where('hotel', self::HOTEL)
            ->whereIn('unidad', ['D2DL', 'V1BR', 'V2BR'])
            ->pluck('id', 'unidad');

        $u = [
            'doble' => $tipos['D2DL'],
            'villa4' => $tipos['V1BR'],
            'villa6' => $tipos['V2BR'],
        ];

        // Listas de opciones en orden de prioridad. Cada opción: [alias => cantidad].
        $unaUnidadChica = [
            ['doble' => 1],
            ['villa4' => 1],
            ['villa6' => 1],
        ];

        $unaUnidadMediana = [
            ['villa4' => 1],
            ['villa6' => 1],
            ['doble' => 2],
        ];

        $grandesCinco = [
            ['villa6' => 1],
            ['doble' => 1, 'villa4' => 1],
            ['villa4' => 2],
            ['doble' => 3],
        ];

        $medianasCinco = [
            ['villa6' => 1],
            ['doble' => 2],
            ['doble' => 1, 'villa4' => 1],
            ['villa4' => 2],
        ];

        $soloVillaSeis = [
            ['villa6' => 1],
        ];

        $grandesSiete = [
            ['villa4' => 2],
            ['doble' => 1, 'villa6' => 1],
            ['villa4' => 1, 'villa6' => 1],
            ['doble' => 2, 'villa4' => 1],
            ['doble' => 4],
        ];

        $medianasSiete = [
            ['doble' => 1, 'villa4' => 1],
            ['villa4' => 2],
            ['doble' => 1, 'villa6' => 1],
            ['villa4' => 1, 'villa6' => 1],
            ['doble' => 3],
        ];

        $chicasSiete = [
            ['doble' => 2],
            ['doble' => 1, 'villa4' => 1],
            ['villa4' => 2],
            ['doble' => 1, 'villa6' => 1],
            ['villa4' => 1, 'villa6' => 1],
        ];

        // [total, adultos, ninos] => lista de opciones.
        // 7/1/6 y 8/1/7 no existen: "No disponible con el inventario actual".
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
            [5, 1, 4, $soloVillaSeis],
            [6, 6, 0, $grandesCinco],
            [6, 5, 1, $grandesCinco],
            [6, 4, 2, $medianasCinco],
            [6, 3, 3, $medianasCinco],
            [6, 2, 4, $medianasCinco],
            [6, 1, 5, $soloVillaSeis],
            [7, 7, 0, $grandesSiete],
            [7, 6, 1, $medianasSiete],
            [7, 5, 2, $medianasSiete],
            [7, 4, 3, $chicasSiete],
            [7, 3, 4, $chicasSiete],
            [7, 2, 5, $chicasSiete],
            [8, 8, 0, $grandesSiete],
            [8, 7, 1, $grandesSiete],
            [8, 6, 2, $medianasSiete],
            [8, 5, 3, $medianasSiete],
            [8, 4, 4, $chicasSiete],
            [8, 3, 5, $chicasSiete],
            [8, 2, 6, $chicasSiete],
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
