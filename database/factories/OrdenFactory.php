<?php

namespace Database\Factories;

use App\Models\Orden;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrdenFactory extends Factory
{
    protected $model = Orden::class;

    public function definition(): array
    {
        if (Orden::where('estado_entrega', 'entregado')) {
            $fecha_entrega = $this->faker->dateTimeBetween('-90 days', '-1 days');
            $estado_pago = 'pagado';
        }
        else {
            $fecha_entrega = null;
            $estado_pago = $this->faker->randomElement(['pagado', 'procesando']);
        }
        return [
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            'updated_at' => Carbon::now(),
            'user_id' => User::inRandomOrder()->first(),
            'sub_total' => $this->faker->numberBetween(10, 100),
            'total_final' => $this->faker->numberBetween(0, 10000),
            'metodo_pago' => $this->faker->randomElement(['par', 'efectivo', 'tarjeta']),
            'estado_pago' => $estado_pago,
            'estado_entrega' => $this->faker->randomElement(['nuevo', 'procesado', 'enviado', 'entregado', 'cancelado']),
            'costos_envio' => $this->faker->numberBetween(90, 95),
            'fecha_entrega' => $fecha_entrega,
            'notas' => $this->faker->text()
        ];
    }
}
