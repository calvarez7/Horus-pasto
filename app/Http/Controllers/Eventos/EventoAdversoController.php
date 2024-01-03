<?php

namespace App\Http\Controllers\Eventos;

use App\User;
use Illuminate\Http\Request;
use App\Modelos\AdjuntoEvento;
use Illuminate\Support\Carbon;
use App\Modelos\Gestion_evento;
use App\Modelos\Sedeproveedore;
use App\Modelos\Analisis_evento;
use App\Modelos\Eventos_adverso;
use App\Modelos\Acciones_mejoras;
use App\Modelos\Asignado_eventos;
use Illuminate\Support\Facades\DB;
use App\Modelos\Acciones_inseguras;
use App\Modelos\NotificacionEvento;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class EventoAdversoController extends Controller
{
    public function create(Request $request){

        $validate = Validator::make($request->all(), [
            'nombre_del_suceso' => 'required',
            'sede_ocurrencia' => 'required',
            'sede_reportante' => 'required',
            'fecha_ocurrencia' => 'required',
            'descripcion_hechos' => 'required',
            'accion_tomada' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $sede = Sedeproveedore::find($request->sede_ocurrencia);
        $tipoSumi = strpos($sede->Nombre, 'SUMIMEDICAL');
        $tipoClinica = strpos($sede->Nombre, 'CLINICA VICTORIANA');
        if($tipoSumi !== false || $tipoClinica !== false){
            $red = 'Interna';
        }else{
            $red = 'Externa';
        }

        $evento_adverso = new Eventos_adverso;
        $evento_adverso->evento_id = $request->nombre_del_suceso;
        $evento_adverso->detallearticulo_id = $request->medicamento_evento;
        $evento_adverso->user_id = auth()->id();
        $evento_adverso->fecha_venci_medicamento = $request->fecha_vence_medicamento;
        $evento_adverso->lote_medicamento = $request->lote_medicamento;
        $evento_adverso->invima_medicamento = $request->invima_medicamento;
        $evento_adverso->sede_ocurrencia = $request->sede_ocurrencia;
        $evento_adverso->sede_reportante = $request->sede_reportante;
        $evento_adverso->clasificacion = $request->clasificacion;
        $evento_adverso->fecha_ocurrencia = $request->fecha_ocurrencia;
        $evento_adverso->relacion = $request->relacion;
        $evento_adverso->dispositivo = $request->dispositivo;
        $evento_adverso->referencia = $request->referencia_dispositivo;
        $evento_adverso->modelo = $request->modelo_dispositivo;
        $evento_adverso->serial = $request->serial_dispositivo;
        $evento_adverso->invima_dispositivo = $request->invima_dispositivo;
        $evento_adverso->descripcion_hechos = $request->descripcion_hechos;
        $evento_adverso->accion_tomada = $request->accion_tomada;
        $evento_adverso->tipo_id = 12;
        $evento_adverso->estado_id = 18;
        $evento_adverso->paciente_id = $request->paciente;
        $evento_adverso->clasificacionevento_id = $request->clasificacion_area;
        $evento_adverso->tipoevento_id = $request->tipo_evento;
        $evento_adverso->clasificacion_invima = $request->clasificacion_invima;
        $evento_adverso->dosis_medicamento = $request->dosis_medicamento;
        $evento_adverso->medida_medicamento = $request->medida_medicamento;
        $evento_adverso->via_medicamento = $request->via_medicamento;
        $evento_adverso->frecuencia_medicamento = $request->frecuencia_medicamento;
        $evento_adverso->infusion_medicamento = $request->infusion_medicamento;
        $evento_adverso->nombre_equipo = $request->nombre_equipo;
        $evento_adverso->marca_equipo = $request->marca_equipo;
        $evento_adverso->modelo_equipo = $request->modelo_equipo;
        $evento_adverso->serie_equipo = $request->serie_equipo;
        $evento_adverso->placa_equipo = $request->placa_equipo;
        $evento_adverso->otro_evento = $request->otro_evento;
        $evento_adverso->red = $red;
        $evento_adverso->servicio_ocurrencia = $request->servicio_ocurrencia;
        $evento_adverso->servicio_reportante = $request->servicio_reportante;
        $evento_adverso->lote_dispositivo = $request->lote_dispositivo;
        $evento_adverso->profesional = $request->profesional;
        $evento_adverso->save();

        $users = [];

        $user_solo = NotificacionEvento::select(['users.name', 'users.email'])
        ->join('users', 'notificacion_eventos.user_id', 'users.id')
        ->where('notificacion_eventos.estado_id', 1)
        ->where('sedeproveedore_id', $sede->id)
        ->where('tipo', 'SOLO')
        ->distinct()
        ->get();

        foreach ($user_solo as $solo) {
            array_push($users, $solo);
        }

        $user_todas = NotificacionEvento::select(['users.name', 'users.email'])
        ->join('users', 'notificacion_eventos.user_id', 'users.id')
        ->where('notificacion_eventos.estado_id', 1)
        ->where('tipo', 'TODAS')
        ->distinct()
        ->get();

        foreach ($user_todas as $todo) {
            array_push($users, $todo);
        }

        $count_todasmenos = NotificacionEvento::where('estado_id', 1)
        ->where('sedeproveedore_id', $sede->id)
        ->where('tipo', 'TODAS MENOS')
        ->count();

        if($count_todasmenos <= 0){
            $user_todas_menos = NotificacionEvento::select(['users.name', 'users.email'])
            ->join('users', 'notificacion_eventos.user_id', 'users.id')
            ->where('notificacion_eventos.estado_id', 1)
            ->where('sedeproveedore_id', '!=', $sede->id)
            ->where('tipo', 'TODAS MENOS')
            ->distinct()
            ->get();

            foreach ($user_todas_menos as $todo_menos) {
                array_push($users, $todo_menos);
            }
        }

        if(count($users) > 0){
            $email = Mail::send('email_notificacion_eventos',
            ['data' => $evento_adverso, 'type' => 'reporte'], function ($m) use ($users) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación Eventos');
                }
            });
        }

        return response()->json([
            'eventoadverso_id' => $evento_adverso->id,
            'message' => 'Evento generado con exito!',
        ], 201);
    }

    public function getpendientes(Request $request){

        $get_eventos = Eventos_adverso::select('eventos_adversos.id', 'e.nombre as nombreEvento', 'e.id as eventoId', 'p.Num_Doc',
        DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ',p.Primer_Ape,' ',p.Segundo_Ape) as nombre_completo"),
        'eventos_adversos.clasificacion', 'eventos_adversos.created_at', 'eventos_adversos.paciente_id',
        'eventos_adversos.relacion', 'p.Edad_Cumplida', 'p.sexo', 'sed.Nombre as sede_ocurrencia', 'sed.id as sedeocurrencia_id',
        'sede.Nombre as sede_reportante', 'eventos_adversos.fecha_ocurrencia', 'd.Producto as medicamento',
        'eventos_adversos.fecha_venci_medicamento', 'eventos_adversos.lote_medicamento',
        'eventos_adversos.invima_medicamento', 'eventos_adversos.referencia', 'eventos_adversos.modelo',
        'eventos_adversos.serial', 'eventos_adversos.invima_dispositivo', 'de.Producto as dispositivo',
        'eventos_adversos.descripcion_hechos', 'eventos_adversos.accion_tomada', 'u.name',
        'u.apellido', 'eventos_adversos.clasificacion_invima', 'eventos_adversos.dosis_medicamento',
        'eventos_adversos.medida_medicamento', 'eventos_adversos.via_medicamento',
        'eventos_adversos.frecuencia_medicamento', 'eventos_adversos.infusion_medicamento',
        'cl.nombre as nombreClasificacion', 'cl.id as clasificacionId', 'te.nombre as nombreTipoevento',
        'te.id as tipoeventoId','u.email', 'eventos_adversos.nombre_equipo','eventos_adversos.marca_equipo',
        'eventos_adversos.modelo_equipo', 'eventos_adversos.serie_equipo', 'eventos_adversos.placa_equipo', 'ent.nombre as entidad',
        'es.Nombre as estado', 'es.id as estado_id', 'eventos_adversos.lote_dispositivo', 'eventos_adversos.otro_evento',
        'eventos_adversos.servicio_ocurrencia', 'eventos_adversos.servicio_reportante', 'eventos_adversos.nivel_priorizacion',
        'eventos_adversos.priorizacion', 'analisis.fecha_analisis',
        'analisis.cronologia', 'analisis.acciones_inseguras', 'analisis.clasificacion', 'analisis.metodologia',
        'analisis.que_fallo', 'analisis.porque_fallo', 'analisis.que_causo', 'analisis.accion_implemento',
        'analisis.despues_adminmedicamento', 'analisis.factores_explicarelevento', 'analisis.desaparecio_suspendermedicamento',
        'analisis.reaccion_medicamentosospechoso', 'analisis.ampliar_informacionpaciente', 'analisis.evaluacion_causalidad',
        'analisis.clasificacion_invima', 'analisis.seriedad', 'analisis.fecha_muerte', 'analisis.desenlace_evento', 'analisis.causas_esavi',
        'analisis.asociacion_esavi', 'analisis.ventana_mayoriesgo', 'analisis.evidencia_asociacioncausal', 'analisis.factores_esavi',
        'analisis.farmaco_cinetica', 'analisis.condiciones_farmacocinetica', 'analisis.prescribio_manerainadecuada',
        'analisis.medicamento_entrenamiento', 'analisis.potenciales_interacciones', 'analisis.notificacion_refieremedicamento',
        'analisis.problema_biofarmaceutico', 'analisis.deficiencias_sistemas', 'analisis.factores_asociados', 'analisis.medicamento_manerainadecuada',
        'analisis.acciones_inseguras', 'analisis.elemento_funcion', 'analisis.modo_fallo', 'analisis.efecto', 'analisis.npr',
        'analisis.acciones_propuestas', 'analisis.id as analisisevento_id', 'analisis.descripcion_consecuencias', 'analisis.accion_resarcimiento','eventos_adversos.profesional',
        'analisis.requiere_reporte_ente', 'analisis.requiere_marca_especifica')
        ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id')
        ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id')
        ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id')
        ->join('sedeproveedores as sed', 'eventos_adversos.sede_ocurrencia','sed.id')
        ->join('users as u', 'eventos_adversos.user_id', 'u.id')
        ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante','sede.id')
        ->join('estados as es', 'eventos_adversos.estado_id', 'es.id')
        ->leftjoin('clasificacion_eventos as cl', 'eventos_adversos.clasificacionevento_id', 'cl.id')
        ->leftjoin('tipo_eventos as te', 'eventos_adversos.tipoevento_id', 'te.id')
        ->leftjoin('detallearticulos as d', 'eventos_adversos.detallearticulo_id', 'd.id')
        ->leftjoin('detallearticulos as de', 'eventos_adversos.dispositivo', 'de.id')
        ->leftjoin('analisis_eventos as analisis', 'analisis.eventosadverso_id', 'eventos_adversos.id')
        ->with(['pre_analisis' => function ($query) {
            $query->select('analisis_eventos.eventosadverso_id', 'analisis_eventos.id')
                ->with(['accion_inseguras' => function ($query) {
                    $query->select('acciones_inseguras.analisisevento_id', 'acciones_inseguras.id' , 'acciones_inseguras.name',
                    'acciones_inseguras.condiciones_paciente',  'acciones_inseguras.metodos', 'acciones_inseguras.colaborador',
                    'acciones_inseguras.equipo_trabajo', 'acciones_inseguras.ambiente', 'acciones_inseguras.recursos',
                    'acciones_inseguras.contexto')
                    ->get();
                }])
                ->with(['accion_mejoras' => function ($query) {
                    $query->select('acciones_mejoras.analisisevento_id', 'acciones_mejoras.id', 'acciones_mejoras.nombre',
                    'acciones_mejoras.responsables','acciones_mejoras.fecha_cumplimiento','acciones_mejoras.fecha_seguimiento',
                    'acciones_mejoras.estado', 'acciones_mejoras.observacion')
                    ->with(['adjuntos' => function ($query) {
                        $query->select('accionmejora_id', 'ruta', 'nombre')
                        ->get();
                    }])
                    ->get();
                }])
                ->get();
        }])
        ->with(['asignado_evento' => function ($query) {
            $query->select('asignado_eventos.eventosadverso_id', 'asignado_eventos.id', 'asignado_eventos.user_id',
            DB::raw("CONCAT(users.name,' ',users.apellido) as nombreAsignado"))
                ->join('users', 'asignado_eventos.user_id', 'users.id')
                ->where('asignado_eventos.estado_id', 1)
                ->get();
        }])
        ->with(['adjuntos' => function ($query) {
            $query->select('eventoadverso_id', 'ruta', 'nombre')
            ->get();
        }])
        ->where('eventos_adversos.tipo_id', 12)
        ->whereIn('eventos_adversos.estado_id', [18, 5, 46, 48, 49]);
        if(isset($request->evento_id)){
            $get_eventos->where('eventos_adversos.evento_id', $request->evento_id);
        }

        return response()->json($get_eventos->get(), 200);

    }

    public function saveAnalisis(Request $request){

        $analisis_guardado = Analisis_evento::select('id')
        ->where('eventosadverso_id', $request->eventoadverso_id)
        ->first();

        if(!$analisis_guardado){

            $analisis = new Analisis_evento;
            $analisis->eventosadverso_id = $request->eventoadverso_id;
            $analisis->fecha_analisis = $request->fecha_analisis;
            $analisis->cronologia = $request->cronologia;
            $analisis->consecuencias = $request->consecuencias;
            $analisis->acciones_inseguras = $request->acciones_inseguras;
            $analisis->tarea_tecnologia = $request->tarea_tecnologia;
            $analisis->clasificacion = $request->clasificacion_evento;
            $analisis->paciente = $request->paciente;
            $analisis->tarea_tecnologia = $request->tarea_tecnologia;
            $analisis->individuo = $request->individuo;
            $analisis->equipo_trabajo = $request->equipo_trabajo;
            $analisis->ambiente = $request->ambiente;
            $analisis->organizacion = $request->organizacion;
            $analisis->contexto = $request->contexto;
            $analisis->accion_mejora = $request->accion_mejora;
            $analisis->responsable = $request->responsable;
            $analisis->fecha_inicio = $request->fecha_inicio;
            $analisis->fecha_finalizacion = $request->fecha_finalizacion;
            $analisis->porcentaje_mejora = $request->porcentaje_mejora;
            $analisis->fecha_seguimiento = $request->fecha_seguimiento;
            $analisis->porcentaje_cumplimiento = $request->porcentaje_cumplimiento;
            $analisis->elemento_funcion = $request->elemento_funcion;
            $analisis->modo_fallo = $request->modo_fallo;
            $analisis->efecto = $request->efecto;
            $analisis->npr = $request->npr;
            $analisis->acciones_propuestas = $request->acciones_propuestas;
            $analisis->usercreo_id = auth()->id();
            $analisis->save();

            return response()->json([
                'message' => 'Analisis guardado con exito!',
            ], 201);

        }else{

            Analisis_evento::where('id', $analisis_guardado->id)
            ->update([
                'fecha_analisis' => $request->fecha_analisis,
                'cronologia' => $request->cronologia,
                'consecuencias' => $request->consecuencias,
                'acciones_inseguras' => $request->acciones_inseguras,
                'clasificacion' => $request->clasificacion_evento,
                'paciente' => $request->paciente,
                'tarea_tecnologia' => $request->tarea_tecnologia,
                'individuo' => $request->individuo,
                'equipo_trabajo' => $request->equipo_trabajo,
                'ambiente'  => $request->ambiente,
                'organizacion' => $request->organizacion,
                'contexto' => $request->contexto,
                'accion_mejora' => $request->accion_mejora,
                'responsable' => $request->responsable,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_finalizacion' => $request->fecha_finalizacion,
                'porcentaje_mejora' => $request->porcentaje_mejora,
                'fecha_seguimiento' => $request->fecha_seguimiento,
                'porcentaje_cumplimiento' => $request->porcentaje_cumplimiento,
                'elemento_funcion' => $request->elemento_funcion,
                'modo_fallo' => $request->modo_fallo,
                'efecto' => $request->efecto,
                'npr' => $request->npr,
                'acciones_propuestas' => $request->acciones_propuestas
            ]);

            return response()->json([
                'message' => 'Analisis actualizado con exito!',
            ], 201);

        }

    }

    public function getAnalisis(Request $request){

        $analisis_guardado = Analisis_evento::select('id', 'fecha_analisis', 'cronologia',
        'consecuencias', 'acciones_inseguras', 'clasificacion', 'paciente', 'tarea_tecnologia',
        'individuo', 'equipo_trabajo', 'ambiente', 'organizacion', 'contexto', 'accion_mejora',
        'responsable', 'fecha_inicio', 'fecha_finalizacion', 'porcentaje_mejora', 'fecha_seguimiento',
        'porcentaje_cumplimiento', 'elemento_funcion', 'modo_fallo', 'efecto', 'npr', 'acciones_propuestas',
        'requiere_reporte_ente', 'requiere_marca_especifica')
        ->where('eventosadverso_id', $request->eventoadverso_id)
        ->first();

        return response()->json($analisis_guardado, 200);
    }

    public function closeAnalisis(Request $request){

        $analisis = Analisis_evento::where('eventosadverso_id', $request->eventoadverso_id)->first();
        Acciones_mejoras::where('analisisevento_id', $analisis->id)->update([
            'estado' => 'Cumplido'
        ]);

        Analisis_evento::where('eventosadverso_id', $request->eventoadverso_id)
        ->update([
            'usercerro_id' => auth()->id()
        ]);

        Eventos_adverso::where('id', $request->eventoadverso_id)
        ->update([
            'severidad_evento' => $request->severidad,
            'estado_id' => 13
        ]);

        return response()->json([
            'message' => 'Evento cerrado con exito!',
        ], 201);

    }

    public function getcerrados(Request $request){

        $get_eventos_cerrados = Eventos_adverso::select('eventos_adversos.id', 'e.nombre as nombreEvento', 'e.id as eventoId', 'p.Num_Doc',
        DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ', p.Primer_Ape,' ',p.Segundo_Ape) as nombre_completo"),
        'eventos_adversos.clasificacion', 'eventos_adversos.created_at', 'eventos_adversos.paciente_id',
        'eventos_adversos.relacion', 'p.Edad_Cumplida', 'p.sexo', 'sed.Nombre as sede_ocurrencia',
        'sede.Nombre as sede_reportante', 'eventos_adversos.fecha_ocurrencia', 'd.Producto as medicamento',
        'eventos_adversos.fecha_venci_medicamento', 'eventos_adversos.lote_medicamento',
        'eventos_adversos.invima_medicamento', 'eventos_adversos.referencia', 'eventos_adversos.modelo',
        'eventos_adversos.serial', 'eventos_adversos.invima_dispositivo', 'de.Producto as dispositivo',
        'eventos_adversos.descripcion_hechos', 'eventos_adversos.accion_tomada', 'u.name',
        'u.apellido', 'eventos_adversos.clasificacion_invima', 'eventos_adversos.dosis_medicamento',
        'eventos_adversos.medida_medicamento', 'eventos_adversos.via_medicamento',
        'eventos_adversos.frecuencia_medicamento', 'eventos_adversos.infusion_medicamento',
        'cl.nombre as nombreClasificacion', 'cl.id as clasificacionId', 'te.nombre as nombreTipoevento',
        'te.id as tipoeventoId','u.email', 'eventos_adversos.nombre_equipo','eventos_adversos.marca_equipo',
        'eventos_adversos.modelo_equipo', 'eventos_adversos.serie_equipo', 'eventos_adversos.placa_equipo', 'ent.nombre as entidad',
        'es.Nombre as estado', 'es.id as estado_id', 'eventos_adversos.lote_dispositivo', 'eventos_adversos.otro_evento',
        'eventos_adversos.servicio_ocurrencia', 'eventos_adversos.servicio_reportante', 'eventos_adversos.nivel_priorizacion',
        'asignado_eventos.created_at as fecha_asigna', 'eventos_adversos.priorizacion', 'analisis.fecha_analisis',
        'analisis.cronologia', 'analisis.acciones_inseguras', 'analisis.clasificacion', 'analisis.metodologia',
        'analisis.que_fallo', 'analisis.porque_fallo', 'analisis.que_causo', 'analisis.accion_implemento',
        'analisis.despues_adminmedicamento', 'analisis.factores_explicarelevento', 'analisis.desaparecio_suspendermedicamento',
        'analisis.reaccion_medicamentosospechoso', 'analisis.ampliar_informacionpaciente', 'analisis.evaluacion_causalidad',
        'analisis.clasificacion_invima', 'analisis.seriedad', 'analisis.fecha_muerte', 'analisis.desenlace_evento', 'analisis.causas_esavi',
        'analisis.asociacion_esavi', 'analisis.ventana_mayoriesgo', 'analisis.evidencia_asociacioncausal', 'analisis.factores_esavi',
        'analisis.farmaco_cinetica', 'analisis.condiciones_farmacocinetica', 'analisis.prescribio_manerainadecuada',
        'analisis.medicamento_entrenamiento', 'analisis.potenciales_interacciones', 'analisis.notificacion_refieremedicamento',
        'analisis.problema_biofarmaceutico', 'analisis.deficiencias_sistemas', 'analisis.factores_asociados', 'analisis.medicamento_manerainadecuada',
        'analisis.acciones_inseguras', 'analisis.elemento_funcion', 'analisis.modo_fallo', 'analisis.efecto', 'analisis.npr',
        'analisis.acciones_propuestas', 'analisis.id as analisisevento_id', 'analisis.descripcion_consecuencias','analisis.accion_resarcimiento',
        'analisis.requiere_reporte_ente', 'analisis.requiere_marca_especifica')
        ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id')
        ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id')
        ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id')
        ->join('estados as es', 'eventos_adversos.estado_id', 'es.id')
        ->join('sedeproveedores as sed', 'eventos_adversos.sede_ocurrencia','sed.id')
        ->join('users as u', 'eventos_adversos.user_id', 'u.id')
        ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante','sede.id')
        ->leftjoin('clasificacion_eventos as cl', 'eventos_adversos.clasificacionevento_id', 'cl.id')
        ->leftjoin('tipo_eventos as te', 'eventos_adversos.tipoevento_id', 'te.id')
        ->leftjoin('detallearticulos as d', 'eventos_adversos.detallearticulo_id', 'd.id')
        ->leftjoin('detallearticulos as de', 'eventos_adversos.dispositivo', 'de.id')
        ->leftjoin('asignado_eventos', 'asignado_eventos.eventosadverso_id', 'eventos_adversos.id')
        ->leftjoin('analisis_eventos as analisis', 'analisis.eventosadverso_id', 'eventos_adversos.id')
        ->with(['pre_analisis' => function ($query) {
            $query->select('analisis_eventos.eventosadverso_id', 'analisis_eventos.id')
                ->with(['accion_inseguras' => function ($query) {
                    $query->select('acciones_inseguras.analisisevento_id', 'acciones_inseguras.id' , 'acciones_inseguras.name',
                    'acciones_inseguras.condiciones_paciente',  'acciones_inseguras.metodos', 'acciones_inseguras.colaborador',
                    'acciones_inseguras.equipo_trabajo', 'acciones_inseguras.ambiente', 'acciones_inseguras.recursos',
                    'acciones_inseguras.contexto')
                    ->get();
                }])
                ->with(['accion_mejoras' => function ($query) {
                    $query->select('acciones_mejoras.analisisevento_id', 'acciones_mejoras.id', 'acciones_mejoras.nombre',
                    'acciones_mejoras.responsables','acciones_mejoras.fecha_cumplimiento','acciones_mejoras.fecha_seguimiento',
                    'acciones_mejoras.estado')
                    ->get();
                }])
                ->get();
        }])
        ->with(['adjuntos' => function ($query) {
            $query->select('eventoadverso_id', 'ruta', 'nombre')
            ->get();
        }])
        ->where('eventos_adversos.tipo_id', 12)
        ->whereIn('eventos_adversos.estado_id', [13, 26])
        ->whereBetween('eventos_adversos.created_at', [$request->fechaDesde, $request->fechaHasta]);
        if(isset($request->evento_id)){
            $get_eventos_cerrados->where('eventos_adversos.evento_id', $request->evento_id);
        }

        return response()->json($get_eventos_cerrados->get(), 200);

    }

    public function informe(Request $request){

        if($request->area == null){
            $area = '';
        }else{
            $area = $request->area;
        }

        $appointments = Collect(DB::select("SET NOCOUNT ON exec dbo.SP_Reporte_Eventos_Adversos ?,?,?", [$request->fechaDesde, $request->fechaHasta,$area]));
        $result = json_decode($appointments, true);

        return (new FastExcel($result))->download('file.xls');
    }

    public function updateArea(Request $request){

        Eventos_adverso::where('id', $request->eventoadverso_id)->update([
            'evento_id' => $request->evento_id,
            'clasificacionevento_id' => ($request->clasificacionevento_id == 'null' || $request->clasificacionevento_id == '' ?null:$request->clasificacionevento_id),
            'tipoevento_id' => ($request->tipoevento_id == 'null' || $request->tipoevento_id == '' ?null:$request->tipoevento_id),
            'sede_ocurrencia' => $request->sedeocurrencia_id,
            'servicio_ocurrencia' => ($request->sedeocurrencia_id == 704 ? $request->servicio_ocurrencia: null)
        ]);

        return response()->json([
            'message' => 'Actualizado con exito!',
        ], 201);

    }

    public function history(Request $request){

        $historyEvento = Eventos_adverso::select(['eventos_adversos.id', 'estados.Nombre as estado',
        'eventos_adversos.fecha_ocurrencia', 'eventos.nombre as evento', 'eventos_adversos.descripcion_hechos',
        'sedeproveedores.nombre as sede', 'eventos_adversos.created_at'])
        ->join('estados', 'eventos_adversos.estado_id', 'estados.id')
        ->join('eventos', 'eventos_adversos.evento_id', 'eventos.id')
        ->leftjoin('sedeproveedores', 'eventos_adversos.sede_ocurrencia', 'sedeproveedores.id')
        ->where('paciente_id', $request->paciente_id)
        ->where('eventos_adversos.estado_id', '!=', 13)
        ->latest()
        ->take(5)
        ->get();

        return response()->json($historyEvento, 201);

    }

    public function createUserNotification(Request $request){

        if($request->tipo == 'TODAS'){
            NotificacionEvento::create([
                'user_id' => $request->user_id,
                'tipo' => $request->tipo,
                'estado_id' => 1,
            ]);
        }else{
            foreach ($request->sedeproveedore_id as $sede) {
                NotificacionEvento::create([
                    'user_id' => $request->user_id,
                    'sedeproveedore_id' => $sede,
                    'tipo' => $request->tipo,
                    'estado_id' => 1,
                ]);
            }
        }

        return response()->json([
            'message' => 'Usuario notificación creado con exito!',
        ], 201);

    }

    public function getUserNotification(){

        $allusernotificaciones = NotificacionEvento::select(['users.name', 'users.apellido',
        'users.cedula', 'notificacion_eventos.tipo', 'users.id'])
        ->join('users', 'notificacion_eventos.user_id', 'users.id')
        ->where('notificacion_eventos.estado_id', 1)
        ->distinct()
        ->get();

        return response()->json($allusernotificaciones, 201);

    }

    public function sedesUserNotification($user){


        $sedes = NotificacionEvento::select(['sedeproveedores.id'])
        ->join('sedeproveedores', 'notificacion_eventos.sedeproveedore_id', 'sedeproveedores.id')
        ->join('prestadores', 'sedeproveedores.Prestador_id', 'prestadores.id')
        ->where('notificacion_eventos.user_id', $user)
        ->where('notificacion_eventos.estado_id', 1)
        ->get();

        return response()->json($sedes, 201);

    }

    public function updateUserNotification(Request $request){

        NotificacionEvento::where('user_id', $request->user_id)->update([
            "estado_id" => 2
        ]);

        if($request->tipo == 'TODAS'){
            NotificacionEvento::create([
                'user_id' => $request->user_id,
                'tipo' => $request->tipo,
                'estado_id' => 1,
            ]);
        }else{
            foreach ($request->sedeproveedore_id as $sede) {
                NotificacionEvento::create([
                    'user_id' => $request->user_id,
                    'sedeproveedore_id' => $sede,
                    'tipo' => $request->tipo,
                    'estado_id' => 1,
                ]);
            }
        }

        return response()->json([
            'message' => 'Usuario notificación actualizado con exito!',
        ], 201);

    }

    public function asignar(Eventos_adverso $evento, Request $request){

        $validate = Validator::make($request->all(),[
            'acciones' => 'required',
            'priorizacion' => 'required',
            'user' => 'required',
            // 'probabilidad'  => 'required',
            // 'impacto'   => 'required',
            // 'previsibilidad'    => 'required',
            // 'nivelPriorizacion'    => 'required',
            'identificacion_riesgo' =>  'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        if($evento){

            $evento->update([
                'estado_id' => 5,
                'priorizacion'  => $request->priorizacion,
                'probabilidad'  => $request->probabilidad,
                'impacto'   => $request->impacto,
                'previsibilidad'    => $request->previsibilidad,
                'nivel_priorizacion'    => $request->nivelPriorizacion,
                'voluntariedad' => $request->voluntariedad,
                'identificacion_riesgo' => $request->identificacion_riesgo,
            ]);

            foreach ($request->user as $key) {
                $asignar = new Asignado_eventos;
                $asignar->user_id = $key;
                $asignar->eventosadverso_id = $evento->id;
                $asignar->estado_id = 1;
                $asignar->save();
            }

            $gestion = new Gestion_evento;
            $gestion->eventosadverso_id = $evento->id;
            $gestion->user_id = Auth::user()->id;
            $gestion->tipo_id = 20;
            $gestion->motivo = "Asigno Evento";
            $gestion->save();

            $users = User::select(['email', 'name'])
            ->whereIn('id', $request->user)
            ->get();

            $email = Mail::send('email_notificacion_eventos',
            ['data' => $evento, 'type' => 'asignar'], function ($m) use ($users) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación Eventos');
                }
            });

            return response()->json([
                'message' => 'Evento Asignado con exito!',
            ], 200);

        }else{
            return response()->json([
                'message' => 'Error intenta de nuevo!',
            ], 401);
        }


    }

    public function getpendientesAsignado(){
        try {
            $get_eventos = Eventos_adverso::select(
                'eventos_adversos.id',
                'sede.Nombre as sede_ocurrencia',
                'eventos_adversos.fecha_ocurrencia',
                'p.Num_Doc',
                'e.nombre as nombreEvento',
                'ent.nombre as entidad',
                'eventos_adversos.priorizacion',
                'asignado_eventos.created_at as fecha_asigna',
                DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ', p.Primer_Ape,' ',p.Segundo_Ape) as nombre_completo")
                )
            ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id')
            ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id')
            ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id')
            ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante','sede.id')
            ->join('asignado_eventos', 'asignado_eventos.eventosadverso_id', 'eventos_adversos.id')
            ->where('eventos_adversos.tipo_id', 12)
            ->where('eventos_adversos.estado_id', 5)
            ->where('asignado_eventos.user_id', auth()->id())
            ->where('asignado_eventos.estado_id', 1)
            ->get();

            $urgente = 2;
            $muyPrioritario = 5;
            $prioritario = 10;
            $noPrioritario = 15;
            $totalDias = 0;

            foreach ($get_eventos as $evento) {
                $fecha1 = Carbon::now();
                $fecha2 = carbon::parse($evento->fecha_asigna);
                $diasDiferencia = $fecha2->diffInDays($fecha1);
                if ($diasDiferencia < 0){
                    $diasDiferencia = 0;
                }
                if($evento->priorizacion == 'Urgente'){
                    $totalDias = $urgente-$diasDiferencia;
                }else if($evento->priorizacion == 'Muy prioritario'){
                    $totalDias = $muyPrioritario-$diasDiferencia;
                }else if($evento->priorizacion == 'Prioritario'){
                    $totalDias = $prioritario-$diasDiferencia;
                }else if($evento->priorizacion == 'No prioritario'){
                    $totalDias = $noPrioritario-$diasDiferencia;
                }
                $evento['diasDiferencia'] = $totalDias;
            }

            return response()->json($get_eventos, 200);
        } catch (\Throwable $th) {
            if($th -> getCode())
            {
                return response()->json(['message' => $th->getMessage()], $th->getCode());
            }
            else
            {
                return response()->json(['message' => $th->getMessage()], 400);
            }
        }

    }

    public function getcerradosAsignado(){

        $get_eventos = Eventos_adverso::select('eventos_adversos.id', 'e.nombre as nombreEvento', 'e.id as eventoId', 'p.Num_Doc',
        DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ', p.Segundo_Ape,' ',p.Primer_Ape) as nombre_completo"),
        'eventos_adversos.clasificacion', 'eventos_adversos.created_at', 'eventos_adversos.paciente_id',
        'eventos_adversos.relacion', 'p.Edad_Cumplida', 'p.sexo', 'sed.Nombre as sede_ocurrencia',
        'sede.Nombre as sede_reportante', 'eventos_adversos.fecha_ocurrencia', 'd.Producto as medicamento',
        'eventos_adversos.fecha_venci_medicamento', 'eventos_adversos.lote_medicamento',
        'eventos_adversos.invima_medicamento', 'eventos_adversos.referencia', 'eventos_adversos.modelo',
        'eventos_adversos.serial', 'eventos_adversos.invima_dispositivo', 'de.Producto as dispositivo',
        'eventos_adversos.descripcion_hechos', 'eventos_adversos.accion_tomada', 'u.name',
        'u.apellido', 'eventos_adversos.clasificacion_invima', 'eventos_adversos.dosis_medicamento',
        'eventos_adversos.medida_medicamento', 'eventos_adversos.via_medicamento',
        'eventos_adversos.frecuencia_medicamento', 'eventos_adversos.infusion_medicamento',
        'cl.nombre as nombreClasificacion', 'cl.id as clasificacionId', 'te.nombre as nombreTipoevento',
        'te.id as tipoeventoId','u.email', 'eventos_adversos.nombre_equipo','eventos_adversos.marca_equipo',
        'eventos_adversos.modelo_equipo', 'eventos_adversos.serie_equipo', 'eventos_adversos.placa_equipo', 'ent.nombre as entidad',
        'es.Nombre as estado', 'es.id as estado_id', 'eventos_adversos.lote_dispositivo', 'eventos_adversos.otro_evento',
        'eventos_adversos.servicio_ocurrencia', 'eventos_adversos.servicio_reportante', 'eventos_adversos.nivel_priorizacion',
        'asignado_eventos.created_at as fecha_asigna', 'eventos_adversos.priorizacion', 'analisis.fecha_analisis',
        'analisis.cronologia', 'analisis.acciones_inseguras', 'analisis.clasificacion', 'analisis.metodologia',
        'analisis.que_fallo', 'analisis.porque_fallo', 'analisis.que_causo', 'analisis.accion_implemento',
        'analisis.despues_adminmedicamento', 'analisis.factores_explicarelevento', 'analisis.desaparecio_suspendermedicamento',
        'analisis.reaccion_medicamentosospechoso', 'analisis.ampliar_informacionpaciente', 'analisis.evaluacion_causalidad',
        'analisis.clasificacion_invima', 'analisis.seriedad', 'analisis.fecha_muerte', 'analisis.desenlace_evento', 'analisis.causas_esavi',
        'analisis.asociacion_esavi', 'analisis.ventana_mayoriesgo', 'analisis.evidencia_asociacioncausal', 'analisis.factores_esavi',
        'analisis.farmaco_cinetica', 'analisis.condiciones_farmacocinetica', 'analisis.prescribio_manerainadecuada',
        'analisis.medicamento_entrenamiento', 'analisis.potenciales_interacciones', 'analisis.notificacion_refieremedicamento',
        'analisis.problema_biofarmaceutico', 'analisis.deficiencias_sistemas', 'analisis.factores_asociados', 'analisis.medicamento_manerainadecuada',
        'analisis.acciones_inseguras', 'analisis.elemento_funcion', 'analisis.modo_fallo', 'analisis.efecto', 'analisis.npr',
        'analisis.acciones_propuestas', 'analisis.id as analisisevento_id', 'analisis.descripcion_consecuencias','analisis.accion_resarcimiento',
        'analisis.requiere_reporte_ente', 'analisis.requiere_marca_especifica')
        ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id')
        ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id')
        ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id')
        ->join('sedeproveedores as sed', 'eventos_adversos.sede_ocurrencia','sed.id')
        ->join('users as u', 'eventos_adversos.user_id', 'u.id')
        ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante','sede.id')
        ->join('estados as es', 'eventos_adversos.estado_id', 'es.id')
        ->leftjoin('clasificacion_eventos as cl', 'eventos_adversos.clasificacionevento_id', 'cl.id')
        ->leftjoin('tipo_eventos as te', 'eventos_adversos.tipoevento_id', 'te.id')
        ->leftjoin('detallearticulos as d', 'eventos_adversos.detallearticulo_id', 'd.id')
        ->leftjoin('detallearticulos as de', 'eventos_adversos.dispositivo', 'de.id')
        ->join('asignado_eventos', 'asignado_eventos.eventosadverso_id', 'eventos_adversos.id')
        ->leftjoin('analisis_eventos as analisis', 'analisis.eventosadverso_id', 'eventos_adversos.id')
        ->with(['pre_analisis' => function ($query) {
            $query->select('analisis_eventos.eventosadverso_id', 'analisis_eventos.id')
                ->with(['accion_inseguras' => function ($query) {
                    $query->select('acciones_inseguras.analisisevento_id', 'acciones_inseguras.id' , 'acciones_inseguras.name',
                    'acciones_inseguras.condiciones_paciente',  'acciones_inseguras.metodos', 'acciones_inseguras.colaborador',
                    'acciones_inseguras.equipo_trabajo', 'acciones_inseguras.ambiente', 'acciones_inseguras.recursos',
                    'acciones_inseguras.contexto')
                    ->get();
                }])
                ->with(['accion_mejoras' => function ($query) {
                    $query->select('acciones_mejoras.analisisevento_id', 'acciones_mejoras.id', 'acciones_mejoras.nombre',
                    'acciones_mejoras.responsables','acciones_mejoras.fecha_cumplimiento','acciones_mejoras.fecha_seguimiento',
                    'acciones_mejoras.estado')
                    ->get();
                }])
                ->get();
        }])
        ->with(['adjuntos' => function ($query) {
            $query->select('eventoadverso_id', 'ruta', 'nombre')
            ->get();
        }])
        ->where('eventos_adversos.tipo_id', 12)
        ->whereIn('eventos_adversos.estado_id', [13, 46])
        ->where('asignado_eventos.user_id', auth()->id())
        ->where('asignado_eventos.estado_id', 1)
        ->get();

        return response()->json($get_eventos, 200);

    }

    public function reasignar(Eventos_adverso $evento, Request $request){

        $validate = Validator::make($request->all(),[
            'acciones' => 'required',
            'user' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        if($evento){

            $gestion = new Gestion_evento;
            $gestion->eventosadverso_id = $evento->id;
            $gestion->user_id = Auth::user()->id;
            $gestion->tipo_id = 18;
            $gestion->motivo = "Reasigno Evento";
            $gestion->save();

            Asignado_eventos::where('eventosadverso_id', $evento->id)
            ->where('estado_id', 1)
            ->update([
                'estado_id' => 26,
            ]);

            $userEventos = Asignado_eventos::where('eventosadverso_id', $evento->id)->get(['user_id']);

            $usersEventoArray = [];
            foreach ($userEventos as $user) {
                array_push($usersEventoArray, $user->user_id);
            }

            $nuevosArray = array_diff($request->user, $usersEventoArray);

            if (isset($nuevosArray)) {
                foreach ($nuevosArray as $key) {
                    $asignar = new Asignado_eventos;
                    $asignar->user_id = $key;
                    $asignar->eventosadverso_id = $evento->id;
                    $asignar->estado_id = 1;
                    $asignar->save();
                }
            }

            Asignado_eventos::where('eventosadverso_id', $evento->id)
            ->whereIn('user_id', $request->user)
            ->update([
                'estado_id' => 1,
            ]);

            $users = User::select(['email', 'name'])
            ->whereIn('id', $request->user)
            ->get();

            Mail::send('email_notificacion_eventos',
            ['data' => $evento, 'type' => 'asignar'], function ($m) use ($users) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación Eventos');
                }
            });

            return response()->json([
                'message' => 'Evento reasignado con exito!',
            ], 200);

        }else{
            return response()->json([
                'message' => 'Error intenta de nuevo!',
            ], 401);
        }


    }

    public function anular(Eventos_adverso $evento, Request $request){

        $validate = Validator::make($request->all(),[
            'motivo' => 'required',
            'voluntariedad' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        if($evento){

            $evento->update([
                'estado_id' => 26,
                'clasificacion' => $request->clasificacion,
                'voluntariedad' => $request->voluntariedad
            ]);

            $gestion = new Gestion_evento;
            $gestion->eventosadverso_id = $evento->id;
            $gestion->user_id = Auth::user()->id;
            $gestion->tipo_id = 17;
            $gestion->motivo = $request->motivo;
            $gestion->save();

            return response()->json([
                'message' => 'Evento Anulado con exito!',
            ], 200);

        }else{
            return response()->json([
                'message' => 'Error intenta de nuevo!',
            ], 401);
        }


    }

    public function preAnalisis(Request $request){

        $analisis = Analisis_evento::where('eventosadverso_id', $request->id)->first();

        if($analisis){
            $analisis->update($request->all());
            return response()->json([
                'message' => 'Pre Analisis actualizado con exito!',
            ], 200);
        }else{
            $request['eventosadverso_id'] = $request->id;
            $analisis = Analisis_evento::create($request->all());
            return response()->json([
                'analisisevento_id'  =>  $analisis->id,
                'message' => 'Pre Analisis guardado con exito!',
            ], 200);
        }

    }

    public function addAcciones(Request $request){
        try{
            $existe = Acciones_inseguras::where('name', $request->name)
                ->where('analisisevento_id', $request->analisisevento_id)
                ->exists();

            if($existe){
                throw new \Exception('Ya existe esta acción insegura!');
            }

            Acciones_inseguras::create($request->all());
            return response()->json([
                'mensaje' => 'Acción insegura guardada con exito!',
            ], 201);

        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function editAcciones(Acciones_inseguras $acciones_insegura, Request $request){

        $acciones_insegura->update([
            'condiciones_paciente' => ($request->condiciones_paciente == [] ?null:$request->condiciones_paciente),
            'metodos' => ($request->metodos == [] ?null:$request->metodos),
            'colaborador' => ($request->colaborador == [] ?null:$request->colaborador),
            'equipo_trabajo' => ($request->equipo_trabajo == [] ?null:$request->equipo_trabajo),
            'ambiente' => ($request->ambiente == [] ?null:$request->ambiente),
            'recursos' => ($request->recursos == [] ?null:$request->recursos),
            'contexto' => ($request->contexto == [] ?null:$request->contexto),
        ]);

        return response()->json([
            'message' => 'Acción actualizada con exito!',
        ], 200);

    }

    public function deleteAcciones(Acciones_inseguras $acciones_insegura){

        $acciones_insegura->delete();

        return response()->json([
            'message' => 'Acción eliminada con exito!',
        ], 200);
    }

    public function savepreAnalisis(Eventos_adverso $evento){

        $analisis = Analisis_evento::where('eventosadverso_id', $evento->id)->first();
        $accion = Acciones_mejoras::where('analisisevento_id', $analisis->id)->first();

        if($accion){
            $state = 48;
        }else{
            $state = 46;
        }

        $evento->update([
            'usercreo_id' => auth()->id(),
            'estado_id' => $state
        ]);

        return response()->json([
            'message' => 'Analisis del evento finalizado con exito!',
        ], 200);

    }

    public function addAccionesMejoras(Request $request){
        try{
            $existe = Acciones_mejoras::where('nombre', $request->nombre)
                ->where('analisisevento_id', $request->analisisevento_id)
                ->first();

            if($existe){
                throw new \Exception('Ya existe esta acción de mejora!');
            }
            $request['estado'] = 'Pendiente';
            Acciones_mejoras::create($request->all());
            return response()->json([
                'mensaje' => 'Acción de mejora guardada con exito!',
            ], 201);
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    public function editAccionMejora(Acciones_mejoras $acciones_mejoras, Request $request){

        $acciones_mejoras->update([
            'nombre' => $request->nombre,
            'responsables' => ($request->responsables == [] ?null:$request->responsables),
            'fecha_cumplimiento' => $request->fecha_cumplimiento,
            'fecha_seguimiento' => $request->fecha_seguimiento,
            'estado' => ($request->estado == '' ?'Pendiente' :$request->estado)
        ]);

        return response()->json([
            'message' => 'Acción de mejora actualizada con exito!',
        ], 200);

    }

    public function deleteAccioneMejora(Acciones_mejoras $acciones_mejoras){

        $acciones_mejoras->delete();

        return response()->json([
            'message' => 'Acción de mejora eliminada con exito!',
        ], 200);

    }

    public function adjuntos(Request $request)
    {

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $fileName[$i] = $file->getClientOriginalName();
                $filenametostore[$i] = '/adjuntosEventosAdversos/'.$request->eventoadverso_id.'/'.time().'_'.$fileName[$i];
                Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                $adjunto = new AdjuntoEvento;
                $adjunto->nombre = $fileName[$i];
                $adjunto->ruta = $filenametostore[$i];
                $adjunto->eventoadverso_id = $request->eventoadverso_id;
                $adjunto->save();

                $i++;
            }

            return response()->json([
                'message' => 'Adjunto agregado con exito!',
            ], 200);
        }
    }

    public function getPlanMejoras()
    {

        $user = Auth::user();
        $nameUser = $user->name.' '.$user->apellido;

        $eventosConsulta = Eventos_adverso::select('eventos_adversos.id', 'e.nombre as nombreEvento', 'e.id as eventoId', 'p.Num_Doc',
        DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ', p.Segundo_Ape,' ',p.Primer_Ape) as nombre_completo"),
        'eventos_adversos.clasificacion', 'eventos_adversos.created_at', 'eventos_adversos.paciente_id',
        'eventos_adversos.relacion', 'p.Edad_Cumplida', 'p.sexo', 'sed.Nombre as sede_ocurrencia', 'sed.id as sedeocurrencia_id',
        'sede.Nombre as sede_reportante', 'eventos_adversos.fecha_ocurrencia', 'd.Producto as medicamento',
        'eventos_adversos.fecha_venci_medicamento', 'eventos_adversos.lote_medicamento',
        'eventos_adversos.invima_medicamento', 'eventos_adversos.referencia', 'eventos_adversos.modelo',
        'eventos_adversos.serial', 'eventos_adversos.invima_dispositivo', 'de.Producto as dispositivo',
        'eventos_adversos.descripcion_hechos', 'eventos_adversos.accion_tomada', 'u.name',
        'u.apellido', 'eventos_adversos.clasificacion_invima', 'eventos_adversos.dosis_medicamento',
        'eventos_adversos.medida_medicamento', 'eventos_adversos.via_medicamento',
        'eventos_adversos.frecuencia_medicamento', 'eventos_adversos.infusion_medicamento',
        'cl.nombre as nombreClasificacion', 'cl.id as clasificacionId', 'te.nombre as nombreTipoevento',
        'te.id as tipoeventoId','u.email', 'eventos_adversos.nombre_equipo','eventos_adversos.marca_equipo',
        'eventos_adversos.modelo_equipo', 'eventos_adversos.serie_equipo', 'eventos_adversos.placa_equipo', 'ent.nombre as entidad',
        'es.Nombre as estado', 'es.id as estado_id', 'eventos_adversos.lote_dispositivo', 'eventos_adversos.otro_evento',
        'eventos_adversos.servicio_ocurrencia', 'eventos_adversos.servicio_reportante', 'eventos_adversos.nivel_priorizacion',
        'eventos_adversos.priorizacion', 'analisis.fecha_analisis',
        'analisis.cronologia', 'analisis.acciones_inseguras', 'analisis.clasificacion', 'analisis.metodologia',
        'analisis.que_fallo', 'analisis.porque_fallo', 'analisis.que_causo', 'analisis.accion_implemento',
        'analisis.despues_adminmedicamento', 'analisis.factores_explicarelevento', 'analisis.desaparecio_suspendermedicamento',
        'analisis.reaccion_medicamentosospechoso', 'analisis.ampliar_informacionpaciente', 'analisis.evaluacion_causalidad',
        'analisis.clasificacion_invima', 'analisis.seriedad', 'analisis.fecha_muerte', 'analisis.desenlace_evento', 'analisis.causas_esavi',
        'analisis.asociacion_esavi', 'analisis.ventana_mayoriesgo', 'analisis.evidencia_asociacioncausal', 'analisis.factores_esavi',
        'analisis.farmaco_cinetica', 'analisis.condiciones_farmacocinetica', 'analisis.prescribio_manerainadecuada',
        'analisis.medicamento_entrenamiento', 'analisis.potenciales_interacciones', 'analisis.notificacion_refieremedicamento',
        'analisis.problema_biofarmaceutico', 'analisis.deficiencias_sistemas', 'analisis.factores_asociados', 'analisis.medicamento_manerainadecuada',
        'analisis.acciones_inseguras', 'analisis.elemento_funcion', 'analisis.modo_fallo', 'analisis.efecto', 'analisis.npr',
        'analisis.acciones_propuestas', 'analisis.id as analisisevento_id', 'analisis.descripcion_consecuencias', 'analisis.accion_resarcimiento','eventos_adversos.profesional',
        'analisis.requiere_reporte_ente', 'analisis.requiere_marca_especifica')
        ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id')
        ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id')
        ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id')
        ->join('sedeproveedores as sed', 'eventos_adversos.sede_ocurrencia','sed.id')
        ->join('users as u', 'eventos_adversos.user_id', 'u.id')
        ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante','sede.id')
        ->join('estados as es', 'eventos_adversos.estado_id', 'es.id')
        ->leftjoin('clasificacion_eventos as cl', 'eventos_adversos.clasificacionevento_id', 'cl.id')
        ->leftjoin('tipo_eventos as te', 'eventos_adversos.tipoevento_id', 'te.id')
        ->leftjoin('detallearticulos as d', 'eventos_adversos.detallearticulo_id', 'd.id')
        ->leftjoin('detallearticulos as de', 'eventos_adversos.dispositivo', 'de.id')
        ->leftjoin('analisis_eventos as analisis', 'analisis.eventosadverso_id', 'eventos_adversos.id')
        ->with(['pre_analisis' => function ($query){
            $query->select('analisis_eventos.eventosadverso_id', 'analisis_eventos.id')
                ->with(['accion_inseguras' => function ($query) {
                    $query->select('acciones_inseguras.analisisevento_id', 'acciones_inseguras.id' , 'acciones_inseguras.name',
                    'acciones_inseguras.condiciones_paciente',  'acciones_inseguras.metodos', 'acciones_inseguras.colaborador',
                    'acciones_inseguras.equipo_trabajo', 'acciones_inseguras.ambiente', 'acciones_inseguras.recursos',
                    'acciones_inseguras.contexto')
                    ->get();
                }])
                ->with(['accion_mejoras' => function ($query) {
                    $query->select('acciones_mejoras.analisisevento_id', 'acciones_mejoras.id', 'acciones_mejoras.nombre',
                    'acciones_mejoras.responsables','acciones_mejoras.fecha_cumplimiento','acciones_mejoras.fecha_seguimiento',
                    'acciones_mejoras.estado', 'acciones_mejoras.observacion')
                    ->with(['adjuntos' => function ($query) {
                        $query->select('accionmejora_id', 'ruta', 'nombre')
                        ->get();
                    }])
                    ->get();
                }])
                ->get();
        }])
        ->with(['asignado_evento' => function ($query) {
            $query->select('asignado_eventos.eventosadverso_id', 'asignado_eventos.id', 'asignado_eventos.user_id',
            DB::raw("CONCAT(users.name,' ',users.apellido) as nombreAsignado"))
                ->join('users', 'asignado_eventos.user_id', 'users.id')
                ->where('asignado_eventos.estado_id', 1)
                ->get();
        }])
        ->with(['adjuntos' => function ($query) {
            $query->select('eventoadverso_id', 'ruta', 'nombre')
            ->get();
        }])
        ->where('eventos_adversos.tipo_id', 12)
        ->whereIn('eventos_adversos.estado_id', [48,49]);

        $eventos = $eventosConsulta->get()->toArray();

        $get_eventos = [];

        foreach ($eventos as $key => $reporte) {
            $get_eventos[$key] = $reporte;

            foreach ($reporte["pre_analisis"] as $accion) {
                $a = 1;
                if(count($accion["accion_mejoras"]) < 1){
                    unset($get_eventos[$key]);
                }else{
                    $banderaExiste = false;
                    foreach ($accion["accion_mejoras"] as $key4) {
                        $key4['responsables'] = str_replace(array('[', ']', '"'), array('', '', ''), $key4['responsables']);
                        if(in_array($nameUser, $key4['responsables'])){
                            $banderaExiste = true;
                        }
                        $a++;
                    }
                    if($banderaExiste == false){
                        unset($get_eventos[$key]);
                    }
                }
            }
        }

        rsort($get_eventos);

        return response()->json($get_eventos, 200);
    }

    public function savePlanMejora(Request $request)
    {
        Acciones_mejoras::where('id', $request->accionMejora_id)->update([
            'observacion' => $request->observacion
        ]);

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $fileName[$i] = $file->getClientOriginalName();
                $filenametostore[$i] = '/adjuntosEventosAdversos/planMejoras/'.$request->accionMejora_id.'/'.time().'_'.$fileName[$i];
                Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                $adjunto = new AdjuntoEvento;
                $adjunto->nombre = $fileName[$i];
                $adjunto->ruta = $filenametostore[$i];
                $adjunto->accionmejora_id = $request->accionMejora_id;
                $adjunto->save();

                $i++;
            }

        }

        $accionesCount  = Acciones_mejoras::where('analisisevento_id', $request->analisisevento_id)->count();
        $accionesCobObservacion = Acciones_mejoras::where('analisisevento_id', $request->analisisevento_id)->whereNotNull('observacion')->count();

        if($accionesCount == $accionesCobObservacion){
            Eventos_adverso::where('id', $request->eventoadverso_id)->update([
                'estado_id' => 49
            ]);
        }

        return response()->json([
            'message' => 'Soportes de Plan de Mejora guardado con exito!',
        ], 200);

    }

    public function buscarAnalisis($id){
        try {
            $get_eventos = Eventos_adverso::select(
                'eventos_adversos.id',
                'e.nombre as nombreEvento',
                'e.id as eventoId',
                'p.Num_Doc',
                DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ', p.Segundo_Ape,' ',p.Primer_Ape) as nombre_completo"),
                'eventos_adversos.clasificacion',
                'eventos_adversos.created_at',
                'eventos_adversos.paciente_id',
                'eventos_adversos.relacion',
                'p.Edad_Cumplida',
                'p.sexo',
                'sed.Nombre as sede_ocurrencia',
                'sede.Nombre as sede_reportante',
                'eventos_adversos.fecha_ocurrencia',
                'd.Producto as medicamento',
                'eventos_adversos.fecha_venci_medicamento',
                'eventos_adversos.lote_medicamento',
                'eventos_adversos.invima_medicamento',
                'eventos_adversos.referencia',
                'eventos_adversos.modelo',
                'eventos_adversos.serial',
                'eventos_adversos.invima_dispositivo',
                'de.Producto as dispositivo',
                'eventos_adversos.descripcion_hechos',
                'eventos_adversos.accion_tomada',
                'u.name',
                'u.apellido',
                'eventos_adversos.clasificacion_invima',
                'eventos_adversos.dosis_medicamento',
                'eventos_adversos.medida_medicamento',
                'eventos_adversos.via_medicamento',
                'eventos_adversos.frecuencia_medicamento',
                'eventos_adversos.infusion_medicamento',
                'cl.nombre as nombreClasificacion',
                'cl.id as clasificacionId',
                'te.nombre as nombreTipoevento',
                'te.id as tipoeventoId',
                'u.email',
                'eventos_adversos.nombre_equipo',
                'eventos_adversos.marca_equipo',
                'eventos_adversos.modelo_equipo',
                'eventos_adversos.serie_equipo',
                'eventos_adversos.placa_equipo',
                'ent.nombre as entidad',
                'es.Nombre as estado',
                'es.id as estado_id',
                'eventos_adversos.lote_dispositivo',
                'eventos_adversos.otro_evento',
                'eventos_adversos.servicio_ocurrencia',
                'eventos_adversos.servicio_reportante',
                'eventos_adversos.nivel_priorizacion',
                'asignado_eventos.created_at as fecha_asigna',
                'eventos_adversos.priorizacion',
                'analisis.fecha_analisis',
                'analisis.cronologia',
                'analisis.acciones_inseguras',
                'analisis.clasificacion',
                'analisis.metodologia',
                'analisis.que_fallo',
                'analisis.porque_fallo',
                'analisis.que_causo',
                'analisis.accion_implemento',
                'analisis.despues_adminmedicamento',
                'analisis.factores_explicarelevento',
                'analisis.desaparecio_suspendermedicamento',
                'analisis.reaccion_medicamentosospechoso',
                'analisis.ampliar_informacionpaciente',
                'analisis.evaluacion_causalidad',
                'analisis.clasificacion_invima',
                'analisis.seriedad',
                'analisis.fecha_muerte',
                'analisis.desenlace_evento',
                'analisis.causas_esavi',
                'analisis.asociacion_esavi',
                'analisis.ventana_mayoriesgo',
                'analisis.evidencia_asociacioncausal',
                'analisis.factores_esavi',
                'analisis.farmaco_cinetica',
                'analisis.condiciones_farmacocinetica',
                'analisis.prescribio_manerainadecuada',
                'analisis.medicamento_entrenamiento',
                'analisis.potenciales_interacciones',
                'analisis.notificacion_refieremedicamento',
                'analisis.problema_biofarmaceutico',
                'analisis.deficiencias_sistemas',
                'analisis.factores_asociados',
                'analisis.medicamento_manerainadecuada',
                'analisis.acciones_inseguras',
                'analisis.elemento_funcion',
                'analisis.modo_fallo',
                'analisis.efecto',
                'analisis.npr',
                'analisis.acciones_propuestas',
                'analisis.id as analisisevento_id',
                'analisis.descripcion_consecuencias',
                'analisis.accion_resarcimiento',
                'analisis.requiere_reporte_ente',
                'analisis.requiere_marca_especifica'
                )
            ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id')
            ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id')
            ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id')
            ->join('sedeproveedores as sed', 'eventos_adversos.sede_ocurrencia','sed.id')
            ->join('users as u', 'eventos_adversos.user_id', 'u.id')
            ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante','sede.id')
            ->join('estados as es', 'eventos_adversos.estado_id', 'es.id')
            ->leftjoin('clasificacion_eventos as cl', 'eventos_adversos.clasificacionevento_id', 'cl.id')
            ->leftjoin('tipo_eventos as te', 'eventos_adversos.tipoevento_id', 'te.id')
            ->leftjoin('detallearticulos as d', 'eventos_adversos.detallearticulo_id', 'd.id')
            ->leftjoin('detallearticulos as de', 'eventos_adversos.dispositivo', 'de.id')
            ->join('asignado_eventos', 'asignado_eventos.eventosadverso_id', 'eventos_adversos.id')
            ->leftjoin('analisis_eventos as analisis', 'analisis.eventosadverso_id', 'eventos_adversos.id')
            ->where('eventos_adversos.id', $id)
            ->first();

            return response()->json($get_eventos, 200);
        } catch (\Throwable $th) {
            if($th -> getCode()){
                return response()->json(['message' => $th->getMessage()], $th->getCode());
            }else{
                return response()->json(['message' => $th->getMessage()], 400);
            }
        }

    }

    public function getAccion_inseguras($id)
    {
        try {
            $Accion_inseguras = Acciones_inseguras::select('acciones_inseguras.analisisevento_id', 'acciones_inseguras.id' , 'acciones_inseguras.name',
                        'acciones_inseguras.condiciones_paciente',  'acciones_inseguras.metodos', 'acciones_inseguras.colaborador',
                        'acciones_inseguras.equipo_trabajo', 'acciones_inseguras.ambiente', 'acciones_inseguras.recursos',
                        'acciones_inseguras.contexto')
                    ->where('acciones_inseguras.analisisevento_id',$id)->get();
            return response()->json($Accion_inseguras, 200);
        } catch (\Throwable $th) {
            if($th -> getCode()){
                return response()->json(['message' => $th->getMessage()], $th->getCode());
            }else{
                return response()->json(['message' => $th->getMessage()], 400);
            }
        }
    }

    public function getAccion_mejoras($id)
    {
        try {
            $Accion_mejoras = Acciones_mejoras::select('acciones_mejoras.analisisevento_id', 'acciones_mejoras.id', 'acciones_mejoras.nombre',
            'acciones_mejoras.responsables','acciones_mejoras.fecha_cumplimiento','acciones_mejoras.fecha_seguimiento',
            'acciones_mejoras.estado')
                    ->where('acciones_mejoras.analisisevento_id',$id)->get();
            return response()->json($Accion_mejoras, 200);
        } catch (\Throwable $th) {
            if($th -> getCode()){
                return response()->json(['message' => $th->getMessage()], $th->getCode());
            }else{
                return response()->json(['message' => $th->getMessage()], 400);
            }
        }
    }



}
