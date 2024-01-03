<?php

use App\Modelos\Tipoprestador;
use Illuminate\Database\Seeder;

class TipoprestadorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tipoprestador::create([
            'Nombre' => 'Servicios de Salud'
        ]);

        Tipoprestador::create([
            'Nombre' => 'Medicamentos'
        ]);
    }
}
