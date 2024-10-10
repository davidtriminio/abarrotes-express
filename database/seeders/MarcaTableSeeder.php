<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaTableSeeder extends Seeder
{
    public function run(): void
    {
        Marca::factory(10)->create();
    }
}
