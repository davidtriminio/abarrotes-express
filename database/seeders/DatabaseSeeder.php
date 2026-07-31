<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Categoria;
use App\Models\DireccionGuardada;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesPermisosSeeder::class,
            CategoriaTableSeeder::class,
            CuponesSeeder::class,
            MarcaTableSeeder::class,
            ProductoTableSeeder::class,
            OrdenTableSeeder::class,
            ElementoOrdenTableSeeder::class,
        ]);
    }
}
