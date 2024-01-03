<?php

use Illuminate\Database\Seeder;
use App\Modelos\Tiporden;

class TipoOrdenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Tiporden::create([
            'Nombre' => 'Incapacidades',
            'Descripcion' => 'Incapacidades'
        ]);

        Tiporden::create([
            'Nombre' => 'Interconsulta',
            'Descripcion' => 'Interconsulta'
        ]);

        Tiporden::create([
            'Nombre' => 'Fórmula médica',
            'Descripcion' => 'Fórmula médica'
        ]);

        Tiporden::create([
            'Nombre' => 'Otros Servicios',
            'Descripcion' => 'Otros Servicios'
        ]);

    }
}
