<?php

namespace App\Providers;

use App\Models\User;
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
        // Only run SuperAdmin initialization if explicitly enabled or in non-production environments
        // This prevents expensive Spatie Permission queries on every request during authentication
        if ($this->shouldInitializeSuperAdmin()) {
            $this->initializeSuperAdminIfNeeded();
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

    /**
     * Check if SuperAdmin initialization should run
     * This prevents running expensive Spatie queries on every request
     */
    private function shouldInitializeSuperAdmin(): bool
    {
        // Don't run during web requests if in production
        // Run only during console commands (migrations, seeding)
        if (app()->environment('production') && !app()->runningInConsole()) {
            return false;
        }
        return true;
    }

    /**
     * Initialize SuperAdmin user if it doesn't exist
     * Uses direct SQL queries to avoid Spatie Permission overhead
     */
    private function initializeSuperAdminIfNeeded(): void
    {
        try {
            DB::connection()->getPdo();

            // Check if roles table exists using simple query
            if (!\Schema::hasTable('roles')) {
                return;
            }

            // Use direct SQL queries to check for SuperAdmin user to avoid Spatie overhead
            $superAdminExists = DB::table('users')
                ->where('id', 1)
                ->exists();

            if ($superAdminExists) {
                return; // SuperAdmin already exists
            }

            // Disable foreign key checks for MySQL before inserting
            DBDriver::executeByDriver([
                'mysql' => function($conn) { return $conn->statement('SET FOREIGN_KEY_CHECKS=0;'); },
                'default' => function($conn) { return null; },
            ]);

            // Create SuperAdmin user
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

            // Now assign the SuperAdmin role using Eloquent (only after user exists)
            $superAdmin = User::find(1);
            if ($superAdmin && !$superAdmin->hasRole('SuperAdmin')) {
                $superAdmin->assignRole('SuperAdmin');
            }
        } catch (\Exception $e) {
            if ($e->getCode() === 1049) {  // Database doesn't exist
                $databaseName = env('DB_DATABASE', 'abarrotes_express');
                try {
                    DB::statement("CREATE DATABASE $databaseName");
                    \Log::info("Base de datos '$databaseName' creada exitosamente.");

                    // Reconnect after creating database
                    DB::purge();
                    DB::reconnect();

                    // Run migrations
                    \Artisan::call('migrate');
                } catch (\Exception $ex) {
                    \Log::error('Error al crear la base de datos: ' . $ex->getMessage());
                }
            } else {
                // Only log connection errors, don't crash
                \Log::debug('AppServiceProvider initialization error: ' . $e->getMessage());
            }
        }
    }
}
