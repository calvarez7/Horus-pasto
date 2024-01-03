<?php

use App\Modelos\Tipocita;
use Illuminate\Database\Seeder;

class TipocitasTableSeeder extends Seeder
{    
    public function run()
    {
        Tipocita::create([
            'Nombre' => 'Transcripción',
            'Descripcion' => 'Transcripción',
        ]);
    }
}
