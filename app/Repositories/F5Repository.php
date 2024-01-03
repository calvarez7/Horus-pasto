<?php

namespace App\Repositories;

use App\Interfaces\FiasInterface;
use App\Modelos\Ac;
use App\Modelos\CanastaF4Paciente;
use App\Modelos\CanastaF5Paciente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class F5Repository implements FiasInterface{

    /**
     * Genera la consulta para realizar el fias 4
     * @param Object $data
     * @return Collection
     * @author David Peláez
     */
    public function consultar($data)
    {
        $fechaInicio = $data['anio'].'-'.$data['mes'].'-01';
        $fechaFin = date("Y-m-t", strtotime($fechaInicio));
        //        $acs = Ac::select([
//            'acs.numero_documento',
//            'acs.Fecha_Consulta',
//            'c.Codigo as cup',
//            'ci.Codigo_CIE10 as cie10',
//            'acs.Codigo_Relacionado1'
//        ])->join('afs as af','af.id','acs.Af_id')
//            ->join('paq_rips as pr','pr.id','paq_id')
//            ->join('cups as c','c.id','acs.Codigo_Consulta')
//            ->join('cie10s as ci','ci.id','acs.Diagnostico_Principal')
//            ->whereBetween(DB::raw("CONVERT(date,SUBSTRING(acs.Fecha_Consulta,1,10),103)"),[$fechaInicio,$fechaFin])
//            ->where('pr.estado_id',7)
//            ->get();



        $lineasBases = CanastaF5Paciente::select([
            'd.codigo_dane as DEPARTAMENTO',
            'm.codigo_dane as MUNICIPIO',
            'p.Tipo_Doc as TIPO DE DOCUMENTO',
            'p.Num_Doc as NUMERO DE DOCUMENTO',
            'p.Sexo as SEXO',
            'p.Edad_Cumplida as EDAD',
            DB::raw("(CASE WHEN convert(varchar(7), canasta_f5_pacientes.fecha_novedad, 126) = '".substr($fechaFin,0,7)."' THEN 4 ELSE 3 END ) as [TIPO DE CASO]"),
            DB::raw("(CASE p.Estado_Afiliado WHEN 1 THEN 1 WHEN 27 THEN 2 END ) as NOVEDAD"),
            DB::raw("CONVERT(VARCHAR,canasta_f5_pacientes.fecha_diagnostico,103) as [FECHA DIAGNOSTICO]"),
            'canasta_f5_pacientes.cie10_neoplasia as CIE10 DE LA NEOPLASIA',
            DB::raw("CONVERT(VARCHAR,canasta_f5_pacientes.fecha_inicio_tratamiento,103) as [FECHA DE INICIO DE TRATAMIENTO]"),
            DB::raw("CONVERT(VARCHAR,canasta_f5_pacientes.fecha_ultima_atencion,103) as [FECHA DE ULTIMA ATENCIÓN]"),
            DB::raw("CONVERT(VARCHAR,canasta_f5_pacientes.fecha_ultimo_tratamiento,103) as [FECHA DE INGRESO AL PROGRAMA]"),
            'canasta_f5_pacientes.tratamiento_actual as TRATAMIENTO ACTUAL',
            DB::raw("CONVERT(VARCHAR,canasta_f5_pacientes.fecha_remision_diagnostico_presuntivo,103) as [FECHA DE REMISION CON DIAGNOSTICO PRESUNTIVO]"),
        ])
            ->join('pacientes as p','p.id','canasta_f5_pacientes.paciente_id')
            ->join('sedeproveedores as s','s.id','p.IPS')
            ->join('municipios as m','m.id','s.Municipio_id')
            ->join('departamentos as d','d.id','m.Departamento_id')
            ->get();

        return $lineasBases;
    }
}
