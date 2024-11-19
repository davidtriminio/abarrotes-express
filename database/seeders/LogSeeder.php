<?php

namespace Database\Seeders;

use App\Models\log;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        log::factory(10)->create();
    }
}
