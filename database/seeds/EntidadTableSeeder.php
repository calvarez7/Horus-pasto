<?php

use App\Modelos\Entidade;
use Illuminate\Database\Seeder;

class EntidadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entidades = [
            [
                'estado_id' => 1,
                'nombre' => 'Unimap',
                'agendar_paciente' => '',
                'entrega_medicamentos' => '',
                'atender_paciente' => '',
                'autorizar_ordenes' => '',
                'consutar_historico' => '',
                'generar_ordenes' => '',
                'salud_ocupacional' => '',
                'contrato' => '',
            ],
            [
                'estado_id' => 1,
                'nombre' => 'Famac',
                'agendar_paciente' => '',
                'entrega_medicamentos' => '',
                'atender_paciente' => '',
                'autorizar_ordenes' => '',
                'consutar_historico' => '',
                'generar_ordenes' => '',
                'salud_ocupacional' => '',
                'contrato' => '',
            ]
        ];
        foreach ($entidades as $entidad) {
            Entidade::updateOrCreate(
            [
                'nombre' => $entidad['nombre']
            ],
            [
                'estado_id' => $entidad['estado_id'],
                'nombre' => $entidad['nombre'],
                'agendar_paciente' => $entidad['agendar_paciente'],
                'entrega_medicamentos' => $entidad['entrega_medicamentos'],
                'atender_paciente' => $entidad['atender_paciente'],
                'autorizar_ordenes' => $entidad['autorizar_ordenes'],
                'consutar_historico' => $entidad['consutar_historico'],
                'generar_ordenes' => $entidad['generar_ordenes'],
                'salud_ocupacional' => $entidad['salud_ocupacional'],
                'contrato' => $entidad['contrato'],
            ]);
        };
    }
}
