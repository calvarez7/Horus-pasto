<?php

namespace App\Http\Controllers\Agendas;

use App\User;
use App\Modelos\Cita;
use App\Modelos\Sede;
use App\Modelos\Agenda;
use App\Modelos\Auditoria;
use App\Modelos\agendauser;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Especialidade;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    public function all($fecha)
    {
        $agenda = Agenda::all()
            ->whereHas("permissions", function ($q) {$q->where("name", "agenda");})
            ->get();
        return response()->json($agenda, 200);
    }

    public function agendaEspecialidad()
    {
        $especialidades = Especialidade::select(['et.id as et_id', 'especialidades.*', 'ta.name', 'c.Sede_id as sede'])
            ->join('especialidade_tipoagenda as et', 'et.Especialidad_id', 'especialidades.id')
            ->join('tipo_agendas as ta', 'et.Tipoagenda_id', 'ta.id')
            ->join('agendas as a', 'a.Especialidad_id', 'et.id')
            ->join('consultorios as c', 'a.Consultorio_id', 'c.id')
            ->where('especialidades.Estado_id', 1)
            ->where('ta.Estado_id', 1)
            ->where('a.Fecha', '>=', date('Y-m-d'))
            ->distinct()
            ->get();
        return response()->json($especialidades, 200);
    }

    public function agendaSede(Request $request)
    {
        $sedes = Sede::select(['sedes.*'])
            ->join('consultorios as c', 'c.Sede_id', 'sedes.id')
            ->join('agendas as a', 'a.Consultorio_id', 'c.id')
            ->join('especialidade_tipoagenda as et', 'a.Especialidad_id', 'et.id')
            ->where('sedes.Estado_id', 1)
            ->where('et.Especialidad_id', $request->especialidad)
            ->where('a.Fecha', '>=', date('Y-m-d'))
            ->distinct()
            ->get();

        return response()->json($sedes, 200);
    }

    public function agendaDisponible(Request $request)
    {
        $agendas = Agenda::select(['agendas.id', 's.id as Sede', 'a.Nombre as nombreConsultorio',
        'c.Nombre as Especialidad', 'd.name as tipoActividad', 'u.name as nombreMedico', 'u.apellido as apellidoMedico',
            'agendas.fecha', 'agendas.Hora_Inicio', 'agendas.Hora_Final','b.marcacion'])
            ->join('consultorios as a', 'agendas.Consultorio_id', 'a.id')
            ->join('sedes as s', 'a.Sede_id', 's.id')
            ->join('especialidade_tipoagenda as b', 'agendas.Especialidad_id', 'b.id')
            ->join('especialidades as c', 'b.Especialidad_id', 'c.id')
            ->join('tipo_agendas as d', 'b.Tipoagenda_id', 'd.id')
            ->join('agendausers as au', 'au.Agenda_id', 'agendas.id')
            ->join('users as u', 'au.Medico_id', 'u.id')
            ->with(['citas' => function ($query) {
                $query->select('id', 'Agenda_id', 'Hora_Inicio', 'Estado_id')
                    ->where('Estado_id', 3);
            }])
            ->where('agendas.Fecha', '>=', date('Y-m-d'))
            ->whereHas('citas', function ($query) {
                $query->where('Estado_id', 3);
            })
            ->where('agendas.Estado_id', 3)
            ->where('s.id', $request->sede)
            ->where('b.id', $request->tipo_agenda)
            ->get();
        return response()->json($agendas, 200);
    }

    public function agendamedicos(Request $request)
    {

        $agendas = Agenda::select(['agendas.id',
            'agendas.Hora_Inicio',
            'agendas.Hora_Final',
            'sedes.Nombre as Sede',
            'consultorios.id as consultorio_id',
            'consultorios.Nombre as consultorio',
            'consultorios.Cantidad',
            'd.name as tAgenda',
            'c.Nombre as especialidad',
            'c.id as especialidad_id',
            'users.id as medico_id',
            'users.name as medico',
            'agendas.Fecha',
            'agendas.Estado_id',
            'b.Duracion',
            'b.id as especialidadTipoAgenda_id'])
            ->join('consultorios', 'agendas.Consultorio_id', 'consultorios.id')
            ->join('sedes', 'consultorios.Sede_id', 'sedes.id')
            ->join('especialidade_tipoagenda as b', 'agendas.Especialidad_id', 'b.id')
            ->join('especialidades as c', 'b.Especialidad_id', 'c.id')
            ->join('tipo_agendas as d', 'b.Tipoagenda_id', 'd.id')
            ->join('agendausers as au', 'au.Agenda_id', 'agendas.id')
            ->join('users', 'au.Medico_id', 'users.id')
            ->with(['citas' => function ($query) {
                $query->select('citas.id',
                    'citas.Agenda_id',
                    'citas.Hora_Inicio',
                    'citas.Hora_Final',
                    'citas.Estado_id',
                    'p.Primer_Nom',
                    'p.SegundoNom',
                    'p.Primer_Ape',
                    'p.Segundo_Ape',
                    'p.Tipo_Doc',
                    'p.Num_Doc',
                    'cp.Estado_id as estado_citapaciente',
                    'det.observacion')
                    ->leftJoin('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
                    ->leftJoin('detallecitas as det', 'cp.id', 'det.Citapaciente_id')
                    ->leftJoin('pacientes as p', 'cp.Paciente_id', 'p.id')
                    ->whereIn('citas.Estado_id', [3, 4, 7, 8, 9, 10, 12])
                    ->orderBy('citas.Hora_Inicio', 'ASC')
                    ->distinct();
            }])
            ->where('au.Medico_id', $request->medico_id)
            ->whereBetween('agendas.Fecha', [$request->startDate, $request->endDate])
            ->whereIn('agendas.Estado_id', [3, 10])
            ->get();

        return response()->json($agendas, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'fechas' => 'required',
        ]);

        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        foreach ($request->fechas as $fecha) {
            $hora_inicio = $fecha . ' ' . $request->hora_inicio;
            $hora_final  = $fecha . ' ' . $request->hora_final;
            $agenda      = Agenda::where('Consultorio_id', $request->consultorio_id)
                ->where('Fecha', $fecha)
                ->whereIn('Estado_id', [3, 10])
                ->where(function ($query) use ($hora_inicio, $hora_final) {

                    $query->where(function ($query) use ($hora_inicio, $hora_final) {
                        $query->where('Hora_Inicio', '>', $hora_inicio)
                            ->where('Hora_Inicio', '<', $hora_final);
                    })
                        ->orWhere(function ($query) use ($hora_inicio, $hora_final) {
                            $query->where('Hora_Final', '>', $hora_inicio)
                                ->where('Hora_Final', '<', $hora_final);
                        });
                })->first();
                if ($agenda) {
                    return response()->json([
                        'message' => 'Ya el horario de la agenda se encuentra ocupado!'
                    ], 422);
                }
            $agendaDentroRango = Agenda::where('Consultorio_id', $request->consultorio_id)
                ->where('Fecha', $fecha)
                ->whereIn('Estado_id', [3, 10])
                ->where('Hora_Inicio', '<=', $hora_inicio)
                ->where('Hora_Final', '>=', $hora_final)
                ->first();
                if ($agendaDentroRango) {
                    return response()->json([
                        'message' => 'Ya hay una agenda en este mismo rango de hora!'
                    ], 422);
                }
            $agendaocupada = Agenda::where('Consultorio_id', $request->consultorio_id)
                ->where('Fecha', $fecha)
                ->whereIn('Estado_id', [3, 10])
                ->where('Hora_Inicio', $hora_inicio)
                ->where('Hora_Final', $hora_final)
                ->first();
                if ($agendaocupada) {
                    return response()->json([
                        'message' => 'Agenda se encuentra ocupada!'
                    ], 422);
                }
            $medico = Agenda::join('agendausers as au', 'au.Agenda_id', 'agendas.id')
                ->join('users as u', 'au.Medico_id', 'u.id')
                ->where('agendas.Fecha', $fecha)
                ->whereIn('agendas.Estado_id', [3, 10])
                ->where('u.id', $request->medico_id)
                ->where('u.estado_user', 1)
                ->where(function ($query) use ($hora_inicio, $hora_final) {

                    $query->where(function ($query) use ($hora_inicio, $hora_final) {
                        $query->where('agendas.Hora_Inicio', '>', $hora_inicio)
                            ->where('agendas.Hora_Inicio', '<', $hora_final);
                    })
                        ->orWhere(function ($query) use ($hora_inicio, $hora_final) {
                            $query->where('agendas.Hora_Final', '>', $hora_inicio)
                                ->where('agendas.Hora_Final', '<', $hora_final);
                        });
                })
                ->first();
            $medicoNodisponble = Agenda::join('agendausers as au', 'au.Agenda_id', 'agendas.id')
                ->join('users as u', 'au.Medico_id', 'u.id')
                ->where('agendas.Fecha', $fecha)
                ->whereIn('agendas.Estado_id', [3, 10])
                ->where('u.id', $request->medico_id)
                ->where('u.estado_user', 1)
                ->where('agendas.Hora_Inicio', '<', $hora_final)
                ->where('agendas.Hora_Final', '>', $hora_inicio)
                ->first();

            $agendaMedicoOcupada = Agenda::join('agendausers as au', 'au.Agenda_id', 'agendas.id')
                ->join('users as u', 'au.Medico_id', 'u.id')
                ->where('agendas.Fecha', $fecha)
                ->whereIn('agendas.Estado_id', [3, 10])
                ->where('u.id', $request->medico_id)
                ->where('u.estado_user', 1)
                ->where('agendas.Hora_Inicio', $hora_final)
                ->where('agendas.Hora_Final', $hora_inicio)
                ->first();
            if (isset($medico) || isset($medicoNodisponble) || isset($agendaMedicoOcupada)) {
                return response()->json([
                    'message' => '¡Médico ocupado!'], 422);
            }
        }

        $usuario = $request->user();
        //return response()->json(count($request->citas), 422);
        foreach ($request->fechas as $fecha) {
            $agenda = Agenda::create([
                'Usuariocrea_id'  => $usuario->id,
                'Especialidad_id' => $request->especialidad_id['id'],
                'Consultorio_id'  => $request->consultorio_id,
                'Fecha'           => $fecha,
                //'Medico_id' => $request->medico_id,
                'Hora_Inicio'     => $fecha . ' ' . $request->hora_inicio,
                'Hora_Final'      => $fecha . ' ' . $request->hora_final,
            ]);

            Auditoria::create([
                'Descripcion'        => 'Agenda Creada',
                'Tabla'              => 'agendas',
                'Usuariomodifica_id' => auth()->user()->id,
                'Model_id'           => $agenda->id
            ]);

            agendauser::create([
                'Medico_id' => $request->medico_id,
                'Agenda_id' => $agenda->id,
            ]);
            foreach ($request->citas as $cita) {
                if ($fecha == $cita['date']) {
                    Cita::create([
                        'Agenda_id'   => $agenda->id,
                        'Hora_Inicio' => $fecha . ' ' . $cita['time'],
                        'Hora_Final'  => $fecha . ' ' . $cita['horaFin'],
                        'Estado_id'   => ($cita['estado']) ? 3 : 10, //3 disponible, 10 bloqueada
                    ]);
                }
            }
        }

        return response()->json([
            'message' => '¡Agenda creado con Exito!'], 201);
    }

    public function show(Agenda $agenda)
    {
        $agenda = Agenda::find($agenda);
        if (!isset($agenda)) {
            return response()->json([
                'message' => 'El Agenda buscado no Existe!'], 404);
        }
        return response()->json($agenda, 200);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $agenda->update($request->all());
        return response()->json([
            'message' => 'Agenda Actualizada con Exito!',
        ], 200);
    }

    public function available(Request $request, Agenda $agenda)
    {
        $agenda->update($request->all());
        return response()->json([
            'message' => 'Estado del Agenda Actualizada con Exito!',
        ], 200);
    }

    public function inhabilitarAgenda(Agenda $agenda)
    {
        $agenda->update([
            'Estado_id' => 6
        ]);

        return response()->json([
            'message' => 'Estado de la Agenda Actualizada con Exito!',
        ], 200);
    }

    public function enabled()
    {
        $agenda = Agenda::where('Estado', 3);
        return response()->json($agenda, 200);
    }

    public function cancelarAgenda(Agenda $agenda)
    {

        $tiene_citas_asignadas = Cita::where('Agenda_id', $agenda->id)
        ->whereNotIn('Estado_id', [3,10])
        ->count();

        if($tiene_citas_asignadas > 0){
            return response()->json([
                'message' => 'No puede eliminar una agenda con citas asignadas.',
            ], 402);
        }else{

            Auditoria::create([
                'Descripcion'        => 'Agenda Cancelada',
                'Tabla'              => 'agendas',
                'Usuariomodifica_id' => auth()->user()->id,
                'Model_id'           => $agenda->id
            ]);

            $agenda->update([
                'Estado_id' => 6
            ]);

            $citas = Cita::where('Agenda_id', $agenda->id)->get();
            foreach ($citas as $cita) {

                Cita::where('id', $cita['id'])
                ->update([
                    'Estado_id' => 6
                ]);

            }

            return response()->json([
                'message' => 'Agenda cancelada!',
            ], 200);

        }
    }

    public function bloquearAgenda(Agenda $agenda)
    {
        $tiene_citas_asignadas = Cita::where('Agenda_id', $agenda->id)
        ->whereNotIn('Estado_id', [3,10])
        ->count();

        if($tiene_citas_asignadas > 0){
            return response()->json([
                'message' => 'No puede bloquear una agenda con citas asignadas.',
            ], 402);
        }else{
            $estado = ($agenda->Estado_id == 3) ? 10 : 3;
            $msg    = ($agenda->Estado_id == 3) ? 'Agenda Bloqueada' : 'Agenda Desbloqueada';

            $citas = Cita::where('Agenda_id', $agenda->id)->get();
            foreach ($citas as $cita) {

                Cita::where('id', $cita['id'])->update([
                    'Estado_id' => $estado
                ]);
            }

            Auditoria::create([
                'Descripcion'        => $msg,
                'Tabla'              => 'agendas',
                'Usuariomodifica_id' => auth()->user()->id,
                'Model_id'           => $agenda->id
            ]);

            $agenda->update([
                'Estado_id' => $estado
            ]);

            return response()->json([
                'message' => $msg,
            ], 200);
        }
    }

    public function saveChangeCitas(Request $request)
    {
        $agenda = Agenda::select('agendas.id', 'agendas.Fecha')
            ->join('agendausers as au', 'au.Agenda_id', 'agendas.id')
            ->where('au.Medico_id', $request->medico_id)
            ->where('agendas.Especialidad_id', $request->newActivity)
            ->where('agendas.Fecha', $request->Fecha)
            ->whereIn('agendas.Estado_id', [3, 10])
            ->first();

        if (!isset($agenda)) {
            $agenda = Agenda::create([
                'Usuariocrea_id'  => auth()->user()->id,
                'Especialidad_id' => $request->newActivity,
                'Consultorio_id'  => $request->consultorio_id,
                'Fecha'           => $request->Fecha,
                //'Medico_id' => $request->medico_id,
                'Hora_Inicio'     => $request->Hora_Final,
                'Hora_Final'      => $request->Hora_Inicio,
            ]);

            Auditoria::create([
                'Descripcion'        => 'Agenda Creada',
                'Tabla'              => 'agendas',
                'Usuariomodifica_id' => auth()->user()->id,
                'Model_id'           => $agenda->id
            ]);

            agendauser::create([
                'Medico_id' => $request->medico_id,
                'Agenda_id' => $agenda->id,
            ]);

        }

        foreach ($request->citasSelected as $cita) {
            $cita = Cita::find($cita['id'])->update(['Agenda_id' => $agenda->id]);
        }

        $old = Cita::where('Agenda_id', $request->id)->whereIn('Estado_id', [3, 4, 7, 8, 9, 10, 12])->get();
        if (count($old) == 0) {Agenda::find($request->id)->update(['Estado_id' => 2]);}

        Auditoria::create([
            'Descripcion'        => 'Modifico Agenda',
            'Tabla'              => 'agendas',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $request->id
        ]);

        return response()->json([
            'message' => "Cambio de cita exitoso",
        ], 200);
    }

    public function exportAuditoria(Request $request)
    {

        $appointments = Agenda::select(['agendas.id as Agenda_id', 'e2.Nombre as Estado_Agenda',
        'c.Hora_Inicio as Fecha_Asignacion', 's.Nombre as Sede_Atencion', 'c2.Nombre as Consultorio',
        DB::raw("CONCAT(u.name,' ',u.apellido) as Medico_Consulta"), 'c.id as Cita_id',
        'e.Nombre as Estado_Cita', DB::raw("CONCAT(u2.name,' ',u2.apellido) as Asigna_Cita"),
        'p.Num_Doc as Documento_Paciente', 'd.Motivo as Motivo','au.Descripcion',
        DB::raw("CONCAT(u3.name,' ',u3.apellido) as Usuario_Modifica")])
        ->leftjoin('consultorios as c2', 'agendas.Consultorio_id', 'c2.id')
        ->leftjoin('agendausers as aus', 'agendas.id', 'aus.Agenda_id')
        ->leftjoin('users as u', 'aus.Medico_id', 'u.id')
        ->leftjoin('citas as c', 'agendas.id', 'c.Agenda_id')
        ->leftjoin('cita_paciente as cp', 'c.id', 'cp.Cita_id')
        ->leftjoin('users as u2', 'cp.Usuario_id', 'u2.id')
        ->leftjoin('pacientes as p', 'cp.Paciente_id', 'p.id')
        ->leftjoin('sedes as s', 'c2.Sede_id', 's.id')
        ->leftjoin('detallecitas as d', 'cp.id', 'd.Citapaciente_id')
        ->leftjoin('auditorias as au', 'Model_id', 'agendas.id')
        ->leftjoin('users as u3', 'au.Usuariomodifica_id', 'u3.id')
        ->leftjoin('estados as e', 'cp.Estado_id', 'e.id')
        ->leftjoin('estados as e2', 'c.Estado_id', 'e2.id')
        ->where('au.Tabla', 'agendas')
        ->whereBetween('agendas.Fecha', [$request->fechaDesde,$request->fechaHasta]);
        if($request->medico_id){
            $appointments->where('u.id',$request->medico_id);
        }

        return (new FastExcel($appointments->get()))->download('file.xls');
    }

    public function reporteGrupales(Request $request)
    {

        switch ($request->tipo) {
            case 536:
                $result = Cita::select([
                    'citas.Hora_Inicio as Fecha_bloque_cita_inicial',
                    'citas.Hora_Final as Fecha_blwoque_cita_final',
                    DB::raw("CONCAT(u.name, ' ', u.apellido) as creado_por"),
                    DB::raw("CONCAT(p.Primer_Nom, ' ', p.SegundoNom, ' ', p.Primer_Ape, ' ', p.Segundo_Ape) as paciente"),
                    'p.Num_Doc as cedula', 'p.Direccion_Residencia as direccion', 'p.Telefono', 'p.Correo1 as correo', 'e.Nombre as estado',
                    DB::raw("CONCAT(us.name, ' ', us.apellido) as medico")
                    ])
                    ->join('agendas as a', 'Citas.Agenda_id', 'a.id')
                    ->join('cita_paciente as cp', 'Citas.id', 'cp.Cita_id')
                    ->join('users as u', 'a.Usuariocrea_id', 'u.id')
                    ->join('pacientes as p', 'p.id', 'cp.Paciente_id')
                    ->join('estados as e', 'cp.Estado_id', 'e.id')
                    ->join('agendausers as a2', 'a.id', 'a2.Agenda_id')
                    ->join('users as us', 'a2.Medico_id', 'us.id')
                    ->where('eta.Tipoagenda_id', $request->tipo)
                    ->join('especialidade_tipoagenda as eta','eta.id','a.Especialidad_id')
                    // ->where('eta.Tipoagenda_id', $request->tipo)
                    ->whereBetween('a.Fecha', [$request->fechaInicio,$request->fechaFinal])->get();
                    break;
            case 537:
                $pacientes = DB::select('exec dbo.Total_Citas_Atendidas ?,?', [$request->fechaInicio,$request->fechaFinal]);
                $result = $pacientes;
                break;
        }
        return (new FastExcel($result))->download('file.xls');
    }

    public function sedesAgendas()
    {
        $sedes = Sede::all();
        return response()->json($sedes, 200);
    }
}
