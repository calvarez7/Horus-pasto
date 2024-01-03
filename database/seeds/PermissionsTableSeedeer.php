<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'dev','guard_name' => 'api']);
        Permission::create(['name' => 'medico','guard_name' => 'api']);
        Permission::create(['name' => 'Crearespecialidad','guard_name' => 'api']);
        Permission::create(['name' => 'Editarespecialidad','guard_name' => 'api']);
        Permission::create(['name' => 'Creartipoagenda','guard_name' => 'api']);
        Permission::create(['name' => 'Editartipoagenda','guard_name' => 'api']);
        Permission::create(['name' => 'Crearactividad','guard_name' => 'api']);
        Permission::create(['name' => 'Editaractividad','guard_name' => 'api']);
        Permission::create(['name' => 'Vertiposagenda','guard_name' => 'api']);
        Permission::create(['name' => 'Crearsede','guard_name' => 'api']);
        Permission::create(['name' => 'Crearconsultorio','guard_name' => 'api']);
        Permission::create(['name' => 'Editarconsultorio','guard_name' => 'api']);
        Permission::create(['name' => 'Versedes','guard_name' => 'api']);
        Permission::create(['name' => 'Crearagenda','guard_name' => 'api']);
        Permission::create(['name' => 'Bloquearagenda','guard_name' => 'api']);
        Permission::create(['name' => 'Eliminaragenda','guard_name' => 'api']);
        Permission::create(['name' => 'Veragenda','guard_name' => 'api']);
        Permission::create(['name' => 'Asignarcita','guard_name' => 'api']);
        Permission::create(['name' => 'Cancelarcita','guard_name' => 'api']);
        Permission::create(['name' => 'Bloquearcita','guard_name' => 'api']);

        // //Admin
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'api']);

        // //Asignación de todos los permisos al rol Admin
        $admin->givePermissionTo(Permission::all());

        //  //Asignar rol a usuario
         $user = User::find(1);
         $user->assignRole('Admin');

    }
}
