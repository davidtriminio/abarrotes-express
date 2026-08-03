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
        $marca = Marca::inRandomOrder()->first();
        $categoria = Categoria::inRandomOrder()->first();

        return [
            'nombre' => $this->faker->unique()->word(),
            'imagenes' => null,
            'imagen1' => null,
            'imagen2' => null,
            'imagen3' => null,
            'imagen4' => null,
            'imagen5' => null,
            'descripcion' => $this->faker->paragraph(),
            'precio' => $this->faker->numberBetween(1, 500),
            'disponible' => true,
            'cantidad_disponible' => $this->faker->numberBetween(10, 500),
            'en_oferta' => $this->faker->boolean(),
            'porcentaje_oferta' => $this->faker->numberBetween(1, 100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'marca_id' => $marca?->id ?? 1,
            'categoria_id' => $categoria?->id ?? 1,
            'fecha_expiracion' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31')->format('Y-m-d'),
        ];
    }
}
