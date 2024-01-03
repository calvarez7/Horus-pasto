<?php

use App\Modelos\Tipobodega;
use Illuminate\Database\Seeder;

class TipobodegaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tipobodega::create([
            'Nombre' => 'Medicamentos'
        ]);

        Tipobodega::create([
            'Nombre' => 'Insumos'
        ]);

        Tipobodega::create([
            'Nombre' => 'Medicamentos Controlados'
        ]);
    }
}
