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
        $cantidad = $this->faker->numberBetween(1, 5);
        $monto_unitario = $this->faker->randomFloat(2, 100, 150);

        return [
            'orden_id' => Orden::inRandomOrder()->value('id'),
            'producto_id' => Producto::inRandomOrder()->value('id'),
            'cantidad' => $cantidad,
            'monto_unitario' => $monto_unitario,
            'monto_total' => round($cantidad * $monto_unitario, 2),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ];
    }
}
