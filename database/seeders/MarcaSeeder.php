<?php

namespace Database\Seeders;

use App\Models\Marca;
use Database\Seeders\Concerns\GeneratesPlaceholderImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarcaSeeder extends Seeder
{
    use GeneratesPlaceholderImages;

    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Nike', 'descripcion' => 'Ropa y calzado deportivo de alto rendimiento'],
            ['nombre' => 'Adidas', 'descripcion' => 'Moda deportiva y urbana'],
            ['nombre' => 'Levi\'s', 'descripcion' => 'La marca icónica de ropa de mezclilla'],
            ['nombre' => 'Zara', 'descripcion' => 'Moda rápida, casual y formal'],
            ['nombre' => 'H&M', 'descripcion' => 'Ropa y accesorios accesibles para todos'],
            ['nombre' => 'Puma', 'descripcion' => 'Estilo deportivo y casual'],
            ['nombre' => 'Calvin Klein', 'descripcion' => 'Ropa interior, jeans y moda de diseñador'],
            ['nombre' => 'Vans', 'descripcion' => 'Calzado y ropa inspirada en el skate'],
            ['nombre' => 'Under Armour', 'descripcion' => 'Ropa de alto rendimiento para atletas'],
            ['nombre' => 'Tommy Hilfiger', 'descripcion' => 'Ropa casual con estilo americano'],
        ];

        foreach ($marcas as $marcaData) {
            $slug = Str::slug($marcaData['nombre']);
            $imagenPath = $this->generatePlaceholderImage('marcas', $slug, $marcaData['nombre']);

            Marca::updateOrCreate(
                ['nombre' => $marcaData['nombre']],
                [
                    'descripcion' => $marcaData['descripcion'],
                    'imagen' => $imagenPath,
                    'disponible' => true,
                ]
            );
        }
    }
}
