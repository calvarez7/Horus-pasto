<?php

namespace App\Repositories;

use App\Interfaces\FiasInterface;
use App\Modelos\Ac;
use App\Modelos\CanastaF4Paciente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class F4Repository implements FiasInterface{

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
//
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
//        foreach ($acs as $ac) {
//            $fechaConsulta = explode('/',$ac->Fecha_Consulta);
//            $paciente = Paciente::where('Num_Doc',$ac->numero_documento)->first();
//            $canastaF4 = CanastaF4Paciente::where('paciente_id',$paciente->id)->first();
//            if($canastaF4){
//                $canastaF4Cups = CanastaFiasCup::where('archivo_rips','AC')->where('fias','F4')->where('cup',$ac->cup)->first();
//                $canastaF4Cie10 = CanastaFiasCie10::where('archivo_rips','AC')->where('fias','F4')->where('cie10',$ac->cie10)->first();
//                if($canastaF4Cups && $canastaF4Cie10){
//                    $canastaF4->fecha_ultima_atencion = $fechaConsulta[2].'-'.$fechaConsulta[1].'-'.$fechaConsulta[0];
//                    $canastaF4->save();
//                }
//            }else{
//                $canastaF4Cups = CanastaFiasCup::where('archivo_rips','AC')->where('fias','F4')->where('cup',$ac->cup)->first();
//                $canastaF4Cie10 = CanastaFiasCie10::where('archivo_rips','AC')->where('fias','F4')->where('cie10',$ac->cie10)->first();
//                if($canastaF4Cups && $canastaF4Cie10){
//                    $nuevoRegistro = 0;
//                    $registrofinal = null;
//                    $nivel = 5;
//                    switch ($canastaF4Cie10->grupo){
//                        case 'DISLIPIDEMIA':
//                            $colesterolTotal = RegistroLaboratorios::where('identificacion',$ac->numero_documento)
//                                ->where(DB::raw("CAST(resultado as int)"),">",200)
//                                ->where('codigo_cup','903818')
//                                ->whereBetween(DB::raw("CONVERT(date,SUBSTRING(fecha_registro,1,10),103)"),[$fechaInicio,$fechaFin])
//                                ->first();
//                            $triglicerido = RegistroLaboratorios::where('identificacion',$ac->numero_documento)
//                                ->where(DB::raw("CAST(resultado as int)"),">",150)
//                                ->where('codigo_cup','903868')
//                                ->whereBetween(DB::raw("CONVERT(date,SUBSTRING(fecha_registro,1,10),103)"),[$fechaInicio,$fechaFin])
//                                ->first();
//                            $ldl = RegistroLaboratorios::where('identificacion',$ac->numero_documento)
//                                ->where(DB::raw("CAST(resultado as int)"),">",100)
//                                ->where('codigo_cup','903816')
//                                ->whereBetween(DB::raw("CONVERT(date,SUBSTRING(fecha_registro,1,10),103)"),[$fechaInicio,$fechaFin])
//                                ->first();
//                            if($colesterolTotal || $triglicerido || $ldl){
//                                if($nivel > 3){
//                                    $nivel = 3;
//                                    $nuevoRegistro = 1;
//                                    if($colesterolTotal){
//                                        $registrofinal = $colesterolTotal;
//                                    }elseif ($triglicerido){
//                                        $registrofinal = $triglicerido;
//                                    }elseif ($ldl){
//                                        $registrofinal = $ldl;
//                                    }
//                                }
//                            }
//                            break;
//                        case 'OBESIDAD':
//                            $obecidad = null;
//                            if($obecidad){
//                                if($nivel > 4){
//                                    $nivel = 3;
//                                    $nuevoRegistro = 1;
//                                    $registrofinal = $obecidad;
//                                }
//                            }
//                            break;
//                        case 'DM':
//                            $hemoglobinaGlicosidada = RegistroLaboratorios::where('identificacion',$ac->numero_documento)
//                                ->where(DB::raw("CAST(resultado as decimal)"),">",7.0)
//                                ->where('codigo_cup','903426')
//                                ->whereBetween(DB::raw("CONVERT(date,SUBSTRING(fecha_registro,1,10),103)"),[$fechaInicio,$fechaFin])
//                                ->first();
//                            if($hemoglobinaGlicosidada){
//                                if($nivel > 1){
//                                    $nivel = 1;
//                                    $nuevoRegistro = 1;
//                                    $registrofinal = $hemoglobinaGlicosidada;
//                                }
//                            }
//                            break;
//                        case 'HTA':
//                            $hta = null;
//                            if($nivel > 2){
//                                $nivel = 2;
//                                $nuevoRegistro = 1;
//                                $registrofinal = $hta;
//                            }
//                            break;
//                    }
//
//                    if($nuevoRegistro){
//                        $fechaNovedad = date('Y-m-d',strtotime($registrofinal['fecha_registro']));
//                        $fechaConsulta = date('Y-m-d',strtotime($ac['Fecha_Consulta']));
//                        CanastaF4Paciente::create([
//                            'paciente_id' => $paciente->id,
//                            'fecha_novedad' => $fechaNovedad,
//                            'fecha_diagnostico' => $fechaConsulta,
//                            'fecha_ingreso_programa' => $fechaConsulta,
//                            'fecha_inicio_tratamiento' => $fechaConsulta,
//                            'fecha_ultima_atencion' => $fechaConsulta,
//                            'cie10_primario' =>  $ac['cie10'],
//                            'cie10_secundario' =>  $ac['Codigo_Relacionado1'],
//                        ]);
//
//                    }
//                }
//            }
//        }

        $lineasBases = CanastaF4Paciente::select([
            'd.codigo_dane as DEPARTAMENTO',
            'm.codigo_dane as MUNICIPIO',
            'p.Tipo_Doc as TIPO DE DOCUMENTO',
            'p.Num_Doc as NUMERO DE DOCUMENTO',
            'p.Sexo as SEXO',
            'p.Edad_Cumplida as EDAD',
            DB::raw("(CASE WHEN convert(varchar(7), canasta_f4_pacientes.fecha_novedad, 126) = '".substr($fechaFin,0,7)."' THEN 4 ELSE 3 END ) as [TIPO DE CASO]"),
            DB::raw("(CASE p.Estado_Afiliado WHEN 1 THEN 1 WHEN 27 THEN 2 END ) as NOVEDAD"),
            DB::raw("CONVERT(VARCHAR,canasta_f4_pacientes.fecha_diagnostico,103) as [FECHA DIAGNOSTICO]"),
            DB::raw("CONVERT(VARCHAR,canasta_f4_pacientes.fecha_ingreso_programa,103) as [FECHA DE INGRESO AL PROGRAMA]"),
            DB::raw("CONVERT(VARCHAR,canasta_f4_pacientes.fecha_inicio_tratamiento,103) as [FECHA DE INICIO DE TRATAMIENTO]"),
            DB::raw("CONVERT(VARCHAR,canasta_f4_pacientes.fecha_ultima_atencion,103) as [FECHA DE ULTIMA ATENCIÓN]"),
            'canasta_f4_pacientes.cie10_primario as CIE 10 PRINCIPAL',
            'canasta_f4_pacientes.cie10_secundario as CIE 10 SECUNDARIO'
        ])
            ->join('pacientes as p','p.id','canasta_f4_pacientes.paciente_id')
            ->join('sedeproveedores as s','s.id','p.IPS')
            ->join('municipios as m','m.id','s.Municipio_id')
            ->join('departamentos as d','d.id','m.Departamento_id')
            ->get();

        return $lineasBases;
    }
}
