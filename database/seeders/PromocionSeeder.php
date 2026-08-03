<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
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

        // Seed a simple promotion linked to an existing product (if any)
        $productoId = DB::table('productos')->value('id');
        if ($productoId) {
            $data = [
                'producto_id' => $productoId,
                'estado' => true,
                'fecha_inicio' => Carbon::now(),
                'fecha_expiracion' => Carbon::now()->addDays(30),
                'promocion' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('promociones')->updateOrInsert(
                ['producto_id' => $productoId],
                $data
            );
        }
    }
}
