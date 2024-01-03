<?php

use App\Modelos\Tipocodigo;
use Illuminate\Database\Seeder;

class TipocodigoTableSeeder extends Seeder
{
    public function run()
    {
        Tipocodigo::create([
            'Nombre' => 'Medicamentos',
            'Descripcion' => 'Solo para Medicamentos'
        ]);
    }
}
