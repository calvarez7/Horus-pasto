<?php

use App\Modelos\Grupo;
use Illuminate\Database\Seeder;

class GrupoTableSeeder extends Seeder
{
    public function run()
    {
        Grupo::create([
            'Nombre' => 'Medicamentos',
            'Codigo' => 'MED'
        ]);

        Grupo::create([
            'Nombre' => 'Insumos',
            'Codigo' => 'INS'
        ]);
    }
}
