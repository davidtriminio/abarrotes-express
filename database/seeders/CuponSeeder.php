<?php

namespace Database\Seeders;

use App\Models\Cupon;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
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
                'categoria_id' => null,
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
                'categoria_id' => null,
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
                'categoria_id' => null,
                'cantidad_disponible' => 75,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(60),
                'activo' => true,
            ],
        ];

        foreach ($cupones as $cupon) {
            Cupon::updateOrCreate(
                ['codigo' => $cupon['codigo']],
                $cupon
            );
        }
    }
}
