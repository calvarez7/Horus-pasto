<?php

namespace App\Http\Controllers\Pqrsfs;

use App\User;
use App\Modelos\Pqrsf;
use App\Modelos\Adjunto;
use App\Modelos\Asignado;
use App\Modelos\Ipspqrsf;
use App\Modelos\Paciente;
use App\Modelos\Areapqrsf;
use App\Modelos\Cupspqrsf;
use Illuminate\Http\Request;
use App\Modelos\Empleadopqrsf;
use Illuminate\Support\Carbon;
use App\Modelos\Gestions_pqrsf;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Modelos\Subcategoriaspqrsf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\Model_has_permission;
use App\Modelos\Detallearticulospqrsf;
use Illuminate\Support\Facades\Validator;


class PqrsfController extends Controller
{

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'tiposolicitud' => 'required',
            'paciente_id' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'descripcion' => 'required',
            'canal' => 'sometimes|required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $cedula = $request->documento;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntospqrsf/'.$cedula;
                $paths[$i] = '/storage/adjuntospqrsf/'.$cedula.'/'.time().$file_name[$i];
                $file->move($path, time().$file_name[$i]);
                $i++;
            }
        }

            $pqrsf = new Pqrsf;
            $pqrsf->Paciente_id = $request->paciente_id;
            $pqrsf->CCgenera = $request->cedulagenero;
            $pqrsf->Nombregenera = $request->nombregenero;
            $pqrsf->Tiposolicitud = $request->tiposolicitud;
            $pqrsf->Email = $request->email;
            $pqrsf->Pqr_codigo = $request->supersalud;
            $pqrsf->Telefono = $request->telefono;
            $pqrsf->Descripcion = $request->descripcion;
            if(isset($request->canal)){
                $pqrsf->Canal = $request->canal;
            }
            $pqrsf->Estado_id = 18;
            $pqrsf->save();

            $gestion = new Gestions_pqrsf;
            $gestion->Pqrsf_id = $pqrsf->id;
            if(isset(Auth::user()->id)){
                $gestion->User_id = Auth::user()->id;
            }
            $gestion->Paciente_id = $request->paciente_id;
            $gestion->Tipo_id = 3;
            $gestion->Motivo = "Creo Pqrsf";
            $gestion->Fecha = Carbon::now();
            $gestion->save();


        for ($i=0; $i < count($paths); $i++) {
            $adjunto = new Adjunto;
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre = $file_name[$i];
            $adjunto->Ruta = $paths[$i];
            $adjunto->save();
        }

        return response()->json(["pqrsf" => $pqrsf->id , 'message' => 'Pqrsf creada con Exito!'], 201);

    }

    public function saveCups(Request $request, Pqrsf $pqrsf)
    {
        if (isset($request->cup)) {
            $cups    = $request->cup;
            $cup_id = [];
            $cu            = 0;
            foreach ($cups as $cup) {
                $cup_id[$cu] = $cup;
                $cu++;
            }
            for ($cu = 0; $cu < count($cups); $cu++) {
                $cup = new Cupspqrsf;
                $cup->Cup_id = $cup_id[$cu];
                $cup->Pqrsf_id = $pqrsf->id;
                $cup->save();
            }
        }

        return response()->json([
            'message' => 'Servicio guardado con Exito!',
        ], 200);

    }

    public function saveAreas(Request $request, Pqrsf $pqrsf)
    {
        if (isset($request->area)) {
            $areas    = $request->area;
            $area_id = [];
            $ar            = 0;
            foreach ($areas as $area) {
                $area_id[$ar] = $area;
                $ar++;
            }
            for ($ar = 0; $ar < count($areas); $ar++) {
                $area = new Areapqrsf;
                $area->Area_id = $area_id[$ar];
                $area->Pqrsf_id = $pqrsf->id;
                $area->save();
            }
        }

        return response()->json([
            'message' => 'Area guardada con Exito!',
        ], 200);

    }

    public function ActualizarAreas(Request $request, Pqrsf $pqrsf)
    {

        $areaViejas = Areapqrsf::where('Pqrsf_id',$pqrsf->id)->get();

        foreach($areaViejas as $areaVieja){
            $areaVieja->delete();
        }

        if (isset($request->area)) {
            $areas    = $request->area;
            $area_id = [];
            $ar            = 0;
            foreach ($areas as $area) {
                $area_id[$ar] = $area;
                $ar++;
            }
            for ($ar = 0; $ar < count($areas); $ar++) {
                $area = new Areapqrsf;
                $area->Area_id = $area_id[$ar];
                $area->Pqrsf_id = $pqrsf->id;
                $area->save();
            }
        }

        return response()->json([
            'message' => 'Area guardada con Exito!',
        ], 200);

    }

    public function saveIps(Request $request, Pqrsf $pqrsf)
    {
        if (isset($request->sede)) {
            $sedes    = $request->sede;
            $sede_id = [];
            $sed            = 0;
            foreach ($sedes as $sede) {
                $sede_id[$sed] = $sede;
                $sed++;
            }
            for ($sed = 0; $sed < count($sedes); $sed++) {
                $area = new Ipspqrsf;
                $area->IPS_id = $sede_id[$sed];
                $area->Pqrsf_id = $pqrsf->id;
                $area->save();
            }
        }

        return response()->json([
            'message' => 'IPS guardada con Exito!',
        ], 200);
    }

    public function saveDetallearticulos(Request $request, Pqrsf $pqrsf)
    {
        if (isset($request->detallearticulo)) {
            $detallearticulos    = $request->detallearticulo;
            $detallearticulo_id = [];
            $det            = 0;
            foreach ($detallearticulos as $detallearticulo) {
                $detallearticulo_id[$det] = $detallearticulo;
                $det++;
            }
            for ($det = 0; $det < count($detallearticulos); $det++) {
                $detallearticulo = new Detallearticulospqrsf;
                $detallearticulo->Detallearticulo_id = $detallearticulo_id[$det];
                $detallearticulo->Pqrsf_id = $pqrsf->id;
                $detallearticulo->save();
            }
        }

        return response()->json([
            'message' => 'Medicamento guardado con Exito!',
        ], 200);

    }

    public function savesubcategoria(Request $request, Pqrsf $pqrsf)
    {
        if (isset($request->subcategoria)) {
            $subcategorias    = $request->subcategoria;
            $subcategoria_id = [];
            $sub            = 0;
            foreach ($subcategorias as $subcategoria) {
                $subcategoria_id[$sub] = $subcategoria;
                $sub++;
            }
            for ($sub = 0; $sub < count($subcategorias); $sub++) {
                $subcategoria = new Subcategoriaspqrsf;
                $subcategoria->Subcategoria_id = $subcategoria_id[$sub];
                $subcategoria->Pqrsf_id = $pqrsf->id;
                $subcategoria->save();
            }
        }

        return response()->json([
            'message' => 'Subcategoria guardada con Exito!',
        ], 200);
    }

    public function actualizarSubcategoria(Request $request, Pqrsf $pqrsf)
    {

        $subcategoriaViejas = Subcategoriaspqrsf::where('Pqrsf_id',$pqrsf->id)->get();

        foreach($subcategoriaViejas as $subcategoriaVieja){
            $subcategoriaVieja->delete();
        }

        $subcategorias = $request->subcategoria;

        foreach ($subcategorias as $subcategoria){
            $crear = new Subcategoriaspqrsf;
            $crear->Subcategoria_id = $subcategoria;
            $crear->Pqrsf_id = $pqrsf->id;
            $crear->save();
        }

        return response()->json([
            'message' => 'Subcategoria guardada con Exito!',
        ], 200);
    }

    public function saveEmpleado(Request $request, Pqrsf $pqrsf)
    {
        if (isset($request->empleado)) {
            $empleados    = $request->empleado;
            $empleado_id = [];
            $em            = 0;
            foreach ($empleados as $empleado) {
                $empleado_id[$em] = $empleado;
                $em++;
            }
            for ($em = 0; $em < count($empleados); $em++) {
                $empleado = new Empleadopqrsf;
                $empleado->Empleado_id = $empleado_id[$em];
                $empleado->Pqrsf_id = $pqrsf->id;
                $empleado->save();
            }
        }

        return response()->json([
            'message' => 'Empleado guardado con Exito!',
        ], 200);
    }

    public function deletesubcategoria(Request $request)
    {
        $searchsubcategoria = Subcategoriaspqrsf::where('Subcategoria_id', $request->subcategoria_id)
        ->where('Pqrsf_id', $request->pqrsf_id)
        ->first('id');

        $searchsubcategoria->delete();
    }

    public function deletedetallearticulo(Request $request)
    {
        $searchdetallearticulo = Detallearticulospqrsf::where('Detallearticulo_id', $request->detallearticulo_id)
        ->where('Pqrsf_id', $request->pqrsf_id)
        ->first('id');

        $searchdetallearticulo->delete();
    }

    public function deletearea(Request $request)
    {
        $searcharea = Areapqrsf::where('Area_id', $request->area_id)
        ->where('Pqrsf_id', $request->pqrsf_id)
        ->first('id');

        $searcharea->delete();
    }

    public function deletecup(Request $request)
    {
        $searchcup = Cupspqrsf::where('Cup_id', $request->cup_id)
        ->where('Pqrsf_id', $request->pqrsf_id)
        ->first('id');

        $searchcup->delete();
    }

    public function deleteips(Request $request)
    {
        $searchips = Ipspqrsf::where('IPS_id', $request->sede_id)
        ->where('Pqrsf_id', $request->pqrsf_id)
        ->first('id');

        $searchips->delete();
    }

    public function deletempleado(Request $request){

        $searchempleado = Empleadopqrsf::where('Empleado_id', $request->empleado_id)
        ->where('Pqrsf_id', $request->pqrsf_id)
        ->first('id');

        $searchempleado->delete();
    }

    public function update(Request $request, Pqrsf $pqrsf)
    {
        $pqrsf->Priorizacion = ($request->priorizacion ? $request->priorizacion:NULL);
        $pqrsf->Reabierta = ($request->reabierta ? $request->reabierta:NULL);
        $pqrsf->Atr_calidad = ($request->atrcalidad ? $request->atrcalidad:NULL);
        $pqrsf->Tiposolicitud = ($request->tiposolicitud ? $request->tiposolicitud:NULL);
        $pqrsf->derecho = ($request->derecho ? $request->derecho:NULL);
        $pqrsf->deber = ($request->deber ? $request->deber:NULL);
        $pqrsf->save();

        $gestion = new Gestions_pqrsf;
        $gestion->Pqrsf_id = $pqrsf->id;
        $gestion->User_id = Auth::user()->id;
        $gestion->Tipo_id = 5;
        $gestion->Paciente_id = $pqrsf->Paciente_id;
        $gestion->Motivo = "Actualizo Pqrsf";
        $gestion->Fecha = Carbon::now();
        $gestion->save();


        return response()->json([
            'message' => 'Pqrsf Actualizada con Exito!',
        ], 200);
    }

    public function pqrfscancel(Request $request, Pqrsf $pqrsf)
    {
        $validate = Validator::make($request->all(),[
            'motivo' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $pqrsf->Estado_id = 26;
        $pqrsf->save();

        $gestion = new Gestions_pqrsf;
        $gestion->Pqrsf_id = $pqrsf->id;
        $gestion->User_id = Auth::user()->id;
        $gestion->Tipo_id = 4;
        $gestion->Paciente_id = $pqrsf->Paciente_id;
        $gestion->Motivo = $request->motivo;
        $gestion->Fecha = Carbon::now();
        $gestion->save();

        return response()->json([
            'message' => 'Pqrsf Anulada con Exito!',
        ], 200);

    }

    public function checkStatus(Request $request)
    {
        $filtro = Pqrsf::where('id', $request->radicado)->where('Paciente_id',$request->paciente_id)->first();

        if (isset($filtro)){

            $pqrsf = Pqrsf::getRepository()->checkStatus($request)->get();

        return response()->json($pqrsf, 200);

        }

        return response()->json(['message' => 'Por favor verifica la información, ya que la consulta no generó ningún resultado.'], 403);

    }

    public function assign(Request $request, Pqrsf $pqrsf)
    {
        $validate = Validator::make($request->all(),[
            'permiso' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $permiso_ids = [];
        foreach ($request->permiso as $permiso) {
            array_push($permiso_ids, $permiso);
        }

        $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
            ->join('users as us', 'us.id', 'model_has_permissions.model_id')
            ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
            ->whereIn('per.id', $permiso_ids)
            ->distinct()
            ->get();

        if(isset($request->documento2)){
            $cedula = $request->documento2;
        }
        $cedula = $request->documento1;
        $pqrsfid = $pqrsf->id;

        $email = Mail::send('email_alert_pqrsfs',
            ['users' => $users, 'pqrsfid' => $pqrsfid, 'cedula' => $cedula], function ($m) use ($users, $pqrsfid, $cedula) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación PQRSF');
                }
            });

        $pqrsf->Estado_id = 5;
        $pqrsf->save();

        $permiso_id = [];
        $id = 0;
        foreach ($request->permiso as $permisoid) {
                $permiso_id[$id] = $permisoid;
                $id++;
        }
        for ($id = 0; $id < count($permiso_id); $id++) {
            $asignar = new Asignado;
            $asignar->Permission_id = $permiso_id[$id];
            $asignar->Pqrsf_id = $pqrsf->id;
            $asignar->Estado_id = 1;
            $asignar->save();
        }

        $gestion = new Gestions_pqrsf;
        $gestion->Pqrsf_id = $pqrsf->id;
        $gestion->User_id = Auth::user()->id;
        $gestion->Tipo_id = 6;
        $gestion->Paciente_id = $pqrsf->Paciente_id;
        $gestion->Motivo = "Asigno Pqrsf";
        $gestion->Fecha = Carbon::now();
        $gestion->save();

        return response()->json([
            'message' => 'Pqrsf Asignada con Exito!',
        ], 200);
    }

    public function solution(Request $request, Pqrsf $pqrsf)
    {

        $validate = Validator::make($request->all(),[
            'respuesta' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $user      = auth()->user()->id;
        $file_name = [];
        $paths     = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path          = '../storage/app/public/adjuntospqrsf/' . $user;
                $paths[$i]     = '/storage/adjuntospqrsf/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $gestion    = new Gestions_pqrsf;
        $gestion->Pqrsf_id   = $pqrsf->id;
        $gestion->User_id     = auth()->user()->id;
        $gestion->Motivo   = $request->respuesta;
        $gestion->Tipo_id = 9;
        $gestion->Paciente_id = $request->paciente_id;
        $gestion->Fecha = Carbon::now();
        $gestion->save();

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto               = new Adjunto;
            $adjunto->Gestion_id = $gestion->id;
            $adjunto->Nombre       = $file_name[$i];
            $adjunto->Ruta         = $paths[$i];
            $adjunto->save();
        }

        $pqrsf->Estado_id = 13;
        $pqrsf->save();

        $datospaciente = Pqrsf::select(['Pqrsfs.Email as Email',
        'Pqrsfs.id as id','Pacientes.Primer_Nom as Nombre'])
        ->join('Pacientes', 'Pqrsfs.Paciente_id', 'pacientes.id')
        ->where('Pqrsfs.id', $pqrsf->id)
        ->get();

        $datospacientepq = Pqrsf::select(['Pqrsfs.Email as Email',
        'Pqrsfs.id as id', 'Pqrsfs.Afec_nombres as Nombre'])
        ->where('Pqrsfs.id', $pqrsf->id)
        ->get();

        $solucion = $request->respuesta;
        $adjuntosrespuesta = $request->add_adjunto;
        if(isset($adjuntosrespuesta)){
            $alladjuntos = array_merge($paths, $adjuntosrespuesta);
        }else{
            $alladjuntos = $paths;
        }

        if (isset($datospaciente[0]->Nombre)) {
            $email = Mail::send('email_solution_pqrsfs',
            ['datospaciente' => $datospaciente, 'solucion' => $solucion], function ($m) use ($datospaciente, $alladjuntos) {
            $m->to($datospaciente[0]->Email, $datospaciente[0]->Nombre)->subject('PQRSF Solucionado');
                foreach($alladjuntos as $ruta){
                    $m->attach(substr($ruta, 1));
                }
            });
        } else {
            $email = Mail::send('email_solution_pqrsfs',
            ['datospacientepq' => $datospacientepq, 'solucion' => $solucion], function ($m) use ($datospacientepq, $alladjuntos) {
            $m->to($datospacientepq[0]->Email, $datospacientepq[0]->Nombre)->subject('PQRSF Solucionado');
                foreach($alladjuntos as $ruta){
                    $m->attach(substr($ruta, 1));
                }
            });
        }

        return response()->json([
            'message' => 'Solución enviada con exito!',
        ], 201);
    }

    public function alert(Request $request)
    {
        $permiso_ids = [];
        foreach ($request->responsables as $permiso) {
            array_push($permiso_ids, $permiso);
        }

        $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
            ->join('users as us', 'us.id', 'model_has_permissions.model_id')
            ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
            ->whereIn('per.id', $permiso_ids)
            ->distinct()
            ->get();

        if(isset($request->documento2)){
            $cedula = $request->documento2;
        }
        $cedula = $request->documento1;
        $pqrsfAlert = $request->pqrsf_id;

        $email = Mail::send('email_alert_pqrsfs',
            ['users' => $users, 'pqrsfAlert' => $pqrsfAlert, 'cedula' => $cedula], function ($m) use ($users, $pqrsfAlert, $cedula) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación PQRSF');
                }
            });

        return response()->json([
            'message' => 'Alerta enviada con exito!',
        ], 200);
    }

    public function reasignar(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'responsable' => 'required',
            'motivo'      => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $permiso_ids = [];
        foreach ($request->responsable as $permiso) {
            array_push($permiso_ids, $permiso);
        }

        $users = Model_has_permission::select(['per.name as name', 'us.name as nameu', 'us.email as email'])
            ->join('users as us', 'us.id', 'model_has_permissions.model_id')
            ->join('permissions as per', 'per.id', 'model_has_permissions.permission_id')
            ->whereIn('per.id', $permiso_ids)
            ->distinct()
            ->get();

        if(isset($request->documento2)){
            $cedula = $request->documento2;
        }
        $cedula = $request->documento1;
        $pqrsfReasignar = $request->pqrsf_id;

        $email = Mail::send('email_alert_pqrsfs',
            ['users' => $users, 'pqrsfReasignar' => $pqrsfReasignar, 'cedula' => $cedula], function ($m) use ($users, $pqrsfReasignar, $cedula) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación PQRSF');
                }
            });

        $permisoPqrsf = Asignado::where('Pqrsf_id', $request->pqrsf_id)
            ->where('Estado_id', '!=', 26)
            ->get(['Permission_id']);

        $permisoPqrsfUp = Asignado::where('Pqrsf_id', $request->pqrsf_id)
            ->where('Estado_id', 1)
            ->update([
                'Estado_id' => 26,
            ]);

        $permisoid = [];
        foreach ($permisoPqrsf as $permisoPqr) {
            array_push($permisoid, $permisoPqr->Permission_id);
        }

        $nuevopermiso = array_diff($permiso_ids, $permisoid);

        if (isset($nuevopermiso)) {
            $per_id = [];
            $id     = 0;
            foreach ($nuevopermiso as $perid) {
                $per_id[$id] = $perid;
                $id++;
            }
            for ($id = 0; $id < count($per_id); $id++) {
                $asignado            = new Asignado;
                $asignado->Pqrsf_id = $request->pqrsf_id;
                $asignado->Permission_id    = $per_id[$id];
                $asignado->Estado_id = 1;
                $asignado->save();
            }
        }

        $updatestadoasignado = Asignado::where('Pqrsf_id', $request->pqrsf_id)
            ->whereIn('Permission_id', $permiso_ids)
            ->update([
                'Estado_id' => 1,
            ]);

        if ($request->estado == "Parcial") {

            Pqrsf::where('id', $request->pqrsf_id)
                ->update([
                    'Estado_id' => 24
                ]);

        } else {

            Pqrsf::where('id', $request->pqrsf_id)
                ->update([
                    'Estado_id'       => 11
                ]);
        }

            $gestion    = new Gestions_pqrsf;
            $gestion->Pqrsf_id   = $request->pqrsf_id;
            $gestion->User_id     = auth()->user()->id;
            $gestion->Motivo   = $request->motivo;
            $gestion->Tipo_id = 10;
            $gestion->Paciente_id = $request->paciente_id;
            $gestion->Fecha = Carbon::now();
            $gestion->save();

        return response()->json([
            'message' => 'PQRSF reasignada con exito!',
        ], 200);

    }

    public function generarInforme(Request $request){
        $result = [];
        switch ($request->resolucion) {
            case 1:
                $appointments = Collect(DB::select("exec dbo.PendientesPqrsf"));
                $result = json_decode($appointments, true);
                break;
            case 2:
                $appointments = Collect(DB::select("exec dbo.InformePQRSF ?,?",[$request->fechaDesde,$request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
            case 3:
                $appointments = Collect(DB::select("exec dbo.oportunidadPqrsf ?,?",[$request->fechaDesde,$request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
            case 4:
                if ($request->entidad==1){
                    $appointments=Detallearticulospqrsf::select('detallearticulos.Producto as Medicamento',DB::raw('Count(pqrsfs.id) as Cantidad'))
                            ->join('detallearticulos','detallearticulos.id','detallearticulospqrsf.Detallearticulo_id')
                            ->join('pqrsfs','pqrsfs.id','detallearticulospqrsf.Pqrsf_id')
                            ->whereBetween('pqrsfs.created_at',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                            ->groupby('detallearticulos.Producto','pqrsfs.id')
                            ->orderby('pqrsfs.id','desc')->get();
                    $result = json_decode($appointments, true);
                }
                if($request->entidad==2){
                    $appointments=Cupspqrsf::select('cups.Nombre as Procedimiento',DB::raw('Count(pqrsfs.id) as Cantidad'))
                        ->join('cups','cups.id','cupspqrsf.Cup_id')
                        ->join('pqrsfs','pqrsfs.id','cupspqrsf.Pqrsf_id')
                        ->whereBetween('pqrsfs.created_at',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                        ->groupby('cups.Nombre','pqrsfs.id')
                        ->orderby('pqrsfs.id','desc')->get();
                    $result = json_decode($appointments, true);
                }
                if($request->entidad==3){
                    $appointments=Areapqrsf::select('areas.Nombre as Area',DB::raw('Count(pqrsfs.id) as Cantidad'))
                        ->join('areas','areas.id','areaspqrsf.area_id')
                        ->join('pqrsfs','pqrsfs.id','areaspqrsf.Pqrsf_id')
                        ->whereBetween('pqrsfs.created_at',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                        ->groupby('areas.Nombre','pqrsfs.id')
                        ->orderby('pqrsfs.id','desc')->get();
                    $result = json_decode($appointments, true);
                }
                if($request->entidad==4){
                    $appointments=Empleadopqrsf::select('empleados.Nombre as Empleado',DB::raw('Count(pqrsfs.id) as Cantidad'))
                        ->join('empleados','empleados.id','empleadospqrsf.Empleado_id')
                        ->join('pqrsfs','pqrsfs.id','empleadospqrsf.Pqrsf_id')
                        ->whereBetween('pqrsfs.created_at',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                        ->groupby('empleados.Nombre','pqrsfs.id')
                        ->orderby('pqrsfs.id','desc')->get();
                    $result = json_decode($appointments, true);
                }
                break;
            case 5:
                $appointments=Pqrsf::select('pqrsfs.id as Readicado',DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as 'Nombre de Usuario'"),
                'pacientes.Num_Doc as Documento', 'pqrsfs.Canal','pqrsfs.Tiposolicitud','pqrsfs.Descripcion','pqrsfs.created_at as "Fecha de creacion"','estados.nombre as Estado')
                    ->join('pacientes','pacientes.id','pqrsfs.Paciente_id')
                    ->join('estados','estados.id','pqrsfs.Estado_id')
                    ->where('pacientes.Num_Doc',$request->CedulaPacientes)
                    ->whereBetween('pqrsfs.created_at',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                    ->orderby('pqrsfs.id','desc')->get();

                $result = json_decode($appointments, true);
                break;
            case 6:
                $usuario = auth()->user()->id;
                $appointments=Pqrsf::select('pqrsfs.id as Readicado',DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as 'Nombre de Usuario'"),
                    'pacientes.Num_Doc as Documento', 'pqrsfs.Canal','pqrsfs.Tiposolicitud','pqrsfs.Descripcion','pqrsfs.created_at as "Fecha de creacion"', DB::raw("CONCAT(users.name,' ',users.apellido) as 'Nombre de Responsable'"),'estados.nombre as Estado')
                    ->join('pacientes','pacientes.id','pqrsfs.Paciente_id')
                    ->join('estados','estados.id','pqrsfs.Estado_id')
                    ->join('gestions_pqrsf','gestions_pqrsf.Pqrsf_id','pqrsfs.id')
                        ->join('users','users.id','gestions_pqrsf.User_id')
                    ->where('gestions_pqrsf.User_id',$usuario)
                    ->whereIn('gestions_pqrsf.Tipo_id',[3,5])
                    ->whereBetween('pqrsfs.created_at',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                    ->orderby('pqrsfs.id','desc')->get();

                $result = json_decode($appointments, true);
                break;
            case 7:
                $appointments = Collect(DB::select("exec SP_Reporte_SuperSalud ?,?",[$request->fechaDesde,$request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
        }
        return (new FastExcel($result))->download('file.xls');
    }


    public function historybypaciente(Request $request){

        $historyPqrsf = Pqrsf::select(['pqrsfs.id', 'pqrsfs.Tiposolicitud', 'es.Nombre as estado',
        'pqrsfs.Canal', 'pqrsfs.Descripcion', 'pqrsfs.created_at'])
        ->join('estados as es', 'pqrsfs.Estado_id', 'es.id')
        ->with(['Gestion_pqrsfs' => function ($query) {
            $query->select('gestions_pqrsf.id', 'gestions_pqrsf.Pqrsf_id', 'gestions_pqrsf.Tipo_id', 'users.name', 'users.apellido')
                ->join('users', 'User_id', 'users.id')
                ->where('gestions_pqrsf.Tipo_id', 3)
                ->get();
        }])
        ->where('pqrsfs.Paciente_id', $request->paciente_id)
        ->latest()
        ->take(5)
        ->get();

        return response()->json($historyPqrsf, 201);

    }

    public function validationPqrsf($pqrsf_id){

        $pqrsf = Pqrsf::select(['id', 'Tiposolicitud', 'Priorizacion',
        'Reabierta', 'Atr_calidad'])
        ->where('pqrsfs.id', $pqrsf_id)
        ->first();

        $pqrsfarea = Areapqrsf::where('Pqrsf_id', $pqrsf_id)
        ->count();

        $pqrsfsubcategoria = Subcategoriaspqrsf::where('Pqrsf_id', $pqrsf_id)
        ->count();

        $result = true;
        $mensaje = '';

        if(!$pqrsf->Tiposolicitud | !$pqrsf->Priorizacion | !$pqrsf->Reabierta | !$pqrsf->Atr_calidad
        | $pqrsf->Tiposolicitud == 'null' | $pqrsf->Priorizacion == 'null' | $pqrsf->Reabierta == 'null' | $pqrsf->Atr_calidad == 'null'){
            $result = false;
            $mensaje = 'Los campos (tipo solicitud, priorización, reabierta y atributo de calidad son obligatorios!';
        }else if($pqrsfarea <= 0){
            $result = false;
            $mensaje = 'La área es obligatoria!';
        }else if($pqrsfsubcategoria <= 0){
            $result = false;
            $mensaje = 'La subcategoria es obligatoria!';
        }

        $data = [
            'resultado' => $result,
            'mensaje' => $mensaje
        ];

        return response()->json($data, 200);

    }

    public function reclasificar(Request $request){

        Pqrsf::where('id', $request->pqrsf_id)
        ->update([
            'Atr_calidad' => $request->Atr_calidad,
            'Email'       => $request->Email,
            'Tiposolicitud' => $request->Tiposolicitud
        ]);

        $gestion = new Gestions_pqrsf;
        $gestion->Pqrsf_id = $request->pqrsf_id;
        $gestion->User_id = Auth::user()->id;
        $gestion->Tipo_id = 5;
        $gestion->Paciente_id = $request->Paciente_id;
        $gestion->Motivo = "Actualizo Pqrsf";
        $gestion->Fecha = Carbon::now();
        $gestion->save();

        return response()->json([
            'message' => 'Pqrsf Actualizada con Exito!',
        ], 200);

    }

    public function import(Request $request){

        $existe = [];
        $noExiste = [];
        $msg = '';


        (new FastExcel)->import($request->data, function ($line) use (&$noExiste,&$existe,&$msg) {
            $documento_string = (string)$line["Documento"];
            $paciente = Paciente::where('Num_Doc', $documento_string)->first();
            $email = filter_var($line["Email"], FILTER_VALIDATE_EMAIL);
            $usuario = User::where('id', $line["Usuario_id"])->where('estado_user', 1)->first();

            if(!$usuario){
                if($line["Usuario_id"] == ''){
                    $msg = 'Hay campos obligatorios vacios, revisa: ( Usuario_id )';
                }else{
                    $msg = 'El usuario no existe o esta inactivo, revisa: ( Usuario_id )';
                }
            }else if(!is_numeric($line["Telefono"])){
                $msg = 'Hay campos que no son numericos, revisa: ( Telefono )';
            }else if($email == false){
                $msg = 'Hay campos que no son email validos, revisa: ( Email )';
            }else if($paciente){
                if($line["Email"] == '' | $line["Telefono"] == '' | $line["Descripcion"] == ''){
                    $msg = 'Hay campos obligatorios vacios, revisa: ( Email, Telefono, Descripcion )';
                }else{
                    $data = $line;
                    $data["Paciente_id"] = $paciente->id;
                    $data["Usuario_id"] = $usuario->id;
                    $existe[] = $data;
                }
            }elseif(!$paciente){
                if($line["Documento"] == ''){
                    $msg = 'Hay campos obligatorios vacios, revisa: ( Documento )';
                }else{
                    $noExiste[] = $line;
                }
            }
        });

        if($msg !== ''){
            return response()->json([
                'message' => $msg
            ], 400);
        }else if($noExiste){
            return response()->json([
                $noExiste
            ], 400);
        }else{

            $numeroRegistros = count($existe);
            if($numeroRegistros > 700){
                return response()->json([
                    'message' => 'El archivo contiene más de 700 registros!'
                ], 400);
            }else{

                foreach ($existe as $key) {

                    $pqrsf = new Pqrsf;
                    $pqrsf->Paciente_id = $key['Paciente_id'];
                    $pqrsf->Tiposolicitud = 'Solicitud';
                    $key["Supersalud"] == ''?$pqrsf->Pqr_codigo = Null:$pqrsf->Pqr_codigo = $key['Supersalud'];
                    $pqrsf->Email = $key['Email'];
                    $pqrsf->Telefono = $key['Telefono'];
                    $pqrsf->Descripcion = $key['Descripcion'];
                    $pqrsf->Canal = 'Supersalud';
                    $pqrsf->Estado_id = 18;
                    $pqrsf->save();

                    $gestion = new Gestions_pqrsf;
                    $gestion->Pqrsf_id = $pqrsf->id;
                    $gestion->User_id = $key['Usuario_id'];
                    $gestion->Paciente_id = $key['Paciente_id'];
                    $gestion->Tipo_id = 3;
                    $gestion->Motivo = "Creo Pqrsf";
                    $gestion->Fecha = Carbon::now();
                    $gestion->save();

                }

                return response()->json([
                    'message' => 'Carga de pqrsf con exito!'
                ], 200);

            }

        }

    }

}


