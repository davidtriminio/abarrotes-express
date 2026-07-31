<?php

namespace Database\Factories;

use App\Models\log;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class logFactory extends Factory
{
    protected $model = log::class;

    public function definition(): array
    {
        return [
            'tipo' => $this->faker->randomElement(['crear', 'editar', 'borrar','actualizar']),
            'detalles' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
