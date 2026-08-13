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
                'tipo_descuento' => 'porcentaje',
                'descuento_porcentaje' => 10,
                'descuento_dinero' => null,
                'categoria_id' => null,
                'producto_id' => null,
                'marca_id' => null,
                'compra_minima' => null,
                'compra_cantidad' => null,
                'fecha_inicio' => Carbon::now(),
                'fecha_expiracion' => Carbon::now()->addDays(30),
                'estado' => true,
            ],

            [
                'codigo' => 'VERANO20',
                'descripcion' => 'Descuento especial de verano 20%',
                'tipo_descuento' => 'porcentaje',
                'descuento_porcentaje' => 20,
                'descuento_dinero' => null,
                'categoria_id' => null,
                'producto_id' => null,
                'marca_id' => null,
                'compra_minima' => null,
                'compra_cantidad' => null,
                'fecha_inicio' => Carbon::now(),
                'fecha_expiracion' => Carbon::now()->addDays(15),
                'estado' => true,
            ],

            [
                'codigo' => 'LPS50OFF',
                'descripcion' => 'Descuento de LPS 50 en compras mayores a LPS 200',
                'tipo_descuento' => 'dinero',
                'descuento_porcentaje' => null,
                'descuento_dinero' => 50,
                'categoria_id' => null,
                'producto_id' => null,
                'marca_id' => null,
                'compra_minima' => 200,
                'compra_cantidad' => null,
                'fecha_inicio' => Carbon::now(),
                'fecha_expiracion' => Carbon::now()->addDays(60),
                'estado' => true,
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
