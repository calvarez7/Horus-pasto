<?php

namespace App\Http\Controllers\Redvital;

use App\Modelos\Cita;
use App\Modelos\Sede;
use App\PasswordReset;
use App\Modelos\Agenda;
use App\Modelos\Paciente;
use Illuminate\Support\Str;
use App\Modelos\Detallecita;
use Illuminate\Http\Request;
use App\Modelos\Especialidade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Formats\CertificadoCitas;
use App\Http\Controllers\Controller;
use App\Modelos\Certificado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Modelos\Especialidadtipoagenda;
use Illuminate\Support\Facades\Validator;
use App\Formats\CertificadoAfiliado;

class RedvitalController extends Controller
{

    public function validacionPaciente(Request $request)
    {
        $datos = Paciente::getRepository()->validacionRedvital($request);

        if ($datos) {
            $data = Paciente::getRepository()->datosPacienteRedvital($request);
            return response()->json($data, 200);
        }

        if(isset(Auth::user()->id)){
            return response()->json([
                'message' => 'La cédula no registra en nuestra base de datos.'], 403);
        }else{
            return response()->json([
                'message' => 'Señor usuario su cédula y/o contraseña son incorrectos.'], 403);
        }

    }

    public function agendaDisponible($actividad,Request $request)
    {


        $sede = Sede::where('Sedeprestador_id', $request->Sedeprestador_id)->where('Estado_id', 1)->first();
        $sede_id = intval($sede->id);
        $fecha_mas_14dias = date("Y-m-d",strtotime(date('Y-m-d')."+ 14 days"));
        $fecha_mas_unahora  = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')."+ 1 hour"));
        $hoy = date('Y-m-d');

        $agenda = Agenda::select(['agendas.id', 's.id as Sede', 'a.Nombre as nombreConsultorio',
        's.Nombre as nombreSede','c.Nombre as Especialidad', 'd.name as tipoActividad', 'u.name as nombreMedico',
        'u.apellido as apellidoMedico','agendas.fecha', 'agendas.Hora_Inicio', 'agendas.Hora_Final'])
        ->join('consultorios as a', 'agendas.Consultorio_id', 'a.id')
        ->join('sedes as s', 'a.Sede_id', 's.id')
        ->join('especialidade_tipoagenda as b', 'agendas.Especialidad_id', 'b.id')
        ->join('especialidades as c', 'b.Especialidad_id', 'c.id')
        ->join('tipo_agendas as d', 'b.Tipoagenda_id', 'd.id')
        ->join('agendausers as au', 'au.Agenda_id', 'agendas.id')
        ->join('users as u', 'au.Medico_id', 'u.id')
        ->with(['citas' => function ($query) use ($fecha_mas_unahora) {
            $query->select('id', 'Agenda_id', 'Hora_Inicio', 'Estado_id')
            ->where('Hora_Inicio', '>', $fecha_mas_unahora)
            ->where('Estado_id', 3);
        }])
        ->whereHas('citas', function ($query) {
            $query->where('Estado_id', 3);
        })
        ->whereBetween('agendas.Fecha', [$hoy, $fecha_mas_14dias])
        ->where('agendas.Estado_id', 3)
        ->where('s.id', $sede_id)
        ->where('b.id', $actividad)
        ->get();

        return response()->json($agenda, 200);

    }

