<?php

namespace App\Http\Controllers\Solicitudes;

use App\User;
use App\Modelos\Tipo;
use App\Modelos\Auditoria;
use Illuminate\Http\Request;
use App\Modelos\RadicacionOnline;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use App\Modelos\Gestion_radicaciononline;
use Illuminate\Support\Facades\Validator;
use App\Modelos\Adjuntos_radicaciononline;
use App\Modelos\Paciente;

class SolicitudesController extends Controller
{
    public function pendientes()
    {
        $solicitudesPendientes = RadicacionOnline::select([
            'radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
            'p.Num_Doc as documento', 'p.Departamento as departamento', 'p.Mpio_Afiliado as municipio',
            'sp.Nombre as ips', 'es.Nombre as estado', 'p.Primer_Nom as pnombre', 'p.Primer_Ape as papellido',
            'p.SegundoNom as snombre', 'p.Segundo_Ape as sapellido', 'p.Tipo_Doc as tipodoc', 'p.Telefono as telefono',
            'p.Celular1 as celular', 'p.Direccion_Residencia as direccion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->where('radicacion_onlines.estado_id', '!=', 13)
            ->with(['gestion' => function ($query) {
                $query->select('radicaciononline_id', 'gestion_radicaciononlines.id', 'u.name', 'u.apellido')
                    ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                    ->where('gestion_radicaciononlines.tipo_id', 20)
                    ->get();
            }])
            ->with(['adjuntoradicado' => function ($query) {
                $query->select('radicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->get();

        foreach ($solicitudesPendientes as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->id)
                ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
            }
        }

        return response()->json($solicitudesPendientes, 201);
    }

    public function pendientesGestion(Request $request)
    {
        $solicitudesPendientesGestion = RadicacionOnline::select([
            'radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
            'p.Num_Doc as documento', 'p.Departamento as departamento', 'p.Mpio_Afiliado as municipio',
            'sp.Nombre as ips', 'es.Nombre as estado', 'p.Primer_Nom as pnombre', 'p.Primer_Ape as papellido',
            'p.SegundoNom as snombre', 'p.Segundo_Ape as sapellido', 'p.Tipo_Doc as tipodoc', 'p.Telefono as telefono',
            'p.Celular1 as celular', 'p.Direccion_Residencia as direccion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->with(['adjuntoradicado' => function ($query) {
                $query->select('radicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->where('radicacion_onlines.estado_id', $request->estado)
            ->whereNotin("radicacion_onlines.id", function ($query) {
                $query->select('radicaciononline_id')
                    ->where('tipo_id', 20)
                    ->from('gestion_radicaciononlines');
            });

        $fechaDesde = $request->desde;
        $fechaHasta = $request->hasta;
        if ($request->desde && $request->hasta) {
            $solicitudesPendientesGestion->whereBetween('radicacion_onlines.created_at', [$fechaDesde . ' 00:00:00.000', $fechaHasta . ' 23:59:00.000']);
        }
        if ($request->documentos) {
            $paciente = Paciente::where('Num_Doc', $request->documentos)->first();
            $solicitudesPendientesGestion->where('radicacion_onlines.paciente_id', $paciente->id);
        }

        if ($request->radicado) {
            $solicitudesPendientesGestion->where('radicacion_onlines.id', $request->radicado);
        }

        if ($request->solicitud) {
            $solicitudesPendientesGestion->where('ts.nombre', $request->solicitud);
        }

        foreach ($solicitudesPendientesGestion as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->id)
                ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
            }
        }

        return response()->json($solicitudesPendientesGestion->get(), 201);
    }

    public function pendientesAsignadas(Request $request)
    {
        $solicitudesPendientesAsignadas = RadicacionOnline::select([
            'radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
            'p.Num_Doc as documento', 'p.Departamento as departamento', 'p.Mpio_Afiliado as municipio',
            'sp.Nombre as ips', 'es.Nombre as estado', 'p.Primer_Nom as pnombre', 'p.Primer_Ape as papellido',
            'p.SegundoNom as snombre', 'p.Segundo_Ape as sapellido', 'p.Tipo_Doc as tipodoc', 'p.Telefono as telefono',
            'p.Celular1 as celular', 'p.Direccion_Residencia as direccion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->where('radicacion_onlines.estado_id', $request->estado)
            ->with(['adjuntoradicado' => function ($query) {
                $query->select('radicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->whereIn("radicacion_onlines.id", function ($query) {
                $query->select('radicaciononline_id')
                    ->where('user_id', Auth::user()->id)
                    ->where('tipo_id', 20)
                    ->from('gestion_radicaciononlines');
            });

        $fechaDesde = $request->desde;
        $fechaHasta = $request->hasta;
        if ($request->desde && $request->hasta) {
            $solicitudesPendientesAsignadas->whereBetween('radicacion_onlines.created_at', [$fechaDesde . ' 00:00:00.000', $fechaHasta . ' 23:59:00.000']);
        }
        if ($request->documentos) {
            $paciente = Paciente::where('Num_Doc', $request->documentos)->first();
            $solicitudesPendientesAsignadas->where('radicacion_onlines.paciente_id', $paciente->id);
        }

        if ($request->radicado) {
            $solicitudesPendientesAsignadas->where('radicacion_onlines.id', $request->radicado);
        }

        if ($request->solicitud) {
            $solicitudesPendientesAsignadas->where('ts.nombre', $request->solicitud);
        }

        foreach ($solicitudesPendientesAsignadas as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->id)
                ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
            }
        }
        return response()->json($solicitudesPendientesAsignadas->get(), 201);
    }

    public function comentar(Request $request)
    {

        if ($request->gestionando == 'true') {

            RadicacionOnline::where('id', $request->radicaciononline_id)
                ->update([
                    'estado_id' => 19,
                ]);
        }

        $tipo = Tipo::where('Nombre', $request->accion)->first();

        $gestion   = new Gestion_radicaciononline();
        $gestion->radicaciononline_id = $request->radicaciononline_id;
        $gestion->User_id     = Auth()->user()->id;
        $gestion->tipo_id     = $tipo->id;
        $gestion->motivo      = $request->motivo;
        $gestion->save();

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');
            $user = Auth()->user()->cedula;

            $i = 0;
            foreach ($files as $file) {
                $fileName[$i] = $file->getClientOriginalName();
                $filenametostore[$i] = '/adjuntosradicacion/' . $user . '/' . $request->radicaciononline_id . '/' . time() . '_' . $fileName[$i];
                Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                $adjunto = new Adjuntos_radicaciononline;
                $adjunto->nombre = $fileName[$i];
                $adjunto->ruta = $filenametostore[$i];
                $adjunto->gestionradicaciononline_id = $gestion->id;
                $adjunto->save();

                $i++;
            }
        }

        if ($request->accion == 'Comentarios al Solicitante') {

            $paciente = RadicacionOnline::select(['pacientes.Primer_Nom', 'pacientes.Correo1'])
                ->join('pacientes', 'radicacion_onlines.paciente_id', 'pacientes.id')
                ->where('radicacion_onlines.id', $request->radicaciononline_id)
                ->first();

            $email = Mail::send(
                'email_alert_radicacion',
                ['radicado_id' => $request->radicaciononline_id, 'tipo' => 'Comentarios al Solicitante', 'name' => $paciente->Primer_Nom],
                function ($m) use ($paciente) {
                    $m->to($paciente->Correo1, $paciente->Primer_Nom)->subject('Notificación Radicación');
                }
            );
        }

        return response()->json([
            'message' => '¡Ha comentado la solicitud con exito!',
        ], 201);
    }

    public function showcomentariosPublicos(Request $request)
    {

        $comentariosPublicos = Gestion_radicaciononline::select(
            'gestion_radicaciononlines.id',
            'radicaciononline_id',
            'tipo_id',
            'motivo',
            'gestion_radicaciononlines.created_at',
            'u.name',
            'u.apellido',
            'tipos.Nombre'
        )
            ->leftjoin('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
            ->join('tipos', 'gestion_radicaciononlines.tipo_id', 'tipos.id')
            ->with(['adjuntosgestion' => function ($query) {
                $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->where('gestion_radicaciononlines.radicaciononline_id', $request->radicaciononline_id)
            ->where('tipos.Nombre', 'Comentarios al Solicitante')
            ->get();

        return response()->json($comentariosPublicos, 201);
    }

    public function showcomentariosPrivados(Request $request)
    {

        $comentariosPrivados = Gestion_radicaciononline::select(
            'gestion_radicaciononlines.id',
            'radicaciononline_id',
            'tipo_id',
            'motivo',
            'gestion_radicaciononlines.created_at',
            'u.name',
            'u.apellido',
            'tipos.Nombre'
        )
            ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
            ->join('tipos', 'gestion_radicaciononlines.tipo_id', 'tipos.id')
            ->with(['adjuntosgestion' => function ($query) {
                $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->where('gestion_radicaciononlines.radicaciononline_id', $request->radicaciononline_id)
            ->where('tipos.Nombre', 'Comentarios Internos')
            ->get();

        return response()->json($comentariosPrivados, 201);
    }

    public function asignar(Request $request)
    {

        RadicacionOnline::where('id', $request->radicaciononline_id)
            ->where('estado_id', 19)
            ->update([
                'estado_id' => 18,
            ]);

        $users = [];

        foreach ($request->users as $user) {
            Gestion_radicaciononline::create([
                'radicaciononline_id' =>  $request->radicaciononline_id,
                'user_id'        => $user,
                'tipo_id' => 20,
                'motivo' => 'Asignado',
            ]);

            $users[] = User::select(['name', 'email'])
                ->where('id', $user)
                ->first();
        }

        $email = Mail::send(
            'email_radicacion',
            ['radicado_id' => $request->radicaciononline_id, 'tipo' => 'asignar'],
            function ($m) use ($users) {
                foreach ($users as $user) {
                    $m->to($user->email, $user->name)->subject('Notificación Radicación');
                }
            }
        );


        return response()->json([
            'message' => '¡Ha asignado la solicitud con exito!',
        ], 201);
    }

    public function respuesta(Request $request)
    {

        RadicacionOnline::where('id', $request->radicaciononline_id)
            ->update([
                'estado_id' => 13,
            ]);

        $gestion = Gestion_radicaciononline::create([
            'radicaciononline_id' =>  $request->radicaciononline_id,
            'user_id'        =>  Auth()->user()->id,
            'tipo_id' => 21,
            'motivo' => $request->motivo,
        ]);

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');
            $user = Auth()->user()->cedula;

            $i = 0;
            foreach ($files as $file) {
                $fileName[$i] = $file->getClientOriginalName();
                $filenametostore[$i] = '/adjuntosradicacion/' . $user . '/' . $request->radicaciononline_id . '/' . time() . '_' . $fileName[$i];
                Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                $adjunto = new Adjuntos_radicaciononline;
                $adjunto->nombre = $fileName[$i];
                $adjunto->ruta = $filenametostore[$i];
                $adjunto->gestionradicaciononline_id = $gestion->id;
                $adjunto->save();

                $i++;
            }
        }

        $paciente = RadicacionOnline::select(['pacientes.Primer_Nom', 'pacientes.Correo1'])
            ->join('pacientes', 'radicacion_onlines.paciente_id', 'pacientes.id')
            ->where('radicacion_onlines.id', $request->radicaciononline_id)
            ->first();

        $email = Mail::send(
            'email_alert_radicacion',
            ['radicado_id' => $request->radicaciononline_id, 'tipo' => 'Respuesta', 'name' => $paciente->Primer_Nom],
            function ($m) use ($paciente) {
                $m->to($paciente->Correo1, $paciente->Primer_Nom)->subject('Notificación Radicación');
            }
        );

        return response()->json([
            'message' => '¡Ha dado respuesta a la solicitud con exito!',
        ], 201);
    }

    public function devolver(Request $request)
    {

        RadicacionOnline::where('id', $request->radicaciononline_id)
            ->where('estado_id', 19)
            ->update([
                'estado_id' => 18,
            ]);

        Gestion_radicaciononline::where('radicaciononline_id', $request->radicaciononline_id)
            ->where('tipo_id', 20)
            ->delete();

        Gestion_radicaciononline::create([
            'radicaciononline_id' =>  $request->radicaciononline_id,
            'user_id'        => Auth()->user()->id,
            'tipo_id' => 22,
            'motivo' => $request->motivo,
        ]);

        return response()->json([
            'message' => '¡Ha devuelto la solicitud con exito!',
        ], 201);
    }

    public function showDevoluciones(Request $request)
    {

        $devoluciones = Gestion_radicaciononline::select(
            'gestion_radicaciononlines.id',
            'radicaciononline_id',
            'tipo_id',
            'motivo',
            'gestion_radicaciononlines.created_at',
            'u.name',
            'u.apellido',
            'tipos.Nombre'
        )
            ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
            ->join('tipos', 'gestion_radicaciononlines.tipo_id', 'tipos.id')
            ->with(['adjuntosgestion' => function ($query) {
                $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->where('gestion_radicaciononlines.radicaciononline_id', $request->radicaciononline_id)
            ->where('tipos.id', 22)
            ->get();

        return response()->json($devoluciones, 201);
    }

    public function solucionadosGestion(Request $request)
    {

        $solicitudeSolucionadasGestion = RadicacionOnline::select([
            'radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
            'p.Num_Doc as documento', 'p.Departamento as departamento', 'p.Mpio_Afiliado as municipio',
            'sp.Nombre as ips', 'es.Nombre as estado', 'p.Primer_Nom as pnombre', 'p.Primer_Ape as papellido',
            'p.SegundoNom as snombre', 'p.Segundo_Ape as sapellido', 'p.Tipo_Doc as tipodoc', 'p.Telefono as telefono',
            'p.Celular1 as celular', 'p.Direccion_Residencia as direccion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->with(['adjuntoradicado' => function ($query) {
                $query->select('radicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->with(['gestion' => function ($query) {
                $query->select(
                    'radicaciononline_id',
                    'motivo',
                    'gestion_radicaciononlines.created_at',
                    'gestion_radicaciononlines.id',
                    'u.name',
                    'u.apellido'
                )
                    ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                    ->with(['adjuntosgestion' => function ($query) {
                        $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                            ->get();
                    }])
                    ->where('gestion_radicaciononlines.tipo_id', 21)
                    ->get();
            }])
            ->where('radicacion_onlines.estado_id', 13)
            ->whereNotin("radicacion_onlines.id", function ($query) {
                $query->select('radicaciononline_id')
                    ->where('tipo_id', 20)
                    ->from('gestion_radicaciononlines');
            })
            ->whereBetween('radicacion_onlines.created_at', [$request->desde, $request->hasta]);

        if (isset($request->departamento)) {
            $solicitudeSolucionadasGestion->where('p.Departamento', $request->departamento);
        }
        if (isset($request->solicitud)) {
            $solicitudeSolucionadasGestion->where('ts.nombre', $request->solicitud);
        }
        if (isset($request->documento)) {
            $solicitudeSolucionadasGestion->where('p.Num_Doc', $request->documento);
        }
        if (isset($request->municipio)) {
            $solicitudeSolucionadasGestion->where('p.Mpio_Afiliado', $request->municipio);
        }

        $solicitudes = $solicitudeSolucionadasGestion->get();

        foreach ($solicitudes as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->id)
                ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
            }
        }

        return response()->json($solicitudes, 201);
    }

    public function solucionadosAsignadas(Request $request)
    {

        $radicadoId = $request->input('radicadoId');
        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_final = $request->input('fecha_final');
        $numero_documento = $request->input('numero_documento');

        $solicitudesFiltradas = RadicacionOnline::select([
            'radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
            'p.Num_Doc as documento', 'p.Departamento as departamento', 'p.Mpio_Afiliado as municipio',
            'sp.Nombre as ips', 'es.Nombre as estado', 'p.Primer_Nom as pnombre', 'p.Primer_Ape as papellido',
            'p.SegundoNom as snombre', 'p.Segundo_Ape as sapellido', 'p.Tipo_Doc as tipodoc', 'p.Telefono as telefono',
            'p.Celular1 as celular', 'p.Direccion_Residencia as direccion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->with(['adjuntoradicado' => function ($query) {
                $query->select('radicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->with(['gestion' => function ($query) {
                $query->select(
                    'radicaciononline_id',
                    'motivo',
                    'gestion_radicaciononlines.created_at',
                    'gestion_radicaciononlines.id',
                    'u.name',
                    'u.apellido'
                )
                    ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                    ->with(['adjuntosgestion' => function ($query) {
                        $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                            ->get();
                    }])
                    ->where('gestion_radicaciononlines.tipo_id', 21)
                    ->get();
            }])
            ->where('radicacion_onlines.estado_id', 13)
            ->whereIn("radicacion_onlines.id", function ($query) {
                $query->select('radicaciononline_id')
                    ->where('user_id', Auth::user()->id)
                    ->where('tipo_id', 20)
                    ->from('gestion_radicaciononlines');
            })
            ->when($radicadoId, function ($query) use ($radicadoId) {
                return $query->where('radicacion_onlines.id', $radicadoId);
            })
            ->when($fecha_inicial && $fecha_final, function ($query) use ($fecha_inicial, $fecha_final) {
                return $query->whereBetween('radicacion_onlines.created_at', [$fecha_inicial, $fecha_final]);
            })
            ->when($numero_documento, function ($query) use ($numero_documento) {
                return $query->where('p.Num_Doc', $numero_documento);
            })
            ->get();

        foreach ($solicitudesFiltradas as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->id)
                ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
            }
        }

        return response()->json($solicitudesFiltradas, 201);
    }

    public function solucionados(Request $request)
    {

        $solicitudeSolucionadas = RadicacionOnline::select([
            'radicacion_onlines.*', 'ts.nombre as nombreSolicitud',
            'p.Num_Doc as documento', 'p.Departamento as departamento', 'p.Mpio_Afiliado as municipio',
            'sp.Nombre as ips', 'es.Nombre as estado', 'p.Primer_Nom as pnombre', 'p.Primer_Ape as papellido',
            'p.SegundoNom as snombre', 'p.Segundo_Ape as sapellido', 'p.Tipo_Doc as tipodoc', 'p.Telefono as telefono',
            'p.Celular1 as celular', 'p.Direccion_Residencia as direccion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->with(['adjuntoradicado' => function ($query) {
                $query->select('radicaciononline_id', 'ruta', 'nombre')
                    ->get();
            }])
            ->with(['gestion' => function ($query) {
                $query->select(
                    'radicaciononline_id',
                    'motivo',
                    'gestion_radicaciononlines.created_at',
                    'gestion_radicaciononlines.id',
                    'u.name',
                    'u.apellido'
                )
                    ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                    ->with(['adjuntosgestion' => function ($query) {
                        $query->select('gestionradicaciononline_id', 'ruta', 'nombre')
                            ->get();
                    }])
                    ->where('gestion_radicaciononlines.tipo_id', 21)
                    ->get();
            }])
            ->where('radicacion_onlines.estado_id', 13)
            ->whereBetween('radicacion_onlines.created_at', [$request->desde, $request->hasta]);

        if (isset($request->departamento)) {
            $solicitudeSolucionadas->where('p.Departamento', $request->departamento);
        }
        if (isset($request->solicitud)) {
            $solicitudeSolucionadas->where('ts.nombre', $request->solicitud);
        }
        if (isset($request->documento)) {
            $solicitudeSolucionadas->where('p.Num_Doc', $request->documento);
        }
        if (isset($request->municipio)) {
            $solicitudeSolucionadas->where('p.Mpio_Afiliado', $request->municipio);
        }

        $soliSolucionadas = $solicitudeSolucionadas->get();

        foreach ($soliSolucionadas as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->id)
                ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
            }
        }

        return response()->json($soliSolucionadas, 201);
    }

    public function saveGestion(Request $request)
    {

        if ($request->radicaciononline_id) {

            switch ($request->tipo) {
                case 'Reasignar':

                    if ($request->usuario_id == null) {
                        return response()->json([
                            'message' => 'Debe seleccionar un usuario',
                        ], 402);
                    }

                    foreach ($request->radicaciononline_id as $keyRadicado) {
                        Gestion_radicaciononline::where('radicaciononline_id', $keyRadicado)
                            ->where('tipo_id', 20)
                            ->delete();
                    }

                    foreach ($request->usuario_id as $keyUser) {
                        foreach ($request->radicaciononline_id as $keyRadicadoCreate) {
                            Gestion_radicaciononline::create([
                                'radicaciononline_id' =>  $keyRadicadoCreate,
                                'user_id'        => $keyUser,
                                'tipo_id' => 20,
                                'motivo' => 'Asignado',
                            ]);

                            Auditoria::create([
                                'Descripcion'        => 'Radicado Reasignado',
                                'Tabla'              => 'radicacion_onlines',
                                'Usuariomodifica_id' => auth()->user()->id,
                                'Model_id'           => $keyRadicadoCreate
                            ]);
                        }
                    }
                    break;
                case 'Compartir':

                    if ($request->usuario_id == null) {
                        return response()->json([
                            'message' => 'Debe seleccionar un usuario',
                        ], 402);
                    }

                    foreach ($request->usuario_id as $keyUser) {
                        foreach ($request->radicaciononline_id as $keyRadicadoCreate) {

                            $exist = Gestion_radicaciononline::where('radicaciononline_id', $keyRadicadoCreate)
                                ->where('user_id', $keyUser)
                                ->first();

                            if (!$exist) {
                                Gestion_radicaciononline::create([
                                    'radicaciononline_id' =>  $keyRadicadoCreate,
                                    'user_id'        => $keyUser,
                                    'tipo_id' => 20,
                                    'motivo' => 'Asignado',
                                ]);

                                Auditoria::create([
                                    'Descripcion'        => 'Radicado Compartido',
                                    'Tabla'              => 'radicacion_onlines',
                                    'Usuariomodifica_id' => auth()->user()->id,
                                    'Model_id'           => $keyRadicadoCreate
                                ]);
                            }
                        }
                    }
                    break;
                case 'Devolver':
                    foreach ($request->radicaciononline_id as $keyRadicado) {
                        Gestion_radicaciononline::where('radicaciononline_id', $keyRadicado)
                            ->where('tipo_id', 20)
                            ->delete();

                        RadicacionOnline::where('id', $keyRadicado)
                            ->where('estado_id', 19)
                            ->update([
                                'estado_id' => 18,
                            ]);

                        Gestion_radicaciononline::create([
                            'radicaciononline_id' =>  $keyRadicado,
                            'user_id'        => Auth()->user()->id,
                            'tipo_id' => 22,
                            'motivo' => $request->motivo,
                        ]);
                    }
                    break;
            }
        } else {

            switch ($request->tipo) {
                case 'Reasignar':

                    if ($request->delusuario_id == null || $request->alusuario_id == null) {
                        return response()->json([
                            'message' => 'Debe seleccionar los usuarios',
                        ], 402);
                    }

                    $radicadosActivos = RadicacionOnline::where('estado_id', '!=', 13)
                        ->whereIn("radicacion_onlines.id", function ($query) use ($request) {
                            $query->select('radicaciononline_id')
                                ->where('user_id', $request->delusuario_id)
                                ->where('tipo_id', 20)
                                ->from('gestion_radicaciononlines');
                        })
                        ->get();

                    if (count($radicadosActivos) == 0) {
                        return response()->json([
                            'message' => 'No se han encontrado radicados pendientes del usuario seleccionado',
                        ], 402);
                    }

                    foreach ($radicadosActivos as $keyRadicado) {
                        Gestion_radicaciononline::where('radicaciononline_id', $keyRadicado->id)
                            ->where('tipo_id', 20)
                            ->delete();
                    }

                    foreach ($request->alusuario_id as $keyUser) {
                        foreach ($radicadosActivos as $keyRadicadoCreate) {
                            Gestion_radicaciononline::create([
                                'radicaciononline_id' =>  $keyRadicadoCreate->id,
                                'user_id'        => $keyUser,
                                'tipo_id' => 20,
                                'motivo' => 'Asignado',
                            ]);

                            Auditoria::create([
                                'Descripcion'        => 'Radicado Reasignado',
                                'Tabla'              => 'radicacion_onlines',
                                'Usuariomodifica_id' => auth()->user()->id,
                                'Model_id'           => $keyRadicadoCreate->id
                            ]);
                        }
                    }

                    break;
                case 'Compartir':

                    if ($request->delusuario_id == null || $request->alusuario_id == null) {
                        return response()->json([
                            'message' => 'Debe seleccionar los usuarios',
                        ], 402);
                    }

                    $radicadosActivos = RadicacionOnline::where('estado_id', '!=', 13)
                        ->whereIn("radicacion_onlines.id", function ($query) use ($request) {
                            $query->select('radicaciononline_id')
                                ->where('user_id', $request->delusuario_id)
                                ->where('tipo_id', 20)
                                ->from('gestion_radicaciononlines');
                        })
                        ->get();

                    if (count($radicadosActivos) == 0) {
                        return response()->json([
                            'message' => 'No se han encontrado radicados pendientes del usuario seleccionado',
                        ], 402);
                    }

                    foreach ($request->alusuario_id as $keyUser) {
                        foreach ($radicadosActivos as $keyRadicadoCreate) {

                            $exist = Gestion_radicaciononline::where('radicaciononline_id', $keyRadicadoCreate->id)
                                ->where('user_id', $keyUser)
                                ->first();

                            if (!$exist) {
                                Gestion_radicaciononline::create([
                                    'radicaciononline_id' =>  $keyRadicadoCreate->id,
                                    'user_id'        => $keyUser,
                                    'tipo_id' => 20,
                                    'motivo' => 'Asignado',
                                ]);

                                Auditoria::create([
                                    'Descripcion'        => 'Radicado Reasignado',
                                    'Tabla'              => 'radicacion_onlines',
                                    'Usuariomodifica_id' => auth()->user()->id,
                                    'Model_id'           => $keyRadicadoCreate->id
                                ]);
                            }
                        }
                    }
                    break;
                case 'Devolver':

                    if ($request->delusuario_id == null) {
                        return response()->json([
                            'message' => 'Debe seleccionar los usuarios',
                        ], 402);
                    }

                    $radicadosActivos = RadicacionOnline::where('estado_id', '!=', 13)
                        ->whereIn("radicacion_onlines.id", function ($query) use ($request) {
                            $query->select('radicaciononline_id')
                                ->where('user_id', $request->delusuario_id)
                                ->where('tipo_id', 20)
                                ->from('gestion_radicaciononlines');
                        })
                        ->get();

                    if (count($radicadosActivos) == 0) {
                        return response()->json([
                            'message' => 'No se han encontrado radicados pendientes del usuario seleccionado',
                        ], 402);
                    }

                    foreach ($radicadosActivos as $keyRadicado) {
                        Gestion_radicaciononline::where('radicaciononline_id', $keyRadicado->id)
                            ->where('tipo_id', 20)
                            ->delete();

                        RadicacionOnline::where('id', $keyRadicado->id)
                            ->where('estado_id', 19)
                            ->update([
                                'estado_id' => 18,
                            ]);

                        Gestion_radicaciononline::create([
                            'radicaciononline_id' =>  $keyRadicado->id,
                            'user_id'        => Auth()->user()->id,
                            'tipo_id' => 22,
                            'motivo' => $request->motivo,
                        ]);
                    }
                    break;
            }
        }
    }

    public function informe(Request $request)
    {

        $informe = RadicacionOnline::select([
            'radicacion_onlines.id as Radicado',
            'radicacion_onlines.created_at as Fecha Creado',
            'radicacion_onlines.updated_at as Fecha Actualizado',
            'es.Nombre as Estado', 'p.Tipo_Doc as Tipo Documento',
            'p.Num_Doc as Documento',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as Nombre"),
            'p.Telefono as Telefono',
            'p.Celular1 as Celular',
            'p.Direccion_Residencia as Direccion',
            'p.Departamento as Departamento',
            'p.Mpio_Afiliado as Municipio',
            'sp.Nombre as IPS primaria',
            'radicacion_onlines.ruta as Ruta',
            'ts.nombre as Solicitud',
            'radicacion_onlines.descripcion as Descripcion'
        ])
            ->join('tipos_solicitud as ts', 'radicacion_onlines.tiposolicitud_id', 'ts.id')
            ->join('pacientes as p', 'radicacion_onlines.paciente_id', 'p.id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->join('estados as es', 'radicacion_onlines.estado_id', 'es.id')
            ->leftjoin('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->whereBetween('radicacion_onlines.created_at', [$request->fechaDesde, $request->fechaHasta]);

        if (isset($request->departamento)) {
            $informe->where('p.Departamento', $request->departamento);
        }
        if (isset($request->solicitud)) {
            $informe->where('ts.nombre', $request->solicitud);
        }
        if (isset($request->documento)) {
            $informe->where('p.Num_Doc', $request->documento);
        }
        if (isset($request->municipio)) {
            $informe->where('p.Mpio_Afiliado', $request->municipio);
        }

        $info = $informe->get();

        foreach ($info as $key) {
            $userColaborar = Gestion_radicaciononline::select(['u.name', 'u.apellido', 'u.especialidad_medico'])
                ->join('users as u', 'gestion_radicaciononlines.user_id', 'u.id')
                ->where('gestion_radicaciononlines.radicaciononline_id', $key->Radicado)
                // ->where('gestion_radicaciononlines.tipo_id', 3)
                ->first();

            if ($userColaborar) {
                $key['Colaborador'] = $userColaborar->name . ' ' . $userColaborar->apellido;
                $key['Cargo'] = $userColaborar->especialidad_medico;
            }
        }

        return (new FastExcel($info))->download('file.xls');
    }
}
