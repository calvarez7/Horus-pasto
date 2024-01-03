<?php

namespace App\Repositories;

use App\Interfaces\FiasInterface;
use App\Modelos\Ah;
use App\Modelos\Ahripsfias;
use App\Modelos\Au;
use App\Modelos\Auripsfias;
use Illuminate\Support\Facades\DB;

class F3Repository implements FiasInterface{

    /**
     * Genera la consulta para realizar el fias
     * @param Object $data
     * @return Collection
     * @author jonathan cobaleda
     */
    public function consultar($data)
    {

        $select_au = [
            'AU_RIPS_FIAS.dane_dpto',
            'AU_RIPS_FIAS.dane_mpio', 
            'AU_RIPS_FIAS.tipo_doc', 
            'AU_RIPS_FIAS.num_doc', 
            'AU_RIPS_FIAS.Causabasica_Muerte as diagnostico',
            DB::raw("case  when AU_RIPS_FIAS.edad < 6 then 0
            when AU_RIPS_FIAS.edad >=6 and AU_RIPS_FIAS.edad < 12 then 1
            when AU_RIPS_FIAS.edad >= 12 and AU_RIPS_FIAS.edad < 18 then 2
            when AU_RIPS_FIAS.edad >=18 and AU_RIPS_FIAS.edad < 29 then 3
            when AU_RIPS_FIAS.edad >=29 and AU_RIPS_FIAS.edad < 35 then 4
            when AU_RIPS_FIAS.edad >= 35 and AU_RIPS_FIAS.edad < 40 then 5
            when AU_RIPS_FIAS.edad >= 40 and AU_RIPS_FIAS.edad < 45 then 6
            when AU_RIPS_FIAS.edad >= 45 and AU_RIPS_FIAS.edad < 50 then 7
            when AU_RIPS_FIAS.edad >= 50 and AU_RIPS_FIAS.edad < 55 then 8
            when AU_RIPS_FIAS.edad >= 55 and AU_RIPS_FIAS.edad < 60 then 9
            when AU_RIPS_FIAS.edad >= 60 and AU_RIPS_FIAS.edad < 65 then 10
            when AU_RIPS_FIAS.edad >= 65 and AU_RIPS_FIAS.edad < 70 then 11
            else 12
            end ciclovida"),
            DB::raw("case pacientes.Sexo when 'M' then 0 else 1 end sexo")
        ];

        $consulta_au = Auripsfias::select($select_au)
            ->join('pacientes', 'AU_RIPS_FIAS.num_doc', '=', 'pacientes.Num_Doc')
            ->where('AU_RIPS_FIAS.diagnostico_principal_salida', 'not like', 'z%')
            ->where('AU_RIPS_FIAS.Estado_Salida', 2)
            ->whereFecha($data['mes'], $data['anio'])
            ->get();

        $select_ah = [
            'AH_RIPS_FIAS.dane_dpto',
            'AH_RIPS_FIAS.dane_mpio', 
            'AH_RIPS_FIAS.tipo_doc',
            'AH_RIPS_FIAS.num_doc', 
            'AH_RIPS_FIAS.Diagnosticocausa_Muerte as diagnostico',
            DB::raw("case  when AH_RIPS_FIAS.edad < 6 then 0
            when AH_RIPS_FIAS.edad >=6 and AH_RIPS_FIAS.edad < 12 then 1
            when AH_RIPS_FIAS.edad >= 12 and AH_RIPS_FIAS.edad < 18 then 2
            when AH_RIPS_FIAS.edad >=18 and AH_RIPS_FIAS.edad < 29 then 3
            when AH_RIPS_FIAS.edad >=29 and AH_RIPS_FIAS.edad < 35 then 4
            when AH_RIPS_FIAS.edad >= 35 and AH_RIPS_FIAS.edad < 40 then 5
            when AH_RIPS_FIAS.edad >= 40 and AH_RIPS_FIAS.edad < 45 then 6
            when AH_RIPS_FIAS.edad >= 45 and AH_RIPS_FIAS.edad < 50 then 7
            when AH_RIPS_FIAS.edad >= 50 and AH_RIPS_FIAS.edad < 55 then 8
            when AH_RIPS_FIAS.edad >= 55 and AH_RIPS_FIAS.edad < 60 then 9
            when AH_RIPS_FIAS.edad >= 60 and AH_RIPS_FIAS.edad < 65 then 10
            when AH_RIPS_FIAS.edad >= 65 and AH_RIPS_FIAS.edad < 70 then 11
            else 12
            end ciclovida"),
            DB::raw("case pacientes.Sexo when 'M' then 0 else 1 end sexo")
        ];
    
        $consulta_ah = Ahripsfias::select($select_ah)
            ->join('pacientes', 'AH_RIPS_FIAS.num_doc', '=', 'pacientes.Num_Doc')
            ->where('AH_RIPS_FIAS.diagnostico_principal_egreso', 'not like', 'z%')
            ->where('AH_RIPS_FIAS.Estado_Salida', 2)
            ->whereFecha($data['mes'], $data['anio'])
            ->get();

        return $consulta_ah->merge($consulta_au);

    }

}
