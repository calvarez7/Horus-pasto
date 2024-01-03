<?php

use App\Modelos\Parentesco;
use Illuminate\Database\Seeder;

class ParentescoTableSeeder extends Seeder
{

    public function run()
    {
        Parentesco::create([
            'Nombre' => 'Padre',
        ]);
        Parentesco::create([
            'Nombre' => 'Madre',
        ]);
        Parentesco::create([
            'Nombre' => 'Hermano(a)',
        ]);
        Parentesco::create([
            'Nombre' => 'Hijo(a)',
        ]);
        Parentesco::create([
            'Nombre' => 'Abuelo(a)'
        ]);
    }
}
