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

        if ($estado_entrega === 'entregado') {
            $fecha_entrega = $this->faker->dateTimeBetween('-90 days', '-1 days');
            $estado_pago = 'pagado';
        } else {
            $fecha_entrega = null;
            $estado_pago = $this->faker->randomElement(['pagado', 'procesando', 'pendiente']);
        }

        $user = User::inRandomOrder()->first();

        return [
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            'updated_at' => Carbon::now(),
            'user_id' => $user?->id ?? 1,
            'sub_total' => $this->faker->numberBetween(10, 100),
            'total_final' => $this->faker->numberBetween(100, 10000),
            'metodo_pago' => $this->faker->randomElement(['par', 'efectivo', 'tarjeta']),
            'estado_pago' => $estado_pago,
            'estado_entrega' => $estado_entrega,
            'costos_envio' => $this->faker->numberBetween(50, 150),
            'fecha_entrega' => $fecha_entrega,
            'notas' => $this->faker->text(),
        ];
    }
}
