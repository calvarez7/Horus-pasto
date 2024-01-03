<?php

use Illuminate\Database\Seeder;
use App\Modelos\Sede;

class SedeTableSeeder extends Seeder
{
    public function run()
    {
        Sede::create([
            'Municipio_id' => '59',
            'Nombre' => 'ITAGUI',
            'Direccion' => 'CR 49 #51 40',
            'Telefono' => '5201040 EXT 8',
            'Nit' => '900033371',
        ]);
        Sede::create([
            'Municipio_id' => '41',
            'Nombre' => 'COPACABANA',
            'Direccion' => 'Calle 53 # 56 - 34',
            'Telefono' => '5201040',
            'Nit' => '900033371',
        ]);
    }
}
