<?php

namespace App\Http\Controllers\Helpdesk;

use App\User;
use App\Modelos\Tipo;
use App\Modelos\Areahelp;
use App\Modelos\Helpdesk;
use Illuminate\Http\Request;
use App\Modelos\Adjuntoshelp;
use App\Modelos\Gestions_help;
use App\Modelos\Asignado_helpdesk;
use App\Modelos\Sedes_sumimedical;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\Model_has_permission;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DateTime;

class HelpdeskController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'sede' => 'required',
            'asunto' => 'required',
            'descripcion' => 'required',
            'area' => 'required',
            'prioridad' => 'required',
            'categoria' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $helpdesk = new Helpdesk();
        $helpdesk->area_id = $request->area;
        $helpdesk->Sede = $request->sede;
        $helpdesk->Estado_id = 18;
        $helpdesk->Asunto = $request->asunto;
        $helpdesk->Descripcion = $request->descripcion;
        $helpdesk->categoria_id = $request->categoria;
        $helpdesk->prioridad = $request->prioridad;
        $helpdesk->save();

        $tipo = Tipo::where('Nombre', 'Solicitud')->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $helpdesk->id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $helpdesk->Asunto;
        $gestion->save();

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        if ($request->area == 3 | $request->area == 4 | $request->area == 5) {

            if ($request->sede == 'Clínica Victoriana') {

                $permiso = Permission::where('name', 'Helpdesk Clinica')->first('id');

                $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
                    ->join('users as us', 'us.id', 'model_has_permissions.model_id')
                    ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
                    ->where('per.id', $permiso->id)
                    ->distinct()
                    ->get();

                $helpid = $helpdesk->id;
                $asunto = $request->asunto;

                $email = Mail::send('email_helpdesk',
                    ['helpid' => $helpid, 'asunto' => $asunto], function ($m) use ($users) {
                        foreach ($users as $user) {
                            $m->to($user->email, $user->name)->subject('Notificación Helpdesk');
                        }
                    });

            } else {

                $area = Areahelp::where('id', $request->area)->first('Permission_id');

                $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
                    ->join('users as us', 'us.id', 'model_has_permissions.model_id')
                    ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
                    ->where('per.id', $area->Permission_id)
                    ->distinct()
                    ->get();

                $helpid = $helpdesk->id;
                $asunto = $request->asunto;

                $email = Mail::send('email_helpdesk',
                    ['helpid' => $helpid, 'asunto' => $asunto], function ($m) use ($users) {
                        foreach ($users as $user) {
                            $m->to($user->email, $user->name)->subject('Notificación Helpdesk');
                        }
                    });
            }
        } else {

            $area = Areahelp::where('id', $request->area)->first('Permission_id');

            $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
                ->join('users as us', 'us.id', 'model_has_permissions.model_id')
                ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
                ->where('per.id', $area->Permission_id)
                ->distinct()
                ->get();

            $helpid = $helpdesk->id;
            $asunto = $request->asunto;

            $email = Mail::send('email_helpdesk',
                ['helpid' => $helpid, 'asunto' => $asunto], function ($m) use ($users) {
                    foreach ($users as $user) {
                        $m->to($user->email, $user->name)->subject('Notificación Helpdesk');
                    }
                });

        }

        return response()->json([
            'message' => 'Solicitud generada con exito!', 'data' => $helpdesk
        ], 201);
    }

    public function pendientesUser()
    {
        $user = auth()->user()->id;

        $pendientes = Helpdesk::select('helpdesks.id as id', 'helpdesks.created_at as creado', 'helpdesks.Sede as sede',
            'estados.Nombre as estado', 'actividades_help.Nombre as actividad','actividades_help.id as Actividades_id', 'categorias_help.Nombre as categoria'
            ,'categorias_help.id as Categorias_id','helpdesks.Requerimiento','helpdesks.tiempo_estimado as tiempo_estimado',
            'areas_help.Nombre as area', 'helpdesks.Asunto as asunto', 'helpdesks.prioridad as prioridad', 'helpdesks.Descripcion as descripcion')
            ->join('estados', 'helpdesks.Estado_id', 'estados.id')
            ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
            ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
            ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
            ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'Helpdesk_id')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->where('tipos.Nombre', 'Solicitud')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->where('gestions_help.Tipo_id', 13)
            ->where('gestions_help.User_id', $user)
            ->where('helpdesks.Estado_id', '!=', 13)
            ->distinct()
            ->get();

        return response()->json($pendientes, 201);
    }

    public function pendientesArea(Request $request)
    {

        $fechaDesde = $request->desde;
        $fechaHasta = $request->hasta;
        $userpermisos = auth()->user()->getAllPermissions();
        $permisos = [];
        $per_name = [];
        foreach ($userpermisos as $permiso) {
            array_push($permisos, $permiso->id);
            array_push($per_name, $permiso->name);
        }

        $clinica_area = array_search("Helpdesk Clinica", $per_name);
        $admin_clinica = var_export($clinica_area, true);

        if ($request->estado == 'Pendientes') {
            $estado = [18];
        } else if ($request->estado == 'Asignadas') {
            $estado = [5];
        } else if ($request->estado == 'Gestionando') {
            $estado = [41];
        } else if ($request->estado == 'Re Abiertos') {
            $estado = [52];
        } else if ($request->estado == 'Anulados') {
            $estado = [26];
        } else {
            $estado = [14];
        }

        $compras_area = array_search("Compras Helpdesk", $per_name);
        $logistica_area = array_search("Logística Helpdesk", $per_name);
        $mensajeria_area = array_search("Mensajería Helpdesk", $per_name);

        $pendienteArea = Helpdesk::select('helpdesks.id as id', 'helpdesks.created_at as creado', 'helpdesks.Sede as sede',
            'estados.Nombre as estado', 'actividades_help.Nombre as actividad', 'categorias_help.Nombre as categoria', 'categorias_help.id as id_categoria',
            'areas_help.Nombre as area', 'helpdesks.Asunto as asunto', 'helpdesks.tiempo_estimado', 'helpdesks.prioridad as prioridad', 'helpdesks.Descripcion as descripcion', 'users.name as nombreUser',
            'users.email', 'areas_help.id as area_id')
            ->join('estados', 'helpdesks.Estado_id', 'estados.id')
            ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
            ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
            ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'Helpdesk_id')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->where('tipos.Nombre', 'Solicitud')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->where('gestions_help.Tipo_id', 13)
            ->whereIn('areas_help.Permission_id', $permisos)
            ->whereIn('helpdesks.Estado_id', $estado)
            ->distinct();
        // if ($admin_clinica == "false") {
        //     if ($compras_area != '' | $logistica_area != '' | $mensajeria_area != '') {
        //         $pendienteArea->where('helpdesks.Sede', '!=', 'Clínica Victoriana');
        //     }
        // } else {
        //     $pendienteArea->where('helpdesks.Sede', 'Clínica Victoriana');
        // }
        if ($request->area) {
            $pendienteArea->where('helpdesks.area_id', $request->area);
        }

        if ($request->categoria) {
            $pendienteArea->where('helpdesks.categoria_id', $request->categoria);
        }

        if ($request->sede) {
            $pendienteArea->where('helpdesks.Sede', $request->sede);
        }
        if ($request->desde & $request->hasta) {
            $pendienteArea->where('helpdesks.created_at', '>=', $fechaDesde)
                ->where('helpdesks.created_at', '<=', $fechaHasta . ' 23:59:59');
        }

        return response()->json($pendienteArea->get(), 201);
    }

    public function comentar(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required',
            'categoria' => 'required',
            'tipo_requerimiento' => 'required',
            'prioridad' => 'required',
            'tiempo_estimado' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        if ($request->gestionando == 'true') {

            $permiso = Permission::where('name', $request->responsable)->first('id');

            Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)
                ->where('permission_id', $permiso->id)
                ->update([
                    'estado_id' => 41,
                ]);

        }

        if ($request->gestionandoArea == 'true') {

            Helpdesk::where('id', $request->helpdesk_id)
                ->update([
                    'Estado_id' => 41,
                ]);

        }

        $tipo = Tipo::where('Nombre', $request->accion)->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->responsable = $request->responsable;
        $gestion->save();

        $helpdesk = Helpdesk::where('id',$request->helpdesk_id);
        if($request->tiempo_estimado === 'null'){
            $tiempo = new DateTime();
        }else{
            $tiempo = $request->tiempo_estimado;
        }
        $helpdesk->update([
        'categoria_id' => $request->categoria,
        'Requerimiento' => $request->tipo_requerimiento,
        'prioridad' => $request->prioridad,
        'tiempo_estimado' => $tiempo,
        ]);

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        if (($request->desde == 'miasignada' | $request->desde == 'solicitudarea') && $request->accion = 'Comentarios al Solicitante') {

            $user = Gestions_help::select(['gestions_help.Motivo', 'us.name', 'us.email'])
                ->join('users as us', 'gestions_help.User_id', 'us.id')
                ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                ->where('Helpdesk_id', $request->helpdesk_id)
                ->where('tipos.Nombre', 'Solicitud')
                ->get();

            $helpid_comentario = $request->helpdesk_id;
            $asunto = $user[0]->Motivo;

            $email = Mail::send('email_helpdesk',
                ['helpid_comentario' => $helpid_comentario, 'asunto' => $asunto], function ($m) use ($user) {
                    foreach ($user as $us) {
                        $m->to($us->email, $us->name)->subject('Notificación Helpdesk');
                    }
                });

        }

        if ($request->accion == 'Comentarios al Solicitante' && $request->desde == 'misolicitud') {

            $permisoAsignado = Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)->get(['permission_id']);

            if (empty($permisoAsignado[0])) {

                $help = Helpdesk::where('id', $request->helpdesk_id)->select('area_id', 'Asunto')->first();
                $area = Areahelp::where('id', $help->area_id)->first('Permission_id');

                $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
                    ->join('users as us', 'us.id', 'model_has_permissions.model_id')
                    ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
                    ->where('per.id', $area->Permission_id)
                    ->distinct()
                    ->get();

                $helpid_comentario = $request->helpdesk_id;
                $asunto = $help->Asunto;

                $email = Mail::send('email_helpdesk',
                    ['helpid_comentario' => $helpid_comentario, 'asunto' => $asunto], function ($m) use ($users) {
                        foreach ($users as $user) {
                            $m->to($user->email, $user->name)->subject('Notificación Helpdesk');
                        }
                    });

            } else {

                $gestion = Gestions_help::select('gestions_help.Motivo')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->where('Helpdesk_id', $request->helpdesk_id)
                    ->where('tipos.Nombre', 'Solicitud')
                    ->first();

                $perhelpid = [];
                foreach ($permisoAsignado as $per) {
                    array_push($perhelpid, $per->permission_id);
                }

                $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
                    ->join('users as us', 'us.id', 'model_has_permissions.model_id')
                    ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
                    ->whereIn('per.id', $perhelpid)
                    ->distinct()
                    ->get();

                $helpid_comentario = $request->helpdesk_id;
                $asunto = $gestion->Motivo;

                $email = Mail::send('email_helpdesk',
                    ['helpid_comentario' => $helpid_comentario, 'asunto' => $asunto], function ($m) use ($users) {
                        foreach ($users as $us) {
                            $m->to($us->email, $us->name)->subject('Notificación Helpdesk');
                        }
                    });

            }
        }

        return response()->json([
            'message' => '¡Ha comentado la solicitud con exito!',
        ], 201);
    }

    public function showcomentariosPublicos(Request $request)
    {
        $comentariosPublicos = Gestions_help::select('gestions_help.id', 'Helpdesk_id', 'tipos.Nombre as tipo', 'Motivo',
            'tipos.Descripcion as descripcionTipo', 'users.name', 'users.apellido', 'gestions_help.created_at',
            'gestions_help.responsable','categorias_help.Nombre as categoria','prioridad','tiempo_estimado')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->join('helpdesks','helpdesks.id','gestions_help.Helpdesk_id')
           ->join('categorias_help','categorias_help.id','helpdesks.categoria_id')
            ->with(['Adjuntos_helpdesks' => function ($query) {
                $query->select('Ruta', 'Gestion_id')
                    ->get();
            }])
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->where('tipos.Nombre', 'Comentarios al Solicitante')
            ->get();

        return response()->json($comentariosPublicos, 201);

    }

    public function showcomentariosPrivados(Request $request)
    {
        $comentariosPrivados = Gestions_help::select('gestions_help.id', 'Helpdesk_id', 'tipos.Nombre as tipo', 'Motivo',
            'tipos.Descripcion as descripcionTipo', 'users.name', 'users.apellido', 'gestions_help.created_at',
            'gestions_help.responsable','categorias_help.Nombre as categoria','prioridad','tiempo_estimado')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
           ->join('helpdesks','helpdesks.id','gestions_help.Helpdesk_id')
          ->join('categorias_help','categorias_help.id','helpdesks.categoria_id')
            ->with(['Adjuntos_helpdesks' => function ($query) {
                $query->select('Ruta', 'Gestion_id')
                    ->get();
            }])
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->where('tipos.Nombre', 'Comentarios Internos')
            ->get();

        return response()->json($comentariosPrivados, 201);

    }

    public function comentarioAnulado(Request $request)
    {

        $comentariosAnulados = Gestions_help::select('gestions_help.id', 'Helpdesk_id', 'tipos.Nombre as tipo', 'Motivo',
            'tipos.Descripcion as descripcionTipo', 'users.name', 'users.apellido', 'gestions_help.created_at',
            'gestions_help.responsable')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->with(['Adjuntos_helpdesks' => function ($query) {
                $query->select('Ruta', 'Gestion_id')
                    ->get();
            }])
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->where('tipos.Nombre', 'Anular')
            ->get();

        return response()->json($comentariosAnulados, 201);

    }

    public function solucion(Request $request, Helpdesk $helpdesk)
    {
        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $tipo = Tipo::where('Nombre', $request->accion)->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->save();

        if ($request->prioridad) {

            $helpdesk->Actividad_id = ($request->actividad == 'undefined' ? null : $request->actividad);
            $helpdesk->prioridad = $request->prioridad;
            $helpdesk->tiempo_estimado = $request->tiempo_estimado;
            $helpdesk->Requerimiento = $request->tipo_requerimiento;
            $helpdesk->categoria_id = ($request->categoria == 'undefined' ? null : $request->categoria);
            $helpdesk->Estado_id = 13;
            $helpdesk->save();

        }

        Helpdesk::where('id', $request->helpdesk_id)
            ->update([
                'Estado_id' => 13,
            ]);

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        $user = Gestions_help::select(['gestions_help.Motivo', 'us.name', 'us.email'])
            ->join('users as us', 'gestions_help.User_id', 'us.id')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->where('tipos.Nombre', 'Solicitud')
            ->get();

        $helpid = $request->helpdesk_id;
        $asuntoSolucionado = $user[0]->Motivo;

        $email = Mail::send('email_helpdesk',
            ['helpid' => $helpid, 'asuntoSolucionado' => $asuntoSolucionado], function ($m) use ($user) {
                foreach ($user as $us) {
                    $m->to($us->email, $us->name)->subject('Notificación Helpdesk');
                }
            });

        return response()->json([
            'message' => '¡Ha solucionado la solicitud con exito!',
        ], 201);
    }

    public function showSolucion(Request $request)
    {

        $solucion = Gestions_help::select('gestions_help.id', 'Helpdesk_id', 'tipos.Nombre as tipo', 'Motivo',
            'tipos.Descripcion as descripcionTipo', 'users.name', 'users.apellido', 'gestions_help.created_at')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->with(['Adjuntos_helpdesks' => function ($query) {
                $query->select('Ruta', 'Gestion_id')
                    ->get();
            }])
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->whereIn('tipos.Nombre', ['Solucionar', 'Anular'])
            ->get();

        return response()->json($solucion, 201);

    }

    public function respuesta(Request $request, Helpdesk $helpdesk)
    {
        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $permiso = Permission::where('name', $request->responsable)->first('id');

        Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)
            ->where('permission_id', $permiso->id)
            ->update([
                'estado_id' => 2,
            ]);

        $cuentaAsignados = Asignado_helpdesk::where('estado_id', 2)
            ->where('helpdesk_id', $request->helpdesk_id)
            ->count();

        $helpdesk_estado = Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)
            ->where('estado_id', '!=', 26)
            ->count();

        if ($cuentaAsignados == $helpdesk_estado) {

            Helpdesk::where('id', $request->helpdesk_id)
                ->update([
                    'Estado_id' => 14,
                ]);

            $area = Helpdesk::where('id', $request->helpdesk_id)->first('area_id');
            $areaHelp = Areahelp::where('id', $area->area_id)->first('Permission_id');

            $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
                ->join('users as us', 'us.id', 'model_has_permissions.model_id')
                ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
                ->where('per.id', $areaHelp->Permission_id)
                ->distinct()
                ->get();

            $helpCierre_id = $helpdesk->id;

            $email = Mail::send('email_helpdesk',
                ['helpCierre_id' => $helpCierre_id], function ($m) use ($users) {
                    foreach ($users as $user) {
                        $m->to($user->email, $user->name)->subject('Notificación Helpdesk');
                    }
                });
        }

        $tipo = Tipo::where('Nombre', $request->accion)->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->responsable = $request->responsable;
        $gestion->save();

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        return response()->json([
            'message' => '¡Ha solucionado la solicitud con exito!',
        ], 201);
    }

    public function devolver(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $permiso = Permission::where('name', $request->responsable)->first('id');

        Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)
            ->where('permission_id', $permiso->id)
            ->update([
                'estado_id' => 26,
            ]);

        $tipo = Tipo::where('Nombre', 'Comentarios Internos')->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->responsable = $request->responsable;
        $gestion->save();

        $searchAsignados = Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)
            ->whereNotin('estado_id', [2, 26])
            ->count();

        if ($searchAsignados == 0) {
            Helpdesk::where('id', $request->helpdesk_id)
                ->update([
                    'Estado_id' => 18,
                ]);
        }

        return response()->json([
            'message' => '¡Ha devuelto la solicitud con exito!',
        ], 201);
    }

    public function showRespuestas(Request $request)
    {

        $respuestas = Gestions_help::select('gestions_help.id', 'Helpdesk_id', 'tipos.Nombre as tipo', 'Motivo',
            'tipos.Descripcion as descripcionTipo', 'users.name', 'users.apellido', 'gestions_help.created_at',
            'gestions_help.responsable')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->with(['Adjuntos_helpdesks' => function ($query) {
                $query->select('Ruta', 'Gestion_id')
                    ->get();
            }])
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->where('tipos.Nombre', 'Respuesta')
            ->get();

        return response()->json($respuestas, 201);

    }

    public function asignar(Request $request, Helpdesk $helpdesk)
    {

        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $tipo = Tipo::where('Nombre', $request->accion)->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->save();

        if ($request->prioridad) {

            $helpdesk->Actividad_id = ($request->actividad == 'undefined' ? null : $request->actividad);
            $helpdesk->prioridad = $request->prioridad;
            $helpdesk->tiempo_estimado = $request->tiempo_estimado;
            $helpdesk->Requerimiento = $request->tipo_requerimiento;
            $helpdesk->categoria_id = ($request->categoria == 'undefined' ? null : $request->categoria);
            $helpdesk->Estado_id = 5;
            $helpdesk->save();

        }

        $permisoAsignado = Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)->get(['permission_id']);

        $perhelpid = [];
        foreach ($permisoAsignado as $per) {
            array_push($perhelpid, $per->permission_id);
        }

        $per_id = [];
        foreach ($request->responsable as $res) {
            array_push($per_id, $res);
        }

        $nuevosAsignados = array_diff($per_id, $perhelpid);

        if (isset($permisoAsignado)) {
            if (isset($nuevosAsignados)) {
                $permis_id = [];
                $id = 0;
                foreach ($nuevosAsignados as $permid) {
                    $permis_id[$id] = $permid;
                    $id++;
                }
                for ($id = 0; $id < count($permis_id); $id++) {
                    $asignar = new Asignado_helpdesk;
                    $asignar->permission_id = $permis_id[$id];
                    $asignar->helpdesk_id = $helpdesk->id;
                    $asignar->estado_id = 1;
                    $asignar->save();
                }
            }

            $updatestadopermiso = Asignado_helpdesk::where('helpdesk_id', $request->helpdesk_id)
                ->whereIn('permission_id', $per_id)
                ->update([
                    'estado_id' => 1,
                ]);

            Helpdesk::where('id', $request->helpdesk_id)
                ->update([
                    'Estado_id' => 5,
                ]);

        } else {
            $permiso_id = [];
            $id = 0;
            foreach ($request->responsable as $permisoid) {
                $permiso_id[$id] = $permisoid;
                $id++;
            }
            for ($id = 0; $id < count($permiso_id); $id++) {
                $asignar = new Asignado_helpdesk;
                $asignar->permission_id = $permiso_id[$id];
                $asignar->helpdesk_id = $helpdesk->id;
                $asignar->estado_id = 1;
                $asignar->save();
            }
        }

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
            ->join('users as us', 'us.id', 'model_has_permissions.model_id')
            ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
            ->whereIn('per.id', $per_id)
            ->distinct()
            ->get();

        $helpAsignar_id = $request->helpdesk_id;

        $email = Mail::send('email_helpdesk',
            ['helpAsignar_id' => $helpAsignar_id], function ($m) use ($users) {
                foreach ($users as $us) {
                    $m->to($us->email, $us->name)->subject('Notificación Helpdesk');
                }
            });

        return response()->json([
            'message' => '¡Ha asignado la solicitud con exito!',
        ], 201);
    }

    public function showAsignadosinArea(Request $request)
    {

        $showAsignados = Helpdesk::select(['helpdesks.id'])
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'gestions_help.Helpdesk_id', 'tipos.Descripcion as Nombretipo',
                    'gestions_help.Motivo as Motivo', 'users.name as Nombre', 'users.apellido as Apellido', 'gestions_help.created_at as Faccion')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->join('users', 'gestions_help.User_id', 'users.id')
                    ->where('tipos.Nombre', 'Asignar')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->with(['Permisohelpdesks' => function ($query) {
                $query->select('helpdesk_id', 'permissions.name as nombrePermiso', 'permissions.id as idPermiso')
                    ->join('permissions', 'asignado_helpdesks.permission_id', 'permissions.id')
                    ->whereIn('Estado_id', [1, 2, 41])
                    ->get();
            }])
            ->where('helpdesks.id', $request->helpdesk_id)
            ->get();

        return response()->json($showAsignados, 201);

    }

    public function anular(Request $request, Helpdesk $helpdesk)
    {

        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $tipo = Tipo::where('Nombre', $request->accion)->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->save();

        $helpdesk->Estado_id = 26;
        $helpdesk->save();

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        return response()->json([
            'message' => '¡Ha anulado la solicitud con exito!',
        ], 201);
    }

    public function reasignar(Request $request, Helpdesk $helpdesk)
    {

        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'accion' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $tipo = Tipo::where('Nombre', 'Re asignar')->first();

        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->save();

        $helpdesk->area_id = $request->area;
        $helpdesk->save();

        return response()->json([
            'message' => '¡Ha re asignado la solicitud con exito!',
        ], 201);
    }

    public function permisos()
    {
        $permisos = Permission::select('id', 'name')
            ->where('Tipo_id', 19)
            ->get();

        return response()->json($permisos, 201);
    }

    public function permisoUser()
    {

        $user = auth()->user();
        $permisos = $user->permissions;

        $permissoids = [];
        foreach ($permisos as $per) {
            array_push($permissoids, $per->id);
        }

        $namePermission = Permission::whereIn('id', $permissoids)
            ->where('Tipo_id', 19)
            ->get('name');

        return response()->json($namePermission, 201);

    }

    public function permisoAsignadoHelp(Request $request)
    {

        $namePermisoAsignado = Asignado_helpdesk::select('permissions.name', 'permissions.id')
            ->join('permissions', 'asignado_helpdesks.permission_id', 'permissions.id')
            ->where('asignado_helpdesks.helpdesk_id', $request->helpdesk_id)
            ->whereIn('estado_id', [1, 41])
            ->get();

        return response()->json($namePermisoAsignado, 201);

    }

    /**
     * solucionadosArea
     *
     * @param  mixed $request
     * @return void
     */
    public function solucionadosArea(Request $request)
    {

        $fechaDesde = $request->desde;
        $fechaHasta = $request->hasta;
        $userpermisos = auth()->user()->getAllPermissions();
        $permisos = [];
        $per_name = [];
        foreach ($userpermisos as $permiso) {
            array_push($permisos, $permiso->id);
            array_push($per_name, $permiso->name);
        }

        // $clinica_area = array_search("Helpdesk Clinica", $per_name);
        // $admin_clinica = var_export($clinica_area, true);

        // $compras_area = array_search("Compras Helpdesk", $per_name);
        // $logistica_area = array_search("Logística Helpdesk", $per_name);
        // $mensajeria_area = array_search("Mensajería Helpdesk", $per_name);

        $solucionasignados = Helpdesk::select('helpdesks.id as id', 'helpdesks.created_at as creado', 'helpdesks.Sede as sede',
            'estados.Nombre as estado', 'actividades_help.Nombre as actividad', 'categorias_help.Nombre as categoria','categorias_help.id as id_categoria',
            'areas_help.Nombre as area', 'helpdesks.Asunto as asunto', 'helpdesks.tiempo_estimado', 'helpdesks.prioridad as prioridad', 'helpdesks.Descripcion as descripcion',
            'users.name as nombreUser', 'users.email')
            ->join('estados', 'helpdesks.Estado_id', 'estados.id')
            ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
            ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
            ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'gestions_help.Helpdesk_id', 'tipos.Descripcion as Nombretipo',
                    'gestions_help.Motivo as Motivo', 'users.name as Nombre', 'users.apellido as Apellido',
                    'gestions_help.created_at as Faccion')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->join('users', 'gestions_help.User_id', 'users.id')
                    ->where('tipos.Nombre', 'Solicitud')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->where('gestions_help.Tipo_id', 13)
            ->whereIn('areas_help.Permission_id', $permisos)
            ->where('helpdesks.Estado_id', 13)
            ->distinct();

            if ($request->area) {
                $solucionasignados->where('helpdesks.area_id', $request->area);
            }

            if ($request->categoria) {
                $solucionasignados->where('helpdesks.categoria_id', $request->categoria);
            }

            if ($request->sede) {
                $solucionasignados->where('helpdesks.Sede', $request->sede);
            }
            if ($request->desde & $request->hasta) {
                $solucionasignados->where('helpdesks.created_at', '>=', $fechaDesde)
                    ->where('helpdesks.created_at', '<=', $fechaHasta . ' 23:59:59');
        }

        return response()->json($solucionasignados->get(), 201);
    }

    public function solucionadosUser()
    {
        $user = auth()->user()->id;

        $soluciongenerado = Helpdesk::select('helpdesks.id as id', 'helpdesks.created_at as creado', 'helpdesks.Sede as sede',
            'estados.Nombre as estado', 'actividades_help.Nombre as actividad', 'categorias_help.Nombre as categoria',
            'areas_help.Nombre as area', 'helpdesks.Asunto as asunto', 'helpdesks.calificacion', 'helpdesks.prioridad as prioridad', 'helpdesks.Descripcion as descripcion')
            ->join('estados', 'helpdesks.Estado_id', 'estados.id')
            ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
            ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
            ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
            ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'gestions_help.Helpdesk_id', 'tipos.Descripcion as Nombretipo',
                    'gestions_help.Motivo as Motivo', 'users.name as Nombre', 'users.apellido as Apellido', 'gestions_help.created_at as Faccion')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->join('users', 'gestions_help.User_id', 'users.id')
                    ->where('tipos.Nombre', 'Solicitud')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->where('gestions_help.Tipo_id', 13)
            ->where('gestions_help.User_id', $user)
            ->where('helpdesks.Estado_id', 13)
            ->distinct()
            ->get();

        return response()->json($soluciongenerado, 201);
    }

    public function pendientesAsignada(Request $request)
    {

        if ($request->estado == 'Pendientes') {
            $estado = [1];
        } else {
            $estado = [41];
        }

        $userpermisos = auth()->user()->getAllPermissions();
        $permisos = [];
        foreach ($userpermisos as $permiso) {
            array_push($permisos, $permiso->id);
        }

        $pendientesAsignadas = Helpdesk::select('helpdesks.id as id', 'helpdesks.created_at as creado', 'helpdesks.Sede as sede',
            'estados.Nombre as estado', 'actividades_help.Nombre as actividad','actividades_help.id as Actividades_id', 'categorias_help.Nombre as categoria','categorias_help.id as Categorias_id',
            'areas_help.Nombre as area', 'helpdesks.Asunto as asunto', 'helpdesks.Descripcion as descripcion', 'users.name as nombreUser',
            'users.email', 'areas_help.id as area_id', 'helpdesks.prioridad', 'helpdesks.tiempo_estimado', 'asignado_helpdesks.estado_id as estadoAsignado')
            ->join('estados', 'helpdesks.Estado_id', 'estados.id')
            ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
            ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->join('asignado_helpdesks', 'asignado_helpdesks.helpdesk_id', 'helpdesks.id')
            ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
            ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'gestions_help.Helpdesk_id', 'tipos.Descripcion as Nombretipo',
                    'gestions_help.Motivo as Motivo', 'users.name as Nombre', 'users.apellido as Apellido', 'gestions_help.created_at as Faccion')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->join('users', 'gestions_help.User_id', 'users.id')
                    ->where('tipos.Nombre', 'Solicitud')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->where('gestions_help.Tipo_id', 13)
            ->whereIn('asignado_helpdesks.permission_id', $permisos)
            ->whereIn('asignado_helpdesks.estado_id', $estado)
            ->whereNotin('helpdesks.Estado_id', [13, 26])
            ->distinct()
            ->get();

        return response()->json($pendientesAsignadas, 201);
    }

    public function solucionadasAsignada()
    {

        $userpermisos = auth()->user()->getAllPermissions();
        $permisos = [];
        foreach ($userpermisos as $permiso) {
            array_push($permisos, $permiso->id);
        }

        $solucionadasAsignadas = Helpdesk::select('helpdesks.id as id', 'helpdesks.created_at as creado', 'helpdesks.Sede as sede',
            'estados.Nombre as estado', 'actividades_help.Nombre as actividad', 'categorias_help.Nombre as categoria',
            'areas_help.Nombre as area', 'helpdesks.Asunto as asunto', 'helpdesks.Descripcion as descripcion', 'users.name as nombreUser',
            'users.email', 'areas_help.id as area_id', 'helpdesks.prioridad', 'asignado_helpdesks.estado_id as estadoAsignado')
            ->join('estados', 'helpdesks.Estado_id', 'estados.id')
            ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
            ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->join('asignado_helpdesks', 'asignado_helpdesks.helpdesk_id', 'helpdesks.id')
            ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
            ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
            ->with(['Gestion_helpdesks' => function ($query) {
                $query->select('gestions_help.id', 'gestions_help.Helpdesk_id', 'tipos.Descripcion as Nombretipo',
                    'gestions_help.Motivo as Motivo', 'users.name as Nombre', 'users.apellido as Apellido', 'gestions_help.created_at as Faccion')
                    ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
                    ->join('users', 'gestions_help.User_id', 'users.id')
                    ->where('tipos.Nombre', 'Solicitud')
                    ->with(['Adjuntos_helpdesks' => function ($query) {
                        $query->select('Ruta', 'Gestion_id')
                            ->get();
                    }])
                    ->get();
            }])
            ->where('gestions_help.Tipo_id', 13)
            ->whereIn('asignado_helpdesks.permission_id', $permisos)
            ->where('helpdesks.Estado_id', 13)
            ->distinct()
            ->get();

        return response()->json($solucionadasAsignadas, 201);
    }

    public function informe(Request $request)
    {

        $appointments = Collect(DB::select("exec dbo.SP_ReporteHelpdesk ?,?,?", [$request->fechaDesde, $request->fechaHasta, intval($request->area_id)]));
        $result = json_decode($appointments, true);
        return (new FastExcel($result))->download('file.xls');
        // $informeHelpdesk = Helpdesk::select('helpdesks.id as id',
        // 'helpdesks.created_at as Fecha',
        // 'estados.Nombre as Estado',
        // DB::raw("CONCAT(users.name,' ',users.apellido) as Reporto"),
        // 'helpdesks.Sede as Sede',
        // 'areas_help.Nombre as Área',
        // 'categorias_help.Nombre as Categoria',
        // 'actividades_help.Nombre as Actividad',
        // 'helpdesks.Asunto as Asunto',
        // 'helpdesks.prioridad as Prioridad',
        // 'helpdesks.Requerimiento as Tipo requerimiento'
        // )
        // ->join('estados', 'helpdesks.Estado_id', 'estados.id')
        // ->join('areas_help', 'helpdesks.area_id', 'areas_help.id')
        // ->join('gestions_help', 'gestions_help.Helpdesk_id', 'helpdesks.id')
        // ->join('users', 'gestions_help.User_id', 'users.id')
        // ->leftjoin('categorias_help', 'helpdesks.categoria_id', 'categorias_help.id')
        // ->leftjoin('actividades_help', 'helpdesks.Actividad_id', 'actividades_help.id')
        // ->where('helpdesks.created_at','>=',$request->fechaDesde)
        // ->where('helpdesks.created_at','<=',$request->fechaHasta.' 23:59:59')
        // ->where('gestions_help.Tipo_id', 13)
        // ->orderBy('helpdesks.id','ASC')
        // ->distinct();
        // if(isset($request->area_id)){
        //     $informeHelpdesk->where('helpdesks.area_id', $request->area_id);
        // }
        // $informe = $informeHelpdesk->get();

        // foreach ($informe as $inf) {

        //     $userSoluciono = Gestions_help::select(DB::raw("CONCAT(users.name,' ',users.apellido) as name"),
        //     'gestions_help.created_at as fecha','tipos.Nombre as situacion')
        //     ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
        //     ->leftjoin('users', 'gestions_help.User_id', 'users.id')
        //     ->where('tipos.Nombre', 'Solucionar')
        //     ->where('gestions_help.Helpdesk_id', $inf->id)
        //     ->first();

        //     if(isset($userSoluciono->name)){
        //         $inf['Soluciono'] = $userSoluciono->name;
        //         $inf['Fecha Solucion'] = $userSoluciono->fecha;
        //         $inf['Manejo'] = $userSoluciono->situacion;

        //     }else {
        //         $inf['Soluciono'] = null;
        //         $inf['Fecha Solucion'] = null;
        //         $inf['Manejo'] = null;
        //     }
        // }
    }

    public function sedes()
    {

        $sedes = Sedes_sumimedical::select('id', 'nombre')->where('estado_id', 1)
            ->get();

        return response()->json($sedes, 201);
    }

    public function reabrir(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'motivo' => 'required',
            'helpdesk_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        //inserta en gestion helpdesk
        $tipo = Tipo::where('Nombre', 'Re Abierto')->first();
        $gestion = new Gestions_help();
        $gestion->Helpdesk_id = $request->helpdesk_id;
        $gestion->User_id = Auth()->user()->id;
        $gestion->Tipo_id = $tipo->id;
        $gestion->Motivo = $request->motivo;
        $gestion->save();

        //cambia el estado del helpdesk
        Helpdesk::where('id', $request->helpdesk_id)
            ->update([
                'Estado_id' => 52,
            ]);

        //guarda el adjunto
        $user = Auth()->user()->cedula;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntoshelp/' . $user;
                $paths[$i] = '/storage/adjuntoshelp/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto = new Adjuntoshelp();
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

    }

    public function calificar(Request $request)
    {
        Helpdesk::where('id', $request->helpdesk_id)
            ->update([
                'calificacion' => $request->calificacion,
            ]);
        return response()->json(200);

    }

    public function comentariosReabierto(Request $request)
    {

        $reAbierto = Gestions_help::select('gestions_help.id', 'Helpdesk_id', 'tipos.Nombre as tipo', 'Motivo',
            'tipos.Descripcion as descripcionTipo', 'users.name', 'users.apellido', 'gestions_help.created_at',
            'gestions_help.responsable')
            ->join('tipos', 'gestions_help.Tipo_id', 'tipos.id')
            ->join('users', 'gestions_help.User_id', 'users.id')
            ->with(['Adjuntos_helpdesks' => function ($query) {
                $query->select('Ruta', 'Gestion_id')
                    ->get();
            }])
            ->where('Helpdesk_id', $request->helpdesk_id)
            ->where('tipos.Nombre', 'Re Abierto')
            ->get();

        return response()->json($reAbierto, 201);

    }

}
