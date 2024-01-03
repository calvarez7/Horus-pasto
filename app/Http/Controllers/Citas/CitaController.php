<?php

namespace App\Http\Controllers\Citas;

use Error;
use App\User;
use App\Modelos\Cum;
use App\Modelos\Cup;
use App\Modelos\Cita;
use App\Modelos\Cie10;
use App\Modelos\Orden;
use GuzzleHttp\Client;
use App\Modelos\AcRips;
use App\Modelos\Agenda;
use App\Modelos\Estado;
use App\Modelos\Conducta;
use App\Modelos\Cuporden;
use App\Modelos\Paciente;
use App\Modelos\Tipocita;
use App\Modelos\agendauser;
use App\Modelos\Referencia;
use App\Modelos\Detallecita;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Examenfisico;
use App\Modelos\Imagenologia;
use App\Modelos\Cie10paciente;
use App\Modelos\Especialidade;
use App\Services\CitasService;
use Illuminate\Support\Carbon;
use App\Modelos\CitasCompuesta;
use App\Modelos\Model_has_role;
use App\Modelos\notaenfermeria;
use App\Modelos\Adjuntos_general;
use App\Modelos\Detaarticulorden;
use App\Modelos\Saludocupacional;
use App\Services\FonoPlusService;
use Illuminate\Http\JsonResponse;
use App\Modelos\EspecialidadesCup;
use App\Modelos\Sedes_sumimedical;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Modelos\DemandaInsatisfecha;
use App\Modelos\DescripcionPaciente;
use App\Services\FonoPlusWebService;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\AntecedentesPersonale;
use App\Modelos\CupsordenCitapaciente;
use App\Modelos\Antecedenteocupacional;
use App\Modelos\AntecedentesQuirurgico;
use App\Modelos\Especialidadtipoagenda;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Constraint\Count;
use App\Modelos\AntecedenteToxicologico;
use App\Modelos\AntecedenteFarmacologico;
use Illuminate\Support\Facades\Validator;
use App\Modelos\OtrosAntecedentesPersonale;
use Symfony\Component\VarDumper\Cloner\Data;
use App\Http\Requests\GuardarFirmaConsentimientoRequest;

