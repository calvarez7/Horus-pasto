<?php

namespace App\Http\Controllers\Referencia;

use App\Modelos\Censo;
use App\Events\EventEndChatBitacora;
use App\Events\EventSendMessageBitacora;
use App\Http\Controllers\Controller;
use App\Modelos\AdjuntoReferencia;
use App\Modelos\Bitacora;
use App\Modelos\Cie10;
use App\Modelos\concurrenciaCuentaMedica;
use App\Modelos\ConcurrenciaOrdenamiento;
use App\Modelos\Cuporden;
use App\Modelos\Detaarticulorden;
use App\Modelos\Eventosconcurrencia;
use App\Modelos\Message;
use App\Modelos\Paciente;
use App\Modelos\Referencia;
use App\Modelos\Registroconcurrencia;
use App\Modelos\Seguimientoconcurrencia;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ReferenciaController extends Controller
{
    public function newreferencia(Request $request)
    {
        // return $request->all();
        $referencia = Referencia::create([
            'id_paci'           => $request->id_paci,
            'id_prestador'      => auth()->user()->id,
            'tipo_anexo'        => $request->tipo_anexo,
            'Especialidad_remi' => $request->Especialidad_remi,
            'entidad_id'        => auth()->user()->entidad_id,
            'adjunto'           => 'Sin archivo',
            'state'             => 0,
        ]);

        $cie10s = Cie10::find($request->cie10s);
        $referencia->cie10s()->attach($cie10s);
        $files = $request->adjuntos;

        if (isset($files)) {
            foreach ($files as $file) {
                $store = $file->store('/public/upload_anexos');
                $path  = '/storage/upload_anexos/' . explode('/', $store)[2];
                $referencia->adjuntos()->save(new AdjuntoReferencia(['Ruta' => $path]));
            }
        }

        if ($request->tipo_anexo == 2 && !auth()->user()->can('referencia.anexo2.notAutomatic')) {

            $referencia->update([
                'rzeuz'          => 'U-' . $referencia->id,
                'state'          => 2,
                'tipo_solicitud' => 'Codigo atencion urgencias',
            ]);

            $bitacora = Bitacora::create([
                'User_id'       => auth()->user()->id,
                'Referencia_id' => $referencia->id,
                'Estado'        => 2,
            ]);
            $msg = "Paciente activo en base de datos, se asigna código de atención de urgencias sujeto a auditoría de cuentas médicas y concurrencia";
            $bitacora->messages()->save(new Message(['message' => $msg, 'User_id' => auth()->user()->id]));
        }

        return response()->json([
            'message' => 'Referencia creada',
        ], 200);
    }

    public function pendingReferences($anexo)
    {
        if (auth()->user()->can('referencia.manage') || auth()->user()->can('dev')) {
            $referencias = $this->allPendingReferenceAnexo($anexo);
        } else {
            $referencias = $this->lenderPendingReferenceAnexo($anexo, auth()->user()->id);
        }

        return response()->json($referencias, 200);
    }

    public function inProcessReferences($anexo)
    {
        if (auth()->user()->can('referencia.manage') || auth()->user()->can('dev')) {
            $referencias = $this->allInProcessReferenceAnexo($anexo);
        } else {
            $referencias = $this->lenderInProcessReferenceAnexo($anexo, auth()->user()->id);
        }
        return response()->json($referencias, 200);
    }

    public function internalProcessReferences(Request $request)
    {

        if (auth()->user()->can('referencia.manage') || auth()->user()->can('dev')) {
            $referencias = $this->allInternalProcessReferences($request);
        } else {
            $referencias = $this->lenderInternalProcessReferences(auth()->user()->id, $request);
        }

        return response()->json($referencias, 200);
    }

    public function getReferenciaByBitacora(Bitacora $bitacora)
    {
        $referencia = Referencia::select([
            'referencias.*',
            'u.name as prestador',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Tipo_Afiliado'
        ])
            ->join('users as u', 'referencias.id_prestador', 'u.id')
            ->join('pacientes as p', 'referencias.id_paci', 'p.id')
            ->join('bitacoras as b', 'b.Referencia_id', 'referencias.id')
            ->where('b.id', $bitacora->id)
            ->where("referencias.entidad_id", auth()->user()->entidad_id)
            ->first();

        return response()->json([
            'referencia' => $referencia,
        ], 200);
    }

    public function gestionar(Referencia $referencia, Request $request)
    {
        if ($referencia->state != 0 && $referencia->state != 1 && $referencia->state != 2) {
            return response()->json([
                'message' => '¡Referencia no disponible!',
            ], 404);
        }
        if ($referencia->state == 1 || $referencia->state == 2) {
            return response()->json([
                'message' => '¡Referencia ya estaba gestionada!',
                'gestion' => $referencia->bitacora->id,
            ], 200);
        }
        $referencia->update([
            'rzeuz'          => $request->rzeuz,
            'state'          => 1,
            'tipo_solicitud' => $request->tipo_solicitud,
        ]);

        $bitacora = Bitacora::create([
            'User_id'       => auth()->user()->id,
            'Referencia_id' => $referencia->id,
            'Estado'        => 1,
        ]);

        return response()->json([
            'message' => '¡Referencia gestionada con exito!',
            'gestion' => $bitacora->id,
        ], 200);
    }

    public function sendMessage(Bitacora $bitacora, Request $request)
    {
        $message = $bitacora->messages()->save(new Message(['message' => $request->message, 'User_id' => auth()->user()->id]));

        broadcast(new EventSendMessageBitacora($message, auth()->user()));
        return [$message, auth()->user()];
    }

    public function sendFile(Bitacora $bitacora, Request $request)
    {

        $store   = $request->file->store('/public/upload_bitacora');
        $path    = '/storage/upload_bitacora/' . explode('/', $store)[2];
        $message = $bitacora->messages()->save(new Message(['Archivo' => $path, 'User_id' => auth()->user()->id]));

        broadcast(new EventSendMessageBitacora($message, auth()->user()));
        return [$message, auth()->user()];
    }

    public function getAllMessagesBitacora(Bitacora $bitacora)
    {
        $messages = Message::select(['messages.*', 'u.name'])
            ->join('users as u', 'messages.User_id', 'u.id')
            ->where('messages.Bitacora_id', $bitacora->id)
            ->get();

        return response()->json([
            'messages' => $messages,
        ], 200);
    }

    public function getBitacora(Bitacora $bitacora)
    {
        return response()->json([
            'bitacora' => $bitacora,
        ], 200);
    }

    public function endBitacora(Bitacora $bitacora)
    {
        $referencia = Referencia::find($bitacora->Referencia_id);

        $referencia->update(['state' => 2]);
        $bitacora->update(['Estado' => 2]);

        broadcast(new EventEndChatBitacora($bitacora));
    }

    private function allPendingReferenceAnexo($anexo)
    {
        $referencias = [
            'referencias' => Referencia::select([
                'referencias.id',
                'referencias.created_at',
                'referencias.Tipo_anexo',
                'ci.referencia_id',
                'p.Tipo_Doc', 'p.Num_Doc',
                'p.Primer_Nom',
                'p.SegundoNom',
                'p.Primer_Ape',
                'p.Segundo_Ape',
                'p.Edad_Cumplida',
                'p.Telefono',
                'p.Celular1',
                'p.Celular2',
                'p.Correo1',
                'p.Correo2',
                'u.name',
                'u.apellido'
            ])
                ->join('pacientes as p', 'referencias.id_paci', 'p.id')
                ->join('users as u', 'referencias.id_prestador', 'u.id')
                ->leftjoin('cita_paciente as ci', 'ci.referencia_id', 'referencias.id')
                ->with(['cie10s' => function ($q) {
                    $q->select(['Codigo_CIE10', 'Descripcion'])->get();
                }])
                ->with(['adjuntos' => function ($q) {
                    $q->select(['Ruta', 'referencia_id'])->get();
                }])
                ->where("referencias.state", 0)
                ->where("referencias.tipo_anexo", $anexo)
                ->where("referencias.entidad_id", auth()->user()->entidad_id)
                ->get(),
            '_a2'         => Referencia::where("state", 0)->where("referencias.entidad_id", auth()->user()->entidad_id)->where("tipo_anexo", "2")->count(),
            '_a3'         => Referencia::where("state", 0)->where("referencias.entidad_id", auth()->user()->entidad_id)->where("tipo_anexo", "3")->count(),
            '_a9'         => Referencia::where("state", 0)->where("referencias.entidad_id", auth()->user()->entidad_id)->where("tipo_anexo", "9")->count(),
        ];

        return $referencias;
    }

    private function lenderPendingReferenceAnexo($anexo, $lender)
    {
        $referencias = [
            'referencias' => Referencia::select([
                'referencias.id',
                'referencias.created_at',
                'referencias.Tipo_anexo',
                'ci.referencia_id',
                'p.Tipo_Doc', 'p.Num_Doc',
                'p.Primer_Nom',
                'p.SegundoNom',
                'p.Primer_Ape',
                'p.Segundo_Ape',
                'p.Edad_Cumplida',
                'p.Telefono',
                'p.Celular1',
                'p.Celular2',
                'p.Correo1',
                'p.Correo2',
                'u.name',
                'u.apellido'
            ])
                ->join('pacientes as p', 'referencias.id_paci', 'p.id')
                ->join('users as u', 'referencias.id_prestador', 'u.id')
                ->leftjoin('cita_paciente as ci', 'ci.referencia_id', 'referencias.id')
                ->with(['cie10s' => function ($q) {
                    $q->select(['Codigo_CIE10', 'Descripcion'])->get();
                }])
                ->with(['adjuntos' => function ($q) {
                    $q->select(['Ruta', 'referencia_id'])->get();
                }])
                ->where("referencias.state", 0)
                ->where("referencias.tipo_anexo", $anexo)
                ->where("referencias.tipo_anexo", $anexo)
                ->where("id_prestador", $lender)
                ->where("referencias.entidad_id", auth()->user()->entidad_id)
                ->get(),

            '_a2'         => Referencia::where("state", 0)->where("tipo_anexo", "2")->where("id_prestador", $lender)->count(),
            '_a3'         => Referencia::where("state", 0)->where("tipo_anexo", "3")->where("id_prestador", $lender)->count(),
            '_a9'         => Referencia::where("state", 0)->where("tipo_anexo", "9")->where("id_prestador", $lender)->count(),
        ];

        return $referencias;
    }

    private function allInProcessReferenceAnexo($anexo)
    {
        $referencias = [
            'referencias' => Referencia::select([
                'referencias.id',
                'referencias.created_at',
                'referencias.Tipo_anexo',
                'referencias.state',
                'referencias.rzeuz',
                'referencias.tipo_solicitud',
                'ci.referencia_id',
                'p.Tipo_Doc', 'p.Num_Doc',
                'p.Primer_Nom',
                'p.SegundoNom',
                'p.Primer_Ape',
                'p.Segundo_Ape',
                'p.Edad_Cumplida',
                'p.Telefono',
                'p.Celular1',
                'p.Celular2',
                'p.Correo1',
                'p.Correo2',
                'u.name',
                'u.apellido'
            ])
                ->join('pacientes as p', 'referencias.id_paci', 'p.id')
                ->join('users as u', 'referencias.id_prestador', 'u.id')
                ->leftjoin('cita_paciente as ci', 'ci.referencia_id', 'referencias.id')
                ->with(['cie10s' => function ($q) {
                    $q->select(['Codigo_CIE10', 'Descripcion'])->get();
                }])
                ->with(['adjuntos' => function ($q) {
                    $q->select(['Ruta', 'referencia_id'])->get();
                }])
                ->where("referencias.state", 1)
                ->where("referencias.tipo_anexo", $anexo)
                ->where("referencias.entidad_id", auth()->user()->entidad_id)
                ->get(),
            '_a2'         => Referencia::where("state", 1)->where("tipo_anexo", "2")->count(),
            '_a3'         => Referencia::where("state", 1)->where("tipo_anexo", "3")->count(),
            '_a9'         => Referencia::where("state", 1)->where("tipo_anexo", "9")->count(),
        ];

        return $referencias;
    }

    private function lenderInProcessReferenceAnexo($anexo, $lender)
    {
        $referencias = [
            'referencias' => Referencia::select([
                'referencias.id',
                'referencias.created_at',
                'referencias.Tipo_anexo',
                'referencias.state',
                'referencias.rzeuz',
                'referencias.tipo_solicitud',
                'ci.referencia_id',
                'p.Tipo_Doc', 'p.Num_Doc',
                'p.Primer_Nom',
                'p.SegundoNom',
                'p.Primer_Ape',
                'p.Segundo_Ape',
                'p.Edad_Cumplida',
                'p.Telefono',
                'p.Celular1',
                'p.Celular2',
                'p.Correo1',
                'p.Correo2',
                'u.name',
                'u.apellido'
            ])
                ->join('pacientes as p', 'referencias.id_paci', 'p.id')
                ->join('users as u', 'referencias.id_prestador', 'u.id')
                ->leftjoin('cita_paciente as ci', 'ci.referencia_id', 'referencias.id')
                ->with(['cie10s' => function ($q) {
                    $q->select(['Codigo_CIE10', 'Descripcion'])->get();
                }])
                ->with(['adjuntos' => function ($q) {
                    $q->select(['Ruta', 'referencia_id'])->get();
                }])
                ->where("referencias.state", 1)
                ->where("referencias.tipo_anexo", $anexo)
                ->where("id_prestador", $lender)
                ->where("referencias.entidad_id", auth()->user()->entidad_id)
                ->get(),
            '_a2'         => Referencia::where("state", 1)->where("tipo_anexo", "2")->where("id_prestador", $lender)->count(),
            '_a3'         => Referencia::where("state", 1)->where("tipo_anexo", "3")->where("id_prestador", $lender)->count(),
            '_a9'         => Referencia::where("state", 1)->where("tipo_anexo", "9")->where("id_prestador", $lender)->count(),
        ];

        return $referencias;
    }

    private function allInternalProcessReferences($request)
    {
        $referencias = Referencia::select([
            'referencias.id',
            'referencias.created_at',
            'referencias.Tipo_anexo',
            'referencias.estadoconcurrencia_id',
            'referencias.state',
            'referencias.rzeuz',
            'referencias.tipo_solicitud',
            'p.id as paciente_id',
            'p.IPS as ips',
            'se.Nombre as ipsNombre',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'u.name',
            'u.apellido'
        ])
            ->join('pacientes as p', 'referencias.id_paci', 'p.id')
            ->join('users as u', 'referencias.id_prestador', 'u.id')
            ->leftJoin('sedeproveedores as se', 'p.IPS', 'se.id')
            ->with(['cie10s' => function ($q) {
                $q->select(['Codigo_CIE10', 'Descripcion'])->get();
            }])
            ->with(['adjuntos' => function ($q) {
                $q->select(['Ruta', 'referencia_id'])->get();
            }])
            ->where("referencias.state", 2)
            ->where("referencias.entidad_id", auth()->user()->entidad_id);
        if ($request->documento != "") {
            $referencias->where("p.Num_Doc", $request->documento);
        }
        $referencias->orderBy('referencias.id', 'desc');


        return $referencias->paginate(50);
    }

    private function lenderInternalProcessReferences($lender, $request)
    {
        $referencias = Referencia::select([
            'referencias.id',
            'referencias.created_at',
            'referencias.estadoconcurrencia_id',
            'referencias.Tipo_anexo',
            'referencias.state',
            'referencias.rzeuz',
            'referencias.tipo_solicitud',
            'p.Tipo_Doc', 'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'u.name',
            'u.apellido'
        ])
            ->join('pacientes as p', 'referencias.id_paci', 'p.id')
            ->join('users as u', 'referencias.id_prestador', 'u.id')
            ->with(['cie10s' => function ($q) {
                $q->select(['Codigo_CIE10', 'Descripcion'])->get();
            }])
            ->with(['adjuntos' => function ($q) {
                $q->select(['Ruta', 'referencia_id'])->get();
            }])
            ->where("referencias.state", 2)
            ->where("referencias.entidad_id", auth()->user()->entidad_id);
        if ($request->documento != "") {
            $referencias->where("p.Num_Doc", $request->documento);
        }
        $referencias->where("id_prestador", $lender)
            ->orderBy('referencias.id', 'desc');

        return $referencias->paginate(50);
    }

    public function exportarreferencia(Referencia $referencia, Request $request)
    {

        $objreferencia = Bitacora::join('referencias as ref', 'ref.id', 'bitacoras.Referencia_id')
            ->with([
                'messages' => function ($query) {
                    $query->select(['messages.id', 'messages.Bitacora_id', 'messages.message', 'messages.Archivo', 'messages.User_id']);
                },
            ])
            ->where('ref.id', $referencia->id)
            ->where("ref.entidad_id", auth()->user()->entidad_id)
            ->join('pacientes as pac', 'pac.id', 'ref.id_paci')
            ->join('sedeproveedores as sed', 'sed.id', 'pac.IPS')
            ->orderBy('ref.id', 'DESC')
            ->first(['ref.id', 'ref.id_paci', 'pac.Tipo_doc', 'pac.Num_Doc', 'pac.primer_Nom', 'pac.SegundoNom', 'pac.Primer_Ape', 'pac.Segundo_Ape', 'pac.Telefono', 'pac.Celular1', 'pac.Correo1', 'pac.Direccion_Residencia', 'pac.IPS', 'sed.Nombre as Nombre_sede', 'ref.tipo_anexo', 'ref.especialidad_remi', 'ref.state', 'ref.tipo_solicitud', 'bitacoras.Referencia_id']);

        return response()->json(
            $objreferencia,
            200
        );
    }

    public function counter()
    {
        $pendientes   = Referencia::where('state', 0)->where("referencias.entidad_id", auth()->user()->entidad_id)->count();
        $gestion      = Referencia::where('state', 1)->where("referencias.entidad_id", auth()->user()->entidad_id)->count();
        $concurrencia = Referencia::where('state', 2)->where("referencias.entidad_id", auth()->user()->entidad_id)->count();

        $total = $pendientes + $gestion + $concurrencia;

        return response()->json(
            [
                'pendientes'   => $pendientes,
                'gestion'      => $gestion,
                'concurrencia' => $concurrencia,
                'total'        => $total,
            ],
            200
        );
    }

    public function informeReferencia(Request $request)
    {
        $reporte = Collect(DB::select('exec dbo.SP_Reporte_Referencia_Anexo ?,?', [$request->startDate, $request->finishDate]));
        $array = json_decode($reporte, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function anexo3ToAnexo9(Request $request)
    {
        if ($request->tipo_anexo == '3') {

            Referencia::find($request->id)->update(['tipo_anexo' => 9]);
            return response()->json([
                'message' => 'el Anexo ' . $request->tipo_anexo . ' fue actulaizado a 9',
            ], 200);
        } else {
            return response()->json([
                'message' => 'el Anexo ' . $request->tipo_anexo . ' no es de tipo 3',
            ], 200);
        }
    }

    private function getCie10sReferencia($referencia)
    {
        $msg = '';
        foreach ($referencia->cie10s as $cie10) {
            if ($msg == '') {
                $msg = $msg . $cie10['Codigo_CIE10'] . " - " . $cie10['Descripcion'];
            } else {
                $msg = $msg . ", " . $cie10['Codigo_CIE10'] . " - " . $cie10['Descripcion'];
            }
        }
        return $msg;
    }

    public function registrarCocurrencia(Request $request)
    {
        $referencia = Referencia::find($request->id_concurrencia);
        $referencia->update(['estadoconcurrencia_id' => 43]);
        Registroconcurrencia::create([
            'paciente_id' => $request->paciente_id,
            'auditor_id' => auth()->user()->id,
            'cie10_id' => $request->cie10_id,
            'referencia_id' => $request->id_concurrencia,
            'ips_atencion' => $request->ips_atencion,
            'costo_atencion' => $request->costo_atencion,
            'via_ingreso' => $request->via_ingreso,
            'reingreso_hospitalización15días' => $request->reingreso_hospitalización15días,
            'unidad_funcional' => $request->unidad_funcional,
            'fecha_egreso' => $request->fecha_egreso,
            'destino_egreso' => $request->destino_egreso,
            'dias_estancia_observacion' => $request->dias_estancia_observacion,
            'dias_estancia_intensivo' => $request->dias_estancia_intensivo,
            'dias_estancia_intermedio' => $request->dias_estancia_intermedio,
            'dias_estancia_basicos' => $request->dias_estancia_basicos,
            'estancia_total_dias' => $request->estancia_total_dias,
            'especialidad_tratante' => $request->especialidad_tratante,
            'peso_rn' => $request->peso_rn,
            'edad_gestacional' => $request->edad_gestacional,
            'hospitalizacion_evitable' => $request->hospitalizacion_evitable,
            'reporte_patologia_diagnostica' => $request->reporte_patologia_diagnostica,
            'eventos_seguridad' => $request->eventos_seguridad,
            'eventos_centinelas' => $request->eventos_centinelas,
            'eventos_notificacion_inmediata' => $request->eventos_notificacion_inmediata,
            'descripicion_gestion_realizada' => $request->descripicion_gestion_realizada,
            'costo_evitado' => $request->costo_evitado,
            'descripcion_costo_evitado' => $request->descripcion_costo_evitado,
            'valor_costo_evitado' => $request->valor_costo_evitado,
            'costo_evitable' => $request->costo_evitable,
            'descripción_costo_evitable' => $request->descripción_costo_evitable,
            'valor_costo_evitable' => $request->valor_costo_evitable,
            'alto_costo' => $request->alto_costo,
            'incumplimiento_habilitación' => $request->incumplimiento_habilitación,
            'descripcion_habilitación' => $request->descripcion_habilitación,
            'asesoria_especialista' => $request->asesoria_especialista,
            'numero_factura' => $request->numero_factura,
            'lider_concurrencia' => $request->lider_concurrencia,
        ]);

        return response()->json([
            'Message' => 'Registro con exito!'
        ]);
    }


    public function crearCocurrencia(Request $request, int $id)
    {
        $data = Registroconcurrencia::create([
            'paciente_id' => $id,
            'auditor_id' => auth()->user()->id,
            'cie10_id' => $request->cie10_ingreso,
            'ips_atencion' => $request->prestador_id,
            'via_ingreso' => $request->via_ingreso,
            'reingreso_hospitalización15días' => $request->reingreso_hospitalización15días,
            'reingreso_hospitalización30días' => $request->reingreso_hospitalización30días,
            'unidad_funcional' => $request->unidad_funcional,
            'estancia_total_dias' => 0,
            'especialidad_tratante' => $request->especialidad_tratante,
            'tipo_hospitalizacion' => $request->tipo_hospitalizacion,
            'peso_rn' => $request->peso_rn,
            'edad_gestacional' => $request->edad_gestacional,
            'hospitalizacion_evitable' => $request->hospitalizacion_evitable,
            'lider_concurrencia' => $request->nota_concurrencia,
            'fecha_ingreso_concurrencia' => $request->fecha_ingreso,
            'codigo_hospitalizacion' => $request->codigo_hoispitalizacion,
            'cama' => $request->codigo_hoispitalizacion,
            'dias_estancia_intensivo' => 0,
            'dias_estancia_intermedio' => 0,
            'dias_estancia_basicos' => 0
        ]);

        return response()->json([
            'data' => $data,
            'Message' => 'Registro con exito!'
        ], 201);
    }

    public function editarRegistrarCocurrencia(Request $request, int $id)
    {

        Registroconcurrencia::where('id', $id)->update([
            'cie10_id' => $request->cie10_ingreso,
            'ips_atencion' => $request->prestador_id,
            'via_ingreso' => $request->via_ingreso,
            'reingreso_hospitalización15días' => $request->reingreso_hospitalización15días,
            'reingreso_hospitalización30días' => $request->reingreso_hospitalización30días,
            'unidad_funcional' => $request->unidad_funcional,
            'estancia_total_dias' => $request->estancia_total_dias,
            'especialidad_tratante' => $request->especialidad_tratante,
            'tipo_hospitalizacion' => $request->tipo_hospitalizacion,
            'peso_rn' => $request->peso_rn,
            'edad_gestacional' => $request->edad_gestacional,
            'hospitalizacion_evitable' => $request->hospitalizacion_evitable,
            'lider_concurrencia' => $request->nota_concurrencia,
            'fecha_ingreso_concurrencia' => $request->fecha_ingreso,
            'codigo_hospitalizacion' => $request->codigo_hoispitalizacion,
            'cama' => $request->cama
        ]);

        $data =  Registroconcurrencia::where('id', $id)->first();
        return response()->json([
            'data' => $data,
            'Message' => 'se actualizo con exito!'
        ], 200);
    }

    public function editarSeguimientoCocurrencia(Request $request, int $id)
    {

        Registroconcurrencia::where('id', $id)->update([
            'ips_atencion' => $request->ips_atencion,
            'fecha_ingreso_concurrencia' => $request->fecha_ingreso_concurrencia,
            'cie10_id' => $request->cie10_id,
            'via_ingreso' => $request->via_ingreso,
            'reingreso_hospitalización15días' => $request->reingreso_hospitalización15días,
            'reingreso_hospitalización30días' => $request->reingreso_hospitalización30días,
            'unidad_funcional' => $request->unidad_funcional,
            'codigo_hospitalizacion' => $request->codigo_hospitalizacion,
            'cama' => $request->cama,
            'especialidad_tratante' => $request->especialidad_tratante,
            'tipo_hospitalizacion' => $request->tipo_hospitalizacion,
            'peso_rn' => $request->peso_rn,
            'edad_gestacional' => $request->edad_gestacional,
            'hospitalizacion_evitable' => $request->hospitalizacion_evitable,
            'lider_concurrencia' => $request->lider_concurrencia,
            'numero_factura' => $request->numero_factura,
            'alto_costo'    => $request->alto_costo,
            'reporte_patologia_diagnostica' => $request->reporte_patologia_diagnostica,
            'hospitalizacion_evitable' => $request->hospitalizacion_evitable,
            'incumplimiento_habilitación' => $request->incumplimiento_habilitación,
            'cie10_id_egresoAsociado' => $request->cie10_id_egresoAsociado,
            'dx_concurrencia' => $request->dx_concurrencia,
            'destino_egreso' => $request->destino_egreso,
            'fecha_egreso' => $request->fecha_egreso,
            'costo_total_global' => $request->costo_total_global

        ]);

        $data = Registroconcurrencia::select(
            'registroconcurrencias.*',
            'registroconcurrencias.id as registroconcurrencias_id',
            'p.*',
            DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) AS paciente_nombre"),
            DB::raw("CONCAT(u.name,' ',u.apellido) AS auditor"),
            DB::raw("CONCAT(us.name,' ',us.apellido) AS medico_familia"),
            's.Nombre as NombreIPS',
            DB::raw("DATEDIFF(day,registroconcurrencias.fecha_ingreso_concurrencia,GETDATE()) AS dias_estancia")
        )
            ->join('Pacientes as p', 'registroconcurrencias.Paciente_id', 'p.id')
            ->join('sedeproveedores as s', 's.id', 'p.IPS')
            ->leftjoin('users as us', 'us.id', 'p.Medicofamilia')
            ->join('users as u', 'registroconcurrencias.auditor_id', 'u.id')
            ->where('registroconcurrencias.estado_id', 1)
            ->where('registroconcurrencias.id', $id)
            ->first();
        return response()->json([
            'data' => $data,
            'Message' => 'se actualizo con exito!'
        ]);
    }

    public function eventoConcurrencia(Request $request)
    {
        // dd($request->datos);
        foreach ($request->datos as $key) {
            if (!isset($key['actualizar'])) {
                $evento = new Eventosconcurrencia;
                $evento->eventos_seguridad = $key['eventos_seguridad'];
                $evento->observacion_seguridad = $key['observacion_seguridad'];
                $evento->valor_costo_evitado = (isset($key['valor_costo_evitado']) ? $key['valor_costo_evitado'] : null);
                $evento->valor_costo_evitable = (isset($key['valor_costo_evitable']) ? $key['valor_costo_evitable'] : null);
                $evento->tipo_id = $request->type;
                $evento->registroconcurrencias_id  = $request->registroconcurrencia_id;
                $evento->save();
            }
        }
    }

    public function concurrenciaseguridad($referencia)
    {
        $registro = Registroconcurrencia::select('e.eventos_seguridad', 'e.observacion_seguridad', DB::raw("CONCAT('n','o') AS actualizar"))
            ->join('eventosconcurrencias as e', 'registroconcurrencias.id', 'e.registroconcurrencias_id')
            ->where('registroconcurrencias.id', $referencia)
            ->where('e.tipo_id', 23)
            ->get();
        return response()->json($registro, 200);
    }
    public function concurrenciacentinela($referencia)
    {
        $registro = Registroconcurrencia::select('e.eventos_seguridad', 'e.observacion_seguridad', DB::raw("CONCAT('n','o') AS actualizar"))
            ->join('eventosconcurrencias as e', 'registroconcurrencias.id', 'e.registroconcurrencias_id')
            ->where('registroconcurrencias.id', $referencia)
            ->where('e.tipo_id', 24)
            ->get();
        return response()->json($registro, 200);
    }
    public function concurrencianotificacion($referencia)
    {
        $registro = Registroconcurrencia::select('e.eventos_seguridad', 'e.observacion_seguridad', DB::raw("CONCAT('n','o') AS actualizar"))
            ->join('eventosconcurrencias as e', 'registroconcurrencias.id', 'e.registroconcurrencias_id')
            ->where('registroconcurrencias.id', $referencia)
            ->where('e.tipo_id', 25)
            ->get();
        return response()->json($registro, 200);
    }
    public function costoevitado($referencia)
    {
        $registro = Registroconcurrencia::select(
            'e.eventos_seguridad',
            'e.observacion_seguridad',
            'e.valor_costo_evitado',
            DB::raw("CONCAT('n','o') AS actualizar")
        )
            ->join('eventosconcurrencias as e', 'registroconcurrencias.id', 'e.registroconcurrencias_id')
            ->where('registroconcurrencias.id', $referencia)
            ->where('e.tipo_id', 26)
            ->get();
        return response()->json($registro, 200);
    }
    public function costoevitable($referencia)
    {
        $registro = Registroconcurrencia::select(
            'e.eventos_seguridad',
            'e.observacion_seguridad',
            'e.valor_costo_evitable',
            DB::raw("CONCAT('n','o') AS actualizar")
        )
            ->join('eventosconcurrencias as e', 'registroconcurrencias.id', 'e.registroconcurrencias_id')
            ->where('registroconcurrencias.id', $referencia)
            ->where('e.tipo_id', 27)
            ->get();
        return response()->json($registro, 200);
    }

    public function enSeguimiento()
    {
        $referencia = Referencia::select([
            'referencias.*',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Num_Doc',
            'p.Tipo_Doc',
            'p.Edad_Cumplida',
            'r.*',
            'u.name',
            'u.apellido',
            'cie.Codigo_CIE10',
            'cie.Descripcion',
        ])
            ->join('pacientes as p', 'referencias.id_paci', 'p.id')
            ->join('registroconcurrencias as r', 'referencias.id', 'r.referencia_id')
            ->join('users as u', 'r.auditor_id', 'u.id')
            ->leftjoin('cie10s as cie', 'r.cie10_id', 'cie.id')
            ->whereIn('referencias.tipo_anexo', [3, 9])
            ->where('referencias.estadoconcurrencia_id', 43)
            ->get();
        return response()->json($referencia, 200);
    }

    public function Altaconcurrencia()
    {
        $referencia = Referencia::select([
            'referencias.*',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Num_Doc',
            'p.Tipo_Doc',
            'p.Edad_Cumplida',
            'r.*',
            'u.name',
            'u.apellido',
            'cie.Codigo_CIE10',
            'cie.Descripcion',
            'c.Cita_id',
            'ct.Estado_id as estadocita',
            'ct.Hora_Inicio as fechacita',
            'c.Tipocita_id',
            'C.Usuario_id',
            'c.Cup_id'
        ])
            ->join('pacientes as p', 'referencias.id_paci', 'p.id')
            ->join('registroconcurrencias as r', 'referencias.id', 'r.referencia_id')
            ->leftjoin('cita_paciente as c', 'referencias.id', 'c.referencia_id')
            ->leftjoin('citas as ct', 'c.Cita_id', 'ct.id')
            ->join('users as u', 'r.auditor_id', 'u.id')
            ->leftjoin('cie10s as cie', 'r.cie10_id', 'cie.id')
            ->whereIn('referencias.tipo_anexo', [3, 9])
            ->where('referencias.estadoconcurrencia_id', 44)
            ->get();
        return response()->json($referencia->unique(), 200);
    }

    public function seguimientoConcurrencia(Request $request, $id)
    {
        // if ($request->estado == 'En seguimiento') {
        //     Referencia::where('id', $request->referencia_id)
        //     ->update([
        //         'estadoconcurrencia_id' => 43,
        //     ]);
        // }elseif ($request->estado == 'De alta') {
        //     Referencia::where('id', $request->referencia_id)
        //     ->update([
        //         'estadoconcurrencia_id' => 44,
        //     ]);
        // }
        Seguimientoconcurrencia::create([
            'seguimientoConcurrencia' => $request->nota,
            'user_id' => auth()->user()->id,
            'concurrenciaRegistro_id' => $id,
        ]);

        return response()->json([
            'message' => 'Seguimiento creado con exito!'
        ], 200);
    }

    public function editregistro(Request $request)
    {
        Registroconcurrencia::where('id', $request->id)
            ->update([
                'unidad_funcional' => $request->unidad_funcional,
                'via_ingreso' => $request->via_ingreso,
                'reingreso_hospitalización15días' => $request->reingreso_hospitalización15días,
                'fecha_egreso' => $request->fecha_egreso,
                'dias_estancia_observacion' => $request->dias_estancia_observacion,
                'dias_estancia_intensivo' => $request->dias_estancia_intensivo,
                'dias_estancia_intermedio' => $request->dias_estancia_intermedio,
                'dias_estancia_basicos' => $request->dias_estancia_basicos,
                'estancia_total_dias' => $request->estancia_total_dias,
                'cie10_id' => $request->cie10_id,
                'destino_egreso' => $request->destino_egreso,
                'costo_atencion' => $request->costo_atencion,
                'especialidad_tratante' => $request->especialidad_tratante,
                'peso_rn' => $request->peso_rn,
                'edad_gestacional' => $request->edad_gestacional,
                'reporte_patologia_diagnostica' => $request->reporte_patologia_diagnostica,
                'hospitalizacion_evitable' => $request->hospitalizacion_evitable,
                'eventos_seguridad' => $request->eventos_seguridad,
                'eventos_centinelas' => $request->eventos_centinelas,
                'eventos_notificacion_inmediata' => $request->eventos_notificacion_inmediata,
                'descripicion_gestion_realizada' => $request->descripicion_gestion_realizada,
                'costo_evitado' => $request->costo_evitado,
                'descripcion_costo_evitado' => $request->descripcion_costo_evitado,
                'valor_costo_evitado' => $request->valor_costo_evitado,
                'costo_evitable' => $request->costo_evitable,
                'descripción_costo_evitable' => $request->descripción_costo_evitable,
                'valor_costo_evitable' => $request->valor_costo_evitable,
                'alto_costo' => $request->alto_costo,
                'incumplimiento_habilitación' => $request->incumplimiento_habilitación,
                'descripcion_habilitación' => $request->descripcion_habilitación,
                'asesoria_especialista' => $request->asesoria_especialista,
                'numero_factura' => $request->numero_factura,
                'lider_concurrencia' => $request->lider_concurrencia,
                'dx_concurrencia' => $request->dx_concurrencia,
                'cie10_id_egresoAsociado' => $request->cie10_id_egresoAsociado,
                'fecha_ingreso_concurrencia' => $request->fecha_ingreso_concurrencia,
            ]);

        return response()->json([
            'message' => 'Registro actualizado con exito!'
        ], 200);
    }

    public function verseguimientos($referencia)
    {
        $seguimientos = Seguimientoconcurrencia::select(
            'u.name',
            'u.apellido',
            'seguimientoconcurrencias.*',
            'us.name as nameDss',
            'us.apellido as apellidoDss'
        )
            ->join('users as u', 'seguimientoconcurrencias.user_id', 'u.id')
            ->leftjoin('users as us', 'seguimientoconcurrencias.user_nota_id', 'us.id')
            ->where('registroConcurrencia_id', $referencia)
            ->orderby('id', 'desc')
            ->get();

        $concurrencias = Registroconcurrencia::select('registroconcurrencias.*', 'registroconcurrencias.id as registroconcurrencias_id', 'p.*', 'u.name', 'u.apellido')
            ->join('Pacientes as p', 'registroconcurrencias.Paciente_id', 'p.id')
            ->join('users as u', 'registroconcurrencias.auditor_id', 'u.id')
            ->where('registroconcurrencias.referencia_id', $referencia)
            ->get();

        $data = ['status' => 200, 'seguimientos' => $seguimientos, 'concurrencias' => $concurrencias];

        return response()->json($data);
    }

    public function concurrenciaReporte(Request $request)
    {
        $exportarsaldos = Collect(DB::select("exec dbo.ExportConcurrencia ?,?", [$request->startDate, $request->finishDate]));
        $array = json_decode($exportarsaldos, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function notaDss(Request $request, $id)
    {
        $seguimientos = Seguimientoconcurrencia::where('id', $id)->update([
            'nota_dss'          => $request->nota,
            'user_nota_id'      => auth()->user()->id,
        ]);

        return response()->json($seguimientos, 201);
    }

    public function notaAac(Request $request, $id)
    {
        $seguimientos = Seguimientoconcurrencia::where('id', $id)->update([
            'nota_aac'          => $request->nota,
            'user_notaaac_id'      => auth()->user()->id,
        ]);

        return response()->json($seguimientos, 201);
    }

    public function costoConcurrencia(Request $request, $id)
    {
        $costo = ConcurrenciaOrdenamiento::select(
            'concurrencia_ordenamientos.id',
            'concurrencia_ordenamientos.cantidad',
            DB::raw("'$' + CONVERT(VARCHAR, CONVERT(VARCHAR, CAST(concurrencia_ordenamientos.costo AS MONEY), 1)) as valor"),
            'concurrencia_ordenamientos.created_at',
            DB::raw("CONCAT(users.name, ' ', users.apellido) AS usuario"),
            'cups.Nombre as servicios',
            'cups.Codigo'
        )
            ->join('cups', 'cups.id', 'concurrencia_ordenamientos.cup_id')
            ->join('users', 'users.id', 'concurrencia_ordenamientos.user_id')
            ->where('registroConcurrencia_id', $id)->get();

        $total_suma = ConcurrenciaOrdenamiento::select(DB::raw("'$' + CONVERT(VARCHAR, CONVERT(VARCHAR, CAST(SUM(costo)  AS MONEY), 1)) as total_suma"))
            ->where('registroConcurrencia_id', $id)->first();

        $dias = Registroconcurrencia::select(
            'dias_estancia_intensivo',
            'dias_estancia_intermedio',
            'dias_estancia_basicos',
            'estancia_total_dias',
            DB::raw("DATEDIFF(day,registroconcurrencias.fecha_ingreso_concurrencia,registroconcurrencias.fecha_egreso) AS dias_estancia")
        )
            ->where('id', $id)->first();

        return response()->json([
            'datos' => $costo,
            'total_suma' => $total_suma,
            'dias' => $dias
        ]);
    }

    public function guardarCosto(Request $request, $id)
    {

        $costo = ConcurrenciaOrdenamiento::create([
            'registroConcurrencia_id' => $id,
            'cup_id' => $request->cup_id,
            'cantidad' => $request->cantidad,
            'user_id' => auth()->user()->id,
            'costo' => $request->precio,
        ]);

        #UCI
        if ($request->cup_id == 21 || $request->cup_id == 29 || $request->cup_id == 34) {
            $dato_actual = Registroconcurrencia::where('id', $id)->first();
            $suma = $dato_actual->dias_estancia_intensivo + $request->cantidad;
            $suma_total = $dato_actual->estancia_total_dias + $suma;
            $dato_actual->update([
                'dias_estancia_intensivo' =>  $suma,
                'estancia_total_dias' =>  $suma_total
            ]);
        }

        #UCE
        if ($request->cup_id == 22 || $request->cup_id == 30 || $request->cup_id == 31 || $request->cup_id == 9874) {
            $dato_actual = Registroconcurrencia::where('id', $id)->first();
            $suma = $dato_actual->dias_estancia_intermedio + $request->cantidad;
            $suma_total = $dato_actual->estancia_total_dias + $suma;
            $dato_actual->update([
                'dias_estancia_intermedio' =>  $suma,
                'estancia_total_dias' =>  $suma_total
            ]);
        }

        #HOSPITALIZACION
        if (
            $request->cup_id == 9 || $request->cup_id == 10 || $request->cup_id == 11 || $request->cup_id == 12 || $request->cup_id == 13 || $request->cup_id == 14 || $request->cup_id == 15
            || $request->cup_id == 16 || $request->cup_id == 17 || $request->cup_id == 18 || $request->cup_id == 19 || $request->cup_id == 20 || $request->cup_id == 23
        ) {
            $dato_actual = Registroconcurrencia::where('id', $id)->first();
            $suma = $dato_actual->dias_estancia_basicos + $request->cantidad;
            $suma_total = $dato_actual->estancia_total_dias + $suma;
            $dato_actual->update([
                'dias_estancia_basicos' =>  $suma,
                'estancia_total_dias' =>  $suma_total
            ]);
        }

        return response()->json($costo, 201);
    }

    public function listarSeguimiento()
    {

        $concurrencias = Registroconcurrencia::select(
            'registroconcurrencias.*',
            'registroconcurrencias.id as registroconcurrencias_id',
            'p.*',
            'cie10s.Descripcion as nombre_cie10',
            DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) AS paciente_nombre"),
            DB::raw("CONCAT(u.name,' ',u.apellido) AS auditor"),
            DB::raw("CONCAT(us.name,' ',us.apellido) AS medico_familia"),
            's.Nombre as NombreIPS',
            DB::raw("DATEDIFF(day,registroconcurrencias.fecha_ingreso_concurrencia,GETDATE()) AS dias_estancia"),
            DB::raw("CONVERT(varchar,registroconcurrencias.fecha_ingreso_concurrencia,23) AS fecha_ingreso_c"),
        )
            ->join('Pacientes as p', 'registroconcurrencias.Paciente_id', 'p.id')
            ->join('sedeproveedores as s', 's.id', 'p.IPS')
            ->leftjoin('users as us', 'us.id', 'p.Medicofamilia')
            ->join('cie10s', 'cie10s.id', 'registroconcurrencias.cie10_id')
            ->join('users as u', 'registroconcurrencias.auditor_id', 'u.id')
            ->where('registroconcurrencias.estado_id', 1)
            ->get();

        return response()->json($concurrencias, 200);
    }

    public function counterSeguimiento()
    {
        $concurrencias = Registroconcurrencia::select(
            'registroconcurrencias.*',
            'registroconcurrencias.id as registroconcurrencias_id',
            'p.*',
            'u.name',
            'u.apellido',
            'concurrencia_ordenamientos.costo',
            DB::raw("DATEDIFF(day,registroconcurrencias.fecha_ingreso_concurrencia,GETDATE()) AS dias_estancia")
        )
            ->join('Pacientes as p', 'registroconcurrencias.Paciente_id', 'p.id')
            ->join('users as u', 'registroconcurrencias.auditor_id', 'u.id')
            ->leftjoin('concurrencia_ordenamientos', 'concurrencia_ordenamientos.registroConcurrencia_id', 'registroconcurrencias.id')
            ->where('registroconcurrencias.estado_id', 1)
            ->get();

        $menos_4 = $concurrencias->whereIn('dias_estancia', [0, 1, 2, 3])->count();
        $suma_4 = $concurrencias->whereIn('dias_estancia', [0, 1, 2, 3])->sum('costo');
        $entre4_5 = $concurrencias->whereIn('dias_estancia', [4, 5])->count();
        $suma_4_5 = $concurrencias->whereIn('dias_estancia', [4, 5])->sum('costo');
        $mayor_5 = $concurrencias->where('dias_estancia', '>', 5)->count();
        $suma_5 = $concurrencias->where('dias_estancia', '>', 5)->sum('costo');


        $total = $menos_4 + $entre4_5 + $mayor_5;
        $total_Suma = $suma_4 + $suma_4_5 + $suma_5;

        return response()->json(
            [
                'menor'   => $menos_4,
                'suma_menor' => number_format($suma_4),
                'entre'      => $entre4_5,
                'suma_entre' => number_format($suma_4_5),
                'mayor' => $mayor_5,
                'suma_mayor' => number_format($suma_5),
                'total'        => $total,
                'suma_total'  => number_format($total_Suma)
            ],
            200
        );
    }

    public function listarEventosCentinela($id)
    {
        $data = Eventosconcurrencia::where('registroconcurrencias_id', $id)->where('tipo_id', 24)->get();

        return response()->json($data, 200);
    }

    public function guardarEventosSeguridad(Request $request, $id)
    {
        $evento = new Eventosconcurrencia;
        $evento->eventos_seguridad = $request->Seguridad;
        $evento->observacion_seguridad = $request->observacionnSeguridad;
        $evento->tipo_id = $request->type;
        $evento->registroconcurrencias_id  = $id;
        $evento->save();
    }

    public function listarEventosSeguridad($id)
    {
        $data = Eventosconcurrencia::where('registroconcurrencias_id', $id)->where('tipo_id', 23)->get();

        return response()->json($data, 200);
    }

    public function guardarNotificacion(Request $request, $id)
    {
        $evento = new Eventosconcurrencia;
        $evento->eventos_seguridad = $request->eventos_notificacion_inmediata;
        $evento->observacion_seguridad = $request->observacion_inmediata;
        $evento->tipo_id = $request->type;
        $evento->registroconcurrencias_id  = $id;
        $evento->save();
    }

    public function listarNotificacion($id)
    {
        $data = Eventosconcurrencia::where('registroconcurrencias_id', $id)->where('tipo_id', 25)->get();

        return response()->json($data, 200);
    }

    public function guardarCostoEvitado(Request $request, $id)
    {
        $evento = new Eventosconcurrencia;
        $evento->eventos_seguridad = $request->costo_evitado;
        $evento->observacion_seguridad = $request->observacion_costo;
        $evento->valor_costo_evitado = $request->valor_evitado;
        $evento->tipo_id = $request->type;
        $evento->tipo_altaTemprana = $request->tipo_altaTemprana;
        $evento->registroconcurrencias_id  = $id;
        $evento->save();
    }

    public function listarCostoEvitado($id)
    {
        $data = Eventosconcurrencia::where('registroconcurrencias_id', $id)->where('tipo_id', 26)->get();

        return response()->json($data, 200);
    }

    public function guardarCostoEvitable(Request $request, $id)
    {
        $evento = new Eventosconcurrencia;
        $evento->eventos_seguridad = $request->costo_evitable;
        $evento->observacion_seguridad = $request->observacion_evitable;
        $evento->valor_costo_evitable = $request->valor_evitable;
        $evento->tipo_id = $request->type;
        $evento->registroconcurrencias_id  = $id;
        $evento->objeciones = $request->objeciones;
        $evento->save();
    }

    public function listarCostoEvitable($id)
    {
        $data = Eventosconcurrencia::where('registroconcurrencias_id', $id)->where('tipo_id', 27)->get();

        return response()->json($data, 200);
    }

    public function listarNotas($id)
    {
        $data = Seguimientoconcurrencia::select(
            'u.name',
            'u.apellido',
            'seguimientoconcurrencias.*',
            'us.name as nameDss',
            'us.apellido as apellidoDss',
            'use.name as nameAac',
            'use.apellido as apellidoAac'
        )
            ->join('users as u', 'seguimientoconcurrencias.user_id', 'u.id')
            ->leftjoin('users as us', 'seguimientoconcurrencias.user_nota_id', 'us.id')
            ->leftjoin('users as use', 'seguimientoconcurrencias.user_nota_id', 'use.id')
            ->where('concurrenciaRegistro_id', $id)->orderby('id', 'desc')->get();
        return response()->json($data, 200);
    }

    public function counterReporte()
    {
        $concurrencias = Registroconcurrencia::select(
            DB::raw("COUNT(registroconcurrencias.ips_atencion) AS title"),
            'registroconcurrencias.ips_atencion AS subTitle'
        )
            ->join('Pacientes as p', 'registroconcurrencias.Paciente_id', 'p.id')
            ->join('users as u', 'registroconcurrencias.auditor_id', 'u.id')
            ->leftjoin('users as use', 'seguimientoconcurrencias.user_notaaac_id', 'use.id')
            ->where('registroconcurrencias.estado_id', 1)
            ->groupBy('registroconcurrencias.ips_atencion')
            ->get();

        return response()->json($concurrencias, 200);
    }

    public function altaConcurrenncia($id)
    {
        $data = Registroconcurrencia::where('id', $id)->first();
        if ($data->destino_egreso != null) {
            $data->update(['estado_id' => 44]);
            return response()->json($data, 200);
        } else {
            return response()->json(['mensaje' => "El destino de egreso no esta guardado"], 200);
        }
    }

    public function cuentasMedicas(Request $request, $id)
    {
        $referencia = concurrenciaCuentaMedica::create([
            'concurrenciaRegistro_id'           => $id,
            'user_id'      => auth()->user()->id,
            'numero_factura'        => $request->numero_factura,
            'valor_facturado' => $request->valor_facturado,
            'valor_objetado'           => $request->valor_objetado,
            'valor_objetado_concurrencia'           => $request->valor_objetado_concurrencia,
            'factura_final'           => $request->factura_final,
            'porcentaje_objecion'           => $request->porcentaje_objecion,
        ]);
        return response()->json($referencia, 200);
    }

    public function editarCuentasMedicas(Request $request, $id)
    {
        $referencia = concurrenciaCuentaMedica::where('concurrenciaRegistro_id', $id)->first();
        $referencia->update([
            'user_id'      => auth()->user()->id,
            'numero_factura'        => $request->numero_factura,
            'valor_facturado' => $request->valor_facturado,
            'valor_objetado'           => $request->valor_objetado,
            'valor_objetado_concurrencia'           => $request->valor_objetado_concurrencia,
            'factura_final'           => $request->factura_final,
            'porcentaje_objecion'           => $request->porcentaje_objecion,
        ]);
        return response()->json($referencia, 200);
    }

    public function getCuentasMedicas($id)
    {
        $cuentas = concurrenciaCuentaMedica::where('concurrenciaRegistro_id', $id)->first();

        return response()->json($cuentas, 200);
    }

    public function generarInforme(Request $request)
    {
        $result = [];
        switch ($request->informe) {
            case 1:
                $appointments = Collect(DB::select("SET NOCOUNT ON exec dbo.SP_Reporte_Concurrencia_Ingreso"));
                $result = json_decode($appointments, true);
                break;
            case 2:
                $appointments = Collect(DB::select("SET NOCOUNT ON exec dbo.SP_Reporte_Concurrencia_Egreso ?,?", [$request->fechaDesde, $request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
            case 3:
                $censo = Registroconcurrencia::select(
                    'p.Ut as Entidad',
                    'p.Num_Doc as Documento',
                    DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) AS paciente_nombre"),
                    'registroconcurrencias.ips_atencion',
                    'registroconcurrencias.cama'
                )
                    ->join('Pacientes as p', 'registroconcurrencias.paciente_id', 'p.id')
                    ->where('registroconcurrencias.estado_id', 1)
                    ->get();
                $result = json_decode($censo, true);
                break;
        }
        return (new FastExcel($result))->download('file.xls');
    }

    public function guardarEventosCentinela(Request $request, $id)
    {
        $evento = new Eventosconcurrencia();
        $evento->tipo_id = 24;
        $evento->registroconcurrencias_id = $id;
        $evento->eventos_seguridad = $request->eventos_centinelas;
        $evento->observacion_seguridad = $request->observacion_centinela;
        $evento->save();

        return response()->json($evento, 201);
    }

    public function eliminarEvento($id)
    {
        $evento = Eventosconcurrencia::where('id', $id)->first();
        $evento->delete();

        return response()->json($evento, 200);
    }

    public function censoConcurrencia(Request $request)
    {
        $i = 2;
        (new FastExcel)->import($request->data, function ($line) use (&$noRegistra, &$registra, &$msg, &$i) {
            $codigo_string = (string)$line["numero_identificacion"];
            $censo = Censo::where('numero_identificacion', $codigo_string)->first();
            if ($censo) {
                $registra[] = $line;
            } elseif (!$censo) {
                if ($line["ips"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (ips) linea ' . $i;
                } else if ($line["tipo_identficicacion"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (tipo_identficicacion) linea ' . $i;
                } else if ($line["numero_identificacion"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (numero_identificacion) linea ' . $i;
                } else if ($line["codigo"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (codigo) linea ' . $i;
                } else if ($line["diganostico_cie10"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (diganostico_cie10) linea ' . $i;
                } else if ($line["fecha_ingreso"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (fecha_ingreso) linea ' . $i;
                } else if ($line["nombres_apellidos"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (nombres_apellidos) linea ' . $i;
                } else if ($line["ubicacion"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (ubicacion) linea ' . $i;
                } else if ($line["especialidad"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: (especialidad) linea ' . $i;
                } else if ($line["especialidad"] == '' & $line["dias_estancia"] < 0) {
                    $msg = 'Hay campos obligatorios vacios, revisa: (dias_estancia) linea ' . $i;
                } else {
                    $noRegistra[] = $line;
                }
            }
            $i++;
        });
        if ($msg !== '' & $msg !== null) {
            return response()->json([
                'message' => $msg
            ], 400);
        } else if ($registra) {
            foreach ($registra as $key) {
                $codigo_string = (string)$key["numero_identificacion"];
                $paciente = Paciente::where('Num_Doc',$codigo_string)->first();
                $concurrencia = Registroconcurrencia::where('paciente_id',$paciente->id)->where('estado_id',1)->first();
                if($concurrencia){
                    $concurrencia_id = $concurrencia->id;
                }else{
                    $concurrencia_id = null;
                }
                $censo = Censo::where('numero_identificacion', $codigo_string)->first();
                $censo->update([
                    'ips'                       => $key['ips'],
                    'atencion_admision'         => $key['atencion_admision'],
                    'codigo'                    => $key['codigo'],
                    'diganostico_cie10'         => $key['diganostico_cie10'],
                    'fecha_ingreso'             => $key['fecha_ingreso'],
                    'nombres_apellidos'         => $key['nombres_apellidos'],
                    'ubicacion'                 => $key['ubicacion'],
                    'especialidad'              => $key['especialidad'],
                    'dias_estancia'             => $key['dias_estancia'],
                    'via_ingreso'               => $key['via_ingreso'],
                    'Asegurador'                => $key['asegurador'],
                    'grupo_diagnostico'         => $key['grupo_diagnostico'],
                    'ips_basica'                => $key['ips_basica'],
                    'Alta'                      => $key['Alta'],
                    'areas_reporte'             => $key['areas_reporte'],
                    'pym'                       => $key['pym'],
                    'concurrencia_id'           => $concurrencia_id
                ]);
            }
        } else {
            foreach ($noRegistra as $key) {
                $codigo_string = (string)$key["numero_identificacion"];
                $paciente = Paciente::where('Num_Doc',$codigo_string)->first();
                $concurrencia = Registroconcurrencia::where('paciente_id',$paciente->id)->where('estado_id',1)->first();
                if($concurrencia){
                    $concurrencia_id = $concurrencia->id;
                }else{
                    $concurrencia_id = null;
                }
                Censo::create([
                    'ips'                       => $key['ips'],
                    'atencion_admision'         => $key['atencion_admision'],
                    'tipo_identficicacion'      => $key['tipo_identficicacion'],
                    'numero_identificacion'     => $key['numero_identificacion'],
                    'codigo'                    => $key['codigo'],
                    'diganostico_cie10'         => $key['diganostico_cie10'],
                    'fecha_ingreso'             => $key['fecha_ingreso'],
                    'nombres_apellidos'         => $key['nombres_apellidos'],
                    'ubicacion'                 => $key['ubicacion'],
                    'especialidad'              => $key['especialidad'],
                    'dias_estancia'             => $key['dias_estancia'],
                    'via_ingreso'               => $key['via_ingreso'],
                    'Asegurador'                => $key['asegurador'],
                    'grupo_diagnostico'         => $key['grupo_diagnostico'],
                    'ips_basica'                => $key['ips_basica'],
                    'Alta'                      => $key['Alta'],
                    'areas_reporte'             => $key['areas_reporte'],
                    'pym'                       => $key['pym'],
                    'concurrencia_id'           => $concurrencia_id,
                ]);
            }
        }
        return response()->json([
            'message' => 'Carga de censo fue exitosa!',
        ], 200);
    }

    public function listarCenso()
    {
        $censo = (DB::select('SET NOCOUNT ON exec dbo.SP_CensoConcurrencia'));
        $resultado = json_decode(collect($censo), true);

        $noCruzan = count(array_filter(array_column($resultado,'id_RegistroConcurrencia'), function($v, $k) {
            return $v == null;
        }, ARRAY_FILTER_USE_BOTH));

        $Cruzan = count(array_filter(array_column($resultado,'id_RegistroConcurrencia'), function($v, $k) {
            return $v != null;
        }, ARRAY_FILTER_USE_BOTH));

        $total = count($resultado);

        return response()->json(['resultado' => $resultado, 'noCruzan' => $noCruzan, 'cruzan' => $Cruzan, 'total' => $total], 200);
    }

    public function formato() {

        $formato = collect([
            ['ips' => '',
        'atencion_admision' => '',
        'tipo_identficicacion' => '',
        'numero_identificacion' => '',
        'codigo' => '','diganostico_cie10' => '',
        'fecha_ingreso' => '','nombres_apellidos' => '',
        'ubicacion' => '','especialidad' => '','dias_estancia' => '',
        'via_ingreso' => '','asegurador' => '','grupo_diagnostico' => '',
        'ips_basica' => '','Alta' => '','areas_reporte' => '','pym' => '']
    ]);

        return (new FastExcel($formato))->download('file.xls');
    }

    public function abrirRegistro($referencia_id){
        Registroconcurrencia::find($referencia_id)->update([
            'estado_id' => 1
        ]);

        return response()->json([
            'message' => 'Actualizacion fue exitosa!',
        ], 200);
    }
}
