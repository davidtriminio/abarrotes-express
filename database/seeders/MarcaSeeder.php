<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Nike', 'descripcion' => 'Ropa y calzado deportivo de alto rendimiento', 'keyword' => 'nike,logo'],
            ['nombre' => 'Adidas', 'descripcion' => 'Moda deportiva y urbana', 'keyword' => 'adidas,logo'],
            ['nombre' => 'Levi\'s', 'descripcion' => 'La marca icónica de ropa de mezclilla', 'keyword' => 'levis,denim'],
            ['nombre' => 'Zara', 'descripcion' => 'Moda rápida, casual y formal', 'keyword' => 'zara,store'],
            ['nombre' => 'H&M', 'descripcion' => 'Ropa y accesorios accesibles para todos', 'keyword' => 'hm,store'],
            ['nombre' => 'Puma', 'descripcion' => 'Estilo deportivo y casual', 'keyword' => 'puma,logo'],
            ['nombre' => 'Calvin Klein', 'descripcion' => 'Ropa interior, jeans y moda de diseñador', 'keyword' => 'calvinklein,fashion'],
            ['nombre' => 'Vans', 'descripcion' => 'Calzado y ropa inspirada en el skate', 'keyword' => 'vans,skate'],
            ['nombre' => 'Under Armour', 'descripcion' => 'Ropa de alto rendimiento para atletas', 'keyword' => 'underarmour,sports'],
            ['nombre' => 'Tommy Hilfiger', 'descripcion' => 'Ropa casual con estilo americano', 'keyword' => 'tommyhilfiger,fashion'],
        ];

        // Usamos un contador (desde 500 para no chocar visualmente con los de productos)
        $lockId = 500;

        foreach ($marcas as $marcaData) {
            $keyword = $marcaData['keyword'];
            $imagenUrl = "https://loremflickr.com/320/240/{$keyword}?lock={$lockId}";

            Marca::updateOrCreate(
                ['nombre' => $marcaData['nombre']],
                [
                    'descripcion' => $marcaData['descripcion'],
                    'imagen' => $imagenUrl,
                    'disponible' => true,
                ]
            );

            $lockId++;
        }
    }
}
