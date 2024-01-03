<?php

use Modelos\Tipocita;
use Illuminate\Database\Seeder;

class TipocitaTableSeeder extends Seeder
{
    public function run()
    {
        Tipocita::cteate([
            'Nombre' => 'Transcripcion',
            'Descripcion' => 'Transcripcion'
        ]);
    }
}
