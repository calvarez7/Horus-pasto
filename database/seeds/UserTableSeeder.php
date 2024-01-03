<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    public function run()
    {
        $filepath = storage_path('avatars');
        User::create([
            'name' => 'Horus',
            'apellido' => 'Demo',
            'direccion' => null,
            'celular' => '00',
            'cedula' => '00',
            'nit' => '000',
            'telefono' => '00',
            'email' => 'horus.demo@sumimedical.com',
            'password' => bcrypt('1234')
            ]);
    }
}
