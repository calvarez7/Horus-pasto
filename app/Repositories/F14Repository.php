<?php

namespace App\Repositories;

use App\Modelos\Ac;
use App\Interfaces\FiasInterface;

class F14Repository implements FiasInterface{

    /**
     * Genera la consulta para realizar el fias
     * @param Object $data
     * @return Collection
     * @author jonathan cobaleda
     */
    public function consultar($data){
        $cups = [176,71,8829,8830,2266,2012,2344,2013,2014,2358,118,72,8643,150,2345,73,8831,8716,8717,8718,8719,8720,8709,8721,
            85,88,96,93,2679,97,2682,107,2686,123,74,8710,2697,222,104,98,226,108,206,111,112,124,125,113,134,137,141,142,2698,
            147,207,185,151,152,153,155,156,157,119,245,182,2691,187,2694,2708,190,191,246,196,197,203,2699,2700,2701,214,215,
            219,223,230,231,2710,2711,236,237,247,248,2712,242,2015,177,249,2016,2017,2267,2018,2019,2020,2021,2022,2023,250,
            2268,159,2024,160,8711,8722,8712,8723,8724,8725,8713,8726,86,89,90,94,2680,99,2690,103,2687,126,75,8714,2684,251,
            105,100,227,109,208,114,115,127,128,132,135,138,143,144,2713,148,209,69,162,163,252,253,165,120,8696,183,
            2692,188,2695,2714,192,193,254,198,199,204,2702,2703,2704,212,213,220,224,232,233,2715,2716,238,239,255,256,
            2717,243,166,167,2025
        ];

        $select = [
            'c.Codigo',
            'p.id as paciente',
            'm.codigo_Dane as codigo_dane_municipio',
            'd.codigo_Dane as codigo_dane_departamento',
        ];

        $acs = Ac::select($select)
            ->confirmado()
            ->join('pacientes as p', 'acs.numero_documento', '=', 'p.Num_Doc')
            ->join('sedeproveedores as s ', 's.id','=','p.IPS')
            ->join('municipios as m', 'm.id', '=','s.Municipio_id')
            ->join('departamentos as d', 'd.id', '=','m.Departamento_id')
            ->join('cups as c', 'c.id', '=','acs.Codigo_Consulta')
            ->whereIn('acs.Codigo_Consulta', $cups)
            ->whereFecha($data->mes, $data->anio)
            ->get();

        /** Agrupar por de paciente*/
        $agrupado_por_pacientes = array();
        foreach ($acs as $pacientes){
            $consulta = $acs->where('codigo_dane_municipio',$pacientes->codigo_dane_municipio)->where('Codigo',$pacientes->Codigo)->where('codigo_dane_departamento',$pacientes->codigo_dane_departamento);
            $consulta_cups = $acs->where('codigo_dane_municipio',$pacientes->codigo_dane_municipio)->where('codigo_dane_departamento',$pacientes->codigo_dane_departamento);
            array_push($agrupado_por_pacientes,[
                'codigo' => $pacientes->Codigo,
                'N_Personas' => count(array_unique($consulta->pluck('paciente')->toArray())),
                'N_Consultas' => count(array_unique($consulta_cups->pluck('Codigo')->toArray())),
                'Departamento' => $pacientes->codigo_dane_departamento,
                'Municipio' => $pacientes->codigo_dane_municipio
            ]);
        }

        return $agrupado_por_pacientes;

    }

}
