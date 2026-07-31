<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaTableSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::factory(10)->create();
    }
}
