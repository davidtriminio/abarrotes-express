<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            ['nombre' => 'Coca Cola', 'descripcion' => 'Bebidas refrescantes'],
            ['nombre' => 'Pepsi', 'descripcion' => 'Bebidas gaseosas'],
            ['nombre' => 'Sprite', 'descripcion' => 'Bebidas cítricas'],
            ['nombre' => 'Fanta', 'descripcion' => 'Bebidas de frutas'],
            ['nombre' => 'Nestlé', 'descripcion' => 'Productos alimenticios variados'],
            ['nombre' => 'Danone', 'descripcion' => 'Lácteos y yogures'],
            ['nombre' => 'La Lechera', 'descripcion' => 'Leche y derivados'],
            ['nombre' => 'Bimbo', 'descripcion' => 'Panadería y productos de trigo'],
            ['nombre' => 'Grupo Lala', 'descripcion' => 'Productos lácteos frescos'],
            ['nombre' => 'Sabritas', 'descripcion' => 'Snacks y botanas'],
            ['nombre' => 'Marinela', 'descripcion' => 'Pastelería y dulces'],
            ['nombre' => 'Maggi', 'descripcion' => 'Condimentos y sopas'],
            ['nombre' => 'Heinz', 'descripcion' => 'Salsas y condimentos'],
            ['nombre' => 'Knorr', 'descripcion' => 'Sopas y condimentos'],
            ['nombre' => 'McCormick', 'descripcion' => 'Especias y condimentos'],
        ];

        foreach ($marcas as $marca) {
            Marca::updateOrCreate(
                ['nombre' => $marca['nombre']],
                $marca
            );
        }
    }
}
