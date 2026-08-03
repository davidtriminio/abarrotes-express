<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuponSeeder extends Seeder
{
    public function run(): void
    {
        $cupones = [
            [
                'codigo' => 'DESCUENTO10',
                'descripcion' => 'Descuento del 10% en toda la tienda',
                'descuento' => 10,
                'tipo_descuento' => 'porcentaje',
                'cantidad_disponible' => 100,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(30),
                'activo' => true,
            ],
            [
                'codigo' => 'VERANO20',
                'descripcion' => 'Descuento especial de verano 20%',
                'descuento' => 20,
                'tipo_descuento' => 'porcentaje',
                'cantidad_disponible' => 50,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(15),
                'activo' => true,
            ],
            [
                'codigo' => 'LPS50OFF',
                'descripcion' => 'Descuento de LPS 50 en compras mayores a LPS 200',
                'descuento' => 50,
                'tipo_descuento' => 'fijo',
                'cantidad_disponible' => 75,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(60),
                'activo' => true,
            ],
        ];

        foreach ($cupones as $cupon) {
            // Map seeder fields to actual table columns
            $data = [
                'codigo' => $cupon['codigo'],
                'fecha_inicio' => $cupon['fecha_inicio'],
                'fecha_expiracion' => $cupon['fecha_fin'],
                'estado' => $cupon['activo'] ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($cupon['tipo_descuento'] === 'porcentaje') {
                $data['descuento_porcentaje'] = $cupon['descuento'];
            } else {
                $data['descuento_dinero'] = $cupon['descuento'];
            }

            DB::table('cupones')->updateOrInsert(
                ['codigo' => $cupon['codigo']],
                $data
            );
        }
    }
}
