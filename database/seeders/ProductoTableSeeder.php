<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoTableSeeder extends Seeder
{
    public function run(): void
    {
        Producto::factory(12)->create();
    }
}
