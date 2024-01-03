<?php

use App\Modelos\Estado;
use Illuminate\Database\Seeder;

class EstadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Estados de Citas
        Estado::create([
            'Nombre' => 'Activo'
            ]);
        Estado::create([
            'Nombre' => 'inactivo'
            ]);
        Estado::create([
            'Nombre' => 'disponible'
            ]);
        Estado::create([
            'Nombre' => 'Por confirmar'
            ]);
        Estado::create([
            'Nombre' => 'Asignado'
            ]);
        Estado::create([
            'Nombre' => 'cancelada'
            ]);
        Estado::create([
            'Nombre' => 'confirmada'
            ]);
        Estado::create([
            'Nombre' => 'En consulta'
            ]);
        Estado::create([
            'Nombre' => 'Atendida'
            ]);
        Estado::create([
            'Nombre' => 'Bloqueada'
            ]);
        Estado::create([
            'Nombre' => 'Reasignada'
            ]);
        Estado::create([
            'Nombre' => 'inasistencia'
            ]);
        Estado::create([
            'Nombre' => 'Cerrado'
            ]);
        Estado::create([
            'Nombre' => 'Cerrado temporal'
            ]);
        Estado::create([
            'Nombre' => 'Pendiente por Autorizar'
            ]);
        Estado::create([
            'Nombre' => 'Vencida'
            ]);
        Estado::create([
            'Nombre' => 'Dispensado'
            ]);
        Estado::create([
            'Nombre' => 'Pendiente'
            ]);
        Estado::create([
            'Nombre' => 'Parcial'
            ]);
        Estado::create([
            'Nombre' => 'Impresión diagnóstica'
            ]);
        Estado::create([
            'Nombre' => 'Confirmado nuevo'
            ]);
        Estado::create([
            'Nombre' => 'Confirmado repetido'
            ]);
        Estado::create([
            'Nombre' => 'Confirmado repetido'
            ]);
        Estado::create([
            'Nombre' => 'Inadecuado'
            ]);
        Estado::create([
            'Nombre' => 'Negado'
            ]);
        Estado::create([
            'Nombre' => 'Anulado'
            ]);
        Estado::create([
            'Nombre' => 'Retirado'
        ]);
        Estado::create([
            'Nombre' => 'Proteccion Laboral Cotizante'
        ]);
        Estado::create([
            'Nombre' => 'Proteccion Laboral Beneficiario'
        ]);
        Estado::create([
            'Nombre' => 'Conteo1 Finalizado'
        ]);
        Estado::create([
            'Nombre' => 'Conteo2 Finalizado'
        ]);
        Estado::create([
            'Nombre' => 'Conteo3 Finalizado'
        ]);
        Estado::create([
            'Nombre' => 'Conteo4 Finalizado'
        ]);
        Estado::create([
            'Nombre' => 'Terminado'
        ]);
        Estado::create([
            'Nombre' => 'Revision Quimica Farmaceutica'
        ]);
    }
}
