<?php

namespace Database\Seeders;

use App\Models\ElementoOrden;
use Illuminate\Database\Seeder;

class ElementoOrdenTableSeeder extends Seeder
{
    public function run(): void
    {
        ElementoOrden::factory(100)->create();
    }
}