class CitaController extends Controller
{
    public function all(Request $request)
    {
        $citas = Cita::all();
        return response()->json($citas, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Hora_Inicio' => 'string',
            'Hora_Final'  => 'string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        //return $request;
        $agenda            = Agenda::find($request->agenda_id);
        $cita              = new Cita;
        $cita->Hora_Inicio = $request->horainicio;
        $cita->Hora_Final  = $request->horafinal;
        $agenda->cita()->save($cita);
        return response()->json([
            'message' => 'Cita Creada con Exito!',
        ], 201);
    }

    public function show(Cita $cita)
    {
        $cita = Cita::find($cita);
        if (!isset($cita)) {
            return response()->json([
                'message' => 'La Cita buscada no Existe!'], 404);
        }
        return response()->json($cita, 200);
    }

    public function update(Request $request, Cita $cita)
    {
        $cita->update($request->all());
        return response()->json([
            'message' => 'Cita Actualizada con Exito!',
        ], 200);
    }

    public function asignarcita(Request $request, Cita $cita)
    {
        $citaPacienteCreado = null;
        $pacientesAsignados = citapaciente::where("Cita_id",$cita->id)->where('Estado_id',4)->count();
        $cantidadDisponible = Agenda::select(["c.Cantidad"])->join('consultorios as c','c.id','agendas.Consultorio_id')->where("agendas.id",$cita->Agenda_id)->first();
        if($pacientesAsignados === intval($cantidadDisponible->Cantidad)){
            $cita->Estado_id = 4;
            $cita->save();
            return response()->json([
                'message' => '¡La cita ya se encuentra ocupada!',
            ], 422);
        }

        if(isset($request->cuporden)){
            $existeOrdenPaciente = CupsordenCitapaciente::join('cita_paciente', 'citapaciente_id', 'cita_paciente.id')
            ->where('cita_paciente.Estado_id', 4)
            ->where('cupsorden_citapacientes.cupordens_id', $request->cuporden)
            ->first();

            if($existeOrdenPaciente){
                return response()->json([
                    'message' => '¡El paciente ya tiene una cita asignada con esa Orden y Cup!',
                ], 422);
            }
        }

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
                    ->where('pacientes.id', $request->Paciente_id)
                    ->where('cp.Estado_id', '!=', 6)
                    ->first();

                $disponibildad2 = Paciente::join('cita_paciente as cp', 'cp.Paciente_id', 'pacientes.id')
                    ->join('citas as c', 'cp.Cita_id', 'c.id')
                    ->join('agendas as a', 'c.Agenda_id', 'a.id')
                    ->where('a.Fecha', $agenda->fecha)
                    ->where('pacientes.id', $request->Paciente_id)
                    ->where('c.Hora_Inicio', '=', $hora_inicio)
                    ->where('cp.Estado_id', '!=', 6)
                    ->first();

                if (isset($disponibildad) || isset($disponibildad2)) {
                    return response()->json([
                        'message' => '¡El paciente tiene cita para ese día en ese rango de hora!'
                    ], 422);
                }

                $disponibildad3 = Paciente::join('cita_paciente as cp', 'cp.Paciente_id', 'pacientes.id')
                    ->join('citas as c', 'cp.Cita_id', 'c.id')
                    ->join('agendas as a', 'c.Agenda_id', 'a.id')
                    ->where('pacientes.id', $request->Paciente_id)
                    ->where('a.Especialidad_id', $agenda->especilidad)
                    ->whereIn('cp.Estado_id', [4, 7, 11])
                    ->first();

                $enabledEsp = Especialidade::select(['especialidades.id','et.Tipoagenda_id'])
                    ->join('especialidade_tipoagenda AS et','et.Especialidad_id','especialidades.id')
                    ->where('et.id', $agenda->especilidad)->first();

                if($enabledEsp->id != 49 && $enabledEsp->id != 31){
                    if (isset($disponibildad3)) {
                        return response()->json([
                            'message' => '¡El paciente ya tiene una cita del mismo tipo!',
                        ], 422);
                    }
                }


                $especialidades = Especialidade::select(['Especialidades.*','especialidade_tipoagenda.marcacion'])
                    ->join('especialidade_tipoagenda', 'Especialidades.id', 'especialidade_tipoagenda.Especialidad_id')
                    ->where('especialidade_tipoagenda.id', $agenda->especilidad)
                    ->first();

                if($especialidades->Nombre == "Imagenologia"){
                    $estado = 18;
                }else {
                    $estado = 4;
                }

                if (isset($especialidades)) {
                    $year = date('Y');

                    $pacientes = Paciente::select(['pacientes.*'])
                        ->join('cita_paciente as cp', 'pacientes.id', 'cp.Paciente_id')
                        ->where('cp.Paciente_id', $request->Paciente_id)
                        ->where('cp.especialidad', $especialidades->Nombre)
                        ->where('cp.created_at', 'LIKE', $year.'%')
                        ->where('cp.Estado_id', '<>', 6)
                        ->where('cp.Estado_id', '<>', 12)
                        ->get();

                    $ciclo = Paciente::select(['pacientes.*'])
                        ->where('id', $request->Paciente_id)
                        ->first();

                    if($especialidades->marcacion == '1ra Infancia'){
                        if($ciclo->ciclo_vida != '1ra Infancia'){
                            return response()->json([
                            'message' => '¡Al Paciente no se le puede asinar esta cita, Solo es para personas de 0 a 5 años!',
                            ], 422);
                        }
                    }
                    if($especialidades->marcacion == 'Infancia'){
                        if($ciclo->ciclo_vida != 'Infancia'){
                            return response()->json([
                            'message' => '¡Al Paciente no se le puede asinar esta cita, Solo es para personas de 6 a 11 años!',
                            ], 422);
                        }
                    }
                    if($especialidades->marcacion == 'Adolescencia'){
                        if($ciclo->ciclo_vida != 'Adolescencia'){
                            return response()->json([
                            'message' => '¡Al Paciente no se le puede asinar esta cita, Solo es para personas de 12 a 17 años!',
                            ], 422);
                        }
                    }
                    if($especialidades->marcacion == 'Juventud'){
                        if($ciclo->ciclo_vida != 'Juventud'){
                            return response()->json([
                            'message' => '¡Al Paciente no se le puede asinar esta cita, Solo es para personas de 18 a 28 años!',
                            ], 422);
                        }
                    }
                    if($especialidades->marcacion == 'Adultez'){
                        if($ciclo->ciclo_vida != 'Adultez'){
                            return response()->json([
                            'message' => '¡Al Paciente no se le puede asinar esta cita, Solo es para personas de 29 a 59 años!',
                            ], 422);
                        }
                    }
                    if($especialidades->marcacion == 'Vejez'){
                        if($ciclo->ciclo_vida != 'Vejez'){
                            return response()->json([
                            'message' => '¡Al Paciente no se le puede asinar esta cita, Solo es para personas de 60 años!',
                            ], 422);
                        }
                    }

                    if (count($pacientes) === 0) {
                        $pivote = [
                            "Cup_id"         => $especialidades->Primeravez_id,
                            "Estado_id"      => $estado,
                            "Usuario_id"     => auth()->id(),
                            "Fecha_solicita" => $request->fecha_solicitada,
                            "salud_ocupacional" => null,
                            "Tipocita_id" => null
                        ];
                    } else {
                        $pivote = [
                            "Cup_id"         => $especialidades->Control_id,
                            "Estado_id"      => $estado,
                            "Usuario_id"     => auth()->id(),
                            "Fecha_solicita" => $request->fecha_solicitada,
                            "salud_ocupacional" => null,
                            "Tipocita_id" => null
                        ];
                    }
                    $tipoCita = ['Psicologia', 'Voz', 'Visiometria', 'Consulta Medica'];
                    $tipoCitaOtro = ['Consulta Medica'];
                    $cupO = [2013, 2358, 2344, 71];
                    if ($especialidades->Nombre == "Examenes ocupacionales periódicos") {
                        foreach($tipoCita as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }elseif ($especialidades->Nombre == "Examenes ocupacionales ingreso") {
                        foreach($tipoCita as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }elseif ($especialidades->Nombre == "Examenes ocupacionales egreso") {
                        foreach($tipoCita as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }elseif ($especialidades->Nombre == "Examenes ocupacionales post incapacidad") {
                        foreach($tipoCitaOtro as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }elseif ($especialidades->Nombre == "Examenes ocupacionales reubicación") {
                        foreach($tipoCitaOtro as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }elseif ($especialidades->Nombre == "Examenes ocupacionales para participar en eventos deportivos"){
                        foreach($tipoCitaOtro as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }elseif ($especialidades->Nombre == "Examenes ocupacionales para participar en eventos folcloricos"){
                        foreach($tipoCitaOtro as $key => $tipo){
                            $tipocita = Tipocita::where('Nombre', $tipo)->first();
                            $pivote["salud_ocupacional"] = $tipo;
                            $pivote["Tipocita_id"] = $tipocita->id;
                            $pivote["Cup_id"] = $cupO[$key];
                            $cita->paciente()->attach($request->Paciente_id, $pivote);
                        }
                    }
                    else{
                        $pivote["especialidad"] = $especialidades->Nombre;
                        $cita->paciente()->attach($request->Paciente_id, $pivote);
                    }
                    $cita->update([
                        'Cantidad' => $cita->Cantidad + 1,
                    ]);
                    $cita_paciente = Cita::select(['cp.*'])
                        ->join('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
                        ->where('cp.Cita_id', $cita->id)
                        ->where('cp.Paciente_id', $request->Paciente_id)
                        ->where('cp.estado_id',4)
                        ->first();
                    $citaPacienteCreado = $cita_paciente->id;
                    if (isset($request->referencia_id)) {
                        citapaciente::find($cita_paciente->id)
                        ->update([
                            'referencia_id' => $request->referencia_id
                        ]);
                    }

                    if(isset($request->cuporden)){
                        $existeCupOrden = CupsordenCitapaciente::where('cupordens_id', $request->cuporden)->first();
                        $idcups = Cuporden::where('id',$request->cuporden)->first();

                        if($existeCupOrden){
                            $existeCupOrden->update([
                                'citapaciente_id' => $cita_paciente->id
                            ]);
                        }else{
                            CupsordenCitapaciente::create([
                                'cupordens_id' => $request->cuporden,
                                'citapaciente_id' => $cita_paciente->id
                            ]);
                        }
                        $cupOrden = CupsordenCitapaciente::where('cupordens_id', $request->cuporden)->first();
                        citapaciente::find($cupOrden->citapaciente_id)
                        ->update([
                            'Cup_id' => intval($idcups->Cup_id)
                        ]);

                    }

                if(isset($request->cup)){
                    $tipo_paciente = $request->tipo_paciente;
                    $cup_id = explode("-", $request->cup);
                    $cup = $cup_id[0];
                }

                if(isset($request->fecha_orden)){
                  $fecha =  str_replace('T', ' ', $request->fecha_orden);
                }

                $detcita    = new Detallecita;
                $detcita->Citapaciente_id   = $cita_paciente->id;
                $detcita->Usuario_id     = auth()->id();
                $detcita->Estado_id   = 4;
                $detcita->observacion = $request->observaciones;
                $detcita->lado = $request->lado;
                if(isset($cup)){
                    $detcita->cup_id = $cup;
                    $detcita->tipo_paciente = $tipo_paciente;
                }
                $detcita->prioridad = $request->prioridad;
                $detcita->lectura = $request->lectura;
                $detcita->tecnica = $request->tecnica;
                $detcita->ubicacion = $request->ubicacion;
                $detcita->cama = $request->cama;
                $detcita->aislado = $request->aislado;
                $detcita->observacion_aislado = $request->obs_aislado;
                if(isset($fecha)){
                    $detcita->fecha_orden = $fecha;
                }
                $detcita->save();

                    if(isset($request->cita_paciente_padre)){
                        if($request->cita_paciente_padre != null){
                            CitasCompuesta::create([
                                "cita_paciente_id" =>  $request->cita_paciente_padre,
                                "cita_paciente_relacion_id" => $cita_paciente->id
                            ]);
                        }
                    }
                    if ($agenda->Cantidad == $cita->Cantidad) {
                        $cita->update([
                            'Estado_id' => 4,
                        ]);
                    }
                }

                $infosms = citapaciente::select(['p.Celular1', 'p.Primer_Nom', 'p.Primer_Ape', 'ct.Hora_Inicio',
                           'se.Nombre as sede', 'ta.name as actividad', 'esp.Nombre as especialdad',
                           'ta.modalidad', 'ta.sms','se.direccion as direcionSede'])
                           ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
                           ->join('citas as ct', 'cita_paciente.Cita_id', 'ct.id')
                           ->join('agendas as ag', 'ct.Agenda_id', 'ag.id')
                           ->join('especialidade_tipoagenda as et', 'ag.Especialidad_id', 'et.id')
                           ->join('especialidades as esp', 'et.Especialidad_id', 'esp.id')
                           ->join('tipo_agendas as ta', 'et.Tipoagenda_id', 'ta.id')
                           ->join('consultorios as cs', 'ag.Consultorio_id', 'cs.id')
                           ->join('sedes as se', 'cs.Sede_id', 'se.id')
                           ->where('cita_paciente.id', $cita_paciente->id)
                           ->first();

                $Mes = Carbon::parse($agenda->fecha)->isoFormat('YYYY-MM');
                $inicio = date('Y-m').'-01';
                $final = date('Y-m-t');
                $demandas = DemandaInsatisfecha::where('especialidad_id',$enabledEsp->id)
                ->where('tipo_agenda_id',$enabledEsp->Tipoagenda_id)
                ->where('paciente_id',$request->Paciente_id)
                ->where('estado_id',1)
                ->whereBetween('demanda_insatisfechas.created_at', [ $inicio.' 00:00:00.000', $final.' 23:59:00.000'])
                ->get();

                if($demandas){
                    foreach($demandas as $demanda){
                        $demanda->update([
                            'estado_id' => 5,
                            'cita_asignada_id' => $cita->id
                        ]);
                    }
                }

                if($infosms){
                    if($infosms->sms == 'SI' && $infosms->modalidad != ''){
                        if($infosms->Celular1){
                            $fecha = Carbon::parse($infosms->Hora_Inicio)->isoFormat('MMMM Do YYYY');
                            $diaAcento = Carbon::parse($infosms->Hora_Inicio)->isoFormat('dddd');
                            $hora =  substr($infosms->Hora_Inicio, 11, -4);
                            $dia = str_replace(array('é', 'á'),array('e', 'a'), $diaAcento);
                            $msg = 'Senor@ '. $infosms->Primer_Nom .' '. $infosms->Primer_Ape .', Sumimedical le informa su cita de '. $infosms->actividad .' de '.  $infosms->especialdad.  ', el dia '. $dia .', '. $fecha .' a las '. $hora .' en la sede, '. $infosms->sede .' '.'con direccion'.' '.$infosms->direcionSede.' modalidad '. $infosms->modalidad .'. Si su cita es con especialista por favor llevar a la consulta, historias clínicas de atenciones previas. Si no puede asistir, cancele con un día de anticipación, !Recomendaciones de mi cita en https://sumimedical.com/Archivo%20PDF%20recordatorio%20de%20cita.pdf.';
                            $celular = intval('57'.$infosms->Celular1);

                            $client = new Client();
                            $prueba = $client->post(
                                'https://contacto-masivo.com/sms/back_sms/public/api/send/sms/json',
                                [
                                    'json' => [
                                        'token' => '5yjq1kkr4eddl9gjonto1',
                                        'email' => 'sumimedical@fonoplus.com',
                                        'type_send' => '1via',
                                        'data' => [
                                            ['cellphone' => $celular,
                                            'message' => $msg]
                                        ]

                                    ]
                                ]
                            );
                            return $prueba;
                        }
                    }
                }

            } else {
                return response()->json([
                    'message' => '¡La cita no tiene cupo!',
                ], 422);
            }
        } else {
            return response()->json([
                'message' => '¡No está disponible esta cita!',
            ], 422);
        }

        return response()->json([
            'cita_id' => $citaPacienteCreado,
            'message' => 'Se asignó la cita del paciente con Exito!',
        ], 200);
    }

    public function available(Request $request, Cita $cita)
    {
        $cita->update($request->all());
        $pivote = [
            'Actualizado_por' => auth()->id(),
        ];
        $cita->estados()->attach($request->Estado, $pivote);

        if ($request->Estado == 4) {
            return response()->json([
                'message' => 'La cita esta Pendiente por confirmar',
            ], 200);
        } elseif ($request->Estado == 6) {
            return response()->json([
                'message' => 'La cita fue Cancelada por el paciente',
            ], 200);
        } elseif ($request->Estado == 8) {
            return response()->json([
                'message' => 'La cita esta en Curso',
            ], 200);
        } elseif ($request->Estado == 9) {
            return response()->json([
                'message' => 'La cita fue Atendida con Exito',
            ], 200);
        } elseif ($request->Estado == 10) {
            return response()->json([
                'message' => 'La cita fue Bloqueado con Exito',
            ], 200);
        } elseif ($request->Estado == 11) {
            return response()->json([
                'message' => 'La cita fue Reasignado con Exito',
            ], 200);
        } elseif ($request->Estado == 12) {
            return response()->json([
                'message' => 'La cita fue cancelada por Inasistencia con Exito',
            ], 200);
        }
        return response()->json([
            'message' => 'Cita Actualizada con Exito!',
        ], 200);
    }

    public function enabled()
    {
        $citas = Cita::where('Estado', 3)->get();
        return response()->json($citas, 200);
    }

    public function cancelar(Cita $cita, Request $request)
    {

        $cita_paciente = citapaciente::where('Cita_id', $cita->id)
            ->where('Paciente_id', $request->Paciente_id)
            ->where('Estado_id', '!=', 6)
            ->first();
        $paciente = Paciente::where('id', $cita_paciente->Paciente_id)->first();
        try{
            $cita_service = new CitasService;
            $response = $cita_service->cancelar($cita_paciente->id, auth()->id(), $request->motivo);

            if ($paciente->Celular1) {
                $client = new Client();
                $client->post(
                    'https://contacto-masivo.com/sms/back_sms/public/api/send/sms/json',
                    [
                        'json' => [
                            'token' => '5yjq1kkr4eddl9gjonto1',
                            'email' => 'sumimedical@fonoplus.com',
                            'type_send' => '1via',
                            'data' => [
                                [
                                    'cellphone' => $paciente->Celular1,
                                    'message' => 'Se canceló su cita con éxito!'
                                ]
                            ]
                        ]
                    ]
                );
            }

        }catch(\Throwable $th){
            return response()->json($th->getMessage());
        }

        return response()->json([
            'message' => 'Se canceló la cita con exito!',
        ], 200);
    }

    public function bloquearCita(Cita $cita)
    {
        $tiene_citas_asignadas = Cita::where('id', $cita->id)
        ->whereNotIn('Estado_id', [3,10])
        ->count();

        if($tiene_citas_asignadas > 0){
            return response()->json([
                'message' => 'No puede bloquear una cita asignada.',
            ], 402);
        }else{
            $estado = ($cita->Estado_id == 3) ? 10 : 3;
            $msg    = ($cita->Estado_id == 3) ? 'Cita Bloqueada' : 'Cita Desbloqueada';

            $citas = Cita::where('id', $cita->id)->update([
                'Estado_id' => $estado
            ]);

            return response()->json([
                'message' => $msg,
            ], 200);
        }
    }

    public function confirmar(Cita $cita, Request $request)
    {
        $cita_paciente = citapaciente::where('Cita_id', $cita->id)
            ->where('Paciente_id', $request->Paciente_id)
            ->whereIn('Estado_id', [18, 39])
            ->first();

        $cita_paciente->update([
            'Estado_id' => 38,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $cita_paciente->id,
            "Usuario_id"      => auth()->id(),
            "Motivo"          => "Cita enviada a enfermeria",
            "Estado_id"       => 38,
        ]);

        return response()->json([
            'message' => 'Se confirmó la cita con exito!',
        ], 200);
    }

    public function citaspendientesPaciente(Request $request)
    {
        $citaspendientes = Cita::select(['citas.id as id', 'citas.Hora_Inicio',
            'c.Nombre as Consultorio', 's.Nombre as Sede',
            'u.name as Nombre_medico', 'u.apellido as Apellido_medico',
            'e.Nombre as Especialidad', 'ta.name as Tipo_agenda', 'a.Fecha',
            'a.Fecha', 's.Direccion as Direccion_Sede'
            , 'r.name as User_asgina', 'r.apellido as ApUser_asgina',
        ])
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
            ->leftjoin('users as r', 'cp.Usuario_id', 'r.id')
            ->whereIn('cp.Estado_id', [4, 7, 18])
            ->where('p.id', $request->Paciente_id)
            ->get();
        return response()->json($citaspendientes, 200);
    }
    public function cronogramaHoyMedico(Request $request)
    {
       $user = User::find(auth()->id());
        $rol =  $user->hasRole('Psicologia(salud ocupacional)');
        if ($rol == "Psicologia(salud ocupacional)") {
            $citaspendientes = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'cp.salud_ocupacional', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede'])
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
            ->whereIn('citas.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->whereIn('cp.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->where('cp.salud_ocupacional', 'Psicologia');
            if(isset($request->fecha_inicio)){
                $citaspendientes->where('a.Fecha','>=',$request->fecha_inicio);
                $citaspendientes->where('a.Fecha','<=',date('Y-m-d'));
            }else{
                $citaspendientes->where('a.Fecha', date('Y-m-d'));
            }
            $citas = $citaspendientes->get();
            return response()->json($citas, 200);
        }
        $rol =  $user->hasRole('Voz(salud ocupacional)');
        if ($rol == "Voz(salud ocupacional)") {
            $citaspendientes = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'cp.salud_ocupacional', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede'])
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
            ->whereIn('citas.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->whereIn('cp.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->where('cp.salud_ocupacional', 'Voz');
            if(isset($request->fecha_inicio)){
                $citaspendientes->where('a.Fecha','>=',$request->fecha_inicio);
                $citaspendientes->where('a.Fecha','<=',date('Y-m-d'));
            }else{
                $citaspendientes->where('a.Fecha', date('Y-m-d'));
            }
            $citas = $citaspendientes->get();
            return response()->json($citas, 200);
        }
        $rol =  $user->hasRole('Visiometria(salud ocupacional)');
        if ($rol == "Visiometria(salud ocupacional)") {
            $citaspendientes = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'cp.salud_ocupacional', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede'])
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
            ->whereIn('citas.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->whereIn('cp.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->where('cp.salud_ocupacional', 'Visiometria');
            if(isset($request->fecha_inicio)){
                $citaspendientes->where('a.Fecha','>=',$request->fecha_inicio);
                $citaspendientes->where('a.Fecha','<=',date('Y-m-d'));
            }else{
                $citaspendientes->where('a.Fecha', date('Y-m-d'));
            }
            $citas = $citaspendientes->get();
            return response()->json($citas, 200);

        }
        $rol =  $user->hasRole('Consulta Medica(salud ocupacional)');
        if ($rol == "Consulta Medica(salud ocupacional)") {
            $citaspendientes = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'cp.salud_ocupacional', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede'])
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
            ->whereIn('citas.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->whereIn('cp.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->whereIn('cp.salud_ocupacional', ['Psicologia','Voz','Visiometria','consulta Medica']);
            if(isset($request->fecha_inicio)){
                $citaspendientes->where('a.Fecha','>=',$request->fecha_inicio);
                $citaspendientes->where('a.Fecha','<=',date('Y-m-d'));
            }else{
                $citaspendientes->where('a.Fecha', date('Y-m-d'));
            }
            $citas = $citaspendientes->get();
            return response()->json($citas, 200);
        }
        else {
            $citaspendientes = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Sexo',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'cp.salud_ocupacional', 'es.Nombre as Estado','cp.Tipocita_id as Tipocita_id'])
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
            ->with(['adjuntos_cita' => function ($query) {
                $query->select('adjuntos_generals.id', 'adjuntos_generals.cita_id', 'adjuntos_generals.ruta',
                'users.name', 'users.apellido', 'adjuntos_generals.created_at')
                ->join('users','adjuntos_generals.user_id','users.id')
                ->get();
            }])
            ->whereIn('citas.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->whereIn('cp.Estado_id', [3, 4, 5, 7, 8, 9, 12]);
            if(isset($request->documento)){
                $citaspendientes->where('p.Num_Doc', $request->documento);
            }else if(isset($request->tipocita)){
                $citaspendientes->where('ta.name', 'like', '%'.$request->tipocita.'%');
            }else{
                $citaspendientes->where('u.id', auth()->id());
            }
            if( isset($request->documento) && isset($request->tipocita) ){
                $citaspendientes->where('p.Num_Doc', $request->documento);
                $citaspendientes->where('ta.name', 'like', '%'.$request->tipocita.'%');
            }
            if(isset($request->fecha_inicio)){
                $citaspendientes->where('a.Fecha','>=',$request->fecha_inicio);
                $citaspendientes->where('a.Fecha','<=',date('Y-m-d'));
            }else{
                $citaspendientes->where('a.Fecha', date('Y-m-d'));
            }
            $citas = $citaspendientes->get();

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
    }

    public function admisionesimagenes(Request $request){

        $citaimg = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',
            'citas.Hora_Inicio', 'e.Nombre as Especialidad',
            'ta.name as Tipo_agenda', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado',
            's.Direccion as Direccion_Sede', 's.Nombre', 'u.name as nameEspecialista',
            'u.apellido as apellidoEspecialista', 'p.Ut', 'det.tecnica', 'det.prioridad',
            'det.lado', 'det.lectura','det.tipo_paciente', 'det.fecha_orden',
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
            ->where('det.Estado_id', 4)
            ->where('cp.Estado_id', 18)
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

        return response()->json($citaimg->get(), 200);

    }

    public function citasanterioresPaciente(Request $request)
    {
        $citasanteriores = Cita::select(['citas.id', 'citas.Hora_Inicio',
            'c.Nombre as Consultorio', 's.Nombre as Sede',
            'u.name as Nombre_medico', 'u.apellido as Apellido_medico',
            'e.Nombre as Especialidad', 'ta.name as Tipo_agenda',
            'a.Fecha', 's.Direccion as Direccion_Sede',
            'es.Nombre as Nom_estado', 'r.name as User_asgina', 'r.apellido as ApUser_asgina',
            'asi.name as User_asignaAtendido', 'asi.apellido as ApUser_asignaAtendido','dc.Motivo', 'dc.created_at as fechaCancelo',
            'cupUsadas.Codigo as codigo_cups','cupUsadas.Nombre as nombre_cups','ordenesUsadas.created_at as fecha_usada','ordensUsadas.id as ordens'])
            ->join('cita_paciente as cp', 'cp.Cita_id', 'citas.id')
            ->join('detallecitas as dc','dc.Citapaciente_id','cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('agendas as a', 'citas.Agenda_id', 'a.id')
            ->join('especialidade_tipoagenda as et', 'a.Especialidad_id', 'et.id')
            ->join('especialidades as e', 'et.Especialidad_id', 'e.id')
            ->join('tipo_agendas as ta', 'et.Tipoagenda_id', 'ta.id')
            ->join('consultorios as c', 'a.Consultorio_id', 'c.id')
            ->join('sedes as s', 'c.Sede_id', 's.id')
            ->join('agendausers as au', 'au.Agenda_id', 'a.id')
            ->join('users as u', 'au.Medico_id', 'u.id')
            ->leftjoin('users as r', 'dc.Usuario_id', 'r.id')
            ->leftjoin('users as asi', 'cp.Usuario_id', 'asi.id')
            ->leftjoin('estados as es', 'cp.Estado_id', 'es.id')
            ->leftjoin('cupsorden_citapacientes as ordenesUsadas', 'cp.id', 'ordenesUsadas.citapaciente_id')
            ->leftjoin('cupordens as cupordensUsadas', 'ordenesUsadas.cupordens_id', 'cupordensUsadas.id')
            ->leftjoin('ordens as ordensUsadas', 'cupordensUsadas.Orden_id', 'ordensUsadas.id')
            ->leftjoin('cups as cupUsadas', 'cupordensUsadas.Cup_id', 'cupUsadas.id')
            ->whereIn('cp.Estado_id', [9, 6, 12])
            ->where('p.id', $request->Paciente_id)
            ->whereIn('dc.Estado_id', [9,6,12])
            ->get();
        return response()->json($citasanteriores, 200);
    }

    public function datospaciente(citapaciente $citapaciente)
    {
        $datospaciente = citapaciente::select('Pacientes.*')
            ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
        // ->join('Pacienteantecedentes', 'cita_paciente.citapaciente_id', 'Pacienteantecedentes.citapaciente_id')
            ->where('Pacientes.id', $citapaciente->Paciente_id)
            ->first();
        return response()->json($datospaciente, 200);
    }

    public function editarpaciente(Request $request, Paciente $paciente, citapaciente $citapaciente)
    {
        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        $validate = Validator::make($request->all(), [
            'Edad_Cumplida'        => 'required',
            'Etnia'                => 'required',
            'Laboraen'             => 'required',
            'Mpio_Labora'          => 'required',
            'Direccion_Residencia' => 'required',
            'Mpio_Residencia'      => 'required',
            'Telefono'             => 'required',
            'Correo'               => 'required',
            'Estrato'              => 'required',
            'Celular'              => 'required',
            'Num_Hijos'            => 'required',
            'Vivecon'              => 'required',
            'RH'                   => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        // return $request->all();

        $paciente->update([
            'Edad_Cumplida'        => $request->Edad_Cumplida,
            'Discapacidad'         => $request->Discapacidad,
            'Grado_Discapacidad'   => $request->Grado_Discapacidad,
            'Etnia'                => $request->Etnia,
            'Laboraen'             => $request->Laboraen,
            'Mpio_Labora'          => $request->Mpio_Labora,
            'Direccion_Residencia' => $request->Direccion_Residencia,
            'Mpio_Residencia'      => $request->Mpio_Residencia,
            'Telefono'             => $request->Telefono,
            'Correo'               => $request->Correo,
            'Estrato'              => $request->Estrato,
            'Celular'              => $request->Celular,
            'Num_Hijos'            => $request->Num_Hijos,
            'Vivecon'              => $request->Vivecon,
            'RH'                   => $request->RH,
        ]);
        return response()->json(['message' => 'Datos del paciente actualizados con exito!'], 201);
    }
    public function updatepaciente(Request $request, Paciente $paciente)
    {

        $paciente->update($request->all());
        return response()->json(['message' => 'Datos del paciente actualizados con exito!'], 201);
    }

    public function atender(Request $request, citapaciente $citapaciente)
    {
        $cupOrden = CupsordenCitapaciente::where('citapaciente_id', $citapaciente->id)->first();
        if($cupOrden){
            Cuporden::where('id', $cupOrden->cupordens_id)
            ->update([
                'atendida' => 1
            ]);
        }

        if ($citapaciente->salud_ocupacional == "Psicologia") {
            $citapaciente->update([
                "Estado_id" => 8,
                "Tipocita_id" => 13,
                "user_medico_atiende" => auth()->id(),
                ]);
                $Nompaciente = citapaciente::select('Pacientes.Primer_Nom')
                ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
                ->first();
            // return $citapaciente;
            return response()->json(['message' => 'Inicia consulta con el paciente' . ' ' . $Nompaciente->Primer_Nom], 200);
        }elseif ($citapaciente->salud_ocupacional == "Voz") {
            $citapaciente->update([
                "Estado_id" => 8,
                "Tipocita_id" => 14,
                "user_medico_atiende" => auth()->id(),
                ]);
                $Nompaciente = citapaciente::select('Pacientes.Primer_Nom')
                ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
                ->first();
            // return $citapaciente;
            return response()->json(['message' => 'Inicia consulta con el paciente' . ' ' . $Nompaciente->Primer_Nom], 200);
        }elseif ($citapaciente->salud_ocupacional == "Visiometria") {
            $citapaciente->update([
                "Estado_id" => 8,
                "Tipocita_id" => 15,
                "user_medico_atiende" => auth()->id(),
                ]);
                $Nompaciente = citapaciente::select('Pacientes.Primer_Nom')
                ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
                ->first();
            // return $citapaciente;
            return response()->json(['message' => 'Inicia consulta con el paciente' . ' ' . $Nompaciente->Primer_Nom], 200);
        }elseif ($citapaciente->salud_ocupacional == "Consulta Medica") {
            $citapaciente->update([
                "Estado_id" => 8,
                "Tipocita_id" => 16,
                "user_medico_atiende" => auth()->id(),
                ]);
                $Nompaciente = citapaciente::select('Pacientes.Primer_Nom')
                ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
                ->first();
            // return $citapaciente;
            return response()->json(['message' => 'Inicia consulta con el paciente' . ' ' . $Nompaciente->Primer_Nom], 200);
        }
        else {
            $chequeados =  $this->checkEspecialidadLocal($citapaciente->id);
        $tipo = 8;
        switch ($chequeados->id) {
            case 19:
                $tipo = 12;
                break;
            case 31:
                $tipo = 7;
                break;
            case 58:
                $tipo = 6;
                break;
            case 49:
                $tipo = 10;
                break;
        }

        $citapaciente->update([
            "Estado_id" => 8,
            "Tipocita_id" => $tipo
            ]);
            $Nompaciente = citapaciente::select('Pacientes.Primer_Nom')
            ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
            ->first();
        // return $citapaciente;
        return response()->json(['message' => 'Inicia consulta con el paciente' . ' ' . $Nompaciente->Primer_Nom], 200);
        }

    }
    public function atenderNoprogramada(Request $request, citapaciente $citapaciente)
    {
        $this->generarRipsAc($citapaciente);

        $citapaciente->update([
            "Estado_id" => 8,
            "Tipocita_id" => 2
            ]);

        $Nompaciente = citapaciente::select('Pacientes.Primer_Nom')
            ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
            ->first();
        // return $citapaciente;
        return response()->json(['message' => 'Inicia consulta con el paciente' . ' ' . $Nompaciente->Primer_Nom], 200);
    }

    public function setTime(Request $request, citapaciente $citapaciente)
    {
        Imagenologia::where('Citapaciente_id', $citapaciente->id)
        ->update([
            'medico_imagenologia' => auth()->id(),
        ]);

        if ($request->type == 'Ingreso') {
            $date = date("Y-m-d H:i:s");
            $citapaciente->update(["Datetimeingreso" => $date]);
        } elseif ($request->type == 'Salida') {
            $date = date("Y-m-d H:i:s");
            $citapaciente->update(["Datetimesalida" => $date]);
        }
        return response()->json([
            'message' => 'Fecha actualizada de manera exitosa!',
        ], 200);
    }

    public function getTimeIngreso(Request $request, citapaciente $citapaciente)
    {
        $Datetimesalida = citapaciente::select('Datetimeingreso')
            ->where('id', $citapaciente->id)
            ->first();

        return response()->json($Datetimesalida, 200);
    }

    public function setTimeIngreso(Request $request, citapaciente $citapaciente)
    {
        $date = date("Y-m-d H:i:s");
        $citapaciente->update(["Datetimeingreso" => $date]);

        return response()->json([
            'message' => 'Fecha actualizada de manera exitosa!',
        ], 200);
    }

    public function motivo(Request $request, citapaciente $citapaciente)
    {
        // if (!$this->isOpenCita($citapaciente->Estado_id)) {
        //     return response()->json([
        //         'message' => '¡La historia del paciente no se encuentra activa!',
        //     ], 422);
        // }
        $citapaciente->update([
            'Motivoconsulta'         => $request->Motivoconsulta,
            'Enfermedadactual'       => $request->Enfermedadactual,
            'Resultayudadiagnostica' => $request->Resultayudadiagnostica,
            'tratamientoncologico'   => $request->tratamientoncologico,
            'ncirujias'              => $request->ncirujias,
            'iniciocirujia'          => $request->iniciocirujia,
            'fincirujia'             => $request->fincirujia,
            'inicioradioterapia'     => $request->inicioradioterapia,
            'finradioterapia'        => $request->finradioterapia,
            'nsesiones'              => $request->nsesiones,
            'intencion'              => $request->intencion,
            'cirujiaoncologica'      => $request->cirujia,
            'recibioradioterapia'    => $request->radioterapia,
            'Tipocita_id'            => $request->Tipocita_id,
            'ingestaAdecuada'            => $request->ingestaAdecuada,
            'Diuresis'            => $request->Diuresis,
            'deposicion'            => $request->deposicion,
        ]);

        return response()->json(['message' => '!MC y EA guardado con exito!'], 201);
    }

    public function revisionsistema(Request $request, citapaciente $citapaciente)
    {
        //  return $request->Oftalmologico;
        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        $idcitapaciente = citapaciente::select('id')
            ->where('id', $citapaciente->id)
            ->first();
        $idcitapaciente->update([
            'Oftalmologico'         => $request->Oftalmologico,
            'Genitourinario'        => $request->Genitourinario,
            'Otorrinoralingologico' => $request->Otorrinoralingologico,
            'Linfatico'             => $request->Linfatico,
            'Osteomioarticular'     => $request->Osteomioarticular,
            'Neurologico'           => $request->Neurologico,
            'Cardiovascular'        => $request->Cardiovascular,
            'Tegumentario'          => $request->Tegumentario,
            'Respiratorio'          => $request->Respiratorio,
            'Endocrinologico'       => $request->Endocrinologico,
            'Gastrointestinal'      => $request->Gastrointestinal,
            'Norefiere'             => $request->Norefiere,
            'Tipocita_id'           => $request->Tipocita_id,
            'cirujiaoncologica'     => $request->cirujia,
            'recibioradioterapia'   => $request->radioterapia,
            'checkboxOftalmologico' => $request->checkboxOftalmologico,
            'checkboxGenitourinario' => $request->checkboxGenitourinario,
            'checkboxOtorrinoralingologico' => $request->checkboxOtorrinoralingologico,
            'checkboxLinfatico' => $request->checkboxLinfatico,
            'checkboxOsteomioarticular' => $request->checkboxOsteomioarticular,
            'checkboxNeurologico' => $request->checkboxNeurologico,
            'checkboxCardiovascular' => $request->checkboxCardiovascular,
            'checkboxTegumentario' => $request->checkboxTegumentario,
            'checkboxRespiratorio' => $request->checkboxRespiratorio,
            'checkboxEndocrinologico' => $request->checkboxEndocrinologico,
            'checkboxGastrointestinal' => $request->checkboxGastrointestinal,
            'checkboxNorefiere' => $request->checkboxNorefiere,
        ]);
        return response()->json([
            'message' => 'Anamnesis Almacenada con Exito!',
        ], 201);
    }

    public function anamnesis(citapaciente $citapaciente)
    {
        $citapaciente = citapaciente::select(
            'tratamientoncologico',
            'cirujiaoncologica',
            'ncirujias',
            'iniciocirujia',
            'fincirujia',
            'recibioradioterapia',
            'inicioradioterapia',
            'finradioterapia',
            'nsesiones',
            'intencion',
            'Motivoconsulta',
            'Enfermedadactual',
            'Resultayudadiagnostica',
            'Oftalmologico',
            'Genitourinario',
            'Otorrinoralingologico',
            'Linfatico',
            'Osteomioarticular',
            'Neurologico',
            'Cardiovascular',
            'Tegumentario',
            'Respiratorio',
            'Endocrinologico',
            'Gastrointestinal',
            'Norefiere',
            'Tipocita_id',
            'ingestaAdecuada',
            'Diuresis',
            'deposicion',
            'checkboxOftalmologico',
            'checkboxGenitourinario',
            'checkboxOtorrinoralingologico',
            'checkboxLinfatico',
            'checkboxOsteomioarticular',
            'checkboxNeurologico',
            'checkboxCardiovascular',
            'checkboxTegumentario',
            'checkboxRespiratorio',
            'checkboxEndocrinologico',
            'checkboxGastrointestinal',
            'checkboxNorefiere',
            'p.id as paciente_id',
            'p.Sexo',
            'p.Edad_Cumplida',
        )
            ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->where('cita_paciente.id', $citapaciente->id)
            ->first();
        return response()->json($citapaciente, 200);
    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }

    public function update_state_consulta(citapaciente $citapaciente)
    {

        $existe = AcRips::where('citapaciente_id', $citapaciente->id)->first();
        if(!$existe){
            $this->generarRipsAc($citapaciente);
        }

        if ($citapaciente->salud_ocupacional == "Psicologia") {
            $citapaciente->update([
                'Estado_id' => 8,
                'user_medico_atiende' => auth()->id(),
            ]);
            return response()->json([
                'message' => 'Estado actualizado de manera exitosa!',
                'tipo'    => $citapaciente->Tipocita_id
            ], 200);
        }elseif ($citapaciente->salud_ocupacional == "Voz") {
            $citapaciente->update([
                'Estado_id' => 8,
                'user_medico_atiende' => auth()->id(),
            ]);
            return response()->json([
                'message' => 'Estado actualizado de manera exitosa!',
                'tipo'    => $citapaciente->Tipocita_id
            ], 200);
        }elseif ($citapaciente->salud_ocupacional == "Visiometria") {
            $citapaciente->update([
                'Estado_id' => 8,
                'user_medico_atiende' => auth()->id(),
            ]);
            return response()->json([
                'message' => 'Estado actualizado de manera exitosa!',
                'tipo'    => $citapaciente->Tipocita_id
            ], 200);
        }elseif ($citapaciente->salud_ocupacional == "Consulta Medica") {
            $citapaciente->update([
                'Estado_id' => 8,
                'user_medico_atiende' => auth()->id(),
            ]);
            return response()->json([
                'message' => 'Estado actualizado de manera exitosa!',
                'tipo'    => $citapaciente->Tipocita_id
            ], 200);
        }else {
            $citapaciente->update([
                'Estado_id' => 8,
                'user_medico_atiende' => auth()->id(),
            ]);

            Detallecita::create([
                "Citapaciente_id" => $citapaciente->id,
                "Usuario_id"      => auth()->id(),
                "Estado_id"       => 8,
            ]);
        }

        return response()->json([
            'message' => 'Estado actualizado de manera exitosa!',
            'tipo'    => $citapaciente->Tipocita_id
        ], 200);
    }
    public function inasistio(Cita $cita, Request $request)
    {
        $cita->update(["Estado_id" => 12]);

        $cita_paciente = citapaciente::where('Cita_id', $cita->id)
            ->where('id', $request->cita_paciente_id)
            ->first();

        $cita_paciente->update([
            'Estado_id' => 12,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $cita_paciente->id,
            "Usuario_id"      => auth()->id(),
            "Motivo"          => (isset($request->motivo)?$request->motivo:"Paciente no asiste a cita programada!"),
            "Estado_id"       => 12,
        ]);
        return response()->json([
            'message' => 'Cita cancelada por inasistencia del paciente!',
        ], 200);
    }
    public function update_state_atendida(citapaciente $citapaciente)
    {

        $this->generarRipsAc($citapaciente);

        $finalidad = Conducta::select('conductas.*','cita_paciente.Paciente_id','cie10pacientes.Cie10_id')
            ->join('cita_paciente','cita_paciente.id','conductas.Citapaciente_id')
            ->leftjoin('cie10pacientes','cie10pacientes.Citapaciente_id','cita_paciente.id')
        ->where('conductas.Citapaciente_id', $citapaciente->id)
        ->First();
        if($finalidad){
            if($finalidad->Destinopaciente == 'Hospitalización (Remision)'){
                $referencia=Referencia::create([
                    'id_paci'           => $finalidad->Paciente_id,
                    'id_prestador'      => auth()->user()->id,
                    'tipo_anexo'        => 9,
                    'Especialidad_remi' => $finalidad->Especialidad,
                    'adjunto'           => 'Sin archivo',
                    'state'             => 0,]);

                $historia=Referencia::select('id')
                    ->where('id_paci',$finalidad->Paciente_id)
                    ->orderby ('id','desc')->first();

                $citapaciente->update(['referencia_id'=>$historia->id]);

                $cie10s = Cie10::find($finalidad->Cie10_id);
                $referencia->cie10s()->attach($cie10s);
            }
        }
        $flag = 0;
        $citapaciente->update([
            'Estado_id' => 9,
        ]);

        Detallecita::create([
            "Citapaciente_id" => $citapaciente->id,
            "Usuario_id"      => auth()->id(),
            "Estado_id"       => 9,
        ]);

        if (isset($citapaciente->Cita_id)) {

            $cita = Cita::select('citas.*')
                ->where('citas.id', $citapaciente->Cita_id)
                ->first();

            $citapacientes = citapaciente::select('cita_paciente.*')
                ->where('cita_paciente.Cita_id', $citapaciente->Cita_id)
                ->whereIn('cita_paciente.Estado_id', [4, 8, 9])
                ->get();

            if (count($citapacientes) == 1) {
                $cita->update([
                    'Estado_id' => 9,
                ]);
            } elseif (count($citapacientes) > 1) {
                foreach ($citapacientes as $valor) {
                    if ($valor['Estado_id'] != 9) {
                        $flag = 1;
                    };
                };

                if ($flag == 0) {
                    $cita->update([
                        'Estado_id' => 9,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Estado actualizado de manera exitosa!',
        ], 200);
    }
    public function create_cita_paciente(Request $request, Paciente $paciente)
    {
        $medico       = (!empty($request->medicoordeno)) ? $request->medicoordeno : auth()->user()->name . ' ' . auth()->user()->apellido;
        $entidademite = (!empty($request->entidademite)) ? $request->entidademite : auth()->user()->name . ' ' . auth()->user()->apellido;
        if ($request->Tipocita_id == 1) {
            $cita_paciente = citapaciente::create([
                "Motivoconsulta"  => 'Transcipcion',
                "Medicoordeno"  => $medico,
                "Entidademite"  => $entidademite,
                "Finalidad"     => $request->Finalidad,
                "Cup_id"        => $request->Cup_id,
                "Observaciones" => $request->observaciones,
                "Paciente_id"   => $paciente->id,
                "Usuario_id"    => auth()->id(),
                "Tipocita_id"   => $request->Tipocita_id,
                "Ambito"        => $request->ambito,
                'lugar_atencion'=> $request->lugar_atencion,
                'referencia_id' => $request->referencia_id,
            ]);
        }

        if (!empty($request->Cup_id)) {
            $cup = Cup::find($request->Cup_id);
            $cita_paciente->cups()->save($cup);
        }

        if (isset($cita_paciente)) {
            Detallecita::create([
                "Citapaciente_id" => $cita_paciente->id,
                "Usuario_id"      => auth()->id(),
                "Motivo"          => $request->Finalidad,
                "Estado_id"       => 6,
            ]);
        }
        if ($request->Tipocita_id == 6) {
           Imagenologia::create([
                "Citapaciente_id" => $cita_paciente->id,
                "Hallazgos" => $request->imagenologia["Hallazgos"],
                "Conclusion" => $request->imagenologia["Conclusion"],
                "Indicacion" => $request->imagenologia["Indicacion"],
                "Notaclaratoria" => $request->imagenologia["Notaclaratoria"],
                "Tecnica" => $request->imagenologia["Tecnica"],
                "medico_imagenologia" => $request->imagenologia["medico_imagenologia"],
                "Cie10_id" => $request->Cie10_id
           ]);
        }

        return response()->json($cita_paciente, 200);
    }
    public function notProgramed()
    {
        $date = date('Y-m-d');

        $notProgramed = citapaciente::select(['p.id as paciente_id', 'cita_paciente.id as cita_paciente_id', 'cita_paciente.created_at as Fecha', 'cita_paciente.Estado_id as CP_Estado_id', 'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado'])
            ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('estados as es', 'cita_paciente.Estado_id', 'es.id')
            ->join('users as u', 'cita_paciente.Usuario_id', 'u.id')
            ->whereIn('cita_paciente.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->where('cita_paciente.created_at', '>=', $date)
            ->where('cita_paciente.Tipocita_id', 2)
            ->where('u.id', auth()->id())
            ->get();


        $rolesUser = auth()->user()->getRoleNames()->toArray();

        $activities = Especialidade::select(['tp.id', 'tp.name'])
            ->join('especialidade_tipoagenda as et', 'especialidades.id', 'et.Especialidad_id')
            ->join('tipo_agendas as tp', 'et.Tipoagenda_id', 'tp.id')
            ->whereIn('especialidades.Nombre', $rolesUser)
            ->get();

        return response()->json([
            'notProgramed' => $notProgramed,
            'activities'   => $activities,
        ], 200);
    }
    public function checkEspecialidad(Request $request)
    {
        $especialidad = Especialidade::join('especialidade_tipoagenda as et', 'et.Especialidad_id', 'especialidades.id')
            ->join('agendas as a', 'a.Especialidad_id', 'et.id')
            ->join('citas as c', 'c.Agenda_id', 'a.id')
            ->join('cita_paciente as cp', 'cp.Cita_id', 'c.id')
            ->leftjoin('saludocupacionals as s', 's.cita_paciente_id', 'cp.id')
            ->where('cp.id', $request->cita_paciente)
            ->first();

        return response()->json($especialidad, 200);
    }
    public function checkEspecialidadLocal($citaPaciente)
    {
        $especialidad = Especialidade::select([
            "especialidades.id"
        ])
            ->join('especialidade_tipoagenda as et', 'et.Especialidad_id', 'especialidades.id')
            ->join('agendas as a', 'a.Especialidad_id', 'et.id')
            ->join('citas as c', 'c.Agenda_id', 'a.id')
            ->join('cita_paciente as cp', 'cp.Cita_id', 'c.id')
            ->where('cp.id', $citaPaciente)
            ->first();

        return $especialidad;
    }
   public function exportAppointmentsByDateRange(Request $request)
   {
       $procedure_uno = DB::select("exec dbo.ExportCitas ?,?", [$request->startDate,$request->finishDate." 23:59:59.999"]);
       $appointments_uno = json_decode(json_encode($procedure_uno), true);

    //    $procedure_dos = DB::select("exec dbo.ExportCitasAtencion ?,?", [$request->startDate,$request->finishDate." 23:59:59.999"]);
    //    $appointments_dos = json_decode(json_encode($procedure_dos), true);

    //    $appointments = array_merge($appointments_uno,$appointments_dos);

       return (new FastExcel($appointments_uno))->download('citas.xls');
   }
    public function cupspaciente(Paciente $paciente){

        $cups = Cita::getRepository()->cupspaciente($paciente);
        return response()->json($cups, 200);
    }
    public function observacionImagenologia(Request $request)
    {
        $observacion = new Examenfisico;
        $observacion->Citapaciente_id = $request->cita_paciente_id;
        $observacion->user_id = auth()->id();
        $observacion->tipo = $request->tipo;
        $observacion->nota = $request->nota;
        $observacion->save();
    }
    public function adjuntoAdmision(Request $request)
    {
        if ($request->hasFile('adjuntos') == false){
            return response()->json(['message' => 'El adjunto es muy pesado, reduzca el peso del archivo!'], 401);
        }

        $cedula = $request->documento;
        $file_name = [];
        $paths = [];

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntosimagenologia/'.$cedula;
                $paths[$i] = '/storage/adjuntosimagenologia/'.$cedula.'/'.time().$file_name[$i];
                $file->move($path, time().$file_name[$i]);
                $i++;
            }
        }

        for ($i=0; $i < count($paths); $i++) {
            $adjunto = new Adjuntos_general;
            $adjunto->tipo_id = 11;
            $adjunto->nombre = $file_name[$i];
            $adjunto->ruta = $paths[$i];
            $adjunto->cita_id = $request->cita_id;
            $adjunto->user_id = auth()->id();
            $adjunto->save();
        }

        return response()->json([
            'message' => 'Adjunto guardado con exito!',
        ], 201);
    }
    public function validacionHistoria(citapaciente $citaPaciente){
        $result = true;
        $mensaje = '';
        if ($citaPaciente->Tipocita_id == 8 | $citaPaciente->Tipocita_id == 12 | $citaPaciente->Tipocita_id == 2 | $citaPaciente->Tipocita_id == 13
        | $citaPaciente->Tipocita_id == 14 | $citaPaciente->Tipocita_id == 15 | $citaPaciente->Tipocita_id == 16) {
            if(!$citaPaciente->Finalidad){
                $result = false;
                $mensaje = 'La finalidad es requerida';
            }

            $dxPrincipal = Cie10paciente::select(['cie10s.Codigo_CIE10', 'cie10pacientes.Esprimario',
            'cie10pacientes.Tipodiagnostico'])
            ->join('cie10s', 'cie10pacientes.Cie10_id', 'cie10s.id')
            ->where('cie10pacientes.Citapaciente_id', $citaPaciente->id)
            ->whereIn('cie10pacientes.Esprimario', [true, false])
            ->first();

            if(!$dxPrincipal){
                $result = false;
                $mensaje = 'El diagnostico principal es requerido';
            }

            if(!$citaPaciente->Motivoconsulta){
                $result = false;
                $mensaje = 'El motivo de consulta es requerido';
            }

            $ordens = Orden::where('Cita_id',$citaPaciente->id)->get();
            if($ordens){
               foreach ($ordens as $orden){
                  switch ($orden->Tiporden_id){
                      case 3:
                          $detalle = Detaarticulorden::where('Orden_id',$orden->id)->first();
                          if(!$detalle){
                              $result = false;
                              $mensaje = 'La historia tiene ordenes asociadas sin detalle';
                          }
                          break;
                      case 2:
                      case 4:
                          $detalle = Cuporden::where('Orden_id',$orden->id)->first();
                          if(!$detalle){
                              $result = false;
                              $mensaje = 'La Historia tiene ordenes asociadas sin detalle';
                          }
                          break;
                  }
               }
            }
        }
        $data = [
            'resultado' => $result,
            'mensaje' => $mensaje
        ];
        return response()->json($data, 200);
    }
    public function saveOftamologia(Request $request, citapaciente $citaPaciente){

        $citaPaciente->avcc_ojoderecho = $request->avcc_ojoderecho;
        $citaPaciente->avcc_ojoizquierdo = $request->avcc_ojoizquierdo;
        $citaPaciente->avsc_ojoderecho = $request->avsc_ojoderecho;
        $citaPaciente->avsc_ojoizquierdo = $request->avsc_ojoizquierdo;
        $citaPaciente->ph_ojoderecho = $request->ph_ojoderecho;
        $citaPaciente->ph_ojoizquierdo = $request->ph_ojoizquierdo;
        $citaPaciente->motilidad_ojoderecho = $request->motilidad_ojoderecho;
        $citaPaciente->covert_ojoderecho = $request->covert_ojoderecho;
        $citaPaciente->motilidad_ojoizquierdo = $request->motilidad_ojoizquierdo;
        $citaPaciente->covert_ojoizquierdo = $request->covert_ojoizquierdo;
        $citaPaciente->biomicros_ojoderecho = $request->biomicros_ojoderecho;
        $citaPaciente->biomicros_ojoizquierdo = $request->biomicros_ojoizquierdo;
        $citaPaciente->presionintra_ojoderecho = $request->presionintra_ojoderecho;
        $citaPaciente->presionintra_ojoizquierdo = $request->presionintra_ojoizquierdo;
        $citaPaciente->gionios_ojoderecho = $request->gionios_ojoderecho;
        $citaPaciente->gionios_ojoizquierdo = $request->gionios_ojoizquierdo;
        $citaPaciente->fondo_ojoderecho = $request->fondo_ojoderecho;
        $citaPaciente->fondo_ojoizquierdo = $request->fondo_ojoizquierdo;
        $citaPaciente->save();

        return response()->json([
            'message' => 'Oftamologia guardado con exito!',
        ], 201);

    }
    public function getoftamologia(citapaciente $citaPaciente){

        $citapaciente = citapaciente::select('avcc_ojoderecho', 'avcc_ojoizquierdo', 'avsc_ojoderecho',
        'avsc_ojoizquierdo', 'ph_ojoderecho', 'ph_ojoizquierdo','motilidad_ojoderecho', 'covert_ojoderecho',
        'motilidad_ojoizquierdo', 'covert_ojoizquierdo', 'biomicros_ojoderecho','biomicros_ojoizquierdo',
        'presionintra_ojoderecho', 'presionintra_ojoizquierdo', 'gionios_ojoderecho','gionios_ojoizquierdo',
        'fondo_ojoderecho', 'fondo_ojoizquierdo')
        ->where('id', $citaPaciente->id)
        ->first();

        return response()->json($citapaciente, 200);
    }
    public function saveSaludocupacional(Request $request, citapaciente $citaPaciente)
    {
        $saludocupacional = Saludocupacional::where('cita_paciente_id', $citaPaciente->id)->first();
        if ($saludocupacional) {
            $saludocupacional->update($request->all());
            return response()->json([
                'message' => 'actualizado con exito!',
            ]);
        }
        $salud = new Saludocupacional;
        $salud->cita_paciente_id = $citaPaciente->id;
        $salud->proceso_cognitivo = $request->proceso_cognitivo;
        $salud->memoria = $request->memoria;
        $salud->atencion = $request->atencion;
        $salud->lenguaje = $request->lenguaje;
        $salud->ubicacion = $request->ubicacion;
        $salud->estado_mental = $request->estado_mental;
        $salud->presentacion_personal = $request->presentacion_personal;
        $salud->pensamiento_logico = $request->pensamiento_logico;
        $salud->introspeccion = $request->introspeccion;
        $salud->prospeccion = $request->prospeccion;
        $salud->prospeccion = $request->prospeccion;
        $salud->social = $request->social;
        $salud->familiar = $request->familiar;
        $salud->laboral = $request->laboral;
        $salud->analisis_diagnostico = $request->analisis_diagnostico;
        $salud->recoleccion_datos = $request->recoleccion_datos;

        $salud->nombre_de_la_empresa = $request->nombre_de_la_empresa;
        $salud->cargo = $request->cargo;
        $salud->antiguedad = $request->antiguedad;

        $salud->area_familiar = $request->area_familiar;
        $salud->area_mental = $request->area_mental;
        $salud->antecedentes_mentales = $request->antecedentes_mentales;
        $salud->respiracion_modo = $request->respiracion_modo;
        $salud->respiracion_tipo = $request->respiracion_tipo;
        $salud->respiracion_fonorespiratoria = $request->respiracion_fonorespiratoria;
        $salud->perimetros_biaxilar = $request->perimetros_biaxilar;
        $salud->perimetros_xifoideo = $request->perimetros_xifoideo;
        $salud->perimetros_abdominal = $request->perimetros_abdominal;
        $salud->respiracion_prueba_glatzer = $request->respiracion_prueba_glatzer;
        $salud->tiempos_respiracion_inspiracion = $request->tiempos_respiracion_inspiracion;
        $salud->tiempos_respiracion_espiracion = $request->tiempos_respiracion_espiracion;
        $salud->timbre = $request->timbre;
        $salud->intensidad = $request->intensidad;
        $salud->tono = $request->tono;
        $salud->cierre_glotico = $request->cierre_glotico;
        $salud->lugar_cabeza = $request->lugar_cabeza;
        $salud->lugar_frente = $request->lugar_frente;
        $salud->lugar_nasales = $request->lugar_nasales;
        $salud->lugar_mejillas = $request->lugar_mejillas;
        $salud->lugar_cuello = $request->lugar_cuello;
        $salud->voz_observaciones = $request->voz_observaciones;
        $salud->musculatura_laringea_normal = $request->musculatura_laringea_normal;
        $salud->musculatura_laringea_irritada = $request->musculatura_laringea_irritada;
        $salud->musculatura_laringea_inflamada = $request->musculatura_laringea_inflamada;
        $salud->musculatura_laringea_placas = $request->musculatura_laringea_placas;
        $salud->musculatura_extralaringea_dolor_palpar = $request->musculatura_extralaringea_dolor_palpar;
        $salud->musculatura_extralaringea_dolor_deglutir = $request->musculatura_extralaringea_dolor_deglutir;
        $salud->musculatura_extralaringea_tono_normal = $request->musculatura_extralaringea_tono_normal;
        $salud->musculatura_extralaringea_tono_aumentado = $request->musculatura_extralaringea_tono_aumentado;
        $salud->remision_especialista = $request->remision_especialista;
        $salud->aptitud_laboral_psicologia = $request->aptitud_laboral_psicologia;
        $salud->aptitud_laboral_voz = $request->aptitud_laboral_voz;
        $salud->aptitud_laboral_visiomretria = $request->aptitud_laboral_visiomretria;
        $salud->aptitud_laboral_medico = $request->aptitud_laboral_medico;
        $salud->riesgo_ocupacionales = $request->riesgo_ocupacionales;
        $salud->origen_enfermedades = $request->origen_enfermedades;
        $salud->antecedente_accidente_trabajo = $request->antecedente_accidente_trabajo;
        $salud->antecedente_enfermedad_laboral = $request->antecedente_enfermedad_laboral;
        $salud->patologia_osteomuscular = $request->patologia_osteomuscular;
        $salud->patologia_cardiovascular = $request->patologia_cardiovascular;
        $salud->patologia_metabolica = $request->patologia_metabolica;
        $salud->diagnosticos_infecciosos_parasitaria = $request->diagnosticos_infecciosos_parasitaria;
        $salud->diagnosticos_neoplasicos = $request->diagnosticos_neoplasicos;
        $salud->patologia_sangre_hematopoyeticos = $request->patologia_sangre_hematopoyeticos;
        $salud->trastronos_mentales_comportamiento = $request->trastronos_mentales_comportamiento;
        $salud->patologia_sistema_nervioso = $request->patologia_sistema_nervioso;
        $salud->patologia_ojo_anexos = $request->patologia_ojo_anexos;
        $salud->patologia_oido_apofisis_mastoides = $request->patologia_oido_apofisis_mastoides;
        $salud->patologia_sistema_respiratorio = $request->patologia_sistema_respiratorio;
        $salud->patologia_aparato_digestivo = $request->patologia_aparato_digestivo;
        $salud->patologia_piel_tejido_subcutaneo = $request->patologia_piel_tejido_subcutaneo;
        $salud->patologia_aparato_urinario = $request->patologia_aparato_urinario;
        $salud->malformacion_congenitas = $request->malformacion_congenitas;
        // visiometria
        $salud->avcc_ojoderecho = $request->avcc_ojoderecho;
        $salud->avcc_ojoizquierdo = $request->avcc_ojoizquierdo;
        $salud->avsc_ojoderecho = $request->avsc_ojoderecho;
        $salud->avsc_ojoizquierdo = $request->avsc_ojoizquierdo;
        $salud->avscav_ojoizquierdo = $request->avscav_ojoizquierdo;
        $salud->avscav_ojoderecho = $request->avscav_ojoderecho;
        $salud->avcerca_ojoderecho = $request->avcerca_ojoderecho;
        $salud->avcerca_ojoizquierdo = $request->avcerca_ojoizquierdo;
        $salud->motilidad_ocular = $request->motilidad_ocular;
        $salud->estereopsis = $request->estereopsis;
        $salud->vision_color = $request->vision_color;
        $salud->retinoscopia_ojoderecho = $request->retinoscopia_ojoderecho;
        $salud->retinoscopia_ojoizquierdo = $request->retinoscopia_ojoizquierdo;
        $salud->gionios_ojoderecho = $request->gionios_ojoderecho;
        $salud->gionios_ojoizquierdo = $request->gionios_ojoizquierdo;
        $salud->fondo_ojoderecho = $request->fondo_ojoderecho;
        $salud->fondo_ojoizquierdo = $request->fondo_ojoizquierdo;

        $salud->vacunas = $request->vacunas;
        $salud->hepatitis = $request->hepatitis;
        $salud->dosis_v = $request->dosis_v;
        $salud->ultima_dosis = $request->ultima_dosis;
        $salud->t_t = $request->t_t;
        $salud->t_d = $request->t_d;
        $salud->dosi = $request->dosi;
        $salud->otras = $request->otras;
        $salud->Fechas = $request->Fechas;
        $salud->ginecoobstetricos = $request->ginecoobstetricos;
        $salud->menarquia = $request->menarquia;
        $salud->ciclos = $request->ciclos;
        $salud->fum = $request->fum;
        $salud->g = $request->g;
        $salud->p = $request->p;
        $salud->c = $request->c;
        $salud->a = $request->a;
        $salud->v = $request->v;
        $salud->fup = $request->fup;
        $salud->planificacion = $request->planificacion;
        $salud->metodo = $request->metodo;
        $salud->ultima_citologia = $request->ultima_citologia;
        $salud->resultado = $request->resultado;
        $salud->tratada = $request->tratada;
        $salud->fuma = $request->fuma;
        $salud->fumo = $request->fumo;
        $salud->anos_fumador = $request->anos_fumador;
        $salud->cigarrillos_al_dia = $request->cigarrillos_al_dia;
        $salud->hace_cuanto_no_fuma = $request->hace_cuanto_no_fuma;
        $salud->alcohol = $request->alcohol;
        $salud->frecuencia = $request->frecuencia;
        $salud->tipo = $request->tipo;
        $salud->sustancias_psico_activas = $request->sustancias_psico_activas;
        $salud->cuales = $request->cuales;
        $salud->practica_deporte = $request->practica_deporte;
        $salud->cual = $request->cual;
        $salud->regularidad = $request->regularidad;
        $salud->cabeza_y_orl = $request->cabeza_y_orl;
        $salud->sistema_neurologico = $request->sistema_neurologico;
        $salud->sistema_cardiopulmonar = $request->sistema_cardiopulmonar;
        $salud->sistema_digestivo = $request->sistema_digestivo;
        $salud->sistema_musculo_esqueletico = $request->sistema_musculo_esqueletico;
        $salud->sistema_genitourinario = $request->sistema_genitourinario;
        $salud->piel_y_faneras = $request->piel_y_faneras;
        $salud->otros = $request->otros;
        $salud->tipoExamen = $request->tipoExamen;
        $salud->factoresRiesgo = $request->factoresRiesgo;
        $salud->antecedentesenfermedad = $request->antecedentesenfermedad;
        $salud->origenEnfermedades = $request->origenEnfermedades;
        $salud->antecedentedetrabajo = $request->antecedentedetrabajo;
        $salud->antecedenteenfermedadlaboral = $request->antecedenteenfermedadlaboral;
        $salud->enfermedadComun = $request->enfermedadComun;
        $salud->antecedentesfamiliares = $request->antecedentesfamiliares;
        $salud->patologiaAntecedente = $request->patologiaAntecedente;
        $salud->masaCorporal = $request->masaCorporal;
        $salud->patologiaOsteomuscular = $request->patologiaOsteomuscular;
        $salud->patologiaCardiovascular = $request->patologiaCardiovascular;
        $salud->patologiaMetabolica = $request->patologiaMetabolica;
        $salud->infecciososParasitaria = $request->infecciososParasitaria;
        $salud->patologiaSangre = $request->patologiaSangre;
        $salud->trastronosMentales = $request->trastronosMentales;
        $salud->patologiaNervioso = $request->patologiaNervioso;
        $salud->patologiaOjo = $request->patologiaOjo;
        $salud->patologiaOido = $request->patologiaOido;
        $salud->patologiaRespiratorio = $request->patologiaRespiratorio;
        $salud->patologiaDigestivo = $request->patologiaDigestivo;
        $salud->patologiaPiel = $request->patologiaPiel;
        $salud->patologiaUrinario = $request->patologiaUrinario;
        $salud->malformacionCongenitas = $request->malformacionCongenitas;
        $salud->condiciones_generales = $request->condiciones_generales;
        $salud->dominancia_motora = $request->dominancia_motora;
        $salud->talla = $request->talla;
        $salud->peso = $request->peso;
        $salud->kg_p_a = $request->kg_p_a;
        $salud->f_c = $request->f_c;
        $salud->f_r = $request->f_r;
        $salud->imc = $request->imc;
        $salud->perimetroabdominal = $request->perimetroabdominal;
        $salud->presionsistolica = $request->presionsistolica;
        $salud->presiondiastolica = $request->presiondiastolica;
        $salud->presionarterialmedia = $request->presionarterialmedia;

        $salud->t = $request->t;
        $salud->c_ = $request->c_;
        $salud->sato2 = $request->sato2;
        $salud->cabeza = $request->cabeza;
        $salud->ojos = $request->ojos;
        $salud->fondo_de_ojos = $request->fondo_de_ojos;
        $salud->oidos = $request->oidos;
        $salud->otoscopia = $request->otoscopia;
        $salud->nariz = $request->nariz;
        $salud->boca = $request->boca;
        $salud->dentadura = $request->dentadura;
        $salud->faringe = $request->faringe;
        $salud->cuello = $request->cuello;
        $salud->mamas = $request->mamas;
        $salud->torax = $request->torax;
        $salud->corazon = $request->corazon;
        $salud->pulmones = $request->pulmones;
        $salud->columna = $request->columna;
        $salud->abdomen = $request->abdomen;
        $salud->genitales_externos = $request->genitales_externos;
        $salud->miembros_sup = $request->miembros_sup;
        $salud->miembros_inf = $request->miembros_inf;
        $salud->reflejos = $request->reflejos;
        $salud->motilidad = $request->motilidad;
        $salud->fuerza_muscular = $request->fuerza_muscular;
        $salud->marcha = $request->marcha;
        $salud->piel_faneras = $request->piel_faneras;
        $salud->ampliacion_hallazgos = $request->ampliacion_hallazgos;
        $salud->vigilancia_epidemiologico = $request->vigilancia_epidemiologico;
        $salud->firma_medico_examinador = $request->firma_medico_examinador;
        $salud->firma_trabajador = $request->firma_trabajador;
        $salud->save();
        return response()->json([
            'message' => 'Guardada con exito!',
        ], 201);
    }
    public function getSaludocupacional(citapaciente $citaPaciente){
        $saludocupacional = Saludocupacional::where('cita_paciente_id', $citaPaciente->id)
        ->first();

        return response()->json($saludocupacional, 200);
    }
    public function saveAntecedentesOpucacionales(Request $request, citapaciente $citaPaciente)
    {
        $antecedenteocupacional = new Antecedenteocupacional;
        $antecedenteocupacional->cita_paciente_id = $citaPaciente->id;
        $antecedenteocupacional->antecedente_empresa = $request->antecedente_empresa;
        $antecedenteocupacional->antecedente_cargo = $request->antecedente_cargo;
        $antecedenteocupacional->f = $request->f;
        $antecedenteocupacional->q = $request->q;
        $antecedenteocupacional->b = $request->b;
        $antecedenteocupacional->erg = $request->erg;
        $antecedenteocupacional->psic = $request->psic;
        $antecedenteocupacional->mec = $request->mec;
        $antecedenteocupacional->elec = $request->elec;
        $antecedenteocupacional->otro = $request->otro;
        $antecedenteocupacional->tiempo = $request->tiempo;
        $antecedenteocupacional->uso_e_p_p = $request->uso_e_p_p;
        $antecedenteocupacional->accidentes_trabajo = $request->accidentes_trabajo;
        $antecedenteocupacional->fecha_accidentes = $request->fecha_accidentes;
        $antecedenteocupacional->empresa_accidentes = $request->empresa_accidentes;
        $antecedenteocupacional->causa = $request->causa;
        $antecedenteocupacional->tipo_lesion = $request->tipo_lesion;
        $antecedenteocupacional->parte_afectada = $request->parte_afectada;
        $antecedenteocupacional->dias_incap = $request->dias_incap;
        $antecedenteocupacional->secuelas = $request->secuelas;
        $antecedenteocupacional->enfermedades_profesionales = $request->enfermedades_profesionales;
        $antecedenteocupacional->fecha = $request->fecha;
        $antecedenteocupacional->empresa = $request->empresa;
        $antecedenteocupacional->diagnostico = $request->diagnostico;
        $antecedenteocupacional->reubicacion = $request->reubicacion;
        $antecedenteocupacional->indemnizacion = $request->indemnizacion;
        $antecedenteocupacional->save();
        return response()->json([
            'message' => 'Guardado con exito!',
        ], 201);
    }
    public function getAntecedentesOpucacionales(Request $request, citapaciente $citaPaciente)
    {
        $antecedenteocupacional = Antecedenteocupacional::select(
            'antecedente_empresa','antecedente_cargo','f','q','b','erg','psic','mec','elec','otro','tiempo','uso_e_p_p',
            'accidentes_trabajo','fecha_accidentes','empresa_accidentes','causa','tipo_lesion','parte_afectada','dias_incap',
            'secuelas','enfermedades_profesionales','fecha','empresa','diagnostico','reubicacion','indemnizacion'
        )
        ->where('cita_paciente_id', $citaPaciente->id)
        ->get();

        return response()->json($antecedenteocupacional, 200);
    }
    public function firmaPaciente(Request $request, $citaPaciente){
        $name = $citaPaciente.'_'.date('Y-m-d').'.png';
        $path   = '../storage/app/public/saludOcupacional/firma/';
        if (!file_exists($path)) {
            mkdir("../storage/app/public/saludOcupacional/firma/". $name ,777);
        }
        $img = substr($request->file, strpos($request->file, ",")+1);
        $data = base64_decode($img);
        $success = file_put_contents($path.$name, $data);

        if($success){
            // OBTENER LOS CITA_ID
            $citaPac = citapaciente::find($citaPaciente);
            // OBTENER CITA_PACIENTE
            citapaciente::where('Cita_id', $citaPac->Cita_id)->update([
                'firma_trabajador' => $name,
            ]);
            return response()->json([
                'message' => 'Firma guardada con Exito!',
            ], 200);
        }

    }

    public function certificado(Request $request)
    {
        $certificado = Paciente::select(['Pacientes.Primer_Nom','Pacientes.SegundoNom','Pacientes.Primer_Ape',
        'Pacientes.Segundo_Ape','Pacientes.Num_Doc','Pacientes.Sexo','Pacientes.Edad_Cumplida',
        'Pacientes.Fecha_Naci','Pacientes.estado_civil','Pacientes.Telefono','Pacientes.Celular1','Pacientes.Aseguradora',
        'Pacientes.Direccion_Residencia','c.*', 'cond.*', 's.*', 'ant.*','s.id as idCodigoOcupacional'])
        ->join('cita_paciente as c', 'Pacientes.id', 'c.Paciente_id')
        ->join('conductas as cond', 'c.id', 'cond.Citapaciente_id')
        ->join('saludocupacionals as s', 'c.id', 's.cita_paciente_id')
        ->leftjoin('antecedenteocupacionals as ant', 'c.id', 'ant.cita_paciente_id')
        ->where('Pacientes.Num_Doc', $request->Num_Doc)
        ->get();
        return response()->json($certificado, 200);
    }

    public function historiaOcupacional(Request $request)
    {
        $historia = DB::select("exec dbo.Historico_Salud_Ocupacional" . "'$request->Num_Doc'");
        return response()->json($historia, 200);
    }

    public function citaOrdenPaciente(Paciente $paciente, Request $request){

        $especialidadCups = EspecialidadesCup::select(['cup_id'])
        ->where('especialidad_id', $request->especialidad)
        ->where('estado_id', 1)
        ->get();

        $cups = [];
        foreach ($especialidadCups as $key){
            $cups[] = $key->cup_id;
        }

        $newOrden = [];

        if(isset($cups)){

            $ordenes = citapaciente::select(['cups.Nombre', 'cups.id', 'cupordens.Orden_id', 'cupordens.id as cuporden_id', 'cupordens.Observacionorden',
                'ordens.created_at', 'ordens.Fechaorden','cupordens.fecha_orden as fechaCup','cupordens.Cantidad'])
                ->join('ordens', 'ordens.Cita_id', 'cita_paciente.id')
                ->join('cupordens', 'cupordens.Orden_id', 'ordens.id')
                ->join('cups', 'cupordens.Cup_id', 'cups.id')
                ->join('sedeproveedores', 'cupordens.Ubicacion_id', 'sedeproveedores.id')
                ->where('cita_paciente.Paciente_id', $paciente->id)
                ->whereIn('sedeproveedores.Prestador_id', [67,544])
                ->whereIn('cupordens.Estado_id', [1,7])
                ->whereIn('cupordens.Cup_id', $cups)
//                ->where('cupordens.atendida', null)
                ->get();

            $ordens = [];
            foreach ($ordenes as $citaCup) {
                $citaPacienteCups = CupsordenCitapaciente::where('cupordens_id',$citaCup->cuporden_id)
                    ->join('cita_paciente as cp','cp.id','cupsorden_citapacientes.citapaciente_id')
                    ->whereIn('cp.Estado_id',[4,8,9])
                    ->count();
                if($citaPacienteCups < intval($citaCup->Cantidad)){
                    $ordens[] = $citaCup;
                }
            }


            foreach ($ordens as $key => $orden) {
                $fecha1 = Carbon::now()->format('Y-m-d');
                $fecha2 = Carbon::parse($orden->fechaCup);
                $diasDiferencia = $fecha2->diffInDays($fecha1);
                if ($diasDiferencia < 0){
                    $diasDiferencia = 0;
                }
                $orden['dias'] = $diasDiferencia;

                if(strpos($orden->Nombre, 'CONTROL')){
                    if(intval($orden->dias) <= 150){
                        $newOrden[] = $orden;
                    }
                }else{
                    if(intval($orden->dias) <= 120){
                        $newOrden[] = $orden;
                    }
                }
            }

        }

        return response()->json($newOrden, 200);

    }

    public function generarRipsAc($citapaciente){


        $existe = AcRips::where('citapaciente_id', $citapaciente->id)->first();

        if($existe){

            $ordenCita = Orden::where('Cita_id', $citapaciente->id)->first();

            $dxs = Cie10paciente::select(['cie10s.Codigo_CIE10', 'cie10pacientes.Esprimario',
            'cie10pacientes.Tipodiagnostico'])
            ->join('cie10s', 'cie10pacientes.Cie10_id', 'cie10s.id')
            ->where('cie10pacientes.Citapaciente_id', $citapaciente->id)
            ->get();

            $dxPrincipal = null;
            $tipoDiagnostico = null;
            $dxRelacionados = [];
            foreach($dxs as $dx){
                if($dx->Esprimario == true){
                    $dxPrincipal = $dx->Codigo_CIE10;
                    $tipoDiagnostico = $dx->Tipodiagnostico;
                }else{
                    $dxRelacionados[] = $dx->Codigo_CIE10;
                }
            }

            $updateAcRips = AcRips::where('id', $existe->id)
            ->update([
                'estado_id' => 9,
                'orden_id' => (!$ordenCita ? null : $ordenCita->id),
                'finalidad' => $citapaciente->Finalidad,
                'dx_principal' => $dxPrincipal,
                'dx_relacionado1' => (isset($dxRelacionados[0]) ? $dxRelacionados[0] : null),
                'dx_relacionado2' => (isset($dxRelacionados[1]) ? $dxRelacionados[1] : null),
                'dx_relacionado3' => (isset($dxRelacionados[2]) ? $dxRelacionados[2] : null),
                'tipo_diagnostico' => $tipoDiagnostico
            ]);

        }else{

            if($citapaciente->Tipocita_id != 1){

                if($citapaciente->Cita_id != null){
                    $sedeCodigo = Cita::select(['sp.Codigo_habilitacion'])
                    ->join('agendas as a', 'citas.Agenda_id', 'a.id')
                    ->join('consultorios as c', 'a.Consultorio_id', 'c.id')
                    ->join('sedes as se', 'c.Sede_id', 'se.id')
                    ->join('sedeproveedores as sp', 'se.Sedeprestador_id', 'sp.id')
                    ->where('citas.id', $citapaciente->Cita_id)
                    ->first();
                }else{
                    $sedeCodigo = Sedes_sumimedical::select(['sp.Codigo_habilitacion'])
                    ->join('sedeproveedores as sp', 'sedes_sumimedical.Sedeprestador_id', 'sp.id')
                    ->where('sedes_sumimedical.id', $citapaciente->lugar_atencion)
                    ->first();
                }

                $paciente = Paciente::select('Tipo_Doc', 'Num_Doc', 'entidad_id')
                ->where('id', $citapaciente->Paciente_id)
                ->first();

                $codigoCup = Cup::select(['cups.Codigo'])
                ->where('id', $citapaciente->Cup_id)
                ->first();

                $acRips = new AcRips;
                $acRips->citapaciente_id = $citapaciente->id;
                $acRips->paciente_id = $citapaciente->Paciente_id;
                if($sedeCodigo){
                    $acRips->cod_habilitacion_sede = $sedeCodigo->Codigo_habilitacion;
                }
                $acRips->tipo_doc = $paciente->Tipo_Doc;
                $acRips->numero_doc = $paciente->Num_Doc;
                $acRips->fecha_consulta = date('Y-m-d');
                if($codigoCup){
                    $acRips->cod_cup = $codigoCup->Codigo;
                }
                $acRips->entidad_id = $paciente->entidad_id;
                $acRips->estado_id = 8;
                $acRips->save();

            }

        }

    }

    public function antecedenteFarmacologico(Request $request)
    {

        $anteFarmacologico = AntecedenteFarmacologico::create([
            'utiempo' => $request['utiempo'],
            'frecuencia' => $request['frecuencia'],
            'alergia' => $request['alergia'],
            'observacionAlergia' => $request['observacionAlergia'],
            'detallearticulo_id' => $request['data']['id'],
            'Citapaciente_id' => $request['citaPaciente_id'],
        ]);
        return response()->json($anteFarmacologico, 200);
    }

    public function antecedenteToxico(Request $request)
    {
        $toxico = AntecedenteToxicologico::where('Citapaciente_id', $request->citaPaciente_id)->first();
        if (!$toxico) {
            AntecedenteToxicologico::create($request->all());
            return response()->json([
                'message' => 'Antecedente toxicologico creado con exito!'
            ]);
        } else {
            $toxico->update($request->all());
            return response()->json([
                'message' => 'Antecedente actualizado con exito!'
            ]);
        }


    }

    public function farmaco(AntecedenteFarmacologico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }

    public function alimento(AntecedenteFarmacologico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }

    public function ambiente(AntecedenteFarmacologico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }

    public function otralaergia(AntecedenteFarmacologico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }


    public function antePersonal($id)
    {
        AntecedentesPersonale::destroy($id);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }
    public function deleteOtrosAntece($id)
    {
        OtrosAntecedentesPersonale::destroy($id);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }
    public function deleteQuirurg($id)
    {
        AntecedentesQuirurgico::destroy($id);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }
    public function agregarDemanda(Request $request){
        $especialidad_tipo_agenda = Especialidadtipoagenda::where('id',$request->especialidad_tipo_agenda_id)->first();

        $agregaDemanda = DemandaInsatisfecha::create([
            'observacion' => $request->observacion,
            'especialidad_id' => $especialidad_tipo_agenda->Especialidad_id,
            'tipo_agenda_id' => $especialidad_tipo_agenda->Tipoagenda_id,
            'user_id' => auth()->id(),
            'paciente_id' => $request->paciente_id,
        ]);
        return response()->json([
            'message' => 'Demanda Insatisfecha creada con exito!',
        ], 200);
    }

    public function listarDemanda($id){
        $listarDemanda = DemandaInsatisfecha::select(
            'demanda_insatisfechas.id',
            'demanda_insatisfechas.observacion',
            DB::raw("CONCAT(users.name,' ',users.apellido) as usuario"),
            'estados.nombre as estado',
            'especialidades.Nombre as especialidad',
            'tipo_agendas.name as actividad',
            'demanda_insatisfechas.created_at as fecha')
        ->join('especialidades','especialidades.id','demanda_insatisfechas.especialidad_id')
        ->join('tipo_agendas','tipo_agendas.id','demanda_insatisfechas.tipo_agenda_id')
        ->join('users','users.id','demanda_insatisfechas.user_id')
        ->join('estados','estados.id','demanda_insatisfechas.estado_id')
        ->where('estados.id',1)
        ->where('demanda_insatisfechas.paciente_id', $id)->get();
        return response()->json($listarDemanda, 200);
    }

    /**
     * Cancela una cita
     * @param Request $request
     * @param $id citapaciente
     * @return JsonResponse
     * @author David Peláez
     */
    public function cancelarCita(Request $request, $id){
        try{
            $cita_service = new CitasService;
            $response = $cita_service->cancelar($id);
            citapaciente::where('id', $id)->update([
                'confirmacion_cita' => Carbon::now()
            ]);
            return response()->json($response);
        }catch(\Throwable $th){
            return response()->json($th->getMessage());
        }
    }

    public function agregarCierreDemanda(Request $request){

        $buscarDemanda = DemandaInsatisfecha::where('id',$request->id)->first();

        $buscarDemanda->update([
            'fecha_cita_victoriana' => $request->fecha_cita,
            'consultorio_victoriana' => $request->consultorio,
            'estado_id' => 5
        ]);
        return response()->json([
            'message' => 'Demanda Insatisfecha cerrada con exito!',
        ], 200);
    }

    /**
     * Cancela una cita
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @author David Peláez
     */
    public function confirmarCita(Request $request, $id){
        try{
            citapaciente::where('id', $id)->update([
                'confirmacion_cita' => Carbon::now()
            ]);
            return response()->json(true);
        }catch(\Throwable $th){
            return response()->json('Error: ' . $th->getMessage());
        }
    }

    /**
     * Consulta todas las citas en un rango de fechas
     * @param Request $request
     * @return JsonResponse
     * @author David Peláez
     */
    public function consultarCitas(Request $request){
        try{
            $cita_service = new CitasService;
            if($request->tipo === 'telemedicina'){
                $consulta = $cita_service->listarCitas('telemedicina');
            }
            $data = $cita_service->formatoParaMeet($consulta);
            Storage::append('consulta_citas.txt', 'Se consulto: ' . Carbon::now() . ' resultaron ' . $consulta->count() . ' registros.');
            return response()->json([
                "code" => "200",
                "description" => "ok",
                "data" => $data
            ]);
        }catch(\Throwable $th){
            return response()->json('Error: ' . $th->getMessage());
        }
    }

    /**
     * Almacena la firma en base_64 del consentimiento para telemedicina
     * @param Request $request
     * @param citapaciente $cita_paciente
     * @param String $documento, documento del paciente
     * @return Response
     * @author David Peláez
     */
    public function firmarConsentimiento(GuardarFirmaConsentimientoRequest $request, citapaciente $cita_paciente, $documento){
        try{
            /** En primer lugar validamos que la cita pertenezca al documento enviado */
            if(!$cita_paciente->validarPaciente($documento)){
                throw new Error('La cita no pertenece a este paciente.', 422);
            }
            /** almacenamos la firma */
            return response()->json($cita_paciente->guardarFirma($request->firma));

        }catch(\Exception $e){
            return response()->json($e->getMessage());
        }catch(\Error $e){
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Consulta un cita paciente en especifico
     * @param Request $request
     * @param citapaciente $cita_paciente
     * @return Response
     * @author David Peláez
     */
    public function consultar(Request $request, $cita_paciente){
        try{
                $cita = citapaciente::select('cita_paciente.*','pacientes.Primer_Nom',
                'pacientes.SegundoNom','pacientes.Primer_Ape','pacientes.Segundo_Ape',
                'pacientes.Num_Doc','pacientes.Edad_Cumplida',
                DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as nombrePaciente"),
                DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as nombreRepresentante"),
                'p.Num_Doc as docRepresentante')
                ->join('pacientes','pacientes.id','cita_paciente.paciente_id')
                ->leftjoin('pacientes as p','pacientes.Doc_Cotizante','p.Num_Doc')
                ->where('cita_paciente.id',$cita_paciente)->first();
            return response()->json($cita);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Modifica una cita, ya sea para cancelarla o para confirmarla
     * @param Request $request
     * @return JsonResponse
     * @author David Peláez
     */
    public function modificar(Request $request) {

        $fonoPlus = new FonoPlusWebService;

        try {
            $cita_paciente_id = intval($request['messages'][0]['externalId']);
            $accion = $request['messages'][0]['payload']['text'];
            $numero = $request['messages'][0]['mobileNumber'];

            if($accion === 'Confirmar cita'){
                Log::info($cita_paciente_id);
                citapaciente::where('id', $cita_paciente_id)->update([
                    'confirmacion_cita' => Carbon::now()
                ]);
                Detallecita::where('Citapaciente_id', $cita_paciente_id)->update([
                    'Motivo' => 'Se confirma la cita por el afiliado con exito mediante WhatsApp!'
                ]);
                $fonoPlus->respuestaWhatsApp($cita_paciente_id, $numero, 'Su cita ha sido confirmada de manera oportuna!.');
            }

            if($accion === 'Cancelar cita'){
                $cita_service = new CitasService;
                $cita_service->cancelar($cita_paciente_id);

                citapaciente::where('id', $cita_paciente_id)->update([
                    'confirmacion_cita' => Carbon::now()
                ]);
                $fonoPlus->respuestaWhatsApp($cita_paciente_id, $numero, 'Su cita ha sido cancelada de manera oportuna!.');
            }

            Storage::append('informacion.json', json_encode($request->all()));
            return response()->json(true);

        }catch(\Throwable $th){
            return response()->json($th->getMessage(), 400);
        }
    }

    public function listarConsultasWhatsApp(){
        try {
            $cita_service = new CitasService;
            $consultaCitas = $cita_service->listarCitasWhatsApp();
            return response()->json($consultaCitas, 200);
        } catch (\Throwable $th) {
            return response()->json('Error: ' . $th->getMessage());
        }
    }
}
