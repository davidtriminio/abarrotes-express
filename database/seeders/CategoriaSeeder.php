<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Abarrotes', 'descripcion' => 'Productos de abarrotes y despensa', 'disponible' => true],
            ['nombre' => 'Bebidas', 'descripcion' => 'Bebidas refrescantes y alcohólicas', 'disponible' => true],
            ['nombre' => 'Lácteos', 'descripcion' => 'Leche, queso y productos lácteos', 'disponible' => true],
            ['nombre' => 'Panadería', 'descripcion' => 'Pan, pasteles y productos de panadería', 'disponible' => true],
            ['nombre' => 'Carnes', 'descripcion' => 'Carnes frescas y embutidos', 'disponible' => true],
            ['nombre' => 'Frutas y Verduras', 'descripcion' => 'Frutas y verduras frescas', 'disponible' => true],
            ['nombre' => 'Congelados', 'descripcion' => 'Productos congelados', 'disponible' => true],
            ['nombre' => 'Snacks', 'descripcion' => 'Galletas, snacks y confites', 'disponible' => true],
            ['nombre' => 'Condimentos', 'descripcion' => 'Especias, condimentos y salsas', 'disponible' => true],
            ['nombre' => 'Higiene Personal', 'descripcion' => 'Productos de higiene y belleza', 'disponible' => true],
        ];

        foreach ($categorias as $categoria) {
            $categoria['created_at'] = now();
            $categoria['updated_at'] = now();
            DB::table('categorias')->updateOrInsert(
                ['nombre' => $categoria['nombre']],
                $categoria
            );
        }
    }
}
