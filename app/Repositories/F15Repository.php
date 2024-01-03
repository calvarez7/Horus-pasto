<?php

namespace App\Repositories;

use App\Modelos\Ah;
use App\Modelos\Au;
use App\Interfaces\FiasInterface;
use App\Modelos\NovedadesPaciente;
use Illuminate\Support\Facades\DB;

class F15Repository implements FiasInterface{

    /**
     * Genera la consulta para realizar el fias
     * @param Object $data
     * @return Collection
     * @author jonathan cobaleda
     */
    public function consultar($data){

        $select = [
            DB::raw("case p.Sexo when 'M' then 1 else 0 end sexo"),
            'p.Edad_Cumplida',
            'p.Estado_Afiliado',
            'novedades_pacientes.fechaInicio_portabilidad',
            'novedades_pacientes.fechaFinal_portabilidad',
            DB::raw("DATEDIFF(week,novedades_pacientes.fechaInicio_portabilidad,novedades_pacientes.fechaFinal_portabilidad) as N_semanas"),
            'd.codigo_Dane as departamento_origen',
            DB::raw("8 as entidad_origen"),
            'novedades_pacientes.departamentoReceptor as departamento_receptor',
            'novedades_pacientes.ipsdestino_portabilidad as entidad_receptora',
            'novedades_pacientes.causa as motivo_solicitud',
            'novedades_pacientes.otra_causa as otra_cual'
        ];

        $novedades_pacientes = NovedadesPaciente::select($select)
            ->join('pacientes as p', 'novedades_pacientes.paciente_id', '=','p.id')
            ->join('sedeproveedores as s ', 's.id','=','p.IPS')
            ->join('municipios as m', 'm.id', '=','s.Municipio_id')
            ->join('departamentos as d', 'd.id', '=','m.Departamento_id')
            ->where('novedades_pacientes.Tipo_id',40)
            ->get();

        return $novedades_pacientes;

    }

}
