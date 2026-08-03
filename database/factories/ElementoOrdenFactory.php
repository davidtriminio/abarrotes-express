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
        $monto_unitario = $this->faker->numberBetween(100, 1000);

        $orden = Orden::inRandomOrder()->first();
        $producto = Producto::inRandomOrder()->first();

        return [
            'orden_id' => $orden?->id ?? 1,
            'producto_id' => $producto?->id ?? 1,
            'cantidad' => $cantidad,
            'monto_unitario' => $monto_unitario,
            'monto_total' => ($cantidad * $monto_unitario),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ];
    }
}
