<?php

namespace App\Repositories;

use App\Modelos\OportunidadCita;
use App\Interfaces\FiasInterface;
use Illuminate\Support\Facades\DB;

class F18Repository implements FiasInterface{

    protected $cups = [
        '890281',
        '890284',
        '890285',
        '890282',
        '890276',
        '890242',
        '890262',
        '890263',
        '890280',
    ];

    /**
     * Genera la consulta para realizar el fias
     * @param Object $data
     * @return Collection
     * @author JDSS
     */
    public function consultar($data){

        $select = [
            'oportunidad_citas.Dpto','oportunidad_citas.Mpio',
            'oportunidad_citas.Tipo_Doc',
            'oportunidad_citas.Num_Doc',
            DB::raw('CONVERT(varchar, oportunidad_citas.fecha_deseada, 103)'), 
            DB::raw("CONVERT(varchar, oportunidad_citas.fecha_asignacion, 103)"),
            DB::raw("case  
            when oportunidad_citas.tipo_consulta ='890280' OR oportunidad_citas.tipo_consulta ='890281' then 1 
            when oportunidad_citas.tipo_consulta = '890284' OR oportunidad_citas.tipo_consulta = '890285' then 2
            when oportunidad_citas.tipo_consulta ='890282' then 3
            when oportunidad_citas.tipo_consulta = '890273' then 4
            when oportunidad_citas.tipo_consulta = '890242' then 5
            when oportunidad_citas.tipo_consulta = '890262' then 6
            when oportunidad_citas.tipo_consulta = '890263' then 7
            else 9 end as tipoconulta ")
        ];

        $pacientes = OportunidadCita::select($select)
            ->whereFecha($data->mes, $data->anio)
            ->whereDepartamento($data->departamento_id)
            ->whereIn('oportunidad_citas.tipo_consulta', $this->cups)
            ->get();

       return $pacientes;
    }

}

