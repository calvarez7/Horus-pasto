<?php

use App\Modelos\Bodega;
use Illuminate\Database\Seeder;

class BodegaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'BODEGA CENTRAL SUMIMEDICAL',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO CENTRO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO ARGENTINA',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO BELLO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO ENVIGADO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO ITAGUI',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO RIONEGRO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO APARTADO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO TURBO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO CAUCASIA',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '1',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO PUERTO BERRIO',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
        Bodega::create([
            'Municipio_id' => '41',
            'Tipobodega_id' => '1',
            'Nombre' => 'SERVICIO FARMACEUTICO COPACABANA',
            'Direccion' => 'no hay direccion',
            'Horainicio' => '7:00',
            'Horafin' => '17:00'
        ]);
    }
}
