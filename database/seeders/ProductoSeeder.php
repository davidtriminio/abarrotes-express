<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Bebidas
            ['marca' => 'Coca Cola', 'categoria' => 'Bebidas', 'nombre' => 'Coca Cola 2L', 'precio' => 35, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 100],
            ['marca' => 'Pepsi', 'categoria' => 'Bebidas', 'nombre' => 'Pepsi 2L', 'precio' => 32, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 80],
            ['marca' => 'Sprite', 'categoria' => 'Bebidas', 'nombre' => 'Sprite 2L', 'precio' => 35, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 75],
            ['marca' => 'Fanta', 'categoria' => 'Bebidas', 'nombre' => 'Fanta Naranja 2L', 'precio' => 28, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 60],

            // Lácteos
            ['marca' => 'La Lechera', 'categoria' => 'Lácteos', 'nombre' => 'Leche Fresca 1L', 'precio' => 22, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 150],
            ['marca' => 'Grupo Lala', 'categoria' => 'Lácteos', 'nombre' => 'Leche Lala 1L', 'precio' => 20, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 120],
            ['marca' => 'Danone', 'categoria' => 'Lácteos', 'nombre' => 'Yogur Danone 125g', 'precio' => 8, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 200],

            // Panadería
            ['marca' => 'Bimbo', 'categoria' => 'Panadería', 'nombre' => 'Pan Integral Bimbo', 'precio' => 15, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 80],
            ['marca' => 'Marinela', 'categoria' => 'Panadería', 'nombre' => 'Galletas Marinela', 'precio' => 12, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 100],

            // Abarrotes
            ['marca' => 'Nestlé', 'categoria' => 'Abarrotes', 'nombre' => 'Cereal Nestlé 500g', 'precio' => 45, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 60],
            ['marca' => 'Maggi', 'categoria' => 'Abarrotes', 'nombre' => 'Caldo Maggi', 'precio' => 5, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 300],

            // Condimentos
            ['marca' => 'Heinz', 'categoria' => 'Condimentos', 'nombre' => 'Salsa Heinz 500ml', 'precio' => 25, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 50],
            ['marca' => 'McCormick', 'categoria' => 'Condimentos', 'nombre' => 'Pimienta McCormick 50g', 'precio' => 18, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 40],

            // Snacks
            ['marca' => 'Sabritas', 'categoria' => 'Snacks', 'nombre' => 'Sabritas Clásicas 50g', 'precio' => 12, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 200],
            ['marca' => 'Sabritas', 'categoria' => 'Snacks', 'nombre' => 'Doritos Nacho 50g', 'precio' => 14, 'fecha_expiracion' => now()->addMonths(3)->toDateString() , 'cantidad' => 150],
        ];

        foreach ($productos as $prod) {
            $categoria = Categoria::where('nombre', $prod['categoria'])->first();
            $marca = Marca::where('nombre', $prod['marca'])->first();

            if ($categoria && $marca) {
                Producto::updateOrCreate(
                    ['nombre' => $prod['nombre']],
                    [
                        'marca_id' => $marca->id,
                        'categoria_id' => $categoria->id,
                        'descripcion' => 'Producto de calidad - ' . $prod['nombre'],
                        'precio' => $prod['precio'],
                        'cantidad_disponible' => $prod['cantidad'],
                        'disponible' => true,
                        'en_oferta' => rand(0, 1) === 1,
                        'porcentaje_oferta' => rand(0, 1) === 1 ? rand(5, 30) : 0,
                    ]
                );
            }
        }
    }
}
