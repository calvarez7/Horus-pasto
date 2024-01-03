<?php

use Illuminate\Database\Seeder;
use App\Modelos\Tipo;

class TiposTableSeeder extends Seeder
{
    public function run()
    {
        Tipo::create([
            'Nombre' => 'Entrada',
        ]);

        Tipo::create([
            'Nombre' => 'Salida',
        ]);
    }
}
