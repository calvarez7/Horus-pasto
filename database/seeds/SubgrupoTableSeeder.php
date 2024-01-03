<?php

use App\Modelos\Subgrupo;
use Illuminate\Database\Seeder;

class SubgrupoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subgrupo::create([
            'Grupo_id' => '1',
            'Nombre' => 'Medicamentos',
            'Descripcion' => 'Solo medicamentos',
        ]);
    }
}
