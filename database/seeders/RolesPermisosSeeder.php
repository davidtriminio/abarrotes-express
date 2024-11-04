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
        $permisoGeneral = Permission::create(['name' => 'N/A']);

        // PERMISOS USUARIOS
        $permisoUsuarioC = Permission::create(['name' => 'crear:usuarios']);
        $permisoUsuarioR = Permission::create(['name' => 'ver:usuarios']);
        $permisoUsuarioU = Permission::create(['name' => 'editar:usuarios']);
        $permisoUsuarioD = Permission::create(['name' => 'borrar:usuarios']);

        // PERMISOS ROLES
        $permisoRolC = Permission::create(['name' => 'crear:roles']);
        $permisoRolR = Permission::create(['name' => 'ver:roles']);
        $permisoRolU = Permission::create(['name' => 'editar:roles']);
        $permisoRolD = Permission::create(['name' => 'borrar:roles']);

        //  PERMISOS PERMISOS
        $permisoPermisoC = Permission::create(['name' => 'crear:permisos']);
        $permisoPermisoR = Permission::create(['name' => 'ver:permisos']);
        $permisoPermisoU = Permission::create(['name' => 'editar:permisos']);
        $permisoPermisoD = Permission::create(['name' => 'borrar:permisos']);

        /* PERMISOS ORDENES */
        $permisoOrdenC = Permission::create(['name' => 'crear:ordenes']);
        $permisoOrdenR = Permission::create(['name' => 'ver:ordenes']);
        $permisoOrdenU = Permission::create(['name' => 'editar:ordenes']);
        $permisoOrdenD = Permission::create(['name' => 'borrar:ordenes']);

        /* PERMISOS SUCURSALES*/
        $permisoSucursalC = Permission::create(['name' => 'crear:sucursales']);
        $permisoSucursalR = Permission::create(['name' => 'ver:sucursales']);
        $permisoSucursalU = Permission::create(['name' => 'editar:sucursales']);
        $permisoSucursalD = Permission::create(['name' => 'borrar:sucursales']);

        /* PERMISOS  PRODUCTOS*/
        $permisoProductoC = Permission::create(['name' => 'crear:productos']);
        $permisoProductoR = Permission::create(['name' => 'ver:productos']);
        $permisoProductoU = Permission::create(['name' => 'editar:productos']);
        $permisoProductoD = Permission::create(['name' => 'borrar:productos']);

        /* PERMISOS MARCAS*/
        $permisoMarcaC = Permission::create(['name' => 'crear:marcas']);
        $permisoMarcaR = Permission::create(['name' => 'ver:marcas']);
        $permisoMarcaU = Permission::create(['name' => 'editar:marcas']);
        $permisoMarcaD = Permission::create(['name' => 'borrar:marcas']);

        /* PERMISOS CATEGORIAS*/
        $permisoCategoriaC = Permission::create(['name' => 'crear:categorias']);
        $permisoCategoriaR = Permission::create(['name' => 'ver:categorias']);
        $permisoCategoriaU = Permission::create(['name' => 'editar:categorias']);
        $permisoCategoriaD = Permission::create(['name' => 'borrar:categorias']);

        /* PERMISOS CUPONES */
        $permisoCuponC = Permission::create(['name' => 'crear:cupones']);
        $permisoCuponR = Permission::create(['name' => 'ver:cupones']);
        $permisoCuponU = Permission::create(['name' => 'editar:cupones']);
        $permisoCuponD = Permission::create(['name' => 'borrar:cupones']);

        // ADMINS
        $permisoAdmin1 = Permission::create(['name' => 'ver:admin']);


        // CREACIÓN DE ROLES

        $superAdministradorRole = Role::create(['name' => 'SuperAdmin'])->syncPermissions([
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
        ]);


        $adminitradorRole = Role::create(['name' => 'Administrador'])->syncPermissions([
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
        ]);
        $adminRole = Role::create(['name' => 'Gerente'])->syncPermissions([
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
        ]);
        $vendedorRol = Role::create(['name' => 'Vendedor'])->syncPermissions([
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

        $usuarioRol = Role::create(['name' => 'Cliente'])->syncPermissions([
            $permisoGeneral,
        ]);

        // CREATE ADMINS & USERS
        User::create([
            'id' => 1,
            'name' => 'SuperAdministrador',
            'email' => 'super@ae.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($superAdministradorRole);

        User::create([
            'name' => 'Abarrotes Express Admin',
            'email' => 'equipo.abarrotes.express@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($superAdministradorRole);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@ae.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'recovery_key' => Str::random(30),
        ])->assignRole($adminitradorRole);;

User::create([
            'name' => 'David',
            'email' => 'triminio@ae.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'telefono' => '95684578',
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($adminitradorRole);;

        User::create([
            'name' => 'Luis Angel',
            'email' => 'l_ortez@ae.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'telefono' => '96321545',
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($adminitradorRole);;

        User::create([
            'name' => 'Claudia',
            'email' => 'claudia@ae.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'telefono' => '32146978',
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($adminitradorRole);;

        User::create([
            'name' => 'Selvin',
            'email_verified_at' => now(),
            'email' => 's_plata@ae.com',
            'password' => bcrypt('admin'),
            'telefono' => '32025896',
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($adminitradorRole);;

        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($adminRole);

        User::create([
            'name' => 'vendedor',
            'email' => 'vendedor@ae.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($vendedorRol);

        User::create([
            'name' => 'cliente',
            'email' => 'cliente@ae.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ])->assignRole($usuarioRol);

        User::create([
            'name' => 'invitado',
            'email' => 'invitado@ae.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'),
            'recovery_key' => Str::random(30),
            'remember_token' => Str::random(10),
        ]);

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $user->assignRole($usuarioRol);
        }
    }
}
