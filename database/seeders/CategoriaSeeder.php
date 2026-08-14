<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Database\Seeders\Concerns\GeneratesPlaceholderImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    use GeneratesPlaceholderImages;

    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Camisetas y Blusas',
                'descripcion' => 'Todo tipo de playeras, camisas y blusas',
                'disponible' => true,
            ],
            [
                'nombre' => 'Pantalones',
                'descripcion' => 'Jeans, pantalones de vestir y casuales',
                'disponible' => true,
            ],
            [
                'nombre' => 'Calzado',
                'descripcion' => 'Tenis, zapatos, botas y sandalias',
                'disponible' => true,
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Cinturones, gorras, bufandas y lentes',
                'disponible' => true,
            ],
            [
                'nombre' => 'Chaquetas y Abrigos',
                'descripcion' => 'Chamarras, suéteres y abrigos de invierno',
                'disponible' => true,
            ],
            [
                'nombre' => 'Ropa Interior',
                'descripcion' => 'Lencería, calcetines y ropa interior',
                'disponible' => true,
            ],
            [
                'nombre' => 'Vestidos',
                'descripcion' => 'Vestidos casuales y de noche',
                'disponible' => true,
            ],
            [
                'nombre' => 'Ropa Deportiva',
                'descripcion' => 'Ropa para entrenamiento y gimnasio',
                'disponible' => true,
            ],
        ];

        foreach ($categorias as $categoriaData) {
            $slug = Str::slug($categoriaData['nombre']);
            $imagenPath = $this->generatePlaceholderImage('categorias', $slug, $categoriaData['nombre']);

            Categoria::updateOrCreate(
                ['nombre' => $categoriaData['nombre']],
                [
                    'descripcion' => $categoriaData['descripcion'],
                    'imagen' => $imagenPath,
                    'disponible' => $categoriaData['disponible'],
                ]
            );
        }
    }
}
