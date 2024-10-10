<?php

namespace Database\Factories;

use App\Models\ElementoOrden;
use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElementoOrdenFactory extends Factory
{
    protected $model = ElementoOrden::class;

    public function definition(): array
    {
        return
            [
                'orden_id' => Orden::inRandomOrder()->first(),
                'producto_id' => Producto::inRandomOrder()->first(),
                'cantidad' => $this->faker->numberBetween(1, 5),
                'monto_unitario' => $this->faker->numberBetween(100, 150),
                'monto_total' => ($this->faker->numberBetween(1, 5) * $this->faker->numberBetween(100, 150)),
                'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            ];
    }
}
