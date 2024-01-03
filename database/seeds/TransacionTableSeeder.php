<?php

use Illuminate\Database\Seeder;
use App\Modelos\Transacion;

class TransacionTableSeeder extends Seeder
{
    public function run()
    {
        Transacion::create([
            'Nombre' => 'Solicitud de Compra',
            'Descripcion' => 'Compras'
        ]);

        Transacion::create([
            'Nombre' => 'Traslado',
            'Descripcion' => 'Traslados'
        ]);

        Transacion::create([
            'Nombre' => 'Dispensación',
            'Descripcion' => 'Dispensación'
        ]);

        Transacion::create([
            'Nombre' => 'Entrada por factura',
            'Descripcion' => 'Dispensación'
        ]);
    }
}
