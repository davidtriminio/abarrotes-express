<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Camisetas y Blusas',
                'descripcion' => 'Todo tipo de playeras, camisas y blusas',
                'disponible' => true,
                'keyword' => 'shirts,clothing'
            ],
            [
                'nombre' => 'Pantalones',
                'descripcion' => 'Jeans, pantalones de vestir y casuales',
                'disponible' => true,
                'keyword' => 'pants,trousers'
            ],
            [
                'nombre' => 'Calzado',
                'descripcion' => 'Tenis, zapatos, botas y sandalias',
                'disponible' => true,
                'keyword' => 'shoes,footwear'
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Cinturones, gorras, bufandas y lentes',
                'disponible' => true,
                'keyword' => 'accessories,fashion'
            ],
            [
                'nombre' => 'Chaquetas y Abrigos',
                'descripcion' => 'Chamarras, suéteres y abrigos de invierno',
                'disponible' => true,
                'keyword' => 'jackets,coats'
            ],
            [
                'nombre' => 'Ropa Interior',
                'descripcion' => 'Lencería, calcetines y ropa interior',
                'disponible' => true,
                'keyword' => 'underwear,lingerie'
            ],
            [
                'nombre' => 'Vestidos',
                'descripcion' => 'Vestidos casuales y de noche',
                'disponible' => true,
                'keyword' => 'dresses,fashion'
            ],
            [
                'nombre' => 'Ropa Deportiva',
                'descripcion' => 'Ropa para entrenamiento y gimnasio',
                'disponible' => true,
                'keyword' => 'sportswear,gym'
            ],
        ];

        // Usamos un contador distinto (desde 1000)
        $lockId = 1000;

        foreach ($categorias as $categoriaData) {
            $keyword = $categoriaData['keyword'];
            $imagenUrl = "https://loremflickr.com/320/240/{$keyword}?lock={$lockId}";

            Categoria::updateOrCreate(
                ['nombre' => $categoriaData['nombre']],
                [
                    'descripcion' => $categoriaData['descripcion'],
                    'imagen' => $imagenUrl,
                    'disponible' => $categoriaData['disponible'],
                ]
            );

            $lockId++;
        }
    }
}
