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
        $estado_entrega = $this->faker->randomElement(['nuevo', 'procesado', 'enviado', 'entregado', 'cancelado']);
        $created_at = $this->faker->dateTimeBetween('-1 year', '-1 day');

        if ($estado_entrega === 'entregado') {
            $fecha_entrega = $this->faker->dateTimeBetween($created_at, 'now');
            $estado_pago = 'pagado';
        } else {
            $fecha_entrega = null;
            $estado_pago = $this->faker->randomElement(['pagado', 'procesando', 'error']);
        }

        $sub_total = $this->faker->randomFloat(2, 200, 3000);

        return [
            'created_at' => $created_at,
            'updated_at' => Carbon::now(),
            'user_id' => User::inRandomOrder()->value('id'),
            'sub_total' => $sub_total,
            'total_final' => $sub_total,
            'metodo_pago' => $this->faker->randomElement(['par', 'efectivo', 'tarjeta']),
            'estado_pago' => $estado_pago,
            'estado_entrega' => $estado_entrega,
            'costos_envio' => $this->faker->numberBetween(90, 95),
            'fecha_entrega' => $fecha_entrega,
            'notas' => $this->faker->optional()->sentence(),
        ];
    }
}
