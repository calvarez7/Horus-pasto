<?php

use Illuminate\Database\Seeder;
use App\Modelos\Tipotransacion;

class TipotransacionTableSeeder extends Seeder
{
    public function run()
    {
        Tipotransacion::create([
            'Transacion_id' => '1',
            'Tipo_id' => '1'
        ]);

        Tipotransacion::create([
            'Transacion_id' => '2',
            'Tipo_id' => '2'
        ]);

        Tipotransacion::create([
            'Transacion_id' => '3',
            'Tipo_id' => '2'
        ]);

        Tipotransacion::create([
            'Transacion_id' => '4',
            'Tipo_id' => '1'
        ]);
    }
}
