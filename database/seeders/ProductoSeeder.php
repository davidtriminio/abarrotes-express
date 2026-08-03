<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Bebidas
            ['marca' => 'Coca Cola', 'categoria' => 'Bebidas', 'nombre' => 'Coca Cola 2L', 'precio' => 35, 'cantidad' => 100],
            ['marca' => 'Pepsi', 'categoria' => 'Bebidas', 'nombre' => 'Pepsi 2L', 'precio' => 32, 'cantidad' => 80],
            ['marca' => 'Sprite', 'categoria' => 'Bebidas', 'nombre' => 'Sprite 2L', 'precio' => 35, 'cantidad' => 75],
            ['marca' => 'Fanta', 'categoria' => 'Bebidas', 'nombre' => 'Fanta Naranja 2L', 'precio' => 28, 'cantidad' => 60],

            // Lácteos
            ['marca' => 'La Lechera', 'categoria' => 'Lácteos', 'nombre' => 'Leche Fresca 1L', 'precio' => 22, 'cantidad' => 150],
            ['marca' => 'Grupo Lala', 'categoria' => 'Lácteos', 'nombre' => 'Leche Lala 1L', 'precio' => 20, 'cantidad' => 120],
            ['marca' => 'Danone', 'categoria' => 'Lácteos', 'nombre' => 'Yogur Danone 125g', 'precio' => 8, 'cantidad' => 200],

            // Panadería
            ['marca' => 'Bimbo', 'categoria' => 'Panadería', 'nombre' => 'Pan Integral Bimbo', 'precio' => 15, 'cantidad' => 80],
            ['marca' => 'Marinela', 'categoria' => 'Panadería', 'nombre' => 'Galletas Marinela', 'precio' => 12, 'cantidad' => 100],

            // Abarrotes
            ['marca' => 'Nestlé', 'categoria' => 'Abarrotes', 'nombre' => 'Cereal Nestlé 500g', 'precio' => 45, 'cantidad' => 60],
            ['marca' => 'Maggi', 'categoria' => 'Abarrotes', 'nombre' => 'Caldo Maggi', 'precio' => 5, 'cantidad' => 300],

            // Condimentos
            ['marca' => 'Heinz', 'categoria' => 'Condimentos', 'nombre' => 'Salsa Heinz 500ml', 'precio' => 25, 'cantidad' => 50],
            ['marca' => 'McCormick', 'categoria' => 'Condimentos', 'nombre' => 'Pimienta McCormick 50g', 'precio' => 18, 'cantidad' => 40],

            // Snacks
            ['marca' => 'Sabritas', 'categoria' => 'Snacks', 'nombre' => 'Sabritas Clásicas 50g', 'precio' => 12, 'cantidad' => 200],
            ['marca' => 'Sabritas', 'categoria' => 'Snacks', 'nombre' => 'Doritos Nacho 50g', 'precio' => 14, 'cantidad' => 150],
        ];

        foreach ($productos as $prod) {
            $categoria = DB::table('categorias')->where('nombre', $prod['categoria'])->first();
            $marca = DB::table('marcas')->where('nombre', $prod['marca'])->first();

            if ($categoria && $marca) {
                $data = [
                    'marca_id' => $marca->id,
                    'categoria_id' => $categoria->id,
                    'nombre' => $prod['nombre'],
                    'descripcion' => 'Producto de calidad - ' . $prod['nombre'],
                    'precio' => $prod['precio'],
                    'cantidad_disponible' => $prod['cantidad'],
                    'disponible' => true,
                    'en_oferta' => rand(0, 1) === 1,
                    'porcentaje_oferta' => rand(0, 1) === 1 ? rand(5, 30) : 0,
                    'fecha_expiracion' => now()->addYear()->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('productos')->updateOrInsert([
                    'nombre' => $prod['nombre']
                ], $data);
            }
        }
    }
}
