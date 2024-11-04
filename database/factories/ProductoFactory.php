<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word(),
            'imagenes' => null,
            'imagen1' => null,
            'imagen2' => null,
            'imagen3' => null,
            'imagen4' => null,
            'imagen5' => null,
            'descripcion' => $this->faker->paragraph(),
            'precio' => $this->faker->numberBetween(1,500),
            'disponible' => true,
            'cantidad_disponible' => $this->faker->numberBetween(10, 500),
            'en_oferta' => $this->faker->boolean(),
            'porcentaje_oferta' => $this->faker->numberBetween(1,100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'marca_id' => Marca::inRandomOrder()->first(),
            'categoria_id' => Categoria::inRandomOrder()->first(),
           'fecha_expiracion' => $this->faker->dateTimeBetween('2024-11-04', '2024-12-30')->format('Y-m-d'),
        ];
    }
}
