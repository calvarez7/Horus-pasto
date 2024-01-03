<?php

namespace App\Http\Controllers\Imagenologia;

use App\User;
use App\Modelos\Cita;
use App\Modelos\Detallecita;
use App\Modelos\Tipo_evento;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Examenfisico;
use App\Modelos\Imagenologia;
use App\Modelos\Detallearticulo;
use App\Modelos\Eventos_adverso;
use App\Modelos\Adjuntos_general;
use App\Http\Controllers\Controller;
use App\Modelos\Bodega_imagenologia;
use App\Modelos\Gastos_imagenologia;
use App\Modelos\Estudio_imagenologia;
use App\Modelos\Especialidadtipoagenda;
use App\Modelos\Plantilla_imagenologia;
use Illuminate\Support\Facades\Validator;
use App\Modelos\Users_plantillaimagenologia;

class ImagenologiaController extends Controller
{
    public function gestion(Request $request){

        $userpermisos = auth()->user()->getAllPermissions();
        $permisos     = [];
        foreach ($userpermisos as $permiso) {
            array_push($permisos, $permiso->name);
        }

        $enfermeria = array_search("enfermeria.imagenologia", $permisos);
        $tecnologo = array_search("tecnologo.imagenologia", $permisos);
        $facturacion = array_search("facturacion.imagenologia", $permisos);
        if($enfermeria){
            $estado = 38;
        }else if($tecnologo){
            $estado = 39;
        }else if($facturacion) {
            $estado = 40;
        }else{
            return response()->json([
                'message' => 'No tienes el permiso!',
            ], 422);
        }

        $citaimg = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede', 's.Nombre', 'u.name as nameEspecialista',
            'u.apellido as apellidoEspecialista', 'p.Ut', 'det.tecnica', 'det.prioridad',
            'det.lado', 'det.lectura', 'det.tipo_paciente', 'det.fecha_orden',
            'det.ubicacion', 'det.cama', 'det.aislado', 'det.observacion_aislado',
            'img.Hallazgos', 'img.Conclusion', 'img.Indicacion', 'img.Notaclaratoria',
            'img.Tecnica as tecnica_radiologo', 'img.prioridad as prioridad_radiologo'])
            ->join('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('agendas as a', 'citas.Agenda_id', 'a.id')
            ->join('especialidade_tipoagenda as et', 'a.Especialidad_id', 'et.id')
            ->join('especialidades as e', 'et.Especialidad_id', 'e.id')
            ->join('tipo_agendas as ta', 'et.Tipoagenda_id', 'ta.id')
            ->join('consultorios as c', 'a.Consultorio_id', 'c.id')
            ->join('sedes as s', 'c.Sede_id', 's.id')
            ->join('agendausers as au', 'au.Agenda_id', 'a.id')
            ->join('users as u', 'au.Medico_id', 'u.id')
            ->join('estados as es', 'cp.Estado_id', 'es.id')
            ->join('detallecitas as det', 'det.Citapaciente_id', 'cp.id')
            ->leftjoin('imagenologias as img', 'img.Citapaciente_id', 'cp.id')
            ->with(['adjuntos_cita' => function ($query) {
                $query->select('adjuntos_generals.id', 'adjuntos_generals.cita_id',
                'adjuntos_generals.ruta', 'users.name', 'users.apellido', 'adjuntos_generals.created_at')
                ->join('users','adjuntos_generals.user_id','users.id')
                ->get();
            }])
            ->where('cp.Estado_id', 38)
            ->where('det.Estado_id', 4)
            ->where('citas.Hora_Inicio','>=',$request->fecha_inicio)
            ->where('citas.Hora_Inicio','<=',$request->fecha_fin.' 23:59:59');
            if(isset($request->documento)){
                $citaimg->where('p.Num_Doc', $request->documento);
            }
            if(isset($request->medico)){
                $citaimg->where('u.id', $request->medico);
            }
            if(isset($request->sede)){
                $citaimg->where('s.Nombre', $request->sede);
            }
            if(isset($request->tipocita)){
                $citaimg->where('ta.name', 'like', '%'.$request->tipocita.'%');
            }
            $citas = $citaimg->get();

            foreach ($citas as $cita) {
                $observacions = Examenfisico::select('examenfisicos.nota', 'users.name',
                'users.apellido', 'examenfisicos.created_at')
                ->join('users', 'examenfisicos.user_id', 'users.id')
                ->where('Citapaciente_id', $cita->cita_paciente_id)
                ->where('tipo', 'admin')
                ->orderBy('examenfisicos.created_at', 'ASC')
                ->get();
                $cita['observacion'] = $observacions;
            }

        return response()->json($citas, 200);
    }

    public function saveEnfermeria(Request $request){

        $validate = Validator::make($request->all(), [
            'filtracion' => 'required',
            'oxigeno' => 'required',
            'presion' => 'required',
            'frecuenciaCardiaca' => 'required',
            'peso' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $enfermeria = new Examenfisico;
        $enfermeria->Citapaciente_id = $request->cita_paciente_id;
        $enfermeria->user_id = auth()->id();
        $enfermeria->tasa_filtracion = $request->filtracion;
        $enfermeria->Frecucardiaca = $request->frecuenciaCardiaca;
        $enfermeria->Frecurespiratoria = $request->frecuenciaRespiratoria;
        $enfermeria->nota = $request->nota;
        $enfermeria->Saturacionoxigeno = $request->oxigeno;
        $enfermeria->Peso = $request->peso;
        $enfermeria->Presionarterialmedia = $request->presion;
        $enfermeria->Temperatura = $request->temperatura;
        $enfermeria->tipo = $request->tipo;
        $enfermeria->save();

        return response()->json([
            'message' => 'Historia guardada con exito!',
        ], 201);
    }

    public function saveInsumos(Request $request){

        $insumo_id = [];
        $cantidad = [];
        $id = 0;
        foreach ($request->obj as $det) {
            $insumo_id[$id] = $det["id"];
            $cantidad[$id] = $det["cantidad_insumo"];
            $id++;
        }
        for ($id = 0; $id < count($insumo_id); $id++) {
            $gastos = new Gastos_imagenologia;
            $gastos->cita_paciente_id = $request->cita_paciente;
            $gastos->bodegaimagenologia_id = $insumo_id[$id];
            $gastos->user_id = auth()->id();
            $gastos->estado_id = 1;
            $gastos->cantidad = $cantidad[$id];
            $gastos->save();
        }

        return response()->json([
            'message' => 'Insumos guardados con exito!',
        ], 201);
    }

    public function createinsumo(Request $request){

        Bodega_imagenologia::create([
            'nombre' => $request->nombre,
            'grupo_id' => 2,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Insumo creado con exito!',
        ], 201);

    }

    public function insumos()
    {
        $insumos = Bodega_imagenologia::select(['id', 'nombre'])
        ->where('estado_id', 1)
        ->where('grupo_id', 2)
        ->get();

        foreach ($insumos as $ins) {
            $ins['cantidad_insumo'] = "";
        }

        return response()->json($insumos, 201);
    }

    public function createmedicamento(Request $request){

        Bodega_imagenologia::create([
            'nombre' => $request->nombre,
            'grupo_id' => 1,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Medicamento creado con exito!',
        ], 201);

    }

    public function medicamentos()
    {
        $medicamentos = Bodega_imagenologia::select(['id', 'nombre'])
        ->where('estado_id', 1)
        ->where('grupo_id', 1)
        ->get();

        return response()->json($medicamentos, 201);
    }

    public function saveEventos(Request $request){

        $validate = Validator::make($request->all(), [
            'tipoevento' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $evento_adverso = new Eventos_adverso;
        $evento_adverso->evento_id = $request->tipoevento;
        $evento_adverso->cita_paciente_id = $request->cita_paciente_id;
        $evento_adverso->bodegaimagenologia_id = $request->medicamento_evento;
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
        $evento_adverso->tipo_id = 11;
        $evento_adverso->save();

        return response()->json([
            'message' => 'Evento guardado con exito!',
        ], 201);
    }

    public function saveEstudio(Request $request){

        $validate = Validator::make($request->all(), [
            'exposicion' => 'required',
            'mas' => 'required',
            'kv' => 'required',
            'distancia' => 'required',
            'totaldosis' => 'required',
            'cita_paciente_id' => 'unique:estudio_imagenologias'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $estudio = new Estudio_imagenologia;
        $estudio->cita_paciente_id = $request->cita_paciente_id;
        $estudio->user_id = auth()->id();
        $estudio->cantidad = $request->cantidad;
        $estudio->via = $request->via;
        $estudio->peso = $request->peso;
        $estudio->tiempo = $request->tiempo;
        $estudio->exposicion = $request->exposicion;
        $estudio->mas = $request->mas;
        $estudio->kv = $request->kv;
        $estudio->distancia = $request->distancia;
        $estudio->foco = $request->foco;
        $estudio->total_dosis = $request->totaldosis;
        $estudio->observacion = $request->observacion;
        $estudio->save();

        return response()->json([
            'message' => 'Estudio guardado con exito!',
        ], 201);
    }

    public function acomuladoDosis(Request $request){

        $acomulado = Estudio_imagenologia::select('estudio_imagenologias.total_dosis as dosis')
        ->join('cita_paciente', 'estudio_imagenologias.cita_paciente_id', 'cita_paciente.id')
        ->where('cita_paciente.Paciente_id', $request->paciente_id)
        ->get();

        $total = 0;
        foreach ($acomulado as $numero) {
            $total += $numero["dosis"];
        }

        return response()->json($total, 201);
    }

    public function confirmarEnfermeria(Cita $cita, Request $request)
    {
        $cita_paciente = citapaciente::where('Cita_id', $cita->id)
            ->where('Paciente_id', $request->Paciente_id)
            ->whereIn('Estado_id', [18,38,8])
            ->first();

        $cita_paciente->update([
            'Estado_id' => 39,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $cita_paciente->id,
            "Usuario_id"      => auth()->id(),
            "Motivo"          => "Cita enviada a tecnologo",
            "Estado_id"       => 39,
        ]);
        return response()->json([
            'message' => 'Se confirmó enfermeria con exito!',
        ], 200);
    }

    public function confirmarTecnologo(Cita $cita, Request $request)
    {
        $cita_paciente = citapaciente::where('Cita_id', $cita->id)
            ->where('Paciente_id', $request->Paciente_id)
            ->whereIn('Estado_id', [18,39,38])
            ->first();

        $cita_paciente->update([
            'Estado_id' => 4,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $cita_paciente->id,
            "Usuario_id"      => auth()->id(),
            "Motivo"          => "Cita enviada a radiologo",
            "Estado_id"       => 39,
        ]);
        return response()->json([
            'message' => 'Se confirmó tecnologo con exito!',
        ], 200);
    }

    public function devolverAdmisiones(Cita $cita, Request $request)
    {
        $cita_paciente = citapaciente::where('Cita_id', $cita->id)
            ->where('Paciente_id', $request->Paciente_id)
            ->whereIn('Estado_id', [38,39,4,8] )
            ->first();

        $cita_paciente->update([
            'Estado_id' => 18,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $cita_paciente->id,
            "Usuario_id"      => auth()->id(),
            "Motivo"          => "Devuelto a admisiones",
            "Estado_id"       => 18,
        ]);
        return response()->json([
            'message' => 'Se devolvio a admisiones con exito!',
        ], 200);
    }

    public function observacionesyadjuntos_admisiones(Cita $cita){

        $observacion_adjuntos = Cita::select(['citas.id as id', 'cp.id as cita_paciente_id'])
        ->join('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
        ->with(['adjuntos_cita' => function ($query) {
            $query->select('adjuntos_generals.id', 'adjuntos_generals.cita_id',
            'adjuntos_generals.ruta', 'users.name', 'users.apellido', 'adjuntos_generals.created_at')
            ->join('users','adjuntos_generals.user_id','users.id')
            ->get();
        }])
        ->where('citas.id', $cita->id)
        ->get();

        foreach ($observacion_adjuntos as $cita) {
            $observacions = Examenfisico::select('examenfisicos.nota', 'users.name',
            'users.apellido', 'examenfisicos.created_at')
            ->join('users', 'examenfisicos.user_id', 'users.id')
            ->where('Citapaciente_id', $cita->cita_paciente_id)
            ->where('tipo', 'admin')
            ->orderBy('examenfisicos.created_at', 'ASC')
            ->get();
            $cita['observacion'] = $observacions;
        }

        return response()->json($observacion_adjuntos, 200);

    }

    public function notasEnfermeria(Request $request){

        $notas = Examenfisico::select(['examenfisicos.id', 'peso', 'Frecucardiaca', 'Frecurespiratoria',
        'Temperatura', 'Saturacionoxigeno', 'Presionarterialmedia', 'nota', 'tasa_filtracion',
        'users.name', 'users.apellido', 'examenfisicos.created_at', 'users.especialidad_medico'])
        ->join('users','examenfisicos.user_id','users.id')
        ->where('Citapaciente_id', $request->cita_paciente_id)
        ->where('tipo', 'historia')
        ->get();
        return response()->json($notas, 201);

    }

    public function estudioTecnologo(Request $request){

        $estudio = Estudio_imagenologia::select(['cantidad', 'via', 'peso', 'tiempo', 'exposicion',
        'mas', 'kv', 'distancia', 'foco', 'total_dosis', 'observacion', 'users.name', 'users.apellido',
        'users.especialidad_medico', 'estudio_imagenologias.created_at'])
        ->join('users','estudio_imagenologias.user_id','users.id')
        ->where('cita_paciente_id', $request->cita_paciente_id)
        ->first();

        return response()->json($estudio, 201);

    }

    public function plantillas(Request $request){

        $plantillas = Users_plantillaimagenologia::select(['pl.id','pl.nombre', 'pl.indicacion',
        'pl.tecnica', 'pl.hallazgos', 'pl.conclusion', 'pl.notaclaratoria'])
        ->join('plantilla_imagenologias as pl', 'users_plantillaimagenologias.plantilla_id', 'pl.id')
        ->where('users_plantillaimagenologias.user_id', auth()->id());
        if(isset($request->id)){
            $plantillas->where('pl.id', $request->id);
        }

        return response()->json($plantillas->get(), 200);

    }

    public function informacionCita(Request $request){

        $citaimg = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede', 's.Nombre', 'u.name as nameEspecialista',
            'u.apellido as apellidoEspecialista', 'p.Ut', 'det.tecnica', 'det.prioridad',
            'det.lado', 'det.lectura', 'det.tipo_paciente', 'det.fecha_orden',
            'det.ubicacion', 'det.cama', 'det.aislado', 'det.observacion_aislado'])
            ->join('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('agendas as a', 'citas.Agenda_id', 'a.id')
            ->join('especialidade_tipoagenda as et', 'a.Especialidad_id', 'et.id')
            ->join('especialidades as e', 'et.Especialidad_id', 'e.id')
            ->join('tipo_agendas as ta', 'et.Tipoagenda_id', 'ta.id')
            ->join('consultorios as c', 'a.Consultorio_id', 'c.id')
            ->join('sedes as s', 'c.Sede_id', 's.id')
            ->join('agendausers as au', 'au.Agenda_id', 'a.id')
            ->join('users as u', 'au.Medico_id', 'u.id')
            ->join('estados as es', 'cp.Estado_id', 'es.id')
            ->join('detallecitas as det', 'det.Citapaciente_id', 'cp.id')
            ->with(['adjuntos_cita' => function ($query) {
                $query->select('adjuntos_generals.id', 'adjuntos_generals.cita_id',
                'adjuntos_generals.ruta', 'users.name', 'users.apellido', 'adjuntos_generals.created_at')
                ->join('users','adjuntos_generals.user_id','users.id')
                ->get();
            }])
            ->where('cp.Estado_id', 8)
            ->where('det.Estado_id', 4)
            ->where('cp.id', $request->id)
            ->get();

            foreach ($citaimg as $cita) {
                $observacions = Examenfisico::select('examenfisicos.nota', 'users.name',
                'users.apellido', 'examenfisicos.created_at')
                ->join('users', 'examenfisicos.user_id', 'users.id')
                ->where('Citapaciente_id', $cita->cita_paciente_id)
                ->where('tipo', 'admin')
                ->orderBy('examenfisicos.created_at', 'ASC')
                ->get();
                $cita['observacion'] = $observacions;
            }

        return response()->json($citaimg, 200);

    }

    public function enviarcitaFacturacion(citapaciente $citapaciente){

        $citapaciente->update([
            'Estado_id' => 40,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $citapaciente->id,
            "Usuario_id"      => auth()->id(),
            "Motivo"          => "Cita enviada a facturación",
            "Estado_id"       => 40,
        ]);
        return response()->json([
            'message' => 'Se confirmó radiologo con exito!',
        ], 200);
    }

    public function saveTecnica(Request $request){

        $imagenologia = Imagenologia::where('Citapaciente_id', $request->citapaciente)->first();

        $imagenologia->update([
            'Tecnica' => $request->tecnica,
        ]);

        return response()->json([
            'message' => 'Se actualizo tecnica con exito!',
        ], 200);
    }

    public function insumosGuardados(Request $request){

        $gasto_insumos = Gastos_imagenologia::select('b.nombre', 'gastos_imagenologia.cantidad', 'gastos_imagenologia.bodegaimagenologia_id', 'gastos_imagenologia.id')
        ->join('bodega_imagenologias as b', 'b.id', 'gastos_imagenologia.bodegaimagenologia_id')
        ->where('gastos_imagenologia.cita_paciente_id', $request->citapaciente)
        ->where('gastos_imagenologia.estado_id', 1)
        ->get();

        return response()->json($gasto_insumos, 200);
    }

    public function cambiarEstadoinsumo(Gastos_imagenologia $Gastos_imagenologia){

        $Gastos_imagenologia->update([
            'estado_id' => 2,
            'actualizo_userid' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Se actualizo estado con exito!',
        ], 200);

    }

    public function tipoCita(){

        $tipocita = Especialidadtipoagenda::select('especialidade_tipoagenda.id' , 'e.Nombre', 'ta.name')
        ->join('especialidades as e', 'especialidade_tipoagenda.Especialidad_id', 'e.id')
        ->join('tipo_agendas as ta', 'especialidade_tipoagenda.Tipoagenda_id', 'ta.id')
        ->where('e.id', 58)
        ->get();

        return response()->json($tipocita, 200);
    }

    public function userImagenologia(){

        $users = User::where('estado_user', 1)
        ->wherehas("roles", function ($q) {$q->where("name", "Imagenologia");})
        ->get();
        return response()->json($users, 200);

    }

    public function createPlantilla(Request $request){

        $validate = Validator::make($request->all(), [
            'nombre' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $plantilla = new Plantilla_imagenologia;
        $plantilla->nombre = $request->nombre;
        $plantilla->indicacion = $request->indicacion;
        $plantilla->tecnica = $request->tecnica;
        $plantilla->hallazgos = $request->hallazgos;
        $plantilla->conclusion = $request->conclusion;
        $plantilla->notaclaratoria = $request->nota;
        $plantilla->save();

        return response()->json([
            'message' => 'Plantilla creada con exito!',
        ], 201);

    }

    public function allPlantilla(){

        $plantillas = Plantilla_imagenologia::all();
        return response()->json($plantillas, 200);

    }

    public function addplantillaUser(Request $request){

        $plantilla_id = [];
        $id = 0;
        foreach ($request->plantilla_id as $p_id) {
            $plantilla_id[$id] = $p_id;
            $id++;
        }
        for ($id = 0; $id < count($plantilla_id); $id++) {
            $user_plantilla = new Users_plantillaimagenologia;
            $user_plantilla->user_id = $request->user_id;
            $user_plantilla->plantilla_id = $plantilla_id[$id];
            $user_plantilla->estado_id = 1;
            $user_plantilla->save();
        }

        return response()->json([
            'message' => 'Plantilla agregada a usuario con exito!',
        ], 201);
    }

    public function allPlantillaUsers(Request $request){

        $user_plantilla = Users_plantillaimagenologia::select('pl.nombre')
        ->join('plantilla_imagenologias as pl', 'users_plantillaimagenologias.plantilla_id', 'pl.id')
        ->where('users_plantillaimagenologias.user_id', $request->id)
        ->get();

        return response()->json($user_plantilla, 200);
    }

    public function addnotaclaratoria(Request $request){

        $enfermeria = new Examenfisico;
        $enfermeria->Citapaciente_id = $request->cita_paciente_id;
        $enfermeria->user_id = auth()->id();
        $enfermeria->nota = $request->nota;
        $enfermeria->tipo = 'notaclaratoria';
        $enfermeria->save();

        return response()->json([
            'message' => 'Nota aclaratoria guardada con exito!',
        ], 201);
    }

    public function notasAclaratorias(Request $request){

        $notasAclaratorias = Examenfisico::select(['examenfisicos.id', 'examenfisicos.nota',
        'users.name', 'users.apellido', 'examenfisicos.created_at', 'users.especialidad_medico'])
        ->join('users','examenfisicos.user_id','users.id')
        ->where('Citapaciente_id', $request->citapaciente)
        ->where('tipo', 'notaclaratoria')
        ->get();
        return response()->json($notasAclaratorias, 201);

    }

}
