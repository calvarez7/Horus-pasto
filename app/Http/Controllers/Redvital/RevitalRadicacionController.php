<?php

namespace App\Http\Controllers\Redvital;


use PDF;
use ZipArchive;
use App\Modelos\Tipo;
use App\Modelos\Orden;
use App\Modelos\Cuporden;
use App\Modelos\Paciente;
use App\Formats\Medicamento;
use Illuminate\Http\Request;
use App\Modelos\TipoSolicitud;
use Illuminate\Support\Carbon;
use App\Formats\ServicioGestion;
use App\Modelos\Detaarticulorden;
use App\Modelos\RadicacionOnline;
use App\Modelos\TipoSolicitudUser;
use Illuminate\Support\Facades\DB;
use App\Formats\MedicamentoGestion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Formats\IncapacidadesGestion;
use Illuminate\Support\Facades\Storage;
use App\Modelos\Gestion_radicaciononline;
use Illuminate\Support\Facades\Validator;
use App\Modelos\Adjuntos_radicaciononline;

class RevitalRadicacionController extends Controller
{

    public function allTipoSolicitud(){

        $alltipos = TipoSolicitud::select(['tipos_solicitud.*', 'e.Nombre as estado'])
        ->join('estados as e', 'tipos_solicitud.estado_id', 'e.id')
        ->get();

        return response()->json($alltipos, 200);

    }


    public function getTipoSolicitud(){

        $tipos = TipoSolicitud::where('estado_id', 1)->get();
        return response()->json($tipos, 200);

    }

    public function getSolicitudUsers($solicitud){

        $tiposUser = TipoSolicitudUser::select(['u.id', 'u.name', 'u.apellido', 'u.cedula'])
        ->join('users as u', 'tipo_solicitud_users.user_id', 'u.id')
        ->where('tipo_solicitud_users.tiposolicitud_id', $solicitud)
        ->where('tipo_solicitud_users.estado_id', 1)
        ->get();

        return response()->json($tiposUser, 200);
    }


    public function inactivarUserSolicitud($user, Request $request){
        
        TipoSolicitudUser::where('tiposolicitud_id', $request->solicitud_id)
        ->where('user_id', $user)
        ->update([
            'estado_id' => 2
        ]);

        return response()->json(['message' => 'Usuario inactivado con exito!'], 201);

    }


    public function createSolicitud(Request $request){

        $existe = TipoSolicitud::where('nombre', $request->nombre)->first();
        if($existe){
            return response()->json(['message' => 'Tipo de solicitud ya existe!'], 401);
        }else{
            TipoSolicitud::create($request->all());
            return response()->json(['message' => 'Tipo de solicitud creada con exito!'], 201);
        }

    }

    public function updateSolicitud(Request $request){

        if($request->usuarios){

            foreach ($request->usuarios as $user) {

                $solicitudUser = TipoSolicitudUser::where('tiposolicitud_id', $request->id)->where('user_id', $user)->first();

                if($solicitudUser){
                    $solicitudUser->update([
                        'estado_id' => 1
                    ]);
                }else{
                    TipoSolicitudUser::create([
                        'tiposolicitud_id' => $request->id,
                        'user_id' => $user,
                        'estado_id' => 1
                    ]);
                }
                
            }

        }

        TipoSolicitud::where('id', $request->id)
        ->update([
            'descripcion' => $request->descripcion,
            'opcion1' => $request->opcion1,
            'opcion2'   => ($request->opcion1 == 'Gestión' || $request->opcion1 == 'Usuario' ? null : $request->opcion2),
            'estado_id' => ($request->estado == 'Activo' ? 1 : 2)
        ]);

        return response()->json(['message' => 'Tipo de solicitud actualizada con exito!'], 201);

    }

