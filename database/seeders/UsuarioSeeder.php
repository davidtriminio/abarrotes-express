<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ae.com'],
            ['name' => 'Admin', 'password' => bcrypt('admin')]
        );

        User::updateOrCreate(
            ['email' => 'triminio@ae.com'],
            ['name' => 'David', 'password' => bcrypt('admin')]
        );

        User::updateOrCreate(
            ['email' => 'l_ortez@ae.com'],
            ['name' => 'Luis Angel', 'password' => bcrypt('admin')]
        );

        User::updateOrCreate(
            ['email' => 'claudia@ae.com'],
            ['name' => 'Claudia', 'password' => bcrypt('admin')]
        );

        User::updateOrCreate(
            ['email' => 's_plata@ae.com'],
            ['name' => 'Selvin', 'password' => bcrypt('admin')]
        );

        // Create additional users only if they don't exist
        User::factory(50)->create();
    }
}
