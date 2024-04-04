<?php

namespace App\Http\Controllers\Tutelas;

use App\Http\Controllers\Controller;
use App\Modelos\Adjuntos_tutelas;
use App\Modelos\Cuptutela;
use App\Modelos\Exclusiontutela;
use App\Modelos\Medicamentotutela;
use App\Modelos\Paciente;
use App\Modelos\Responsabletutela;
use App\Modelos\Respuestatutela;
use App\Modelos\Roltutela;
use App\Modelos\Tiposerviciotutela;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use App\Modelos\Tutelas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class TutelaController extends Controller
{
    public function all(Request $request)
    {
        $pacientes = Paciente::select(['Pacientes.*'])
            ->where('entidad_id',auth()->user()->entidad_id)
            ->where('Tienetutela', 1);
        if (isset($request->search)) {
            $pacientes = $pacientes->where('Num_Doc', $request->search)
                ->orWhereRaw("CONCAT(Primer_Nom,' ',SegundoNom) like '%$request->search%'")
                ->orWhereRaw("CONCAT(Primer_Nom,' ',Primer_Ape) like '%$request->search%'")
                ->orWhereRaw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape) like '%$request->search%'")
                ->orWhereRaw("CONCAT(Primer_Nom,' ',Primer_Ape,' ',Segundo_Ape) like '%$request->search%'")
                ->orWhereRaw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) like '%$request->search%'");
        }
        $pacientes = $pacientes->paginate(5);

        return response()->json($pacientes, 201);
    }

    public function activo($cedula)
    {
        $paciente = Paciente::select('Pacientes.*')
            ->where('Num_Doc', $cedula)
            ->where('entidad_id',auth()->user()->entidad_id)->first();

        if (!isset($paciente)) {
            return response()->json([
                'message' => '¡El paciente consultado no existe en nuestra base de datos validar en HOSVITAL si cambio de UT!',
            ]);
        } elseif ($paciente->Estado_Afiliado == 27) {
            return response()->json([
                'message' => '¡El paciente se encuentra retirado de Sumimedical!',
            ], 200);
        } elseif ($paciente->Tienetutela == 1) {
            return response()->json([
                'message' => '¡El paciente ya se encuentra marcado!',
            ], 200);
        }

        return response()->json([
            'paciente' => $paciente,
        ], 200);
    }

    public function marcacion(Paciente $paciente)
    {
        $paciente = Paciente::find($paciente->id);
        $paciente->update([
            'Tienetutela' => '1',
        ]);

        return response()->json([
            'message' => '¡Paciente marcado con exito!',
        ], 200);
    }

    public function store(Request $request)
    {

        $cedula = Paciente::select('Num_Doc')
            ->where('id', $request->pacienteid)->first();

        $cedulapaciente = $cedula->Num_Doc;
        $file_name      = [];
        $paths          = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path          = '../storage/app/public/adjuntostutelas/' . $cedulapaciente;
                $paths[$i]     = '/storage/adjuntostutelas/' . $cedulapaciente . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $validate = Validator::make($request->all(), [
            'pacienteid'      => 'required',
            'fecharadicacion' => 'required',
            'radicado'        => 'required',
            'direccion'       => 'required',
            'telefono'        => 'required',
            'municipio'       => 'required',
            'juzgado'         => 'required',
            'Newconti'        => 'required',
            'exclusion'       => 'required',
            'integralidad'    => 'required',
            'requerimiento'   => 'required',
            'responsable'     => 'required',
            'observacion'     => 'required',
            'tiposervicio'    => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        if (isset($request->medicinalaboralotro) || isset($request->reembolsotro)
            || isset($request->transporteotro)) {
            $medicina   = $request->medicinalaboralotro;
            $reembolso  = $request->reembolsotro;
            $transporte = $request->transporteotro;
        } else {
            $medicina   = $request->medicinalaboral;
            $reembolso  = $request->reembolso;
            $transporte = $request->transporte;
        }

        $Asignartutela                        = new Tutelas;
        $Asignartutela->User_id               = auth()->user()->id;
        $Asignartutela->Paciente_id           = $request->pacienteid;
        $Asignartutela->Municipio_id          = $request->municipio;
        $Asignartutela->Juzgado_id            = $request->juzgado;
        $Asignartutela->Tipo_requerimiento_id = $request->requerimiento;
        $Asignartutela->Direccion             = $request->direccion;
        $Asignartutela->Telefono              = $request->telefono;
        $Asignartutela->Radicado              = $request->radicado;
        $Asignartutela->Exclusion             = $request->exclusion;
        $Asignartutela->Integralidad          = $request->integralidad;
        $Asignartutela->New_conti             = $request->Newconti;
        $Asignartutela->Fecha_radica          = $request->fecharadicacion;
        $Asignartutela->Observacion           = $request->observacion;
        $Asignartutela->Novedadregistro       = $request->novedadregistro;
        $Asignartutela->Gestiondocumental     = $request->gestiondocumental;
        $Asignartutela->Medicinalaboral       = $medicina;
        $Asignartutela->Reembolso             = $reembolso;
        $Asignartutela->Transporte            = $transporte;
        $Asignartutela->save();

        $responsable = $request->responsable;
        $roles       = Role::select(['id'])
            ->whereIn('name', $responsable)
            ->get();

        $rolids = [];
        foreach ($roles as $rol) {
            array_push($rolids, $rol->id);
        }

        if (isset($rolids)) {
            $rol_id = [];
            $id     = 0;
            foreach ($rolids as $rolid) {
                $rol_id[$id] = $rolid;
                $id++;
            }
            for ($id = 0; $id < count($rol_id); $id++) {
                $roltutela            = new Roltutela;
                $roltutela->Tutela_id = $Asignartutela->id;
                $roltutela->Rol_id    = $rol_id[$id];
                $roltutela->Estado_id = 1;
                $roltutela->save();
            }
        }

        $tutela = $Asignartutela->id;

        $users = Role::join('model_has_roles as ru', 'ru.role_id', 'roles.id')
            ->join('users as us', 'us.id', 'ru.model_id')
            ->select(['roles.name as name', 'us.name as nameu', 'us.email as email'])
            ->whereIn('roles.id', $rolids)
            ->get();

        // $email = Mail::send('email_tutelas',
        //     ['users' => $users, 'tutela' => $tutela, 'cedula' => $cedula], function ($m) use ($users, $tutela, $cedula) {
        //         foreach ($users as $user) {
        //             $m->to($user->email, $user->name)->subject('Notificación Tutelas');
        //         }
        //     });

        if (isset($request->medicamentos)) {
            $medicamentos   = $request->medicamentos;
            $medicamento_id = [];
            $medi           = 0;
            foreach ($medicamentos as $medicamento) {
                $medicamento_id[$medi] = $medicamento;
                $medi++;
            }
            for ($medi = 0; $medi < count($medicamento_id); $medi++) {
                $medicamento                     = new Medicamentotutela;
                $medicamento->Tutela_id          = $Asignartutela->id;
                $medicamento->Detallearticulo_id = $medicamento_id[$medi];
                $medicamento->save();
            }
        }

        if (isset($request->exclusiones)) {
            $exclusiones    = $request->exclusiones;
            $exclusion_name = [];
            $exc            = 0;
            foreach ($exclusiones as $exclusion) {
                $exclusion_name[$exc] = $exclusion;
                $exc++;
            }
            for ($exc = 0; $exc < count($exclusion_name); $exc++) {
                $exclusion            = new Exclusiontutela;
                $exclusion->Tutela_id = $Asignartutela->id;
                $exclusion->Nombre    = $exclusion_name[$exc];
                $exclusion->save();
            }
        }

        if (isset($request->servicios)) {
            $servicios   = $request->servicios;
            $servicio_id = [];
            $serv        = 0;
            foreach ($servicios as $servicio) {
                $servicio_id[$serv] = $servicio;
                $serv++;
            }
            for ($serv = 0; $serv < count($servicio_id); $serv++) {
                $servicio            = new Cuptutela;
                $servicio->Tutela_id = $Asignartutela->id;
                $servicio->Cup_id    = $servicio_id[$serv];
                $servicio->save();
            }
        }

        if (isset($request->otrotiposervicio)) {
            $tiposservicios    = $request->otrotiposervicio;
            $tiposervicio_name = [];
            $t                 = 0;
            foreach ($tiposservicios as $tiposservicio) {
                $tiposervicio_name[$t] = $tiposservicio;
                $t++;
            }
        } else {
            $tiposservicios    = $request->tiposervicio;
            $tiposervicio_name = [];
            $t                 = 0;
            foreach ($tiposservicios as $tiposservicio) {
                $tiposervicio_name[$t] = $tiposservicio;
                $t++;
            }
        }

        for ($t = 0; $t < count($tiposervicio_name); $t++) {
            $tiposervicio            = new Tiposerviciotutela;
            $tiposervicio->Tutela_id = $Asignartutela->id;
            $tiposervicio->Nombre    = $tiposervicio_name[$t];
            $tiposervicio->save();
        }

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto            = new Adjuntos_tutelas;
            $adjunto->Tutela_id = $Asignartutela->id;
            $adjunto->Nombre    = $file_name[$i];
            $adjunto->Ruta      = $paths[$i];
            $adjunto->save();
        }

        return response()->json([
            'message' => 'Tutela asignada con exito!',
        ], 201);
    }

    public function asignados(Request $request)
    {
        if ($request->estado == "Gestionado") {
            $estadoTutela = [19];
        } else if ($request->estado == "Pendiente") {
            $estadoTutela = [18, 11, 24];
        } else {
            $estadoTutela = [14];
        }
        $roles    = Auth::user()->roles;
        $ids      = [];
        $rolnames = [];
        foreach ($roles as $rol) {
            array_push($ids, $rol->id);
            array_push($rolnames, $rol->name);
        }
        $admintutelas = array_search("Admin tutelas", $rolnames);
        $admin        = var_export($admintutelas, true);

        if ($admin == "false") {
            $asignados = Tutelas::select(['Tutelas.*', 'Estados.Nombre as Estado', 'Pacientes.Tipo_Doc as Tdocumento',
                'Pacientes.Num_Doc as Documento', 'Pacientes.Primer_Nom as Nombre', 'Pacientes.Primer_Ape as Apellido',
                'tiporequerimientos.Dias as DiasTr', 'Municipios.Nombre as Municipio', 'Pacientes.Ut',
                'Jusgados.Nombre as Juzgado', 'tiporequerimientos.Nombre as Requerimiento',
                'observacion.name as NombreObservacion', 'observacion.apellido as ApellidoObservacion', 'quienreasigno.name as NombreR',
                'quienreasigno.apellido as ApellidoR'])
                ->join('Estados', 'Tutelas.Estado_id', 'Estados.id')
                ->join('tiporequerimientos', 'Tutelas.Tipo_requerimiento_id', 'tiporequerimientos.id')
                ->join('Pacientes', 'Tutelas.Paciente_id', 'Pacientes.id')
                ->join('Municipios', 'Tutelas.Municipio_id', 'Municipios.id')
                ->join('Jusgados', 'Tutelas.Juzgado_id', 'Jusgados.id')
                ->leftjoin('Roltutelas', 'Roltutelas.Tutela_id', 'Tutelas.id')
                ->join('Users as observacion', 'Tutelas.User_id', 'observacion.id')
                ->leftjoin('Users as quienreasigno', 'Tutelas.Quienrea_id', 'quienreasigno.id')
                ->with(['Adjuntos_tutelas' => function ($query) {
                    $query->select('Ruta', 'Tutela_id')
                        ->get();
                }])
                ->with(['Tiposerviciotutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Exclusiontutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Medicamentotutelas' => function ($query) {
                    $query->select('Medicamentotutelas.Detallearticulo_id', 'Medicamentotutelas.Tutela_id',
                        'de.Producto as Medicamento')
                        ->join('Detallearticulos as de', 'Medicamentotutelas.Detallearticulo_id', 'de.id')
                        ->get();
                }])
                ->with(['Cuptutelas' => function ($query) {
                    $query->select('Cup_id', 'Tutela_id', 'cup.Nombre as Nombrecup')
                        ->join('Cups as cup', 'Cuptutelas.Cup_id', 'cup.id')
                        ->get();
                }])
                ->with(['Respuestatutelas' => function ($query) {
                    $query->select('Respuestatutelas.Respuesta', 'Respuestatutelas.tipoactuacion','Respuestatutelas.Tutela_id', 'Respuestatutelas.created_at',
                        'Respuestatutelas.id', 'Users.name as Nombre', 'Users.apellido as Apellido', 'Respuestatutelas.Responsable')
                        ->join('Users', 'Respuestatutelas.User_id', 'Users.id')
                        ->with(['Adjuntos_respuestas' => function ($query) {
                            $query->select('Ruta', 'Respuesta_id')
                                ->get();
                        }])
                        ->orderBy('Respuestatutelas.created_at', 'asc')
                        ->get();
                }])
                ->with(['Roltutelas' => function ($query) {
                    $query->select('Tutela_id', 'Roles.name as NombreRol')
                        ->join('Roles', 'Roltutelas.Rol_id', 'Roles.id')
                        ->where('Estado_id', 1)
                        ->get();
                }])
                ->where('Roltutelas.Estado_id', 1)
                ->where('Pacientes.entidad_id',auth()->user()->entidad_id)
                ->whereIn('Estados.id', $estadoTutela)
                ->whereIn('Roltutelas.Rol_id', $ids)
                ->distinct()
                ->get();
            return response()->json($asignados, 201);
        } else {
            $asignados = Tutelas::select(['Tutelas.*', 'Estados.Nombre as Estado', 'Pacientes.Tipo_Doc as Tdocumento',
                'Pacientes.Num_Doc as Documento', 'Pacientes.Primer_Nom as Nombre', 'Pacientes.Primer_Ape as Apellido',
                'tiporequerimientos.Dias as DiasTr', 'Municipios.Nombre as Municipio', 'Pacientes.Ut',
                'Jusgados.Nombre as Juzgado', 'tiporequerimientos.Nombre as Requerimiento',
                'observacion.name as NombreObservacion', 'observacion.apellido as ApellidoObservacion',
                'quienreasigno.name as NombreR',
                'quienreasigno.apellido as ApellidoR'])
                ->join('Estados', 'Tutelas.Estado_id', 'Estados.id')
                ->join('tiporequerimientos', 'Tutelas.Tipo_requerimiento_id', 'tiporequerimientos.id')
                ->join('Pacientes', 'Tutelas.Paciente_id', 'Pacientes.id')
                ->join('Municipios', 'Tutelas.Municipio_id', 'Municipios.id')
                ->join('Jusgados', 'Tutelas.Juzgado_id', 'Jusgados.id')
                ->leftjoin('Roltutelas', 'Roltutelas.Tutela_id', 'Tutelas.id')
                ->join('Users as observacion', 'Tutelas.User_id', 'observacion.id')
                ->leftjoin('Users as quienreasigno', 'Tutelas.Quienrea_id', 'quienreasigno.id')
                ->with(['Adjuntos_tutelas' => function ($query) {
                    $query->select('Ruta', 'Tutela_id')
                        ->get();
                }])
                ->with(['Tiposerviciotutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Exclusiontutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Medicamentotutelas' => function ($query) {
                    $query->select('Medicamentotutelas.Detallearticulo_id', 'Medicamentotutelas.Tutela_id',
                        'de.Producto as Medicamento')
                        ->join('Detallearticulos as de', 'Medicamentotutelas.Detallearticulo_id', 'de.id')
                        ->get();
                }])
                ->with(['Cuptutelas' => function ($query) {
                    $query->select('Cup_id', 'Tutela_id', 'cup.Nombre as Nombrecup')
                        ->join('Cups as cup', 'Cuptutelas.Cup_id', 'cup.id')
                        ->get();
                }])
                ->with(['Respuestatutelas' => function ($query) {
                    $query->select('Respuestatutelas.Respuesta','Respuestatutelas.tipoactuacion', 'Respuestatutelas.Tutela_id', 'Respuestatutelas.created_at',
                        'Respuestatutelas.id', 'Users.name as Nombre', 'Users.apellido as Apellido', 'Respuestatutelas.Responsable')
                        ->join('Users', 'Respuestatutelas.User_id', 'Users.id')
                        ->with(['Adjuntos_respuestas' => function ($query) {
                            $query->select('Ruta', 'Respuesta_id')
                                ->get();
                        }])
                        ->orderBy('Respuestatutelas.created_at', 'asc')
                        ->get();
                }])
                ->with(['Roltutelas' => function ($query) {
                    $query->select('Tutela_id', 'Roles.name as NombreRol')
                        ->join('Roles', 'Roltutelas.Rol_id', 'Roles.id')
                        ->where('Estado_id', 1)
                        ->get();
                }])
                ->whereIn('Estados.id', $estadoTutela)
                ->where('Pacientes.entidad_id',auth()->user()->entidad_id)
                ->distinct()
                ->get();
            return response()->json($asignados, 201);
        }
    }

    public function cerradas()
    {
        $roles    = Auth::user()->roles;
        $ids      = [];
        $rolnames = [];
        foreach ($roles as $rol) {
            array_push($ids, $rol->id);
            array_push($rolnames, $rol->name);
        }
        $admintutelas = array_search("Admin tutelas", $rolnames);
        $admin        = var_export($admintutelas, true);

        if ($admin == "false") {
            $cerradas = Tutelas::select(['Tutelas.*', 'Estados.Nombre as Estado', 'Pacientes.Tipo_Doc as Tdocumento',
                'Pacientes.Num_Doc as Documento', 'Pacientes.Primer_Nom as Nombre', 'Pacientes.Primer_Ape as Apellido',
                'tiporequerimientos.Dias as DiasTr', 'Municipios.Nombre as Municipio', 'Pacientes.Ut',
                'Jusgados.Nombre as Juzgado', 'tiporequerimientos.Nombre as Requerimiento',
                'observacion.name as NombreObservacion', 'observacion.apellido as ApellidoObservacion', 'quienreasigno.name as NombreR',
                'quienreasigno.apellido as ApellidoR', 'quiencerro.name as NombreCerro', 'quiencerro.apellido as ApellidoCerro'])
                ->join('Estados', 'Tutelas.Estado_id', 'Estados.id')
                ->join('tiporequerimientos', 'Tutelas.Tipo_requerimiento_id', 'tiporequerimientos.id')
                ->join('Pacientes', 'Tutelas.Paciente_id', 'Pacientes.id')
                ->join('Municipios', 'Tutelas.Municipio_id', 'Municipios.id')
                ->join('Jusgados', 'Tutelas.Juzgado_id', 'Jusgados.id')
                ->leftjoin('Roltutelas', 'Roltutelas.Tutela_id', 'Tutelas.id')
                ->join('Users as observacion', 'Tutelas.User_id', 'observacion.id')
                ->join('Users as quiencerro', 'Tutelas.Quiencerro_id', 'quiencerro.id')
                ->leftjoin('Users as quienreasigno', 'Tutelas.Quienrea_id', 'quienreasigno.id')
                ->with(['Adjuntos_tutelas' => function ($query) {
                    $query->select('Ruta', 'Tutela_id')
                        ->get();
                }])
                ->with(['Tiposerviciotutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Exclusiontutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Medicamentotutelas' => function ($query) {
                    $query->select('Medicamentotutelas.Detallearticulo_id', 'Medicamentotutelas.Tutela_id',
                        'de.Producto as Medicamento')
                        ->join('Detallearticulos as de', 'Medicamentotutelas.Detallearticulo_id', 'de.id')
                        ->get();
                }])
                ->with(['Cuptutelas' => function ($query) {
                    $query->select('Cup_id', 'Tutela_id', 'cup.Nombre as Nombrecup')
                        ->join('Cups as cup', 'Cuptutelas.Cup_id', 'cup.id')
                        ->get();
                }])
                ->with(['Respuestatutelas' => function ($query) {
                    $query->select('Respuestatutelas.Respuesta', 'Respuestatutelas.Tutela_id', 'Respuestatutelas.created_at',
                        'Respuestatutelas.id', 'Users.name as Nombre', 'Users.apellido as Apellido', 'Respuestatutelas.Responsable')
                        ->join('Users', 'Respuestatutelas.User_id', 'Users.id')
                        ->with(['Adjuntos_respuestas' => function ($query) {
                            $query->select('Ruta', 'Respuesta_id')
                                ->get();
                        }])
                        ->get();
                }])
                ->with(['Roltutelas' => function ($query) {
                    $query->select('Tutela_id', 'Roles.name as NombreRol')
                        ->join('Roles', 'Roltutelas.Rol_id', 'Roles.id')
                        ->where('Estado_id', 2)
                        ->get();
                }])
                ->where('Estados.id', 13)
                ->where('Pacientes.entidad_id',auth()->user()->entidad_id)
                ->whereIn('Roltutelas.Rol_id', $ids)
                ->distinct()
                ->get();
            return response()->json($cerradas, 201);
        } else {
            $cerradas = Tutelas::select(['Tutelas.*', 'Estados.Nombre as Estado', 'Pacientes.Tipo_Doc as Tdocumento',
                'Pacientes.Num_Doc as Documento', 'Pacientes.Primer_Nom as Nombre', 'Pacientes.Primer_Ape as Apellido',
                'tiporequerimientos.Dias as DiasTr', 'Municipios.Nombre as Municipio', 'Pacientes.Ut',
                'Jusgados.Nombre as Juzgado', 'tiporequerimientos.Nombre as Requerimiento',
                'observacion.name as NombreObservacion', 'observacion.apellido as ApellidoObservacion', 'quienreasigno.name as NombreR',
                'quienreasigno.apellido as ApellidoR', 'quiencerro.name as NombreCerro', 'quiencerro.apellido as ApellidoCerro'])
                ->join('Estados', 'Tutelas.Estado_id', 'Estados.id')
                ->join('tiporequerimientos', 'Tutelas.Tipo_requerimiento_id', 'tiporequerimientos.id')
                ->join('Pacientes', 'Tutelas.Paciente_id', 'Pacientes.id')
                ->join('Municipios', 'Tutelas.Municipio_id', 'Municipios.id')
                ->join('Jusgados', 'Tutelas.Juzgado_id', 'Jusgados.id')
                ->leftjoin('Roltutelas', 'Roltutelas.Tutela_id', 'Tutelas.id')
                ->join('Users as observacion', 'Tutelas.User_id', 'observacion.id')
                ->join('Users as quiencerro', 'Tutelas.Quiencerro_id', 'quiencerro.id')
                ->leftjoin('Users as quienreasigno', 'Tutelas.Quienrea_id', 'quienreasigno.id')
                ->with(['Adjuntos_tutelas' => function ($query) {
                    $query->select('Ruta', 'Tutela_id')
                        ->get();
                }])
                ->with(['Tiposerviciotutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Exclusiontutelas' => function ($query) {
                    $query->select('Nombre', 'Tutela_id')
                        ->get();
                }])
                ->with(['Medicamentotutelas' => function ($query) {
                    $query->select('Medicamentotutelas.Detallearticulo_id', 'Medicamentotutelas.Tutela_id',
                        'de.Producto as Medicamento')
                        ->join('Detallearticulos as de', 'Medicamentotutelas.Detallearticulo_id', 'de.id')
                        ->get();
                }])
                ->with(['Cuptutelas' => function ($query) {
                    $query->select('Cup_id', 'Tutela_id', 'cup.Nombre as Nombrecup')
                        ->join('Cups as cup', 'Cuptutelas.Cup_id', 'cup.id')
                        ->get();
                }])
                ->with(['Respuestatutelas' => function ($query) {
                    $query->select('Respuestatutelas.Respuesta', 'Respuestatutelas.Tutela_id', 'Respuestatutelas.created_at',
                        'Respuestatutelas.id', 'Users.name as Nombre', 'Users.apellido as Apellido', 'Respuestatutelas.Responsable')
                        ->join('Users', 'Respuestatutelas.User_id', 'Users.id')
                        ->with(['Adjuntos_respuestas' => function ($query) {
                            $query->select('Ruta', 'Respuesta_id')
                                ->get();
                        }])
                        ->get();
                }])
                ->with(['Roltutelas' => function ($query) {
                    $query->select('Tutela_id', 'Roles.name as NombreRol')
                        ->join('Roles', 'Roltutelas.Rol_id', 'Roles.id')
                        ->where('Estado_id', 2)
                        ->get();
                }])
                ->where('Estados.id', 13)
                ->where('Pacientes.entidad_id',auth()->user()->entidad_id)
                ->distinct()
                ->get();
            return response()->json($cerradas, 201);
        }

    }

    public function respuestas(Request $request)
    {

        $user      = auth()->user()->id;
        $file_name = [];
        $paths     = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path          = '../storage/app/public/adjuntostutelas/' . $user;
                $paths[$i]     = '/storage/adjuntostutelas/' . $user . '/' . time() . $file_name[$i];
                $file->move($path, time() . $file_name[$i]);
                $i++;
            }
        }

        $validate = Validator::make($request->all(), [
            'respuesta'             => 'required',
            'responsablerespuesta'  => 'required',
            'tiporespuesta'         => 'required',
            'tipoactuacion'         => 'required',

        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $Respuesta              = new Respuestatutela;
        $Respuesta->Tutela_id   = $request->tutelaid;
        $Respuesta->User_id     = auth()->user()->id;
        $Respuesta->Respuesta   = $request->respuesta;
        $Respuesta->Responsable = $request->responsablerespuesta;
        $Respuesta->tipoactuacion = $request->tipoactuacion;
        $Respuesta->save();

        for ($i = 0; $i < count($paths); $i++) {
            $adjunto               = new Adjuntos_tutelas;
            $adjunto->Respuesta_id = $Respuesta->id;
            $adjunto->Nombre       = $file_name[$i];
            $adjunto->Ruta         = $paths[$i];
            $adjunto->save();
        }

        if ($request->tiporespuesta == "FINAL") {

            $rol       = Role::select('id')->where('name', $request->responsablerespuesta)->first();
            $roltutela = Roltutela::where('Tutela_id', $request->tutelaid)
                ->where('Rol_id', $rol->id)
                ->update([
                    'Estado_id' => 2,
                ]);

            $getroles  = Roltutela::where('Estado_id', 2)->where('Tutela_id', $request->tutelaid)->get('Rol_id');
            $cuentarol = count($getroles);

            $tutela_estado = Roltutela::where('Tutela_id', $request->tutelaid)
                ->where('Estado_id', '!=', 26)
                ->count();

            if ($cuentarol == $tutela_estado) {
                Tutelas::where('id', $request->tutelaid)
                    ->update([
                        'Estado_id' => 19,
                    ]);
            }
        }

        return response()->json([
            'message' => '¡Respuesta guardada con exito!',
        ], 201);
    }

    public function cerrartutela(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'motivoclosetutela' => 'required',
            'tipocierre'        => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        if ($request->tipocierre == "Cerrado Temporal") {
            Tutelas::where('id', $request->tutelaid)
                ->update([
                    'Estado_id'     => 14,
                    'Motivo_cerrar' => $request->motivoclosetutela,
                    'Quiencerro_id' => auth()->user()->id,
                    'Fecha_cerrada' => Carbon::now(),
                ]);
        } else {
            Tutelas::where('id', $request->tutelaid)
                ->update([
                    'Estado_id'     => 13,
                    'Motivo_cerrar' => $request->motivoclosetutela,
                    'Quiencerro_id' => auth()->user()->id,
                    'Fecha_cerrada' => Carbon::now(),
                ]);
        }

        return response()->json([
            'message' => 'Tutela cerrada con exito!',
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

        $responsable = $request->responsable;
        $roles       = Role::select(['id'])
            ->whereIn('name', $responsable)
            ->get();

        $rolids = [];
        foreach ($roles as $rol) {
            array_push($rolids, $rol->id);
        }

        $users = Role::join('model_has_roles as ru', 'ru.role_id', 'roles.id')
            ->join('users as us', 'us.id', 'ru.model_id')
            ->select(['roles.name as name', 'us.name as nameu', 'us.email as email'])
            ->whereIn('roles.id', $rolids)
            ->get();

        $tutela = $request->tutelaid;
        $cedula = $request;

        // $email = Mail::send('email_tutelas',
        //     ['users' => $users, 'tutela' => $tutela, 'cedula' => $cedula], function ($m) use ($users, $tutela, $cedula) {
        //         foreach ($users as $user) {
        //             $m->to($user->email, $user->name)->subject('Notificación Tutelas');
        //         }
        //     });

        $roltutelas = Roltutela::where('Tutela_id', $request->tutelaid)
            ->where('Estado_id', '!=', 26)
            ->get(['Rol_id']);

        $roltutelasup = Roltutela::where('Tutela_id', $request->tutelaid)
            ->where('Estado_id', 1)
            ->update([
                'Estado_id' => 26,
            ]);

        $roltid = [];
        foreach ($roltutelas as $roltut) {
            array_push($roltid, $roltut->Rol_id);
        }

        $nuevosroltutela = array_diff($rolids, $roltid);

        if (isset($nuevosroltutela)) {
            $rol_id = [];
            $id     = 0;
            foreach ($nuevosroltutela as $rolid) {
                $rol_id[$id] = $rolid;
                $id++;
            }
            for ($id = 0; $id < count($rol_id); $id++) {
                $roltutela            = new Roltutela;
                $roltutela->Tutela_id = $request->tutelaid;
                $roltutela->Rol_id    = $rol_id[$id];
                $roltutela->Estado_id = 1;
                $roltutela->save();
            }
        }

        $updatestadorol = Roltutela::where('Tutela_id', $request->tutelaid)
            ->whereIn('Rol_id', $rolids)
            ->update([
                'Estado_id' => 1,
            ]);

        if ($request->estado == "Parcial") {
            Tutelas::where('id', $request->tutelaid)
                ->update([
                    'Motivoreasignar' => $request->motivo,
                    'Quienrea_id'     => auth()->user()->id,
                    'Fecharea'        => Carbon::now(),
                    'Estado_id'       => 24,
                ]);
        } else {
            Tutelas::where('id', $request->tutelaid)
                ->update([
                    'Motivoreasignar' => $request->motivo,
                    'Quienrea_id'     => auth()->user()->id,
                    'Fecharea'        => Carbon::now(),
                    'Estado_id'       => 11,
                ]);
        }

        return response()->json([
            'message' => 'Tutela reasignada con exito!',
        ], 200);

    }

    public function compartir(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'responsable' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $responsable = $request->responsable;
        $roles       = Role::select(['id'])
            ->whereIn('name', $responsable)
            ->get();

        $rolids = [];
        foreach ($roles as $rol) {
            array_push($rolids, $rol->id);
        }

        $users = Role::join('model_has_roles as ru', 'ru.role_id', 'roles.id')
            ->join('users as us', 'us.id', 'ru.model_id')
            ->select(['roles.name as name', 'us.name as nameu', 'us.email as email'])
            ->whereIn('roles.id', $rolids)
            ->get();

        $tutela = $request->tutelaid;
        $cedula = $request;

        // $email = Mail::send('email_tutelas',
        //     ['users' => $users, 'tutela' => $tutela, 'cedula' => $cedula], function ($m) use ($users, $tutela, $cedula) {
        //         foreach ($users as $user) {
        //             $m->to($user->email, $user->name)->subject('Notificación Tutelas');
        //         }
        //     });

        if (isset($rolids)) {
            $rol_id = [];
            $id     = 0;
            foreach ($rolids as $rolid) {
                $rol_id[$id] = $rolid;
                $id++;
            }
            for ($id = 0; $id < count($rol_id); $id++) {
                $roltutela            = new Roltutela;
                $roltutela->Tutela_id = $request->tutelaid;
                $roltutela->Rol_id    = $rol_id[$id];
                $roltutela->Estado_id = 1;
                $roltutela->save();
            }
        }

        return response()->json([
            'message' => 'Tutela compartida con exito!',
        ], 200);

    }

    public function allresponsables(Request $request)
    {

        $allresponsables = Responsabletutela::select(['responsabletutelas.id as id', 'Roles.name as NombreRol',
            'Estados.Nombre as NombreEstado'])
            ->join('Roles', 'responsabletutelas.Rol_id', 'Roles.id')
            ->join('Estados', 'responsabletutelas.Estado_id', 'Estados.id')
            ->get();
        return response()->json($allresponsables, 201);
    }

    public function listresponsable(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'Rol_id' => 'required|integer|unique:responsabletutelas',
        ]);

        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $responsable = Responsabletutela::create([
            'Rol_id' => $request->Rol_id,

        ]);

        return response()->json([
            'message' => 'Responsable agregado con Exito!',
        ], 201);
    }

    public function estadoresponsable($id)
    {

        Responsabletutela::where('id', $id)
            ->update([
                'Estado_id' => 2,
            ]);

        return response()->json([
            'message' => '¡Responsable Deshabilitado!',
        ], 200);

    }

    public function enableresponsable($id)
    {

        Responsabletutela::where('id', $id)
            ->update([
                'Estado_id' => 1,
            ]);

        return response()->json([
            'message' => '¡Responsable Habilitado!',
        ], 200);
    }

    public function showresponsables(Request $request)
    {

        $showresponsables = Responsabletutela::select(['responsabletutelas.id as id', 'Roles.name as NombreRol',
            'Estados.Nombre as NombreEstado'])
            ->join('Roles', 'responsabletutelas.Rol_id', 'Roles.id')
            ->join('Estados', 'responsabletutelas.Estado_id', 'Estados.id')
            ->where('responsabletutelas.Estado_id', 1)
            ->get();
        return response()->json($showresponsables, 201);
    }

    public function roles()
    {

        $user  = auth()->user();
        $roles = $user->roles;

        $rolids = [];
        foreach ($roles as $rol) {
            array_push($rolids, $rol->id);
        }
        $rolestutelas = Responsabletutela::whereIn('Rol_id', $rolids)->get('Rol_id');

        $rol_ids = [];
        foreach ($rolestutelas as $rolt) {
            array_push($rol_ids, $rolt->Rol_id);
        }
        $namerol = Role::whereIn('id', $rol_ids)->get('name');

        return response()->json($namerol, 201);
    }

    public function alert(Request $request)
    {
        $roles= Role::select(['id'])
            ->whereIn('name', $request->responsable)
            ->get();

        $rolids = [];
        foreach ($roles as $rol) {
            array_push($rolids, $rol->id);
        }

        $users = Role::join('model_has_roles as ru', 'ru.role_id', 'roles.id')
            ->join('users as us', 'us.id', 'ru.model_id')
            ->select(['roles.name as name', 'us.name as nameu', 'us.email as email'])
            ->whereIn('roles.id', $rolids)
            ->get();

        $cedula = $request->documento;
        $tutelaAlert = $request->tutelaid;

        $email = Mail::send('email_tutelas',
            ['users' => $users, 'tutelaAlert' => $tutelaAlert, 'cedula' => $cedula], function ($m) use ($users, $tutelaAlert, $cedula) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación Tutelas');
                }
            });

        return response()->json([
            'message' => 'Alerta enviada con exito!',
        ], 200);
    }

    public function desvincularRol(Request $request){

        $roltutela = Roltutela::where('Tutela_id', $request->tutelaid)
        ->where('Estado_id', 1)
        ->update([
            'Estado_id' => 2,
        ]);

        Tutelas::where('id', $request->tutelaid)
        ->update([
            'Estado_id' => 19,
        ]);

        return response()->json([
            'message' => 'Tutela desvinculada con exito!',
        ], 200);

    }

    public function updateRequerimiento(Request $request){

        Tutelas::where('id', $request->tutela_id)
        ->update([
            'Tipo_requerimiento_id' => $request->requerimiento_id,
        ]);

        return response()->json([
            'message' => 'Requerimiento actualizado con exito!'
        ], 200);

    }

   public function generarInforme(Request $request) {

    $result = [];
    switch ($request->resolucion) {
        case 1:
            $appointments = Collect(DB::select("exec SP_Reporte_Juridica_Exclusiones ?,?,?",[$request->fechaDesde,$request->fechaHasta,$request->entidad_id]));
            $result = json_decode($appointments, true);
            break;
    }
    return (new FastExcel($result))->download('file.xls');

    }
}
