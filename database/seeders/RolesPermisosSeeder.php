<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Misc
        $permisoGeneral = Permission::firstOrCreate(['name' => 'N/A']);

        // PERMISOS USUARIOS
        $permisoUsuarioC = Permission::firstOrCreate(['name' => 'crear:usuarios']);
        $permisoUsuarioR = Permission::firstOrCreate(['name' => 'ver:usuarios']);
        $permisoUsuarioU = Permission::firstOrCreate(['name' => 'editar:usuarios']);
        $permisoUsuarioD = Permission::firstOrCreate(['name' => 'borrar:usuarios']);

        // PERMISOS ROLES
        $permisoRolC = Permission::firstOrCreate(['name' => 'crear:roles']);
        $permisoRolR = Permission::firstOrCreate(['name' => 'ver:roles']);
        $permisoRolU = Permission::firstOrCreate(['name' => 'editar:roles']);
        $permisoRolD = Permission::firstOrCreate(['name' => 'borrar:roles']);

        //  PERMISOS PERMISOS
        $permisoPermisoC = Permission::firstOrCreate(['name' => 'crear:permisos']);
        $permisoPermisoR = Permission::firstOrCreate(['name' => 'ver:permisos']);
        $permisoPermisoU = Permission::firstOrCreate(['name' => 'editar:permisos']);
        $permisoPermisoD = Permission::firstOrCreate(['name' => 'borrar:permisos']);

        /* PERMISOS ORDENES */
        $permisoOrdenC = Permission::firstOrCreate(['name' => 'crear:ordenes']);
        $permisoOrdenR = Permission::firstOrCreate(['name' => 'ver:ordenes']);
        $permisoOrdenU = Permission::firstOrCreate(['name' => 'editar:ordenes']);
        $permisoOrdenD = Permission::firstOrCreate(['name' => 'borrar:ordenes']);

        /* PERMISOS SUCURSALES*/
        $permisoSucursalC = Permission::firstOrCreate(['name' => 'crear:sucursales']);
        $permisoSucursalR = Permission::firstOrCreate(['name' => 'ver:sucursales']);
        $permisoSucursalU = Permission::firstOrCreate(['name' => 'editar:sucursales']);
        $permisoSucursalD = Permission::firstOrCreate(['name' => 'borrar:sucursales']);

        /* PERMISOS  PRODUCTOS*/
        $permisoProductoC = Permission::firstOrCreate(['name' => 'crear:productos']);
        $permisoProductoR = Permission::firstOrCreate(['name' => 'ver:productos']);
        $permisoProductoU = Permission::firstOrCreate(['name' => 'editar:productos']);
        $permisoProductoD = Permission::firstOrCreate(['name' => 'borrar:productos']);

        /* PERMISOS MARCAS*/
        $permisoMarcaC = Permission::firstOrCreate(['name' => 'crear:marcas']);
        $permisoMarcaR = Permission::firstOrCreate(['name' => 'ver:marcas']);
        $permisoMarcaU = Permission::firstOrCreate(['name' => 'editar:marcas']);
        $permisoMarcaD = Permission::firstOrCreate(['name' => 'borrar:marcas']);

        /* PERMISOS CATEGORIAS*/
        $permisoCategoriaC = Permission::firstOrCreate(['name' => 'crear:categorias']);
        $permisoCategoriaR = Permission::firstOrCreate(['name' => 'ver:categorias']);
        $permisoCategoriaU = Permission::firstOrCreate(['name' => 'editar:categorias']);
        $permisoCategoriaD = Permission::firstOrCreate(['name' => 'borrar:categorias']);

        /* PERMISOS CUPONES */
        $permisoCuponC = Permission::firstOrCreate(['name' => 'crear:cupones']);
        $permisoCuponR = Permission::firstOrCreate(['name' => 'ver:cupones']);
        $permisoCuponU = Permission::firstOrCreate(['name' => 'editar:cupones']);
        $permisoCuponD = Permission::firstOrCreate(['name' => 'borrar:cupones']);

        /*Permisos Promociones*/
        $permisoPromocionC = Permission::firstOrCreate(['name' => 'crear:promociones']);
        $permisoPromocionR = Permission::firstOrCreate(['name' => 'ver:promociones']);
        $permisoPromocionU = Permission::firstOrCreate(['name' => 'editar:promociones']);
        $permisoPromocionD = Permission::firstOrCreate(['name' => 'borrar:promociones']);


    /* Permisos Proveedores */
        $permisoProveedorC = Permission::firstOrCreate(['name' => 'crear:proveedores']);
        $permisoProveedorR = Permission::firstOrCreate(['name' => 'ver:proveedores']);
        $permisoProveedorU = Permission::firstOrCreate(['name' => 'editar:proveedores']);
        $permisoProveedorD = Permission::firstOrCreate(['name' => 'borrar:proveedores']);

        /*Permisos Quejas y Sugerencias*/
        $permisoQySC = Permission::firstOrCreate(['name' => 'crear:quejas_sugerencias']);
        $permisoQySR = Permission::firstOrCreate(['name' => 'ver:quejas_sugerencias']);
        $permisoQySU = Permission::firstOrCreate(['name' => 'editar:quejas_sugerencias']);
        $permisoQySD = Permission::firstOrCreate(['name' => 'borrar:quejas_sugerencias']);

            /*Permisos Reportes de Problemas*/
        $permisoRpSC = Permission::firstOrCreate(['name' => 'crear:reportes_problemas']);
        $permisoRpSR = Permission::firstOrCreate(['name' => 'ver:reportes_problemas']);
        $permisoRpSU = Permission::firstOrCreate(['name' => 'editar:reportes_problemas']);
        $permisoRpSD = Permission::firstOrCreate(['name' => 'borrar:reportes_problemas']);

        /*Permisos Logs*/
        $permisoLogsR = Permission::firstOrCreate(['name' => 'ver:logs']);

        /*Permisos Copias de Seguridad*/
        $permisoCopiasR = Permission::firstOrCreate(['name' => 'ver:copias-seguridad']);
        $permisoInventarioAvanzadoR = Permission::firstOrCreate(['name' => 'ver:inventario-avanzado']);


        // ADMINS
        $permisoAdmin1 = Permission::firstOrCreate(['name' => 'ver:admin']);
        /*Permisos Notificaciones*/
        $permisoNotificaciones = Permission::firstOrCreate(['name' => 'ver:notificaciones']);


        // CREACIÓN DE ROLES

        $superAdministradorRole = Role::firstOrCreate(['name' => 'SuperAdmin'])->syncPermissions([
            $permisoUsuarioC,
            $permisoUsuarioR,
            $permisoUsuarioU,
            $permisoUsuarioD,
            $permisoRolC,
            $permisoRolR,
            $permisoRolU,
            $permisoRolD,
            $permisoPermisoC,
            $permisoPermisoR,
            $permisoPermisoU,
            $permisoPermisoD,
            $permisoOrdenC,
            $permisoOrdenR,
            $permisoOrdenU,
            $permisoOrdenD,
            $permisoSucursalC,
            $permisoSucursalR,
            $permisoSucursalU,
            $permisoSucursalD,
            $permisoProductoC,
            $permisoProductoR,
            $permisoProductoU,
            $permisoProductoD,
            $permisoMarcaC,
            $permisoMarcaR,
            $permisoMarcaU,
            $permisoMarcaD,
            $permisoCategoriaC,
            $permisoCategoriaR,
            $permisoCategoriaU,
            $permisoCategoriaD,
            $permisoCuponC,
            $permisoCuponR,
            $permisoCuponU,
            $permisoCuponD,
            $permisoAdmin1,
            $permisoPromocionC,
            $permisoPromocionR,
            $permisoPromocionU,
            $permisoPromocionD,
            $permisoPromocionC,
            $permisoPromocionR,
            $permisoPromocionU,
            $permisoPromocionD,
            $permisoRpSC,
            $permisoRpSR,
            $permisoRpSU,
            $permisoRpSD,
            $permisoProveedorC,
            $permisoProveedorR,
            $permisoProveedorU,
            $permisoProveedorD,
            $permisoQySC,
            $permisoQySR,
            $permisoQySU,
            $permisoQySD,
            $permisoNotificaciones,
            $permisoLogsR,
            $permisoCopiasR,
            $permisoInventarioAvanzadoR,
        ]);


        $adminitradorRole = Role::firstOrCreate(['name' => 'Administrador'])->syncPermissions([
            $permisoUsuarioC,
            $permisoUsuarioR,
            $permisoUsuarioU,
            $permisoUsuarioD,
            $permisoOrdenC,
            $permisoOrdenR,
            $permisoOrdenU,
            $permisoOrdenD,
            $permisoSucursalC,
            $permisoSucursalR,
            $permisoSucursalU,
            $permisoSucursalD,
            $permisoProductoC,
            $permisoProductoR,
            $permisoProductoU,
            $permisoProductoD,
            $permisoMarcaC,
            $permisoMarcaR,
            $permisoMarcaU,
            $permisoMarcaD,
            $permisoCategoriaC,
            $permisoCategoriaR,
            $permisoCategoriaU,
            $permisoCategoriaD,
            $permisoCuponC,
            $permisoCuponR,
            $permisoCuponU,
            $permisoCuponD,
            $permisoAdmin1,
            $permisoPromocionC,
            $permisoPromocionR,
            $permisoPromocionU,
            $permisoPromocionD,
            $permisoNotificaciones,
        ]);
        $adminRole = Role::firstOrCreate(['name' => 'Gerente'])->syncPermissions([
            $permisoUsuarioC,
            $permisoUsuarioR,
            $permisoUsuarioU,
            $permisoUsuarioD,
            $permisoOrdenC,
            $permisoOrdenR,
            $permisoOrdenU,
            $permisoOrdenD,
            $permisoSucursalC,
            $permisoSucursalR,
            $permisoSucursalU,
            $permisoSucursalD,
            $permisoProductoC,
            $permisoProductoR,
            $permisoProductoU,
            $permisoProductoD,
            $permisoMarcaC,
            $permisoMarcaR,
            $permisoMarcaU,
            $permisoMarcaD,
            $permisoCategoriaC,
            $permisoCategoriaR,
            $permisoCategoriaU,
            $permisoCategoriaD,
            $permisoCuponC,
            $permisoCuponR,
            $permisoCuponU,
            $permisoCuponD,
            $permisoAdmin1,
            $permisoNotificaciones,
        ]);
        $vendedorRol = Role::firstOrCreate(['name' => 'Vendedor'])->syncPermissions([
            $permisoUsuarioR,
            $permisoOrdenC,
            $permisoOrdenR,
            $permisoOrdenU,
            $permisoOrdenD,
            $permisoSucursalR,
            $permisoProductoR,
            $permisoMarcaR,
            $permisoCategoriaR,
            $permisoCuponC,
            $permisoCuponR,
            $permisoCuponU,
            $permisoCuponD,
            $permisoAdmin1,
        ]);

        $usuarioRol = Role::firstOrCreate(['name' => 'Cliente'])->syncPermissions([
            $permisoGeneral,
        ]);

        // CREATE ADMINS & USERS
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'SuperAdministrador',
                'email' => 'super@ae.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::find(1)?->syncRoles($superAdministradorRole);

        User::updateOrCreate(
            ['email' => 'equipo.abarrotes.express@gmail.com'],
            [
                'name' => 'Abarrotes Express Admin',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'equipo.abarrotes.express@gmail.com')->first()?->syncRoles($superAdministradorRole);

        User::updateOrCreate(
            ['email' => 'admin@ae.com'],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
            ]
        );
        User::where('email', 'admin@ae.com')->first()?->syncRoles($adminitradorRole);

        User::updateOrCreate(
            ['email' => 'triminio@ae.com'],
            [
                'name' => 'David',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'telefono' => '95684578',
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'triminio@ae.com')->first()?->syncRoles($adminitradorRole);

        User::updateOrCreate(
            ['email' => 'l_ortez@ae.com'],
            [
                'name' => 'Luis Angel',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'telefono' => '96321545',
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'l_ortez@ae.com')->first()?->syncRoles($adminitradorRole);

        User::updateOrCreate(
            ['email' => 'claudia@ae.com'],
            [
                'name' => 'Claudia',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'telefono' => '32146978',
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'claudia@ae.com')->first()?->syncRoles($adminitradorRole);

        User::updateOrCreate(
            ['email' => 's_plata@ae.com'],
            [
                'name' => 'Selvin',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'telefono' => '32025896',
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 's_plata@ae.com')->first()?->syncRoles($adminitradorRole);

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'admin@admin.com')->first()?->syncRoles($adminRole);

        User::updateOrCreate(
            ['email' => 'vendedor@ae.com'],
            [
                'name' => 'vendedor',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'vendedor@ae.com')->first()?->syncRoles($vendedorRol);

        User::updateOrCreate(
            ['email' => 'cliente@ae.com'],
            [
                'name' => 'cliente',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );
        User::where('email', 'cliente@ae.com')->first()?->syncRoles($usuarioRol);

        User::updateOrCreate(
            ['email' => 'invitado@ae.com'],
            [
                'name' => 'invitado',
                'email_verified_at' => now(),
                'password' => bcrypt('admin'),
                'recovery_key' => Str::random(30),
                'remember_token' => Str::random(10),
            ]
        );

        // Create additional users only if they don't exist (limited to avoid duplicates)
        $usersCount = User::count();
        if ($usersCount < 25) {
            User::factory(10)->create();

            $users = User::whereDoesntHave('roles')->limit(10)->get();
            foreach ($users as $user) {
                $user->assignRole($usuarioRol);
            }
        }
    }
}
