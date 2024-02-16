<?php

namespace App\Http\Controllers\Usuarios;

use App\Events\NotificacionEvent;
use Avatar;
use Illuminate\Support\Facades\DB;
use Storage;
use App\User;
use App\Modelos\Empleado;
use App\Modelos\Paciente;
use Illuminate\Http\Request;
use App\Modelos\Model_has_role;
use App\Http\Controllers\Controller;
use App\Modelos\actividad_internacional;
use App\Modelos\Model_has_permission;
use App\Modelos\adjunto_sarlaft;
use App\Modelos\declaracion_fondos;
use App\Modelos\especialidadMedica;
use App\Modelos\informacion_financiera;
use App\Modelos\representante_legal;
use App\Modelos\sarlaft;
use App\Modelos\socio;
use App\Modelos\persona_expuesta;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function all()
    {
        $users = User::with('roles')->get();
        return response()->json($users, 200);
    }

    public function enableByRole($role)
    {
        $users = User::where('estado_user', 1)
            ->wherehas("roles", function ($q) use ($role) {$q->where("name", "Userfactura $role");})
            ->get();
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        $resul = Validator::make($request->all(), [
            'name'       => 'required|string',
            'apellido'   => 'required|string',
            'email'      => 'required|string|email|unique:users',
            'entidad_id' => 'required',
            'password'   => 'required|string|confirmed',
        ]);
        if ($resul->fails()) {
            $errores = $resul->errors();
            return response()->json($errores, 422);
        }
        $input             = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user              = User::create($input);
        $user->assignRole($request->roles);
        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::disk('public')->put('avatars/' . $user->id . '/avatar.png', (string) $avatar);

        // broadcast(new NotificacionEvent('usuario Creado'));

        return response()->json([
            'message' => 'Usuario creado con Exito!'], 201);
    }

    public function show(User $user)
    {
        $user->getRoleNames();
        return response()->json($user, 200);

    }

    public function update(Request $request, User $user)
    {
        if ($request['password'] == null) {
            $user->update($request->all());
            $user->syncRoles($request->roles);
            return response()->json([
                'message' => 'Usuario Actualizado con Exito!',
            ], 200);
        }else {
            $usuario = $request->all();
            $usuario['password'] = bcrypt($request['password']);
            $user->update($usuario);
            $user->syncRoles($request->roles);
            return response()->json([
                'message' => 'Usuario Actualizado con Exito!',
            ], 200);
        }
    }

    public function updatefirma(Request $request, User $user)
    {
        if ($file = $request->file('file')) {
            $name   = $file->getClientOriginalName();
            $pathdb = '/storage/app/public/firmas/ordenes/' . $user->cedula . '/' . $name;
            $path   = '../storage/app/public/firmas/ordenes/' . $user->cedula;
            if ($file->move(public_path($path), $name)) {
                $user->update([
                    'Firma' => $pathdb,
                ]);
                return response()->json([
                    'message' => 'Usuario Actualizado con Exito!',
                ], 200);
            }
        }

    }

    public function updatepass(Request $request, User $user)
    {

        $pass           = bcrypt($request['password']);
        $user->password = $pass;
        $user->save();
        return response()->json([
            'message' => 'Contraseña actualizada con Exito!',
        ], 200);

    }

    public function available(Request $request, User $user)
    {
        $user->update(['estado_user' => $request->estado_user]);
        $pivote = [
            'Actualizado_por' => auth()->id(),
        ];
        $user->estados()->attach($request->estado_user, $pivote);
        return response()->json([
            'message' => 'Estado del usuario Actualizado con Exito!',
        ], 200);
    }

    public function especialidadMedico($Especialidade)
    {
        $medicos = User::where('estado_user', 1)
            ->whereHas("roles", function ($q) use ($Especialidade) {$q->where("name", "Medico $Especialidade");})
            ->get();
        return response()->json($medicos, 200);
    }

    public function medicos()
    {
        $medicos = User::permission('medico')
            ->where('estado_user', 1)
            ->get();
        return response()->json($medicos, 200);
    }

    public function getSignByUser()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        return response()->json($user, 200);
    }

    public function addPermissions(User $user, Request $request)
    {
        $permissions = $user->getAllPermissions();
        $permiso_ids = [];
        foreach ($request->permiso as $per) {
            array_push($permiso_ids, $per);
        }
        $permisoid = [];
        foreach ($permissions as $permiso) {
            array_push($permisoid, $permiso->id);
        }
        $validarPermission = array_uintersect($permiso_ids, $permisoid, "strcasecmp");

        if ($validarPermission !== []){
            return response()->json([
                'message' => 'El permiso ya esta asignado a este usuario.',
            ], 401);
        }

        if (isset($permiso_ids)) {
            for ($id = 0; $id < count($permiso_ids); $id++) {
                $permission = new Model_has_permission;
                $permission->permission_id = $permiso_ids[$id];
                $permission->model_type = "App\User";
                $permission->model_id = $user->id;
                $permission->save();
            }
        }

        return response()->json([
            'message' => 'El permiso fue asignado con exito.',
        ], 201);
    }

    public function deletePermission(Request $request, User $user)
    {
        $permiso_ids = [];
        foreach ($request->permiso as $per) {
            array_push($permiso_ids, $per);
        }
        $permissions = Model_has_permission::whereIn('permission_id', $permiso_ids)->where('model_id', $user->id)->delete();

        return response()->json([
            'message' => 'El permiso fue eliminado con exito.',
        ], 201);
    }

    public function findUserForDatabase($identificacion){
        $resuls = [];
        $paciente = Paciente::where('Num_Doc',$identificacion)->first();
        $usuario = Empleado::where('Documento',$identificacion)->first();
        if($usuario){
            $resuls['nombre'] = $usuario->Nombre;
            $resuls["numero"] = $usuario->Documento;
            $resuls["tipo"]= "Empleado";
        }else{
            if($paciente){
                $resuls['nombre'] = $paciente->Primer_Nom." ".$paciente->SegundoNom." ".$paciente->Primer_Ape;
                $resuls["numero"] = $paciente->Num_Doc;
                $resuls["tipo"]= "Paciente";
            }else{
                $resuls = false;
            }
        }
        return response()->json($resuls, 200);

    }

    public function addRol(User $user, Request $request)
    {
        $rol_ids = [];
        foreach ($request->role as $r) {
            array_push($rol_ids, $r);
        }

        $roles = Model_has_role::select('role_id')
            ->where('model_id', $user->id)
            ->get();

        $roleid = [];
        foreach ($roles as $ro) {
            array_push($roleid, $ro->role_id);
        }
        $validarPermission = array_uintersect($rol_ids, $roleid, "strcasecmp");

        if ($validarPermission !== []){
            return response()->json([
                'message' => 'El rol ya esta asignado a este usuario.',
            ], 401);
        }

        if (isset($rol_ids)) {
            for ($id = 0; $id < count($rol_ids); $id++) {
                $rol = new Model_has_role;
                $rol->role_id = $rol_ids[$id];
                $rol->model_type = "App\User";
                $rol->model_id = $user->id;
                $rol->save();
            }
        }

        return response()->json([
            'message' => 'El rol fue asignado con exito.',
        ], 201);
    }

    public function deleteUserRol(Request $request, User $user)
    {
        $rol_ids = [];
        foreach ($request->rol as $r) {
            array_push($rol_ids, $r);
        }
        $roles = Model_has_role::whereIn('role_id', $rol_ids)->where('model_id', $user->id)->delete();

        return response()->json([
            'message' => 'El rol fue eliminado con exito.',
        ], 201);
    }

    public function medicoSumi()
    {
        $users = User::select('id',DB::raw("CONCAT(users.name,' ',users.apellido) as Medicofamilia"),'users.name','users.apellido','users.cedula')
            ->where('especialidad_medico', 'like', '%medico%')
            ->where('estado_user', 1)
            ->get();
        return response()->json($users, 200);
    }
    public function buscarUsuario($identificacion)
    {
        $usuario = User::where('cedula', $identificacion)->first();
        if (!$usuario) {
            return response()->json([
                'message' => 'El proveedor consultado no registra en el sistema',
            ]);
        }
        return response()->json([
            'paciente' => $usuario,
        ], 200);
    }
    public function guardarInformacion(Request $request)
    {
         $zarlaf = sarlaft::create($request->all());

        return response()->json([
            'message' => 'informacion guardad con exito',
            'id'=> $zarlaf->id
        ], 200);
    }
    public function guardarRepresentante(Request $request)
    {
         $repre_legal = representante_legal::create($request->all());
         $repre_legal = representante_legal::where('sarlafs_id',$request->sarlafs_id)->get();

        return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);
    }
    public function guardarSocio(Request $request)
    {
         socio::create($request->all());
         $socios=socio::where('sarlafs_id',$request->sarlafs_id)->get();

        return response()->json($socios);
    }
    public function eliminarSocios(socio $socio)
    {
        $id=$socio->sarlafs_id;
        $socio->delete();
        $socios=socio::where('sarlafs_id',$id)->get();
        return response()->json($socios);
    }
    public function guardarPersonaExpuesta(Request $request)
    {
         persona_expuesta::create($request->all());
         $PersonaExpuesta=persona_expuesta::where('sarlafs_id',$request->sarlafs_id)->get();

        return response()->json($PersonaExpuesta);
    }
    public function eliminarPersonaExpuesta(persona_expuesta $PersonaExpuesta)
    {
        $id=$PersonaExpuesta->sarlafs_id;
        $PersonaExpuesta->delete();
        $PersonaExpuesta=persona_expuesta::where('sarlafs_id',$id)->get();
        return response()->json($PersonaExpuesta);
    }
    public function guardarInformacionFinaciera(Request $request)
    {
        informacion_financiera::create($request->all());
        $InformacionFinaciera=informacion_financiera::where('sarlafs_id',$request->sarlafs_id)->get();

         return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);
    }
    public function guardarActividadesInternacionales(Request $request)
    {
        actividad_internacional::create($request->all());
        $ActividadesInternacionales=actividad_internacional::where('sarlafs_id',$request->sarlafs_id)->get();

         return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);
    }
    public function guardarDeclaracionFondos(Request $request)
    {
        Declaracion_fondos::create($request->all());
        $DeclararFondos=Declaracion_fondos::where('sarlafs_id',$request->sarlafs_id)->get();

         return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);
    }

    public function guardarAdjuntos($sarlafs, Request $request)
    {
        if($request->hasFile('registroUnico')){

            $file = $request->file('registroUnico');
            $filename = $file->getClientOriginalName();
            $path = '../storage/app/public/adjuntosSarlaft/'.$sarlafs;
            $paths = '/storage/adjuntosSarlaft/'.$sarlafs.'/'.time().$filename;
            $file->move($path, time().$filename);

            adjunto_sarlaft::create([
                'sarlafs_id' => $sarlafs,
                'nombre'    => $filename,
                'ruta'      => $paths
            ]);
        }

        if($request->hasFile('cedualRepresentante')){

           $file = $request->file('cedualRepresentante');
           $filename = $file->getClientOriginalName();
           $path ='../storage/app/public/adjuntosSarlaft'.$sarlafs;
           $paths ='/storage/adjuntosSarlaft/'.$sarlafs.'/'.time().$filename;
           $file->move($path,time().$filename);

           adjunto_sarlaft::create([
            'sarlafs_id' => $sarlafs,
            'nombre'    => $filename,
            'ruta'      => $paths
           ]);
        }

        if($request->hasFile('certificadoRepresentacion')){

            $file = $request->file('certificadoRepresentacion');
            $filename = $file->getClientOriginalName();
            $path ='../storage/app/public/adjuntosSarlaft'.$sarlafs;
            $paths ='/storage/adjuntosSarlaft/'.$sarlafs.'/'.time().$filename;
            $file->move($path,time().$filename);

            adjunto_sarlaft::create([
             'sarlafs_id' => $sarlafs,
             'nombre'    => $filename,
             'ruta'      => $paths
            ]);
         }

         if($request->hasFile('decretoPosesion')){

            $file = $request->file('decretoPosesion');
            $filename = $file->getClientOriginalName();
            $path ='../storage/app/public/adjuntosSarlaft'.$sarlafs;
            $paths ='/storage/adjuntosSarlaft/'.$sarlafs.'/'.time().$filename;
            $file->move($path,time().$filename);

            adjunto_sarlaft::create([
             'sarlafs_id' => $sarlafs,
             'nombre'    => $filename,
             'ruta'      => $paths
            ]);
         }

         if($request->hasFile('certificacionBancaria')){

            $file = $request->file('certificacionBancaria');
            $filename = $file->getClientOriginalName();
            $path ='../storage/app/public/adjuntosSarlaft'.$sarlafs;
            $paths ='/storage/adjuntosSarlaft/'.$sarlafs.'/'.time().$filename;
            $file->move($path,time().$filename);

            adjunto_sarlaft::create([
             'sarlafs_id' => $sarlafs,
             'nombre'    => $filename,
             'ruta'      => $paths
            ]);
         }

         if($request->hasFile('formatoDiligenciado')){

            $file = $request->file('formatoDiligenciado');
            $filename = $file->getClientOriginalName();
            $path ='../storage/app/public/adjuntosSarlaft'.$sarlafs;
            $paths ='/storage/adjuntosSarlaft/'.$sarlafs.'/'.time().$filename;
            $file->move($path,time().$filename);

            adjunto_sarlaft::create([
             'sarlafs_id' => $sarlafs,
             'nombre'    => $filename,
             'ruta'      => $paths
            ]);
         }
            return response()->json([
            'message' => 'Adjunto guardado con exito!'
            ], 201);
    }

    public function guardarEspecialidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:5|unique:especialidad_medicas,nombre'
        ]);

        if ($validator->fails()) {
            $errores = $validator->errors();
            return response()->json($errores, 422);
        }
        $request['nombre'] = strtoupper($request->nombre);
        especialidadMedica::create($request->all());
        return response()->json([
            'message' => 'Cargo creado con exito!'
        ]);
    }

    public function allEspecialidades()
    {
        $codepropio = especialidadMedica::all(['id', 'nombre']);
        return response()->json($codepropio, 200);
    }
}