    public function asignarcita(Request $request, Cita $cita)
    {

       $agenda = Agenda::select(['c.*', 'agendas.Fecha as fecha', 'agendas.Especialidad_id as especilidad'])
           ->join('consultorios as c', 'agendas.Consultorio_id', 'c.id')
           ->join('citas as ci', 'ci.Agenda_id', 'agendas.id')
           ->where('ci.id', $cita->id)
           ->where('ci.Estado_id', 3)
           ->first();

       if (isset($agenda)) {
           if ($agenda->Cantidad > $cita->Cantidad) {
               $hora_inicio   = $cita->Hora_Inicio;
               $hora_final    = $cita->Hora_Final;

               $disponibildad = Paciente::join('cita_paciente as cp', 'cp.Paciente_id', 'pacientes.id')
                   ->join('citas as c', 'cp.Cita_id', 'c.id')
                   ->join('agendas as a', 'c.Agenda_id', 'a.id')
                   ->where('a.Fecha', $agenda->fecha)
                   ->where(function ($query) use ($hora_inicio, $hora_final) {
                       $query->where('c.Hora_Inicio', '>', $hora_inicio)
                           ->where('c.Hora_Inicio', '<', $hora_final)
                           ->orWhere(function ($query) use ($hora_inicio, $hora_final) {
                               $query->where('c.Hora_Final', '>', $hora_inicio)
                                   ->where('c.Hora_Final', '<', $hora_final);
                           });
                   })
                   ->where('pacientes.id', $request->paciente_id)
                   ->where('cp.Estado_id', '!=', 6)
                   ->first();

               $disponibildad2 = Paciente::join('cita_paciente as cp', 'cp.Paciente_id', 'pacientes.id')
                   ->join('citas as c', 'cp.Cita_id', 'c.id')
                   ->join('agendas as a', 'c.Agenda_id', 'a.id')
                   ->where('a.Fecha', $agenda->fecha)
                   ->where('pacientes.id', $request->paciente_id)
                   ->where('c.Hora_Inicio', '=', $hora_inicio)
                   ->where('cp.Estado_id', '!=', 6)
                   ->first();

               if (isset($disponibildad) || isset($disponibildad2)) {
                   return response()->json([
                       'message' => '¡Ya tiene cita para ese día en ese rango de hora!'
                   ], 422);
               }

               $disponibildad3 = Paciente::join('cita_paciente as cp', 'cp.Paciente_id', 'pacientes.id')
                   ->join('citas as c', 'cp.Cita_id', 'c.id')
                   ->join('agendas as a', 'c.Agenda_id', 'a.id')
                   ->where('pacientes.id', $request->paciente_id)
                   ->where('a.Especialidad_id', $agenda->especilidad)
                   ->whereIn('cp.Estado_id', [4, 7, 11])
                   ->first();

               $enabledEsp = Especialidade::select(['especialidades.id'])
                   ->join('especialidade_tipoagenda AS et','et.Especialidad_id','especialidades.id')
                   ->where('et.id', $agenda->especilidad)->first();

               if($enabledEsp->id != 49 && $enabledEsp->id != 31){
                   if (isset($disponibildad3)) {
                       return response()->json([
                           'message' => '¡Ya tiene una cita del mismo tipo!',
                       ], 422);
                   }
               }

               $especialidades = Especialidade::select(['Especialidades.*'])
                   ->join('especialidade_tipoagenda', 'Especialidades.id', 'especialidade_tipoagenda.Especialidad_id')
                   ->where('especialidade_tipoagenda.id', $agenda->especilidad)
                   ->first();

               if (isset($especialidades)) {
                   $year = date('Y');

                   $pacientes = Paciente::select(['pacientes.*'])
                       ->join('cita_paciente as cp', 'pacientes.id', 'cp.id')
                       ->where('cp.Paciente_id', $request->paciente_id)
                       ->where('cp.Fecha_solicita', 'LIKE', $year . '%')
                       ->where('cp.Estado_id', '<>', 6)
                       ->first();

                   if (empty($pacientes)) {
                       $pivote = [
                           "Cup_id"         => $especialidades->Primeravez_id,
                           "Estado_id"      => 4,
                           "Fecha_solicita" => $request->fecha_solicitada,
                       ];
                   } else {
                       $pivote = [
                           "Cup_id"         => $especialidades->Control_id,
                           "Estado_id"      => 4,
                           "Fecha_solicita" => $request->fecha_solicitada,
                       ];
                   }

                   $cita->paciente()->attach($request->paciente_id, $pivote);
                   $cita->update([
                       'Cantidad' => $cita->Cantidad + 1,
                   ]);

                   $cita_paciente = Cita::select(['cp.*'])
                       ->join('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
                       ->where('cp.Cita_id', $cita->id)
                       ->where('cp.Paciente_id', $request->paciente_id)
                       ->first();

                   $detcita    = new Detallecita;
                   $detcita->Citapaciente_id   = $cita_paciente->id;
                   $detcita->Estado_id   = 4;
                   $detcita->observacion = "Cita agendada por paciente";
                   $detcita->save();

                   if ($agenda->Cantidad == $cita->Cantidad) {
                       $cita->update([
                           'Estado_id' => 4,
                       ]);
                   }
               }
           }else {
               return response()->json([
                   'message' => '¡La cita no tiene cupo!',
               ], 422);
           }
       }else {
           return response()->json([
               'message' => '¡No está disponible esta cita!',
           ], 422);
       }

       return response()->json([
           'message' => 'Se asignó la cita con exito, puede verla en pendientes!',
       ], 200);

    }

    public function actividades(){

        $especialidadtipoagenda = Especialidadtipoagenda::select([
            'especialidade_tipoagenda.*', 'Especialidades.Nombre as nombreEspecilidad', 'tipo_agendas.name as nombreActividad'
        ])
        ->join('Especialidades', 'especialidade_tipoagenda.Especialidad_id', 'Especialidades.id')
        ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', 'tipo_agendas.id')
        ->where('Especialidades.Estado_id', 1)
        ->where('tipo_agendas.Estado_id', 1)
        ->whereIn('especialidade_tipoagenda.id', [1])
        ->get();

        return response()->json($especialidadtipoagenda, 200);

    }

    public function updatepaciente(Request $request, Paciente $paciente){

        $paciente->update([
            'Telefono'  => $request->Telefono,
            'Celular1'  => $request->Celular1,
            'Celular2'  => $request->Celular2,
            'Correo1'   => $request->Correo1,
            'Direccion_Residencia'  => $request->Direccion_Residencia
        ]);

        return response()->json([
            'message' => 'Datos de contacto actualizados con exito!',
        ], 200);

    }

    public function datapaciente(Paciente $paciente){

        return response()->json($paciente, 200);

    }

    public function generarPassword(Request $request){

        list($año, $mes, $dia) = explode('-', $request->fechaNacimiento_generar);
        $año; $mes; $dia;

        if ($dia < 10){
            $dia = preg_replace('/^0+/', '', $dia);
        }

        $paciente = Paciente::where('Num_Doc', $request->cedula_generar)->first();

        $paciente->update([
            'Correo1' => $request->email_generar
        ]);

        $paciente->where('Correo1', $request->email_generar)
        ->where('Fecha_Naci', 'like', "%".$dia.'/'.$mes.'/'.$año."%")
        ->first();

        if($paciente){

            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $paciente->Correo1],
                [
                    'email' => $paciente->Correo1,
                    'token' => Str::random(60),
                ]
            );

            if ($paciente && $passwordReset) {

                $url = url('/generar/password/'.$paciente->id.'/'.$passwordReset->token);
                $email = Mail::send('email',['url' => $url, 'paciente' => $paciente], function ($m) use ($url, $paciente) {
                    $m->to($paciente->Correo1, $paciente->Primer_Nom);
                    $m->subject('Generar contraseña');
                });

            }

            return response()->json([
                'message' => 'Se le envio al correo una notificacion!',
            ], 200);

        }else{
            return response()->json([
                'message' => 'Los datos ingresados no coinciden con nuestra base de datos, si
                crees que es un error comunicate con el área de atención al usuario, linea Magisterio (018000413860),
                linea Ferrocarril (018000413704 opción 1)',
            ], 422);
        }

    }

    public function createPassword(Request $request){

        $validate = Validator::make($request->all(), [
            'password'          => 'required|string|confirmed',
            'celular'        => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $paciente = Paciente::where('id', $request->paciente_id)->first();
        $passwordReset = PasswordReset::where('email',  $paciente->Correo1)->first();
        if (!$passwordReset) {
            return response()->json([
                'message' => 'No se valido su solicitud vuelva a intentar generar su contraseña.',
            ], 401);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'Su tiempo de generar contraseña caduco vuelva a intentar generar su contraseña.',
            ], 401);
        }

        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset) {
            return response()->json([
                'message' => 'Este token de restablecimiento de contraseña no es válido vuelva a intentar generar su contraseña.',
            ], 401);
        }

        if (!$paciente) {
            return response()->json([
                'message' => 'No registra como usuario de nuestra base de datos o vuelva a intentar generar su contraseña..',
            ], 401);
        }

        $paciente->password = bcrypt($request->password);
        $paciente->Celular1 = $request->celular;
        $paciente->save();
        $passwordReset->delete();

        return response()->json([
            'message' => 'Se ha creado la contraseña con exito!',
        ], 200);

    }

    public function auditoriaCertificado(Request $request)
    {
        $paciente = Certificado::create([
            'num_doc' => $request->Num_Doc,
            'tipo_doc' => $request->Tipo_Doc,
            'full_name' => $request->Primer_Nom. ' ' .$request->Primer_Ape. ' ' .$request->Segundo_Ape,
            'id_estadoafiliado' => $request->Estado_Afiliado,
            'id_tipoafiliado' => $request->Tipo_Afiliado,
            'id_ipsptoAtencion' => $request->Sedeprestador_id
        ]);
        return response()->json($paciente, 200);
    }

    public function imprimirCertificado(Request $request){
        $pdf = new CertificadoAfiliado();
        $pdf->generar($request->id);
        return $pdf->download();
    }

    public function recordatorio(Request $request){
        $pdf = new CertificadoCitas();
        $pdf->generar($request->all());
        return $pdf->download();
    }

}