    public function radicacion(Request $request){

        $data = [];

        $radicado = RadicacionOnline::create([
            'tiposolicitud_id' =>  $request->solicitud_id,
            'estado_id'        => 18, 
            'paciente_id' => $request->paciente_id,
            'descripcion' => $request->descripcion,
            'ruta' => 'Web'
        ]);

        if(isset(Auth::user()->id)){

            $radicado->update([
                'ruta' => 'Interna'
            ]);

            Gestion_radicaciononline::create([
                'radicaciononline_id' =>  $radicado->id,
                'user_id'        => Auth::user()->id, 
                'tipo_id' => 3,
                'motivo' => 'Creo Radicado',
            ]);
        }
        
        $opcionSolicitud = TipoSolicitud::where('id', $request->solicitud_id)->first();

        if($opcionSolicitud->opcion1 == 'Medico Familia'){

            $paciente = Paciente::select(['Medicofamilia'])->where('id', $request->paciente_id)->first();

                if($paciente->Medicofamilia){

                    if($paciente->Medicofamilia != '' && is_numeric($paciente->Medicofamilia)){
                        Gestion_radicaciononline::create([
                            'radicaciononline_id' =>  $radicado->id,
                            'user_id'        => $paciente->Medicofamilia, 
                            'tipo_id' => 20,
                            'motivo' => 'Asignado',
                        ]);
                    }

                }else if($opcionSolicitud->opcion2 == 'Usuario'){

                    $tipoUsers = TipoSolicitudUser::select(['user_id'])->where('tiposolicitud_id', $request->solicitud_id)
                    ->where('estado_id', 1)
                    ->get();

                    if(count($tipoUsers) >= 1){
                        foreach ($tipoUsers as $user) {
                            Gestion_radicaciononline::create([
                                'radicaciononline_id' =>  $radicado->id,
                                'user_id'        => $user->user_id, 
                                'tipo_id' => 20,
                                'motivo' => 'Asignado',
                            ]);
                        } 
                    }
                }
        }else if($opcionSolicitud->opcion1 == 'Usuario'){

            $tipoUsers = TipoSolicitudUser::select(['user_id'])->where('tiposolicitud_id', $request->solicitud_id)
            ->where('estado_id', 1)
            ->get();

            if(count($tipoUsers) >= 1){
                foreach ($tipoUsers as $user) {
                    Gestion_radicaciononline::create([
                        'radicaciononline_id' =>  $radicado->id,
                        'user_id'        => $user->user_id, 
                        'tipo_id' => 20,
                        'motivo' => 'Asignado',
                    ]);
                }
            }
            
        }

        foreach($request->all() as $key => $adjuntos){

            if(is_array($adjuntos)){

                $i = 0;
                foreach ($adjuntos as $file) {

                    $fileName[$i] = $file->getClientOriginalName();
                    $filenametostore[$i] = '/adjuntosradicacion/'.$request->documento.'/'.time().'_'.$request->solicitud_nombre.'_'.$fileName[$i];
                    Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                    $adjunto = new Adjuntos_radicaciononline;
                    $adjunto->nombre = $fileName[$i];
                    $adjunto->ruta = $filenametostore[$i];
                    $adjunto->radicaciononline_id = $radicado->id;
                    $adjunto->save();

                    $i++;
                }

            }

        }

        $data['radicado'] = $radicado->id;
        $data['solicitud'] = $request->solicitud_nombre;
        $data['solicitud_id'] = $request->solicitud_id;

        return response()->json($data, 201);

    }


    public function getRadicadosPaciente(Request $request){

        $solicitudesPaciente = RadicacionOnline::select(['radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
        'es.Nombre as estado'])
        ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
        ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
        ->with(['gestion' => function ($query) {
            $query->select('radicaciononline_id', 'motivo', 'gestion_radicaciononlines.created_at', 
            'gestion_radicaciononlines.id', 'u.name', 'u.apellido')
            ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
            ->with(['adjuntosgestion' => function ($query) {
                $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                ->get();
            }])
            ->where('gestion_radicaciononlines.tipo_id', 21)
            ->get();
        }])
        ->with(['adjuntoradicado' => function ($query) {
            $query->select('radicaciononline_id', 'ruta', 'nombre')
            ->get();
        }])
        ->where('radicacion_onlines.paciente_id', $request->paciente_id)
        ->where('radicacion_onlines.created_at', '>=',$request->fecha.' 00:00:00.000')
        ->get();

        return response()->json($solicitudesPaciente, 201);

    }

