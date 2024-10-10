<?php

namespace Database\Seeders;

use App\Models\Orden;
use Illuminate\Database\Seeder;

class OrdenTableSeeder extends Seeder
{
    public function run(): void
    {
        Orden::factory(100)->create();
    }
}
