<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{

    public function run()
    {
        $rol = Role::where('id', 1)->first();
        $usuarios = [

            [
                'name' => 'Julian',
                'apellido' => 'Cardona',
                'direccion' => '123',
                'celular' => '123',
                'fecha_naci' => '01/01/1990',
                'cedula' => '123',
                'nit' => '123',
                'telefono' => '123',
                'estado_user' => 1,
                'avatar' => '123',
                'Firma' => '',
                'Registromedico' => '',
                'especialidad_medico' => 'TECNOLOGO PROGRAMADOR',
                'prestador_id' => null,
                'sede_id' => null,
                'email' => 'david@domicilios.com',
                'password' => bcrypt(123456789)
            ]
        ];
        foreach ($usuarios as $user) {
            $usuario =  User::updateOrCreate([
                'email' => $user['email']
            ],[
                'name' => $user['name'],
                'apellido' => $user['apellido'],
                'direccion' => $user['direccion'],
                'celular' => $user['celular'],
                'fecha_naci' => $user['fecha_naci'],
                'cedula' => $user['cedula'],
                'nit' => $user['nit'],
                'telefono' => $user['telefono'],
                'estado_user' => $user['estado_user'],
                'avatar' => $user['avatar'],
                'Firma' => $user['Firma'],
                'Registromedico' => $user['Registromedico'],
                'especialidad_medico' => $user['especialidad_medico'],
                'prestador_id' => $user['prestador_id'],
                'sede_id' => $user['sede_id'],
                'email' => $user['email'],
                'password' => $user['password'],
            ]);
        };

        $usuario->assignRole('Admin');

        $permisos = Permission::all();

        $rol->syncPermissions($permisos);

    }
}
