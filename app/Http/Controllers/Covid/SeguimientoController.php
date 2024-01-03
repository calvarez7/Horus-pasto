<?php

namespace App\Http\Controllers\Covid;

use App\Modelos\DetalleAtencionContingencia;
use App\Modelos\SeguimientoAtencionContingencia;
use App\Modelos\Seguimientocovid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modelos\Registrocovi;
use App\Modelos\Seguimientocovi;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class SeguimientoController extends Controller
{

    public  function listar(){

        $seguimientos = SeguimientoAtencionContingencia::select([
            'seguimiento_atencion_contingencia.*',
            'e.Nombre as estado',
            DB::raw("CONCAT(u.name,' ',u.apellido) as user_crea"),
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'p.Sexo',
            's.Nombre as IPS',
            'p.Num_Doc'
        ])->join('pacientes as p','p.id','seguimiento_atencion_contingencia.paciente_id')
            ->join('estados as e', 'e.id', 'seguimiento_atencion_contingencia.estado_id')
            ->join('users as u', 'u.id', 'seguimiento_atencion_contingencia.user_crea_id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->where('seguimiento_atencion_contingencia.estado_id', 18)
            ->get();

        return response()->json($seguimientos);
    }
    public  function showseguimientos($id){
        $verseguimiento = Seguimientocovi::select(['seguimientocovis.*', 'e.Nombre as estado',
            DB::raw("CONCAT(u.name,' ',u.apellido) as user_crea"),
        ])
        ->join('estados as e', 'seguimientocovis.estado_id', 'e.id')
        ->join('users as u', 'seguimientocovis.user_responsable_seguimiento', 'u.id')
        ->where('detalle_atencion_contingencia_id', $id)
        ->get();

        return response()->json($verseguimiento);
    }
    public  function verseguimientos($id){
        $verseguimiento = Seguimientocovi::select(['seguimientocovis.*', 'e.Nombre as estado',
            DB::raw("CONCAT(u.name,' ',u.apellido) as user_crea"),
        ])
        ->join('estados as e', 'seguimientocovis.estado_id', 'e.id')
        ->join('users as u', 'seguimientocovis.user_responsable_seguimiento', 'u.id')
        ->join('detalle_atencion_contingencia as da', 'seguimientocovis.detalle_atencion_contingencia_id', 'da.id')
        ->where('da.seguimiento_atencion_contingencia_id', $id)
        ->get();

        return response()->json($verseguimiento);
    }
    public  function seguimiento(){

        $seguimientos = DetalleAtencionContingencia::select([
                'detalle_atencion_contingencia.*',
                'e.Nombre as estado',
                'p.Num_Doc',
                'sd.Nombre as IPS',
                DB::raw("CONCAT(u.name,' ',u.apellido) as user_crea"),
                DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            ])
            ->join('seguimiento_atencion_contingencia as s','detalle_atencion_contingencia.seguimiento_atencion_contingencia_id','s.id')
            ->join('estados as e', 's.estado_id', 'e.id')
            ->join('users as u', 'u.id', 's.user_crea_id')
            ->join('pacientes as p','p.id','s.paciente_id')
            ->join('sedeproveedores as sd','p.IPS','sd.id')
            ->where('detalle_atencion_contingencia.estado_id', 43)
            ->get();

        return response()->json($seguimientos);
    }
    public  function pacienteAlta(){

        $seguimientos = SeguimientoAtencionContingencia::select([
            'seguimiento_atencion_contingencia.*',
            'e.Nombre as estado',
            'sd.Nombre as IPS',
            'p.Num_Doc',
            DB::raw("CONCAT(u.name,' ',u.apellido) as user_crea"),
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
        ])->join('pacientes as p','p.id','seguimiento_atencion_contingencia.paciente_id')
            ->join('estados as e', 'e.id', 'seguimiento_atencion_contingencia.estado_id')
            ->join('users as u', 'u.id', 'seguimiento_atencion_contingencia.user_crea_id')
            ->join('sedeproveedores as sd','p.IPS','sd.id')
            ->where('seguimiento_atencion_contingencia.estado_id', 44)
            ->get();

        return response()->json($seguimientos);
    }

    public function create(Request $request){
        $mensaje = 'El paciente ya cuenta con un proceso';
        $status = 402;
        $getpaciente = SeguimientoAtencionContingencia::where('paciente_id', $request->paciente_id)->first();
        if (!$getpaciente || ($getpaciente->estado_id != 18 && $getpaciente->estado_id != 43 )) {
            $seguimiento = new SeguimientoAtencionContingencia;
            $seguimiento->user_crea_id = auth()->user()->id;
            $seguimiento->paciente_id = $request->paciente_id;
            $seguimiento->municipio_ocurrencia_id = $request->municipio_ocurrencia_id;
            $seguimiento->estado_id = 18;
            $seguimiento->nacionalidad = $request->nacionalidad;
            $seguimiento->afirmacion_viaje_internacional = $request->afirmacion_viaje_internacional;
            $seguimiento->descripcion_viaje_internacional = $request->descripcion_viaje_internacional;
            $seguimiento->afirmacion_viaje_nacional = $request->afirmacion_viaje_nacional;
            $seguimiento->descripcion_viaje_nacional = $request->descripcion_viaje_nacional;
            $seguimiento->tipo_vivienda = $request->tipo_vivienda;
            $seguimiento->habitacion_individual = $request->habitacion_individual;
            $seguimiento->clasificacion_caso = $request->clasificacion_caso;
            $seguimiento->observaciones = $request->observaciones;
            $seguimiento->tipo_contatos = json_encode($request->tipo_contatos);
            $seguimiento->docente_clase = $request->docenteClase;
            $seguimiento->save();
            $mensaje = 'Registro creado con exito!';
            $status = 200;
        }

        return response()->json([
            'message' => $mensaje
        ], $status);

    }

    public function guardarSeguimiento(Request $request)
    {
            $admision = new DetalleAtencionContingencia;
            $admision->seguimiento_atencion_contingencia_id = $request->seguimiento_atencion_contingencia_id;
            $admision->clasificacion_caso = $request->clasificacion_caso;
            $admision->caso = $request->caso;
            $admision->toma_muestra = $request->toma_muestra;
            $admision->fecha_realizacion = $request->fecha_realizacion;
            $admision->fecha_resultado = $request->fecha_resultado;
            $admision->resultado = $request->resultado;
            $admision->quien_toma_muestra = $request->quien_toma_muestra;
            $admision->quien_procesa_muestra = $request->quien_procesa_muestra;
            $admision->resultado_muestra = $request->resultado_muestra;
            $admision->contacto_estrecho = $request->contacto_estrecho;
            $admision->vacunacion_estacional_vigente = $request->vacunacion_estacional_vigente;
            $admision->antibioticos_ultima_semana = $request->antibioticos_ultima_semana;
            $admision->fecha_ingreso = $request->fecha_ingreso;
            $admision->nombre_institucion = $request->nombre_institucion;
            $admision->tipo_hospitalizacion = $request->tipo_hospitalizacion;
            $admision->fecha_salida = $request->fecha_salida;
            $admision->estado_alta = $request->estado_alta;
            $admision->gestante = $request->gestante;
            $admision->poblacion_riesgo = $request->poblacion_riesgo;
            $admision->tratamiento_biologico = $request->tratamiento_biologico;
            $admision->quimioterapia = $request->quimioterapia;
            $admision->fecha_inicio_sintomas = $request->fecha_inicio_sintomas;
            $admision->requiere_seguimiento = $request->requiere_seguimiento;
            $admision->tipoMuestra = $request->tipoMuestra;
            $admision->requiere_hospitalizacion = $request->requiere_hospitalizacion;
            if ($request->requiere_seguimiento == 0) {
                $admision->estado_id = 44;
                $getRegistrocovi =  SeguimientoAtencionContingencia::where('id', $request->seguimiento_atencion_contingencia_id)->first();
                $getRegistrocovi->update(['estado_id' => 44]);
            }elseif ($request->requiere_seguimiento =! 0) {
                $admision->estado_id = 43;
                $getRegistrocovi =  SeguimientoAtencionContingencia::where('id', $request->seguimiento_atencion_contingencia_id)->first();
                $getRegistrocovi->update(['estado_id' => 43]);
            // $getRegistrocovi->updated('estado_id', 42);
        }
        $admision->save();
        return response()->json([
            'message' => 'Detalle Creado'
        ], 200);
    }

    public function control(Request $request)
    {
        $control = new Seguimientocovi;
        $control->user_responsable_seguimiento = auth()->user()->id;
        $control->detalle_atencion_contingencia_id = $request->detalle_atencion_contingencia_id;
        $control->contacto_fallido = $request->contacto_fallido;
        $control->clasificacion_caso = $request->clasificacion_caso;
        $control->caso = $request->caso;
        $control->toma_muestra = $request->toma_muestra;
        $control->fecha_realizacion = $request->fecha_realizacion;
        $control->fecha_resultado = $request->fecha_resultado;
        $control->resultado = $request->resultado;
        $control->fecha_inicio_sintomas = $request->fecha_inicio_sintomas;
        $control->quien_toma_muestra = $request->quien_toma_muestra;
        $control->quien_procesa_muestra = $request->quien_procesa_muestra;
        $control->resultado_muestra = $request->resultado_muestra;
        $control->requiere_hospitalizacion = $request->requiere_hospitalizacion;
        $control->asintomatico = $request->asintomatico;
        $control->fiebre_mayor38 = $request->fiebre_mayor38;
        $control->tos = $request->tos;
        $control->odinofagia = $request->odinofagia;
        $control->alteracion_olfato = $request->alteracion_olfato;
        $control->alteracion_gusto = $request->alteracion_gusto;
        $control->anorexia = $request->anorexia;
        $control->cefalea = $request->cefalea;
        $control->fatiga_adinamia = $request->fatiga_adinamia;
        $control->dificultad_espiratoria = $request->dificultad_espiratoria;
        $control->somnolencia = $request->somnolencia;
        $control->expectoracion = $request->expectoracion;
        $control->vomito_diarrea_intratable = $request->vomito_diarrea_intratable;
        $control->paciente_adomicilio = $request->paciente_adomicilio;
        $control->frecuencia_respiratoria = $request->frecuencia_respiratoria;
        $control->saturacion_oxigeno = $request->saturacion_oxigeno;
        $control->presion_sistolica = $request->presion_sistolica;
        $control->pulso_minuto = $request->pulso_minuto;
        $control->temperatura = $request->temperatura;
        $control->requiere_oxigenoterapia = $request->requiere_oxigenoterapia;
        $control->oxigeno_indicado = $request->oxigeno_indicado;
        $control->destino_paciente = $request->destino_paciente;
        $control->frecuencia = $request->frecuencia;
        $control->tipoMuestra = $request->tipoMuestra;
        $control->contacto_fallido = $request->contacto_fallido;
        $control->fecha_ingreso = $request->fecha_ingreso;
        $control->tipo_hospitalizacion = $request->tipo_hospitalizacion;
        $control->fecha_salida = $request->fecha_salida;
        $control->estado_alta = $request->estado_alta;
        $control->nombre_institucion = $request->nombre_institucion;
        if ($request->destino_paciente == 'Seguimiento' || $request->destino_paciente == 'Estrategia de Oximetrías') {
            $control->estado_id = 43;

        }elseif ($request->destino_paciente == 'Alta del Modelo de Atención Covid') {
            $control->estado_id = 44;
            $detalle = DetalleAtencionContingencia::find($request->detalle_atencion_contingencia_id);
            $idSegumiento = $detalle->seguimiento_atencion_contingencia_id;
            $detalle->update(['estado_id'=> 44]);
            $seguimiento = SeguimientoAtencionContingencia::find($idSegumiento)->update(['estado_id'=> 44]);

        }
        $control->save();
        return response()->json([
            'message' => 'Seguimiento creado con exito!'
        ], 200);
    }

    public function reporte(Request $request)
    {
        $appointments = Collect(DB::select("exec dbo.ReportCovi ?,?", [$request->startDate,$request->finishDate]));
         $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function reporteRutacovi(Request $request)
    {
        $appointments = Collect(DB::select("exec dbo.ReportCovi ?,?", [$request->startDate,$request->finishDate]));
         $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function countCovi()
    {
        $pendientes = SeguimientoAtencionContingencia::where('estado_id', 18)->count();
        return response()->json(['pendientes' => $pendientes], 200);
    }
}
