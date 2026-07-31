<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UsuarioObserver;
use DB;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // Insertar manualmente el superadministrador con ID 1
                DB::insert('INSERT IGNORE INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [
                    1,
                    'SuperAdministrador',
                    'super@ae.com',
                    now(),
                    \Hash::make('admin'),
                    \Str::random(10),
                    now(),
                    now()
                ]);

                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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

// Compartimos el valor de la sesión con todas las vistas
        $variable = Route::currentRouteName() === 'home' ? 'Inicio' : session('titulo_pagina', 'Inicio');
        View::share('titulo_pagina', $variable);
    }
}
