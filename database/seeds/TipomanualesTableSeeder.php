<?php

use Illuminate\Database\Seeder;
use App\Modelos\Tipomanuale;

class TipomanualesTableSeeder extends Seeder
{
    public function run()
    {
        Tipomanuale::create([
            'Nombre' => 'Soat',
            'Descripcion' => 'Manual soat'
        ]);

        Tipomanuale::create([
            'Nombre' => 'Iss2000',
            'Descripcion' => 'Manual iss2000'
        ]);

        Tipomanuale::create([
            'Nombre' => 'Iss2001',
            'Descripcion' => 'Manual iss2001'
        ]);

        Tipomanuale::create([
            'Nombre' => 'Tarifa Propia',
            'Descripcion' => 'Tarifa Propia por prestador'
        ]);
    }
}