    public function comentar(Request $request)
    {
        $tipo = Tipo::where('Nombre', 'Comentario Publico')->first();

        $gestion   = new Gestion_radicaciononline();
        $gestion->radicaciononline_id = $request->radicaciononline_id;
        $gestion->tipo_id     = $tipo->id;
        $gestion->motivo      = $request->motivo;
        $gestion->save();

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $fileName[$i] = $file->getClientOriginalName();
                $filenametostore[$i] = '/adjuntosradicacion/'.$request->paciente_id.'/'.$request->radicaciononline_id.'/'.time().'_'.$fileName[$i];
                Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                $adjunto = new Adjuntos_radicaciononline;
                $adjunto->nombre = $fileName[$i];
                $adjunto->ruta = $filenametostore[$i];
                $adjunto->gestionradicaciononline_id = $gestion->id;
                $adjunto->save();

                $i++;
            }
        }


        $users = Gestion_radicaciononline::select(['users.name', 'users.email'])
        ->join('users', 'gestion_radicaciononlines.user_id', 'users.id')
        ->where('gestion_radicaciononlines.radicaciononline_id', $request->radicaciononline_id)
        ->where('tipo_id', 20)
        ->get();

        $email = Mail::send('email_radicacion',
            ['radicado_id' => $request->radicaciononline_id, 'tipo' => 'comentario'], function ($m) use ($users) {
                foreach ($users as $user) {
                        $m->to($user->email, $user->name)->subject('Notificación Radicación');
                }
        });

        return response()->json([
            'message' => '¡Ha comentado la solicitud con exito!',
        ], 201);
    }


    public function ordenMedicamentos($documento){

        $ordenesMedicamento = Orden::select(['ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id', 
        'to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant'),'c.valor as valorOrden'])
        ->leftjoin('cobros as c','c.orden_id','ordens.id')
        ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
        ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
        ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
        ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
        ->whereBetween('ordens.Fechaorden', [date('Y-m-d', strtotime(date("d-m-Y") . "- 1 month")), date('Y-m-d 23:59:59.00')])
        ->where('p.Num_Doc', $documento)
        ->whereIn('p.entidad_id', [1,3])
        ->whereIn('da.Estado_id', [1, 7])
        ->whereNotIn('ordens.Tiporden_id', [7])
        ->groupBy('ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id', 'to.Nombre','c.valor')
        ->havingRaw('COUNT(da.id) > 0')
        ->get();
        
        return response()->json($ordenesMedicamento, 201);

    }

    public function ordenservicios($documento){

        $newOrdens = [];

        $ordenes = Cuporden::select(
            'cupordens.id',
            'cupordens.created_at as FechaOrdenamiento',
            'cupordens.Cantidad',
            'cupordens.anexo3',
            'e.Nombre as Estado',
            'cupordens.Estado_id',
            'c.Codigo',
            'c.Nombre',
            'c.Requiere_auditoria as autorizacion',
            'o.id as orden',
            'o.Fechaorden',
            'cupordens.cancelacion',
            'cupordens.fecha_solicitada',
            'cupordens.fecha_sugerida',
            'cupordens.cancelacion',
            'cupordens.observaciones',
            'cupordens.responsable',
            'cupordens.motivo',
            'cupordens.causa',
            'cupordens.cirujano',
            'cupordens.especialidad',
            'cupordens.fecha_Preanestesia',
            'cupordens.fecha_cirugia',
            'cupordens.fecha_ejecucion',
            'cupordens.fecha_resultado',
            's.Nombre as Sede_Nombre',
            'o.Tiporden_id'
        )
            ->join('cups as c', 'cupordens.Cup_id' , 'c.id')
            ->join('ordens as o','cupordens.Orden_id', 'o.id')
            ->join('estados as e' , 'cupordens.Estado_id','e.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p' , 'cp.Paciente_id', 'p.id')
            ->join('tipocitas as t' , 'cp.Tipocita_id' , 't.id')
            ->leftjoin('users as u' , 'cp.Usuario_id' , 'u.id')
            ->leftjoin('autorizacions as a' , 'cupordens.id', 'a.Cuporden_id')
            ->leftJoin('sedeproveedores as s', 'cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->where('p.Num_doc',$documento)
            ->whereIn('o.Tiporden_id',[2,4])
            ->whereIn('cupordens.Estado_id', [1,7])
            ->get();

            foreach ($ordenes as $key => $orden) {
                $fecha1 = Carbon::now()->format('Y-m-d');
                $fecha2 = Carbon::parse($orden->Fechaorden);
                $diasDiferencia = $fecha2->diffInDays($fecha1);
                if ($diasDiferencia < 0){
                    $diasDiferencia = 0;
                }
                $orden['dias'] = $diasDiferencia;

                if($orden->Tiporden_id == 2){
                    if(intval($orden->dias) <= 30){
                        $newOrdens[] = $orden;
                    }
                }else{
                    if(intval($orden->dias) <= 60){
                        $newOrdens[] = $orden;
                    }
                }
            }


        return response()->json($newOrdens);
    }

    public function incapacidades($documento){

        $incapacidades = DB::select("exec dbo.Incapacidadespacientes" . " $documento");
        return response()->json($incapacidades);

    }

    public function printMedicamentos(Request $request){
      
        $articulos = Detaarticulorden::select('detaarticulordens.*', 'cs.Codigo as Codigo', 'cs.Nombre as medicamento', 'cs.unidadMedida as unidadMedida', 'cs.concentracion as concentracion','dc.id as cobro')
        ->join('codesumis as cs', 'detaarticulordens.Codesumi_id', 'cs.id')
        ->leftjoin('detalle_cobros as dc','dc.medicamento_orden_id','detaarticulordens.id')
        ->where('detaarticulordens.Orden_id', $request->Orden_id)
        ->whereIn('detaarticulordens.Estado_id', [1, 7, 35])
        ->get();
        
        return response()->json($articulos, 201);

    }

    function print(Request $request) {

        if (!empty($request->fecha_solicitud)) {
            if ($request->type == 'otros') {
                $fecha_estimada = date("Y-m-d", strtotime("+" . 1 . " months", strtotime($request->fecha_solicitud)));
            } elseif($request->type == 'formula'){
                $orden = Orden::find($request->orden_id);
                if($orden){
                    if($orden->Estado_id == 18){
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 2 . " months", strtotime($orden->Fechaorden)));
                    }else{
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 28 . " days", strtotime($orden->Fechaorden)));
                    }
                }
            } else {
                if (empty($request->numero)) {$request->numero = 1;}
                $fecha_estimada = date("Y-m-d", strtotime("+" . strval(intval($request->numero) * 28) . " day"));
            }
        } else {
            if ($request->type == 'otros') {
                $fecha_estimada = date("Y-m-d", strtotime("+" . 1 . " months"));
            } elseif($request->type == 'formula'){
                $orden = Orden::find($request->orden_id);
                if($orden){
                    if($orden->Estado_id == 18){
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 2 . " months", strtotime($orden->Fechaorden)));
                    }else{
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 28 . " days", strtotime($orden->Fechaorden)));
                    }
                }
            }else{
                if (empty($request->numero)) {$request->numero = 1;}
                $fecha_estimada = date("Y-m-d", strtotime("+" . strval(intval($request->numero) * 28) . " day"));
            }
        }

        $fecha_inicio = date("Y-m-d", strtotime("+" . strval((intval($request->numero) * 28) - 27) . " day"));

        $data = [
            //Radium HC
            'Indicacion'              => $request->Indicacion,
            'Hallazgos'               => $request->Hallazgos,
            'Conclusion'              => $request->Conclusion,
            'Notaclaratoria'          => $request->Notaclaratoria,
            'Tecnica'                 => $request->Tecnica,
            'Cups'                    => $request->Cups,
            'Radiologo'               => $request->Radiologo,
            'Regradiologo'            => $request->Regradiologo,
            'Firmaradiologo'          => $request->Firmaradiologo,
            'Dx'                      => $request->Dx,
            'Sexo'                    => $request->Sexo,
            'Edad_Cumplida'           => $request->Edad_Cumplida,
            'Nombrepaciente'          => $request->Nombrepaciente,
            'Num_Doc'                 => $request->Num_Doc,
            'Fecha_Naci'              => $request->Fecha_Naci,
            'Departamento'            => $request->Departamento,
            'Telefono'                => $request->Telefono,
            'Mpio_Afiliado'           => $request->Mpio_Afiliado,
            'Ocupacion'               => $request->Ocupacion,
            'Nombreacompanante'       => $request->Nombreacompanante,
            'Telefonoacompanante'     => $request->Telefonoacompanante,
            'Nombreresponsable'       => $request->Nombreresponsable,
            'Telefonoresponsable'     => $request->Telefonoresponsable,
            'Parentesco'              => $request->Parentesco,
            'Aseguradora'             => $request->Aseguradora,
            'Tipovinculacion'         => $request->Tipovinculacion,
            'Vivecon'                 => $request->Vivecon,
            'Insumos'                 => $request->Insumos,
            'Notas'                   => $request->Notas,
            'Estudio'                 => $request->Estudio,
            'Notaclaratorias'         => $request->Notaclaratorias,

            //Horus HC
            'Provincapacidad'         => $request->Provincapacidad,
            'Oftalmologico'           => $request->Oftalmologico,
            'Genitourinario'          => $request->Genitourinario,
            'Otorrinoralingologico'   => $request->Otorrinoralingologico,
            'Linfatico'               => $request->Linfatico,
            'Osteomioarticular'       => $request->Osteomioarticular,
            'Neurologico'             => $request->Neurologico,
            'Cardiovascular'          => $request->Cardiovascular,
            'Tegumentario'            => $request->Tegumentario,
            'Respiratorio'            => $request->Respiratorio,
            'Endocrinologico'         => $request->Endocrinologico,
            'Gastrointestinal'        => $request->Gastrointestinal,
            'Norefiere'               => $request->Norefiere,
            'NombreAntecedente'       => $request->NombreAntecedente,
            'logo'                    => $request->logo,
            'identificacion'          => $request->Num_Doc,
            'Cédula'                  => $request->Cédula,
            'Fecha_Nacimiento'        => $request->Fecha_Nacimiento,
            'Departamento'            => $request->Departamento,
            'Municipio_Afiliado'      => $request->Municipio_Afiliado,
            'Direccion_Residencia'    => $request->Direccion_Residencia,
            'Laboraen'                => $request->Laboraen,
            'Motivoconsulta'          => $request->Motivoconsulta,
            'Enfermedadactual'        => $request->Enfermedadactual,
            'Resultayudadiagnostica'  => $request->Resultayudadiagnostica,
            'tratamientoncologico'    => $request->tratamientoncologico,
            'Fechacreaincapacidad'    => $request->Fechacreaincapacidad,
            'tel'                     => $request->tel,
            'hj'                      => $request->hj,
            'Proveedor'               => $request->Proveedor,
            'Tipo_Afiliado'           => $request->Tipo_Afiliado,
            'afilia'                  => $request->afilia,
            'especialidad_medico'     => $request->especialidad_medico,
            'Rmedico'                 => $request->Rmedico,
            'Ut'                      => $request->Ut,
            'ips1'                    => $request->ips1,
            'ma'                      => $request->ma,
            'TTipod'                  => $request->TTipod,
            'Tipocita_id'             => $request->Tipocita_id,
            'cirujiaoncologica'       => $request->cirujiaoncologica,
            'ncirujias'               => $request->ncirujias,
            'iniciocirujia'           => $request->iniciocirujia,
            'fincirujia'              => $request->fincirujia,
            'recibioradioterapia'     => $request->recibioradioterapia,
            'inicioradioterapia'      => $request->inicioradioterapia,
            'finradioterapia'         => $request->finradioterapia,
            'nsesiones'               => $request->nsesiones,
            'intencion'               => $request->intencion,
            'Antropometricas'         => $request->Antropometricas,
            'Signos_Vitales'          => $request->Signos_Vitales,
            'Otros_Signos_Vitales'    => $request->Otros_Signos_Vitales,
            'Presión_Arterial'        => $request->Presión_Arterial,
            'Condiciongeneral'        => $request->Condiciongeneral,
            'Planmanejo'              => $request->Planmanejo,
            'Diagnostico_Principal'   => $request->Diagnostico_Principal,
            'Diagnostico_Secundario'  => $request->Diagnostico_Secundario,
            'Antecedentes'            => $request->Antecedentes,
            'Antecedentes_Parentesco' => $request->Antecedentes_Parentesco,
            // ONCOLOGIA
            'Patologiacancelactual'           => $request->Patologiacancelactual,
            'fdxcanceractual'                 => $request->fdxcanceractual,
            'flaboratoriopatologia'           => $request->flaboratoriopatologia,
            'tumorsegunbiopsia'               => $request->tumorsegunbiopsia,
            'localizacioncancer'              => $request->localizacioncancer,
            'Dukes'                           => $request->Dukes,
            'gleason'                         => $request->gleason,
            'Her2'                            => $request->Her2,
            'estadificacioninicial'           => $request->estadificacioninicial,
            'fechaestadificacion'             => $request->fechaestadificacion,
            'cirujiaoncologica'               => $request->cirujiaoncologica,
            'recibioradioterapia'             => $request->recibioradioterapia,
            // FIN ONCOLOGIA
            'Abdomen'                 => $request->Abdomen,
            'Agudezavisual'           => $request->Agudezavisual,
            'Cabezacuello'            => $request->Cabezacuello,
            'Cardiopulmonar'          => $request->Cardiopulmonar,
            'Examenmama'              => $request->Examenmama,
            'Examenmental'            => $request->Examenmental,
            'Extremidades'            => $request->Extremidades,
            'Frecucardiaca'           => $request->Frecucardiaca,
            'Frecurespiratoria'       => $request->Frecurespiratoria,
            'Genitourinario'          => $request->Genitourinario,
            'Neurologico'             => $request->Neurologico,
            'Ojosfondojos'            => $request->Ojosfondojos,
            'Osteomuscular'           => $request->Osteomuscular,
            'Pielfraneras'            => $request->Pielfraneras,
            'Reflejososteotendinos'   => $request->Reflejososteotendinos,
            'Tactoretal'              => $request->Tactoretal,
            'Dietasaludable'          => $request->Dietasaludable,
            'Suenoreparador'          => $request->Suenoreparador,
            'Duermemenoseishoras'     => $request->Duermemenoseishoras,
            'Altonivelestres'         => $request->Altonivelestres,
            'Actividadfisica'         => $request->Actividadfisica,
            'Frecuenciasemana'        => $request->Frecuenciasemana,
            'Duracion'                => $request->Duracion,
            'Fumacantidad'            => $request->Fumacantidad,
            'Fumainicio'              => $request->Fumainicio,
            'Fumadorpasivo'           => $request->Fumadorpasivo,
            'Cantidadlicor'           => $request->Cantidadlicor,
            'Licorfrecuencia'         => $request->Licorfrecuencia,
            'Consumopsicoactivo'      => $request->Consumopsicoactivo,
            'Psicoactivocantidad'     => $request->Psicoactivocantidad,
            'Estilovidaobservaciones' => $request->Estilovidaobservaciones,
            'Tipodiagnostico'         => $request->Tipodiagnostico,
            'Recomendaciones'         => $request->Recomendaciones,
            'Finalidad'               => $request->Finalidad,
            'finalidadTrans'          => $request->finalidadTrans,
            'Entidademite'            => $request->Entidademite,
            'Tipocita'                => $request->Tipocita,
            'Destinopaciente'         => $request->Destinopaciente,
            'Atendido_Por'            => $request->Atendido_Por,
            'Cedulamedico'            => $request->Cedulamedico,
            'Registromedico'          => $request->Registromedico,
            'nombre'                  => $request->Primer_Nom . ' ' . $request->Segundo_Nom . ' ' . $request->Primer_Ape . ' ' . $request->Segundo_Ape,
            'Nombre'                  => $request->Nombre,
            'Especialidad'            => $request->Especialidad,
            'Firma'                   => $request->Firma,
            //GINECOLOGICOS
            'Fechaultimamenstruacion' => $request->Fechaultimamenstruacion,
            'Gestaciones'             => $request->Gestaciones,
            'Partos'                  => $request->Partos,
            'Abortoprovocado'         => $request->Abortoprovocado,
            'Abortoespontaneo'        => $request->Abortoespontaneo,
            'Mortinato'               => $request->Mortinato,
            'Gestantefechaparto'      => $request->Gestantefechaparto,
            'Gestantenumeroctrlprenatal'  => $request->Gestantenumeroctrlprenatal,
            'Eutoxico'                => $request->Eutoxico,
            'Cesaria'                 => $request->Cesaria,
            'Cancercuellouterino'     => $request->Cancercuellouterino,
            'Ultimacitologia'         => $request->Ultimacitologia,
            'Resultado'               => $request->Resultado,
            'Menarquia'               => $request->Menarquia,
            'Ciclos'                  => $request->Ciclos,
            'Regulares'               => $request->Regulares,
            'Observaciongineco'       => $request->Observaciongineco,

            'marcapaciente'           => $request->marcapaciente,
            'Firma_Auditor'           => $request->Firma_Auditor,
            'edad'                    => $request->Edad_Cumplida,
            'sexo'                    => $request->Sexo,
            'ips'                     => $request->IPS,
            'direccion'               => $request->Direccion_Residencia,
            'aseguradora'             => $request->aseguradora,
            'correo'                  => $request->Correo,
            'telefono'                => $request->Telefono,
            'celular'                 => $request->Celular,
            'estado_afiliado'         => $request->Estado_Afiliado,
            'fecha_solicitud'         => $request->fecha_solicitud,
            'fecha_auditoria'         => $request->fecha_auditoria,
            'dx_principal'            => $request->dx_principal,
            'grupo_farmaceutico'      => $request->grupo_farmaceutico,
            'medicamento'             => $request->medicamento,
            'cantidad_dosis'          => $request->cantidad_dosis,
            'via'                     => $request->via,
            'frecuencia'              => $request->frecuencia,
            'unidad_tiempo'           => $request->unidad_tiempo,
            'duracion'                => $request->duracion,
            'cantidad_mensual'        => $request->cantidad_mensual,
            'renovable'               => $request->renovable,
            'Observaciones'           => $request->Observaciones,
            'Medicoordeno'            => $request->Medicoordeno,
            'sede'                    => $request->sede,
            'Direccion_Sede'          => $request->Direccion_Sede,
            'holi'                    => $request->holi,
            'prestador'               => $request->prestador,
            'servicio'                => $request->servicio,
            'medico_tratante'         => $request->medico_tratante,
            'medico_ordena'           => $request->medico_ordena,
            'autorizado'              => $request->autorizado,
            'firma_digital'           => $request->firma_digital,
            'fecha_formula'           => $request->fecha_formula,
            'autorizacion'            => $request->autorizacion,
            'medicamentos'            => $request->medicamentos,
            'servicios'               => $request->servicios,
            'FechaInicio'             => $request->FechaInicio,
            'CantidadDias'            => $request->CantidadDias,
            'FechaFinal'              => $request->FechaFinal,
            'Prorroga'                => $request->Prorroga,
            'Cie10'                   => $request->Cie10,
            'Contingencia'            => $request->Contingencia,
            'Descripcion'             => $request->Descripcion,
            'Firma'                   => $request->Firma,
            'TextCie10'               => $request->TextCie10,
            'order_id'                => $request->orden_id,
            'tipo_cita'               => $request->Tipo_cita,
            'Especialidad'            => $request->Especialidad,
            'Fecha_actual'            => date("Y-m-d H:i:s"),
            'Fecha_estimada'          => $fecha_estimada,
            'Fecha_inicio'            => $fecha_inicio,
            'total'                   => $request->total,
            'Datetimeingreso'         => $request->Datetimeingreso,
            'numero'                  => $request->numero,
            'page'                    => $request->page,
            'mensaje'                 => $request->mensaje,

            //Prestador
            'nombrePrestador'         => $request->nombrePrestador,
            'direccionPrestador'      => $request->direccionPrestador,
            'nitPrestador'            => $request->nitPrestador,
            'telefono1Prestador'      => $request->telefono1Prestador,
            'telefono2Prestador'      => $request->telefono2Prestador,
            'codigoHabilitacion'      => $request->codigoHabilitacion,
            'municipioPrestador'      => $request->municipioPrestador,
            'departamentoPrestador'   => $request->departamentoPrestador,

        ];

        if ($request->type == 'formula') {
            $pdf = new MedicamentoGestion();
            $pdf->generar($data,$request->SendEmail,"F O R M U L A  M E D I C A");
            exit();
        } elseif ($request->type == 'otros') {
            $pdf = new ServicioGestion();
            $pdf->generar($data,$request->SendEmail);
            exit();
        } elseif ($request->type == 'incapacidad') {
            $pdf = new IncapacidadesGestion();
            $pdf->generar($request->orden_id);
            exit();
        }

        $customPaper = array(0, 0, 792, 612);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download();
    }

    public function printServicios($codigoOrden){

        $servicios = Cuporden::select('cupordens.*','c.Codigo', 'c.Nombre','e.Nombre as Estado','p.Num_Doc as cedula','sp.Nombre as ubicacion','sp.Direccion as direccion', 'sp.Telefono1 as telefono')
            ->join('cups as c', 'cupordens.Cup_id' , 'c.id')
            ->join('ordens as o' , 'cupordens.Orden_id' , 'o.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p','cp.Paciente_id','p.id')
            ->join('estados as e','cupordens.Estado_id','e.id')
            ->leftjoin('autorizacions as a' , 'cupordens.id' , 'a.Cuporden_id')
            ->leftJoin('sedeproveedores as sp', 'cupordens.Ubicacion_id', 'sp.id')
            ->where('cupordens.Orden_id', $codigoOrden)
            ->whereIn('cupordens.Estado_id', [1,7])
            ->get();


        return response()->json($servicios);
    }

}
