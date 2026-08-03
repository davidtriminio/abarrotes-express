<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UsuarioObserver;
use DB;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Helpers\DBDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }
    public function boot(): void
    {
        try {
            DB::connection()->getPdo();
            if (\Schema::hasTable('roles') && !User::role('SuperAdmin')->exists() && !User::find(1)) {
                // Only disable foreign key checks for MySQL before inserting; PostgreSQL doesn't support this global statement
                DBDriver::executeByDriver([
                    'mysql' => function($conn) { return $conn->statement('SET FOREIGN_KEY_CHECKS=0;'); },
                    'default' => function($conn) { return null; },
                ]);

                // Insertar manualmente el superadministrador con ID 1 using query builder (portable)
                DB::table('users')->insert([
                    'id' => 1,
                    'name' => 'SuperAdministrador',
                    'email' => 'super@ae.com',
                    'email_verified_at' => now(),
                    'password' => \Hash::make('admin'),
                    'remember_token' => \Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Re-enable foreign key checks for MySQL
                DBDriver::executeByDriver([
                    'mysql' => function($conn) { return $conn->statement('SET FOREIGN_KEY_CHECKS=1;'); },
                    'default' => function($conn) { return null; },
                ]);

                // Asignar el rol de SuperAdmin
                $superAdmin = User::find(1);
                $superAdmin->assignRole('SuperAdmin');
            }
        } catch (\Exception $e) {
            if ($e->getCode() === 1049) {  // Código de error de base de datos
                $databaseName = env('DB_DATABASE', 'abarrotes_express');
                try {
                    DB::statement("CREATE DATABASE $databaseName");

                    \Log::info("Base de datos '$databaseName' creada exitosamente.");

                    // Reintentar la conexión después de crear la base de datos
                    DB::purge();
                    DB::reconnect();

                    \Artisan::call('migrate');
                } catch (\Exception $ex) {
                    \Log::error('Error al crear la base de datos: ' . $ex->getMessage());
                }
            } else {
                \Log::error('Error al conectar a la base de datos: ' . $e->getMessage());
            }
        }

        /*Estilos de render hooks*/
        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            function (): string {
                // Verificamos si estamos en la página de inicio
                $isHome = Route::currentRouteName() === 'home';

                // Si estamos en el Dashboard (Inicio), asignamos 'Inicio'
                $variable = $isHome ? 'Inicio' : session('titulo_pagina', 'Inicio');

                return view('filament.resources.components.topbar.index', ['titulo_pagina' => $variable])->render();
            }
        );

        $variable = Route::currentRouteName() === 'home' ? 'Inicio' : session('titulo_pagina', 'Inicio');
        View::share('titulo_pagina', $variable);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
