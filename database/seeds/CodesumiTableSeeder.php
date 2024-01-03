<?php

use App\Modelos\Codesumi;
use Illuminate\Database\Seeder;

class CodesumiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Codesumi::create([
            'Nombre' => 'Prueba',
            'Codigo' => '0001',
            'Tipocodesumi_id' => '1'
        ]);
    }
}
