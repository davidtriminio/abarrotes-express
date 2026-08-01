<?php

namespace Database\Seeders;

use App\Models\Promocion;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Producto::limit(5)->pluck('id')->toArray();

        $promociones = [
            [
                'nombre' => 'Promoción de Verano',
                'descripcion' => 'Descuento especial en bebidas - Lleva 2 y paga 1.5',
                'descuento' => 25,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(30),
                'activa' => true,
            ],
            [
                'nombre' => 'Promo Abarrotes',
                'descripcion' => 'Compra abarrotes y recibe 20% de descuento',
                'descuento' => 20,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(15),
                'activa' => true,
            ],
            [
                'nombre' => 'Especial Lácteos',
                'descripcion' => 'Todos los lácteos con 15% de descuento',
                'descuento' => 15,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addDays(7),
                'activa' => true,
            ],
        ];

        foreach ($promociones as $promocion) {
            Promocion::updateOrCreate(
                ['nombre' => $promocion['nombre']],
                $promocion
            );
        }
    }
}
