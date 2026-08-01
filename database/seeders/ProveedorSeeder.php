<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Distribuidora Central',
                'email' => 'contacto@distribuidora-central.com',
                'telefono' => '2238-5555',
                'direccion' => 'Av. Principal #1234, Tegucigalpa',
                'ciudad' => 'Tegucigalpa',
                'estado_proveedor' => 'activo',
            ],
            [
                'nombre' => 'Importadora Americana',
                'email' => 'ventas@importadora-americana.com',
                'telefono' => '2234-8888',
                'direccion' => 'Calle 5 #500, San Pedro Sula',
                'ciudad' => 'San Pedro Sula',
                'estado_proveedor' => 'activo',
            ],
            [
                'nombre' => 'Proveedora Selecta',
                'email' => 'info@proveedora-selecta.com',
                'telefono' => '2231-2222',
                'direccion' => 'Blvd. Los Próceres #789, Tegucigalpa',
                'ciudad' => 'Tegucigalpa',
                'estado_proveedor' => 'activo',
            ],
            [
                'nombre' => 'Hermanos Mercado',
                'email' => 'contacto@hermanosm.com',
                'telefono' => '2239-6666',
                'direccion' => 'Zona Industrial #234, Cortés',
                'ciudad' => 'San Pedro Sula',
                'estado_proveedor' => 'activo',
            ],
            [
                'nombre' => 'Comercial Internacional',
                'email' => 'pedidos@comercial-intl.com',
                'telefono' => '2235-1111',
                'direccion' => 'Centro Comercial #100, La Ceiba',
                'ciudad' => 'La Ceiba',
                'estado_proveedor' => 'activo',
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::updateOrCreate(
                ['nombre' => $proveedor['nombre']],
                $proveedor
            );
        }
    }
}
