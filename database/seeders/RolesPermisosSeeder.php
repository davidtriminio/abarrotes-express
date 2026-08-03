<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\DBDriver;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for truncate operations
        DBDriver::executeByDriver([
            'mysql' => function($conn) { return $conn->statement('SET FOREIGN_KEY_CHECKS=0'); },
            'pgsql' => function($conn) { return $conn->statement('TRUNCATE model_has_roles CASCADE'); },
            'default' => function($conn) { return null; },
        ]);

        // Clear existing data using delete instead of truncate
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('users')->delete();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

        // Re-enable foreign key checks only for MySQL
        DBDriver::executeByDriver([
            'mysql' => function($conn) { return $conn->statement('SET FOREIGN_KEY_CHECKS=1'); },
            'default' => function($conn) { return null; },
        ]);

        // Create permissions in bulk
        $permissionsData = [
            'N/A',
            'crear:usuarios', 'ver:usuarios', 'editar:usuarios', 'borrar:usuarios',
            'crear:roles', 'ver:roles', 'editar:roles', 'borrar:roles',
            'crear:permisos', 'ver:permisos', 'editar:permisos', 'borrar:permisos',
            'crear:ordenes', 'ver:ordenes', 'editar:ordenes', 'borrar:ordenes',
            'crear:sucursales', 'ver:sucursales', 'editar:sucursales', 'borrar:sucursales',
            'crear:productos', 'ver:productos', 'editar:productos', 'borrar:productos',
            'crear:marcas', 'ver:marcas', 'editar:marcas', 'borrar:marcas',
            'crear:categorias', 'ver:categorias', 'editar:categorias', 'borrar:categorias',
            'crear:cupones', 'ver:cupones', 'editar:cupones', 'borrar:cupones',
            'crear:promociones', 'ver:promociones', 'editar:promociones', 'borrar:promociones',
            'crear:proveedores', 'ver:proveedores', 'editar:proveedores', 'borrar:proveedores',
            'crear:quejas_sugerencias', 'ver:quejas_sugerencias', 'editar:quejas_sugerencias', 'borrar:quejas_sugerencias',
            'crear:reportes_problemas', 'ver:reportes_problemas', 'editar:reportes_problemas', 'borrar:reportes_problemas',
            'ver:logs', 'ver:copias-seguridad', 'ver:inventario-avanzado', 'ver:admin', 'ver:notificaciones',
        ];

        $permissions = [];
        foreach ($permissionsData as $permName) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permName],
                ['guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]
            );
            $permissions[$permName] = DB::table('permissions')->where('name', $permName)->value('id');
        }

        // Create roles
        $roles = [
            'SuperAdmin' => array_values($permissions), // All permissions
            'Administrador' => [
                $permissions['crear:usuarios'], $permissions['ver:usuarios'], $permissions['editar:usuarios'], $permissions['borrar:usuarios'],
                $permissions['crear:ordenes'], $permissions['ver:ordenes'], $permissions['editar:ordenes'], $permissions['borrar:ordenes'],
                $permissions['crear:sucursales'], $permissions['ver:sucursales'], $permissions['editar:sucursales'], $permissions['borrar:sucursales'],
                $permissions['crear:productos'], $permissions['ver:productos'], $permissions['editar:productos'], $permissions['borrar:productos'],
                $permissions['crear:marcas'], $permissions['ver:marcas'], $permissions['editar:marcas'], $permissions['borrar:marcas'],
                $permissions['crear:categorias'], $permissions['ver:categorias'], $permissions['editar:categorias'], $permissions['borrar:categorias'],
                $permissions['crear:cupones'], $permissions['ver:cupones'], $permissions['editar:cupones'], $permissions['borrar:cupones'],
                $permissions['ver:admin'], $permissions['crear:promociones'], $permissions['ver:promociones'], $permissions['editar:promociones'], $permissions['borrar:promociones'],
                $permissions['ver:notificaciones'],
            ],
            'Gerente' => [
                $permissions['crear:usuarios'], $permissions['ver:usuarios'], $permissions['editar:usuarios'], $permissions['borrar:usuarios'],
                $permissions['crear:ordenes'], $permissions['ver:ordenes'], $permissions['editar:ordenes'], $permissions['borrar:ordenes'],
                $permissions['ver:admin'], $permissions['ver:notificaciones'],
            ],
            'Vendedor' => [
                $permissions['ver:usuarios'], $permissions['crear:ordenes'], $permissions['ver:ordenes'], $permissions['editar:ordenes'], $permissions['borrar:ordenes'],
                $permissions['ver:productos'], $permissions['ver:admin'],
            ],
            'Cliente' => [$permissions['N/A']],
        ];

        $roleIds = [];
        foreach ($roles as $roleName => $permIds) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName],
                ['guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()]
            );
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');
            $roleIds[$roleName] = $roleId;

            foreach ($permIds as $permId) {
                DB::table('role_has_permissions')->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permId],
                    ['role_id' => $roleId, 'permission_id' => $permId]
                );
            }
        }

        // Create users
        $usersData = [
            ['id' => 1, 'name' => 'SuperAdministrador', 'email' => 'super@ae.com', 'role_name' => 'SuperAdmin'],
            ['name' => 'Abarrotes Express Admin', 'email' => 'equipo.abarrotes.express@gmail.com', 'role_name' => 'SuperAdmin'],
            ['name' => 'Admin', 'email' => 'admin@ae.com', 'role_name' => 'Administrador'],
            ['name' => 'David', 'email' => 'triminio@ae.com', 'role_name' => 'Administrador', 'telefono' => '95684578'],
            ['name' => 'Luis Angel', 'email' => 'l_ortez@ae.com', 'role_name' => 'Administrador', 'telefono' => '96321545'],
            ['name' => 'Claudia', 'email' => 'claudia@ae.com', 'role_name' => 'Administrador', 'telefono' => '32146978'],
            ['name' => 'Selvin', 'email' => 's_plata@ae.com', 'role_name' => 'Administrador', 'telefono' => '32025896'],
            ['name' => 'admin', 'email' => 'admin@admin.com', 'role_name' => 'Gerente'],
            ['name' => 'vendedor', 'email' => 'vendedor@ae.com', 'role_name' => 'Vendedor'],
            ['name' => 'cliente', 'email' => 'cliente@ae.com', 'role_name' => 'Cliente'],
            ['name' => 'invitado', 'email' => 'invitado@ae.com', 'role_name' => 'Cliente'],
        ];

        foreach ($usersData as $userData) {
            $roleName = $userData['role_name'];
            $roleId = $roleIds[$roleName];
            unset($userData['role_name']);

            $userData['email_verified_at'] = now();
            $userData['password'] = bcrypt('admin');
            $userData['recovery_key'] = Str::random(30);
            $userData['remember_token'] = Str::random(10);
            $userData['created_at'] = now();
            $userData['updated_at'] = now();

            DB::table('users')->updateOrInsert(
                isset($userData['id']) ? ['id' => $userData['id']] : ['email' => $userData['email']],
                $userData
            );
            $userId = isset($userData['id']) ? $userData['id'] : DB::table('users')->where('email', $userData['email'])->value('id');

            DB::table('model_has_roles')->updateOrInsert(
                ['role_id' => $roleId, 'model_type' => 'App\\\\Models\\\\User', 'model_id' => $userId],
                ['role_id' => $roleId, 'model_type' => 'App\\\\Models\\\\User', 'model_id' => $userId]
            );
        }
    }
}
