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
        $permisoUsuarioC = Permission::create(['name' => 'crear:usuario']);
        $permisoUsuarioR = Permission::create(['name' => 'ver:usuario']);
        $permisoUsuarioU = Permission::create(['name' => 'actualizar:usuario']);
        $permisoUsuarioD = Permission::create(['name' => 'borrar:usuario']);

        // PERMISOS ROLES
        $permisoRolC = Permission::create(['name' => 'crear:rol']);
        $permisoRolR = Permission::create(['name' => 'ver:rol']);
        $permisoRolU = Permission::create(['name' => 'actualizar:rol']);
        $permisoRolD = Permission::create(['name' => 'borrar:rol']);

        //  PERMISOS PERMISOS
        $permisoPermisoC = Permission::create(['name' => 'crear:permiso']);
        $permisoPermisoR = Permission::create(['name' => 'ver:permiso']);
        $permisoPermisoU = Permission::create(['name' => 'actualizar:permiso']);
        $permisoPermisoD = Permission::create(['name' => 'borrar:permiso']);

        /* PERMISOS ORDENES */
        $permisoOrdenC = Permission::create(['name' => 'crear:orden']);
        $permisoOrdenR = Permission::create(['name' => 'ver:orden']);
        $permisoOrdenU = Permission::create(['name' => 'actualizar:orden']);
        $permisoOrdenD = Permission::create(['name' => 'borrar:orden']);

        /* PERMISOS SUCURSALES*/
        $permisoSucursalC = Permission::create(['name' => 'crear:sucursal']);
        $permisoSucursalR = Permission::create(['name' => 'ver:sucursal']);
        $permisoSucursalU = Permission::create(['name' => 'actualizar:sucursal']);
        $permisoSucursalD = Permission::create(['name' => 'borrar:sucursal']);

        /* PERMISOS  PRODUCTOS*/
        $permisoProductoC = Permission::create(['name' => 'crear:producto']);
        $permisoProductoR = Permission::create(['name' => 'ver:producto']);
        $permisoProductoU = Permission::create(['name' => 'actualizar:producto']);
        $permisoProductoD = Permission::create(['name' => 'borrar:producto']);

        /* PERMISOS MARCAS*/
        $permisoMarcaC = Permission::create(['name' => 'crear:marca']);
        $permisoMarcaR = Permission::create(['name' => 'ver:marca']);
        $permisoMarcaU = Permission::create(['name' => 'actualizar:marca']);
        $permisoMarcaD = Permission::create(['name' => 'borrar:marca']);

        /* PERMISOS CATEGORIAS*/
        $permisoCategoriaC = Permission::create(['name' => 'crear:categoria']);
        $permisoCategoriaR = Permission::create(['name' => 'ver:categoria']);
        $permisoCategoriaU = Permission::create(['name' => 'actualizar:categoria']);
        $permisoCategoriaD = Permission::create(['name' => 'borrar:categoria']);

        /* PERMISOS CUPONES */
        $permisoCuponC = Permission::create(['name' => 'crear:cupon']);
        $permisoCuponR = Permission::create(['name' => 'ver:cupon']);
        $permisoCuponU = Permission::create(['name' => 'actualizar:cupon']);
        $permisoCuponD = Permission::create(['name' => 'borrar:cupon']);

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
