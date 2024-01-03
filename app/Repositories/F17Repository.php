<?php

namespace App\Repositories;

use App\Modelos\OportunidadCita;
use App\Interfaces\FiasInterface;
use Illuminate\Support\Facades\DB;

class F17Repository implements FiasInterface{

    protected $cups = [
        '890203','890201','890266','890283','890250','890235','890263','890262',
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
            when oportunidad_citas.tipo_consulta ='890201' then 1
            when oportunidad_citas.tipo_consulta = '890203' then 2
            when oportunidad_citas.tipo_consulta ='890266' then 3
            when oportunidad_citas.tipo_consulta = '890283' then 4
            when oportunidad_citas.tipo_consulta = '890250' then 5
            when oportunidad_citas.tipo_consulta = '890250' and oportunidad_citas.clasificacion = 1  then 6
            when oportunidad_citas.tipo_consulta = '890235' then 7
            when oportunidad_citas.tipo_consulta = '890263' then 8
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

