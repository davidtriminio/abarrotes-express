<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class CuponesSeeder extends Seeder
{
    public function run()
    {
        // Obtener los primeros 8 usuarios
        $usuarios = User::limit(8)->pluck('id')->toArray();
        $cupones = [];

        // Crear 9 cupones para cada usuario
        foreach ($usuarios as $usuario) {
            for ($i = 0; $i < 9; $i++) {
                // Establecer la fecha de inicio y expiración
                $hoy = Carbon::now()->startOfDay();
                $expiracion = $hoy->copy()->addDays(15);

                // Definir el tipo de descuento y su valor
                $tipoDescuento = rand(0, 1) === 0 ? 'dinero' : 'porcentaje';
                $descuento = $tipoDescuento === 'dinero'
                    ? rand(200, 2000)
                    : rand(15, 50);


                $restriccionCompra = $tipoDescuento === 'dinero'
                    ? rand(200, 2000)
                    : rand(10, 50);

                // Crear el cupón
                $cupones[] = [
                    'codigo' => rand(10000000, 99999999),
                    'tipo_descuento' => $tipoDescuento,
                    'descuento_porcentaje' => $tipoDescuento === 'porcentaje' ? $descuento : null,
                    'descuento_dinero' => $tipoDescuento === 'dinero' ? $descuento : null,
                    'fecha_inicio' => $hoy,
                    'fecha_expiracion' => $expiracion,
                    'estado' => true,
                    'compra_minima' => $tipoDescuento === 'dinero' ? $restriccionCompra : null,
                    'compra_cantidad' => $tipoDescuento === 'porcentaje' ? $restriccionCompra : null,
                    'user_id' => $usuario,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }


        DB::table('cupones')->insert($cupones);
    }
}
