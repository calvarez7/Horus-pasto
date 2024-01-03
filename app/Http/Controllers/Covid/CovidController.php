<?php

namespace App\Http\Controllers\Covid;

use App\User;
use App\Modelos\Paise;
use App\Modelos\Paciente;
use App\Modelos\Ocupacione;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Registrocovi;
use App\Modelos\Cie10paciente;
use App\Modelos\RegistroCovid;
use App\Modelos\Seguimientocovid;
use App\Modelos\Seguimientocovis;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;

class CovidController extends Controller
{
    public function all(Request $request)
    {
       $resultado = RegistroCovid::select(['registro_covids.ruta','registro_covids.entidad','registro_covids.created_at as creado',
        'p.Num_Doc as cedula', 'p.Primer_Nom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.id as paciente_id'])
        ->join('Pacientes as p', 'registro_covids.paciente', 'p.id')
        ->where('p.Num_Doc', $request->Num_Doc)
        ->get();
        return response()->json($resultado, 200);
    }
    public function store(Request $request)
    {
        if ($file = $request->file('adjunto')) {
            $name   = $file->getClientOriginalName();
            $pathdb = '/storage/adjuntoscovid/'.$request->cedula . '/'. time() . $name;
            $path   = '../storage/app/public/adjuntoscovid/'.$request->cedula;
            if ($file->move(public_path($path), time().$name)) {
                $covid = RegistroCovid::create([
                    'paciente' => $request->paciente_id,
                    'entidad' => $request->entidad,
                    'nombre' =>  $name,
                    'ruta' =>  $pathdb
                    ]);

                    return response()->json([
                        'message' => 'Resultado con exito'
                    ], 200);
            }
        }
    }
    public function registro(Request $request)
    {
        // dd($request->all());
        $citapaciente = citapaciente::create([
            'Paciente_id' => $request->paciente_id,
            'Usuario_id' => auth()->user()->id,
            'Tipocita_id' => 8,
            'Cup_id' => 177,
            'Motivoconsulta' => $request->motivoconsulta,
        ]);
        foreach ($request->cie10s as $key=> $cie10) {
            Cie10paciente::create([
                'Cie10_id' => $cie10,
                'Citapaciente_id' => $citapaciente->id,
                'Esprimario' => ($key===0?true:false),
                'Tipodiagnostico' => 'Impresión diagnóstica',
            ]);
        }
        $request->merge(['cita_paciente_id' => $citapaciente->id]);
        $paciente = Paciente::select('Pacientes.*', 'sedeproveedores.Nombre as NombreIPS', 'e.nombre as entidad')
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->where('Pacientes.id', $request->paciente_id)
            ->where('Estado_Afiliado', 1)
            ->whereIn('pacientes.entidad_id', [1,2,3])
            ->first();
        if ($request->destino_paciente == 'Alta descartado' || $request->destino_paciente == 'Alta recuperado' || $request->destino_paciente == 'Alta fallecido') {
            Registrocovi::create([
                'cita_paciente_id' => $citapaciente->id,
                'paciente_id' => $request->paciente_id,
                'medida_edad' => $request->medida_edad,
                'pais_nacionalidad_id' => $request->pais_nacionalidad_id,
                'pais_ocurrencia_id' => $request->pais_ocurrencia_id,
                'municipio_procedencia_id' => $request->municipio_procedencia_id,
                'area_ocurrencia_caso' => $request->area_ocurrencia_caso,
                'localidad_ocurrencia_caso' => $request->localidad_ocurrencia_caso,
                'barrio_ocurrencia_caso' => $request->barrio_ocurrencia_caso,
                'zona' => $request->zona,
                'ocupacion_id' => $request->ocupacion_id,
                'tipo_regimen_salud' => $request->tipo_regimen_salud,
                'pertenencia_etnica' => $request->pertenencia_etnica,
                'estrato' => $request->estrato,
                'discapacitados' => $request->discapacitados,
                'Migrantes' => $request->Migrantes,
                'Gestantes' => $request->Gestantes,
                'Sem_de_gestacion' => $request->Sem_de_gestacion,
                'desplazados' => $request->desplazados,
                'carcelarios' => $request->carcelarios,
                'indigentes' => $request->indigentes,
                'poblacion_infantil_ICBF' => $request->poblacion_infantil_ICBF,
                'madres_comunitarias' => $request->madres_comunitarias,
                'desmovilizados' => $request->desmovilizados,
                'centros_psiquiatricos' => $request->centros_psiquiatricos,
                'victimas_violencia_armada' => $request->victimas_violencia_armada,
                'otros_grupos_poblacionales' => $request->otros_grupos_poblacionales,
                'fuente' => $request->fuente,
                'municipio_residencia_paciente_id' => $request->municipio_residencia_paciente_id,
                'direccion_residencia' => $request->direccion_residencia,
                'fecha_consulta' => $request->fecha_consulta,
                'fecha_inicio_sintomas' => $request->fecha_inicio_sintomas,
                'clasificacion' => $request->clasificacion,
                'hospitalizado' => $request->hospitalizado,
                'fecha_hospitalizacion' => $request->fecha_hospitalizacion,
                'condicion_final' => $request->condicion_final,
                'fecha_defuncion' => $request->fecha_defuncion,
                'numero_certificado_defuncion' => $request->numero_certificado_defuncion,
                'causa_basica_muerte' => $request->causa_basica_muerte,
                'telefono' => $request->telefono,
                'clasificacion_final_caso' => $request->clasificacion_final_caso,
                'Viajo_circulacion_virus' => $request->Viajo_circulacion_virus,
                'viajo_territorio_nacional' => $request->viajo_territorio_nacional,
                'municipio_viajo_id' => $request->municipio_viajo_id,
                'viajo_internacional' => $request->viajo_internacional,
                'pais_viajo_id' => $request->pais_viajo_id,
                'tuvo_contacto_estrecho' => $request->tuvo_contacto_estrecho,
                'tos' => $request->tos,
                'fiebre' => $request->fiebre,
                'dolor_garganta' => $request->dolor_garganta,
                'dificultad_respiratoria' => $request->dificultad_respiratoria,
                'fatiga_adinamia' => $request->fatiga_adinamia,
                'rinorrea' => $request->rinorrea,
                'conjuntivitis' => $request->conjuntivitis,
                'cefalea' => $request->cefalea,
                'diarrea' => $request->diarrea,
                'perdida_de_olfato' => $request->perdida_de_olfato,
                'otros_sintomas' => $request->otros_sintomas,
                'cuales_otros' => $request->cuales_otros,
                'asma' => $request->asma,
                'epoc' => $request->epoc,
                'diabetes' => $request->diabetes,
                'vih' => $request->vih,
                'enfermedad_cardiaca' => $request->enfermedad_cardiaca,
                'cancer' => $request->cancer,
                'malnutricion' => $request->malnutricion,
                'obesidad' => $request->obesidad,
                'insuficiencia_renal' => $request->insuficiencia_renal,
                'medicamentos_inmunosupresores' => $request->medicamentos_inmunosupresores,
                'fumador' => $request->fumador,
                'hipertensión' => $request->hipertensión,
                'tuberculosis' => $request->tuberculosis,
                'otros' => $request->otros,
                'otros_antecedentes' => $request->otros_antecedentes,
                'infiltrado_alveolar_neumonia' => $request->infiltrado_alveolar_neumonia,
                'infiltrados_intesticiales' => $request->infiltrados_intesticiales,
                'infiltrados_basales_vidrio_esmerilado' => $request->infiltrados_basales_vidrio_esmerilado,
                'ninguno' => $request->ninguno,
                'servicio_hospitalizo' => $request->servicio_hospitalizo,
                'uci' => $request->uci,
                'fecha_ingreso_uci' => $request->fecha_ingreso_uci,
                'derrame_pleural' => $request->derrame_pleural,
                'derrame_pericardico' => $request->derrame_pericardico,
                'miocarditis' => $request->miocarditis,
                'septicemia' => $request->septicemia,
                'falla_respiratoria' => $request->falla_respiratoria,
                'otro' => $request->otro,
                'otra_complicacion' => $request->otra_complicacion,
                'requiere_prueba' => $request->requiere_prueba,
                'tipo_prueba' => $request->tipo_prueba,
                'observacionesregistro' => $request->observacionesregistro,
                'modalida_prueba' => $request->modalida_prueba,
                'destino_paciente' => $request->destino_paciente,
                'esperarencasa' => $request->esperarencasa,
                'asignarconsulta' => $request->asignarconsulta,
                'seguimientotelefonico' => $request->seguimientotelefonico,
                'consultaprioritaria' => $request->consultaprioritaria,
                'teleorientacion' => $request->teleorientacion,
                'otrasindicaciones' => $request->otrasindicaciones,
                'tipo_vacuna' => $request->tipo_vacuna,
                'fecha_primera_dosis' => $request->primera_dosis,
                'fecha_segunda_dosis' => $request->segunda_dosis,
                'estado_id' => 44,
            ]);
        }else {
            Registrocovi::create([
                'cita_paciente_id' => $citapaciente->id,
                'paciente_id' => $request->paciente_id,
                'medida_edad' => $request->medida_edad,
                'pais_nacionalidad_id' => $request->pais_nacionalidad_id,
                'pais_ocurrencia_id' => $request->pais_ocurrencia_id,
                'municipio_procedencia_id' => $request->municipio_procedencia_id,
                'area_ocurrencia_caso' => $request->area_ocurrencia_caso,
                'localidad_ocurrencia_caso' => $request->localidad_ocurrencia_caso,
                'barrio_ocurrencia_caso' => $request->barrio_ocurrencia_caso,
                'zona' => $request->zona,
                'ocupacion_id' => $request->ocupacion_id,
                'tipo_regimen_salud' => $request->tipo_regimen_salud,
                'pertenencia_etnica' => $request->pertenencia_etnica,
                'estrato' => $request->estrato,
                'discapacitados' => $request->discapacitados,
                'Migrantes' => $request->Migrantes,
                'Gestantes' => $request->Gestantes,
                'Sem_de_gestacion' => $request->Sem_de_gestacion,
                'desplazados' => $request->desplazados,
                'carcelarios' => $request->carcelarios,
                'indigentes' => $request->indigentes,
                'poblacion_infantil_ICBF' => $request->poblacion_infantil_ICBF,
                'madres_comunitarias' => $request->madres_comunitarias,
                'desmovilizados' => $request->desmovilizados,
                'centros_psiquiatricos' => $request->centros_psiquiatricos,
                'victimas_violencia_armada' => $request->victimas_violencia_armada,
                'otros_grupos_poblacionales' => $request->otros_grupos_poblacionales,
                'fuente' => $request->fuente,
                'municipio_residencia_paciente_id' => $request->municipio_residencia_paciente_id,
                'direccion_residencia' => $request->direccion_residencia,
                'fecha_consulta' => $request->fecha_consulta,
                'fecha_inicio_sintomas' => $request->fecha_inicio_sintomas,
                'clasificacion' => $request->clasificacion,
                'hospitalizado' => $request->hospitalizado,
                'fecha_hospitalizacion' => $request->fecha_hospitalizacion,
                'condicion_final' => $request->condicion_final,
                'fecha_defuncion' => $request->fecha_defuncion,
                'numero_certificado_defuncion' => $request->numero_certificado_defuncion,
                'causa_basica_muerte' => $request->causa_basica_muerte,
                'telefono' => $request->telefono,
                'clasificacion_final_caso' => $request->clasificacion_final_caso,
                'Viajo_circulacion_virus' => $request->Viajo_circulacion_virus,
                'viajo_territorio_nacional' => $request->viajo_territorio_nacional,
                'municipio_viajo_id' => $request->municipio_viajo_id,
                'viajo_internacional' => $request->viajo_internacional,
                'pais_viajo_id' => $request->pais_viajo_id,
                'tuvo_contacto_estrecho' => $request->tuvo_contacto_estrecho,
                'tos' => $request->tos,
                'fiebre' => $request->fiebre,
                'dolor_garganta' => $request->dolor_garganta,
                'dificultad_respiratoria' => $request->dificultad_respiratoria,
                'fatiga_adinamia' => $request->fatiga_adinamia,
                'rinorrea' => $request->rinorrea,
                'conjuntivitis' => $request->conjuntivitis,
                'cefalea' => $request->cefalea,
                'diarrea' => $request->diarrea,
                'perdida_de_olfato' => $request->perdida_de_olfato,
                'otros_sintomas' => $request->otros_sintomas,
                'cuales_otros' => $request->cuales_otros,
                'asma' => $request->asma,
                'epoc' => $request->epoc,
                'diabetes' => $request->diabetes,
                'vih' => $request->vih,
                'enfermedad_cardiaca' => $request->enfermedad_cardiaca,
                'cancer' => $request->cancer,
                'malnutricion' => $request->malnutricion,
                'obesidad' => $request->obesidad,
                'insuficiencia_renal' => $request->insuficiencia_renal,
                'medicamentos_inmunosupresores' => $request->medicamentos_inmunosupresores,
                'fumador' => $request->fumador,
                'hipertensión' => $request->hipertensión,
                'tuberculosis' => $request->tuberculosis,
                'otros' => $request->otros,
                'otros_antecedentes' => $request->otros_antecedentes,
                'infiltrado_alveolar_neumonia' => $request->infiltrado_alveolar_neumonia,
                'infiltrados_intesticiales' => $request->infiltrados_intesticiales,
                'infiltrados_basales_vidrio_esmerilado' => $request->infiltrados_basales_vidrio_esmerilado,
                'ninguno' => $request->ninguno,
                'servicio_hospitalizo' => $request->servicio_hospitalizo,
                'uci' => $request->uci,
                'fecha_ingreso_uci' => $request->fecha_ingreso_uci,
                'derrame_pleural' => $request->derrame_pleural,
                'derrame_pericardico' => $request->derrame_pericardico,
                'miocarditis' => $request->miocarditis,
                'septicemia' => $request->septicemia,
                'falla_respiratoria' => $request->falla_respiratoria,
                'otro' => $request->otro,
                'otra_complicacion' => $request->otra_complicacion,
                'requiere_prueba' => $request->requiere_prueba,
                'tipo_prueba' => $request->tipo_prueba,
                'observacionesregistro' => $request->observacionesregistro,
                'modalida_prueba' => $request->modalida_prueba,
                'destino_paciente' => $request->destino_paciente,
                'esperarencasa' => $request->esperarencasa,
                'asignarconsulta' => $request->asignarconsulta,
                'seguimientotelefonico' => $request->seguimientotelefonico,
                'consultaprioritaria' => $request->consultaprioritaria,
                'teleorientacion' => $request->teleorientacion,
                'otrasindicaciones' => $request->otrasindicaciones,
                'tipo_vacuna' => $request->tipo_vacuna,
                'fecha_primera_dosis' => $request->primera_dosis,
                'fecha_segunda_dosis' => $request->segunda_dosis,
            ]);
        }

        return response()->json([
            'message' => 'Registro con Exito!',
            'cita_paciente_id' => $citapaciente->id,
            'paciente' => $paciente,
        ], 201);
    }
    public  function admision(){
        $admision = Registrocovi::select([
            'cp.*', 'e.Nombre as estado', 'p.*',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'registrocovis.*', 'registrocovis.created_at as fecharegistro', 'us.name', 'us.apellido'
        ])
            ->join('cita_paciente as cp', 'registrocovis.cita_paciente_id', 'cp.id')
            ->join('users as us', 'cp.Usuario_id', 'us.id')
            ->join('estados as e', 'e.id', 'registrocovis.estado_id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->join('users as u', 'u.id', 'cp.Usuario_id')
            ->where('registrocovis.estado_id', 1)
            ->get();
            return response()->json($admision);
    }
    public function registroadmision(Request $request)
    {
        // dd($request->destino_paciente);
        $registro = Registrocovi::where('id', $request->seguimiento_atencion_contingencia_id)->first();

        if ($request->destino_paciente == 'Alta descartado' || $request->destino_paciente == 'Alta recuperado' || $request->destino_paciente == 'Alta fallecido') {
            $registro = Registrocovi::where('id', $request->seguimiento_atencion_contingencia_id)->first();
            $registro->update([
                'tipo_vivienda' => $request->tipo_vivienda,
                'tipo_habitacion' => $request->tipo_habitacion,
                'tipo_contacto' => $request->tipo_contacto,
                'reportar_contactos' => $request->reportar_contactos,
                'nombre_cuidador' => $request->nombre_cuidador,
                'documento_cuidador' => $request->documento_cuidador,
                'aseguradora_cuidador' => $request->aseguradora_cuidador,
                'municipio_cuidador_id' => $request->municipio_cuidador_id,
                'direccion_cuidador' => $request->direccion_cuidador,
                'telefono_cuidador' => $request->telefono_cuidador,
                'correo_cuidador' => $request->correo_cuidador,
                'parentesco_cuidador' => $request->parentesco_cuidador,
                'cumplir_aislamiento' => $request->cumplir_aislamiento,
                'contactado' => $request->contactado,
                'vacunado_influenza' => $request->vacunado_influenza,
                'utilizado_antibioticos' => $request->utilizado_antibioticos,
                'fecha_inicio_aislamiento' => $request->fecha_inicio_aislamiento,
                'presenta_discapacidades' => $request->presenta_discapacidades,
                'presenta_impedimento_aislamiento_domi' => $request->presenta_impedimento_aislamiento_domi,
                'clasificacion_resolucion_521' => $request->clasificacion_resolucion_521,
                'user_admisiona_id' => auth()->user()->id,
                'usuario_seguimiento_id' => $request->usuario_seguimiento_id,
                'destino_paciente' =>$request->destino_paciente,
                'motivoaislamiento' => $request->motivoaislamiento,
                'otravivienda' => $request->otravivienda,
                'presupuestocomun' => $request->presupuestocomun,
                'estado_id' => 44
            ]);

            return response()->json([
                'message' => 'oki oki!',
            ], 200);
        }else {
            $registro->update([
                'tipo_vivienda' => $request->tipo_vivienda,
                'tipo_habitacion' => $request->tipo_habitacion,
                'tipo_contacto' => $request->tipo_contacto,
                'reportar_contactos' => $request->reportar_contactos,
                'nombre_cuidador' => $request->nombre_cuidador,
                'documento_cuidador' => $request->documento_cuidador,
                'aseguradora_cuidador' => $request->aseguradora_cuidador,
                'municipio_cuidador_id' => $request->municipio_cuidador_id,
                'direccion_cuidador' => $request->direccion_cuidador,
                'telefono_cuidador' => $request->telefono_cuidador,
                'correo_cuidador' => $request->correo_cuidador,
                'parentesco_cuidador' => $request->parentesco_cuidador,
                'cumplir_aislamiento' => $request->cumplir_aislamiento,
                'contactado' => $request->contactado,
                'vacunado_influenza' => $request->vacunado_influenza,
                'utilizado_antibioticos' => $request->utilizado_antibioticos,
                'fecha_inicio_aislamiento' => $request->fecha_inicio_aislamiento,
                'presenta_discapacidades' => $request->presenta_discapacidades,
                'presenta_impedimento_aislamiento_domi' => $request->presenta_impedimento_aislamiento_domi,
                'clasificacion_resolucion_521' => $request->clasificacion_resolucion_521,
                'user_admisiona_id' => auth()->user()->id,
                'usuario_seguimiento_id' => $request->usuario_seguimiento_id,
                'destino_paciente' =>$request->destino_paciente,
                'motivoaislamiento' => $request->motivoaislamiento,
                'otravivienda' => $request->otravivienda,
                'presupuestocomun' => $request->presupuestocomun,
                'estado_id' => 43,
            ]);
            return response()->json([
                'message' => 'admision guarda con exito!',
            ], 200);
        }


    }
    public function paises()
    {
        $paises = Paise::all();
        return response()->json($paises, 200);
    }

    public function listarregistros()
    {
       $seguimientos = Registrocovi::select(['registrocovis.*','registrocovis.id as registro_id', 'registrocovis.created_at as fecharegistro', 'p.*',
       'u.name', 'e.Nombre as estado', 'us.name', 'us.apellido', 'seg.destino_paciente as destino'])
        ->join('cita_paciente as c', 'registrocovis.cita_paciente_id', 'c.id')
        ->leftjoin('seguimientocovids as seg', 'registrocovis.id', 'seg.registrocovi_id')
        ->join('users as us', 'c.Usuario_id', 'us.id')
        ->join('pacientes as p', 'c.Paciente_id', 'p.id')
        ->join('users as u', 'registrocovis.user_admisiona_id', 'u.id')
        ->join('estados as e', 'registrocovis.estado_id', 'e.id')
        ->where('registrocovis.estado_id', 43)
        //->where('registrocovis.usuario_seguimiento_id', auth()->user()->id)
        ->get();
       return response()->json($seguimientos->unique(), 200);
    }
    public function alta()
    {
       $seguimientos = Registrocovi::select(['registrocovis.*','registrocovis.id as registro_id','registrocovis.created_at as fecharegistro', 'p.*', 'u.name', 'e.Nombre as estado', 'us.name', 'us.apellido'])
        ->join('cita_paciente as c', 'registrocovis.cita_paciente_id', 'c.id')
        ->join('users as us', 'c.Usuario_id', 'us.id')
        ->join('pacientes as p', 'c.Paciente_id', 'p.id')
        ->leftjoin('users as u', 'registrocovis.user_admisiona_id', 'u.id')
        ->join('estados as e', 'registrocovis.estado_id', 'e.id')
        ->where('registrocovis.estado_id', 44)
        ->orderBy('registrocovis.id', 'desc')
        ->limit(3000)
        ->get();
        
       return response()->json($seguimientos, 200);
    }
    public function seguimientoCovi(Request $request)
    {
        Seguimientocovid::create($request->all());
        return response()->json([
            'message' => 'Seguimiento registrado con exito!'
        ], 200);
    }

    public function dealtaCovi(Request $request)
    {
        $seguimiento = new Seguimientocovid;
            $seguimiento->registrocovi_id = $request->registrocovi_id;
            $seguimiento->contacto_fue = $request->contacto_fue;
            $seguimiento->institucion_realiza_hospitalizacion = $request->institucion_realiza_hospitalizacion;
            $seguimiento->tipo_hospitalizacion = $request->tipo_hospitalizacion;
            $seguimiento->soporte_ventilatorio = $request->soporte_ventilatorio;
            $seguimiento->soporte_hemodinamico = $request->soporte_hemodinamico;
            $seguimiento->fecha_ingreso_hospitalizacion = $request->fecha_ingreso_hospitalizacion;
            $seguimiento->se_encuentra_hospitalizado = $request->se_encuentra_hospitalizado;
            $seguimiento->fecha_egreso_hospitalizacion = $request->fecha_egreso_hospitalizacion;
            $seguimiento->estado_alta = $request->estado_alta;
            $seguimiento->paciente_sintomas = $request->paciente_sintomas;
            $seguimiento->ha_presentado_fiebre = $request->ha_presentado_fiebre;
            $seguimiento->ha_presentado_tos = $request->ha_presentado_tos;
            $seguimiento->ha_presentado_dificultad_respiratoria = $request->ha_presentado_dificultad_respiratoria;
            $seguimiento->ha_presentado_dolor_garganta = $request->ha_presentado_dolor_garganta;
            $seguimiento->ha_presentado_fatiga = $request->ha_presentado_fatiga;
            $seguimiento->ha_presentado_cefalea = $request->ha_presentado_cefalea;
            $seguimiento->que_otros_sintomas = $request->que_otros_sintomas;
            $seguimiento->ha_presentado_sintomas_alarma = $request->ha_presentado_sintomas_alarma;
            $seguimiento->detalles_signos_alarmas = $request->detalles_signos_alarmas;
            $seguimiento->respiracion_rapida_taquipnea = $request->respiracion_rapida_taquipnea;
            $seguimiento->fiebre_mas_2_dias = $request->fiebre_mas_2_dias;
            $seguimiento->pecho_suena_sibilancias = $request->pecho_suena_sibilancias;
            $seguimiento->somnolencia_letargia = $request->somnolencia_letargia;
            $seguimiento->convulsiones = $request->convulsiones;
            $seguimiento->deterioro_rapido_estado_general = $request->deterioro_rapido_estado_general;
            $seguimiento->registrado_segcovid = $request->registrado_segcovid;
            $seguimiento->pruebas_diagnostico_covid = $request->pruebas_diagnostico_covid;
            $seguimiento->tipo_prueba_diagnostica_covid = $request->tipo_prueba_diagnostica_covid;
            $seguimiento->fecha_realizacion_prueba = $request->fecha_realizacion_prueba;
            $seguimiento->fecha_recepcion_prueba = $request->fecha_recepcion_prueba;
            $seguimiento->ips_toma_muestra = $request->ips_toma_muestra;
            $seguimiento->resultado_pruebas = $request->resultado_pruebas;
            $seguimiento->fecha_reporte_resultado_covid = $request->fecha_reporte_resultado_covid;
            $seguimiento->ips_procesa_muestra = $request->ips_procesa_muestra;
            $seguimiento->otra_ips = $request->otra_ips;
            $seguimiento->numero_nit = $request->numero_nit;
            $seguimiento->observaciones = $request->observaciones;
            $seguimiento->user_crea_seguimiento = auth()->user()->id;
            $seguimiento->save();
        $registro = Registrocovi::where('id', $request->registrocovi_id)->first();

            $registro->update([
                'estado_id' => 44,
            ]);
            return response()->json([
                'message' => 'Seguimiento registrado con exito!'
            ], 200);
    }
    public  function verseguimientos($registro){
        $verseguimiento = Seguimientocovid::select([
            'seguimientocovids.*', 'u.name', 'u.apellido'
        ])
        ->leftjoin('users as u', 'seguimientocovids.realizado_por', 'u.id')
        ->where('registrocovi_id', $registro)
        ->get();

        return response()->json($verseguimiento);
    }
    public function guardarSeguimientos(Request $request)
    {
        $paciente = citapaciente::select(['p.*'])
        ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
        ->where('cita_paciente.id', $request->cita_paciente_id)
        ->first();


        if (    $request->destino_paciente == 'Seguimiento' ||
                $request->destino_paciente == 'Oximetrías' ||
                $request->destino_paciente == 'Intitucionalización' ||
                $request->destino_paciente == 'Hospitalización' ||
                $request->destino_paciente == 'Hospitalización UCI/UCE'
            ){
            $seguimiento = new Seguimientocovid;
            $seguimiento->registrocovi_id = $request->registrocovi_id;
            $seguimiento->contacto_fue = $request->contacto_fue;
            $seguimiento->institucion_realiza_hospitalizacion = $request->institucion_realiza_hospitalizacion;
            $seguimiento->tipo_hospitalizacion = $request->tipo_hospitalizacion;
            $seguimiento->soporte_ventilatorio = $request->soporte_ventilatorio;
            $seguimiento->soporte_hemodinamico = $request->soporte_hemodinamico;
            $seguimiento->fecha_ingreso_hospitalizacion = $request->fecha_ingreso_hospitalizacion;
            $seguimiento->se_encuentra_hospitalizado = $request->se_encuentra_hospitalizado;
            $seguimiento->fecha_egreso_hospitalizacion = $request->fecha_egreso_hospitalizacion;
            $seguimiento->estado_alta = $request->estado_alta;
            $seguimiento->paciente_sintomas = $request->paciente_sintomas;
            $seguimiento->ha_presentado_fiebre = $request->ha_presentado_fiebre;
            $seguimiento->ha_presentado_tos = $request->ha_presentado_tos;
            $seguimiento->ha_presentado_dificultad_respiratoria = $request->ha_presentado_dificultad_respiratoria;
            $seguimiento->ha_presentado_dolor_garganta = $request->ha_presentado_dolor_garganta;
            $seguimiento->ha_presentado_fatiga = $request->ha_presentado_fatiga;
            $seguimiento->ha_presentado_cefalea = $request->ha_presentado_cefalea;
            $seguimiento->que_otros_sintomas = $request->que_otros_sintomas;
            $seguimiento->ha_presentado_sintomas_alarma = $request->ha_presentado_sintomas_alarma;
            $seguimiento->detalles_signos_alarmas = $request->detalles_signos_alarmas;
            $seguimiento->respiracion_rapida_taquipnea = $request->respiracion_rapida_taquipnea;
            $seguimiento->fiebre_mas_2_dias = $request->fiebre_mas_2_dias;
            $seguimiento->pecho_suena_sibilancias = $request->pecho_suena_sibilancias;
            $seguimiento->somnolencia_letargia = $request->somnolencia_letargia;
            $seguimiento->convulsiones = $request->convulsiones;
            $seguimiento->deterioro_rapido_estado_general = $request->deterioro_rapido_estado_general;
            $seguimiento->registrado_segcovid = $request->registrado_segcovid;
            $seguimiento->pruebas_diagnostico_covid = $request->pruebas_diagnostico_covid;
            $seguimiento->tipo_prueba_diagnostica_covid = $request->tipo_prueba_diagnostica_covid;
            $seguimiento->fecha_realizacion_prueba = $request->fecha_realizacion_prueba;
            $seguimiento->fecha_recepcion_prueba = $request->fecha_recepcion_prueba;
            $seguimiento->ips_toma_muestra = $request->ips_toma_muestra;
            $seguimiento->resultado_pruebas = $request->resultado_pruebas;
            $seguimiento->fecha_reporte_resultado_covid = $request->fecha_reporte_resultado_covid;
            $seguimiento->ips_procesa_muestra = $request->ips_procesa_muestra;
            $seguimiento->otra_ips = $request->otra_ips;
            $seguimiento->numero_nit = $request->numero_nit;
            $seguimiento->observaciones = $request->observaciones;
            $seguimiento->destino_paciente = $request->destino_paciente;
            $seguimiento->realizado_por = auth()->user()->id;
            $seguimiento->esperarencasa = $request->esperarencasa;
            $seguimiento->asignarconsulta = $request->asignarconsulta;
            $seguimiento->seguimientotelefonico = $request->seguimientotelefonico;
            $seguimiento->consultaprioritaria = $request->consultaprioritaria;
            $seguimiento->teleorientacion = $request->teleorientacion;
            $seguimiento->otrasindicaciones = $request->otrasindicaciones;
            $seguimiento->tiposeguimiento = $request->tiposeguimiento;
            $seguimiento->causaseguimiento = $request->causaseguimiento;
            $seguimiento->saturacion = $request->saturacion;
            $seguimiento->pulso = $request->pulso;
            $seguimiento->temperatura = $request->temperatura;
            $seguimiento->tensionarterial = $request->tensionarterial;
            $seguimiento->frecuenciarespiratoria = $request->frecuenciarespiratoria;
            $seguimiento->escalanews2 = $request->escalanews2;
            $seguimiento->callscore = $request->callscore;
            $seguimiento->concentracionoxigeno = $request->concentracionoxigeno;
            $seguimiento->laboratoriodomicilio = $request->laboratoriodomicilio;
            $seguimiento->oxigenoterapia = $request->oxigenoterapia;
            $seguimiento->flujo_oxigeno = $request->flujo_oxigeno;
            $seguimiento->control = $request->control;
            $seguimiento->save();
            return response()->json([
                'message' => 'Seguimiento guardado con exito!',
                'paciente' => $paciente
            ], 200);
        }else {
            $seguimiento = new Seguimientocovid;
            $seguimiento->registrocovi_id = $request->registrocovi_id;
            $seguimiento->contacto_fue = $request->contacto_fue;
            $seguimiento->institucion_realiza_hospitalizacion = $request->institucion_realiza_hospitalizacion;
            $seguimiento->tipo_hospitalizacion = $request->tipo_hospitalizacion;
            $seguimiento->soporte_ventilatorio = $request->soporte_ventilatorio;
            $seguimiento->soporte_hemodinamico = $request->soporte_hemodinamico;
            $seguimiento->fecha_ingreso_hospitalizacion = $request->fecha_ingreso_hospitalizacion;
            $seguimiento->se_encuentra_hospitalizado = $request->se_encuentra_hospitalizado;
            $seguimiento->fecha_egreso_hospitalizacion = $request->fecha_egreso_hospitalizacion;
            $seguimiento->estado_alta = $request->estado_alta;
            $seguimiento->paciente_sintomas = $request->paciente_sintomas;
            $seguimiento->ha_presentado_fiebre = $request->ha_presentado_fiebre;
            $seguimiento->ha_presentado_tos = $request->ha_presentado_tos;
            $seguimiento->ha_presentado_dificultad_respiratoria = $request->ha_presentado_dificultad_respiratoria;
            $seguimiento->ha_presentado_dolor_garganta = $request->ha_presentado_dolor_garganta;
            $seguimiento->ha_presentado_fatiga = $request->ha_presentado_fatiga;
            $seguimiento->ha_presentado_cefalea = $request->ha_presentado_cefalea;
            $seguimiento->que_otros_sintomas = $request->que_otros_sintomas;
            $seguimiento->ha_presentado_sintomas_alarma = $request->ha_presentado_sintomas_alarma;
            $seguimiento->detalles_signos_alarmas = $request->detalles_signos_alarmas;
            $seguimiento->respiracion_rapida_taquipnea = $request->respiracion_rapida_taquipnea;
            $seguimiento->fiebre_mas_2_dias = $request->fiebre_mas_2_dias;
            $seguimiento->pecho_suena_sibilancias = $request->pecho_suena_sibilancias;
            $seguimiento->somnolencia_letargia = $request->somnolencia_letargia;
            $seguimiento->convulsiones = $request->convulsiones;
            $seguimiento->deterioro_rapido_estado_general = $request->deterioro_rapido_estado_general;
            $seguimiento->registrado_segcovid = $request->registrado_segcovid;
            $seguimiento->pruebas_diagnostico_covid = $request->pruebas_diagnostico_covid;
            $seguimiento->tipo_prueba_diagnostica_covid = $request->tipo_prueba_diagnostica_covid;
            $seguimiento->fecha_realizacion_prueba = $request->fecha_realizacion_prueba;
            $seguimiento->fecha_recepcion_prueba = $request->fecha_recepcion_prueba;
            $seguimiento->ips_toma_muestra = $request->ips_toma_muestra;
            $seguimiento->resultado_pruebas = $request->resultado_pruebas;
            $seguimiento->fecha_reporte_resultado_covid = $request->fecha_reporte_resultado_covid;
            $seguimiento->ips_procesa_muestra = $request->ips_procesa_muestra;
            $seguimiento->otra_ips = $request->otra_ips;
            $seguimiento->numero_nit = $request->numero_nit;
            $seguimiento->observaciones = $request->observaciones;
            $seguimiento->destino_paciente = $request->destino_paciente;
            $seguimiento->realizado_por = auth()->user()->id;
            $seguimiento->esperarencasa = $request->esperarencasa;
            $seguimiento->asignarconsulta = $request->asignarconsulta;
            $seguimiento->seguimientotelefonico = $request->seguimientotelefonico;
            $seguimiento->consultaprioritaria = $request->consultaprioritaria;
            $seguimiento->teleorientacion = $request->teleorientacion;
            $seguimiento->otrasindicaciones = $request->otrasindicaciones;
            $seguimiento->tiposeguimiento = $request->tiposeguimiento;
            $seguimiento->causaseguimiento = $request->causaseguimiento;
            $seguimiento->saturacion = $request->saturacion;
            $seguimiento->pulso = $request->pulso;
            $seguimiento->temperatura = $request->temperatura;
            $seguimiento->tensionarterial = $request->tensionarterial;
            $seguimiento->frecuenciarespiratoria = $request->frecuenciarespiratoria;
            $seguimiento->escalanews2 = $request->escalanews2;
            $seguimiento->callscore = $request->callscore;
            $seguimiento->concentracionoxigeno = $request->concentracionoxigeno;
            $seguimiento->laboratoriodomicilio = $request->laboratoriodomicilio;
            $seguimiento->save();
            $registro = Registrocovi::where('id', $request->registrocovi_id)->first();

                $registro->update([
                    'estado_id' => 44,
                ]);
            return response()->json([
                'message' => 'Paciente enviado de alta!',
                'paciente' => $paciente
            ], 200);
        }


    }
    public function asignados(){
        $asignados = User::select([
            'users.id',
            DB::raw("CONCAT(users.name,' ',users.apellido) AS nombre"),
            DB::raw("(select COUNT(*) from registrocovis as r where r.usuario_seguimiento_id = users.id AND r.estado_id = 43 ) AS [cantidad]"),
        ])->whereIn('users.id',[1834,927,1432])->get();
        return response()->json($asignados, 200);
    }
    public function ocupaciones()
    {
        $ocupaciones = Ocupacione::all();
        return response()->json($ocupaciones, 200);
    }
    public function reporte(Request $request)
    {
        $registrocovis = Registrocovi::select([
            'registrocovis.id','p.Departamento', 'm.Nombre as MUNICIPIO', 'registrocovis.destino_paciente as CLASIFICACIÓN DEL CASO INGRESO','sc.destino_paciente as CLASIFICACIÓN DEL CASO ÚLTIMO',
            'p.Tipo_Afiliado as TIPO DE AFILIADO', 'o.ocupacion as OCUPACION','p.cargo_laboral AS INSTITUCION EDUCATIVA DONDE LABORA',
            DB::raw("CONCAT(u.name,' ', u.apellido) as RESPONSABLE_DEL_SEGUIMIENTO"),'registrocovis.tipo_contacto AS TIPO DE CONTACTO',
            'p.Primer_Nom AS PRIMER N0MBRE','p.SegundoNom AS SEGUNDO NOMBRE','p.Primer_Ape AS PRIMER APELLIDO','p.Segundo_Ape AS SEGUNDO APELLIDO',
            'registrocovis.clasificacion_resolucion_521 as GRUPO AL QUE PERTENECE RES 521', 'registrocovis.Gestantes as GESTANTES',
            'p.Tipo_Doc as TIPO DE DOCUMENTO','p.Num_Doc as # DOCUMENTO', 'p.Edad_Cumplida as EDAD', 'registrocovis.medida_edad as UNIDAD DE MEDIDA',
            'p.Sexo as SEXO (F/M)','registrocovis.telefono as TELEFONOS','p.Ut as ASEGURADORA',
            'p.Departamento as DIVISION', 'registrocovis.presenta_discapacidades as TIPO DE DISCAPACIDAD','pa.nombre as PAÍSES/CIUDADES VISITADOS',
            'registrocovis.modalida_prueba as TOMA DE MUESTRA', 'registrocovis.tipo_prueba as NOMBRE PRUEBA',  'registrocovis.tuvo_contacto_estrecho as CONTACTO ESTRECHO EN LOS ÚLTIMOS 14 DÍAS',
            'pa.nombre as VIAJO FUERA DEL PAÍS DURANTE LOS ÚLTIMOS 14 DÍAS', 'registrocovis.asma','registrocovis.epoc', 'registrocovis.diabetes','registrocovis.vih',
            'registrocovis.enfermedad_cardiaca','registrocovis.cancer','registrocovis.malnutricion','registrocovis.obesidad',
            'registrocovis.insuficiencia_renal','registrocovis.medicamentos_inmunosupresores','registrocovis.fumador',
            'registrocovis.hipertensión','registrocovis.tuberculosis','registrocovis.otros','registrocovis.otros_antecedentes',
            'registrocovis.esperarencasa', 'registrocovis.asignarconsulta' , 'registrocovis.seguimientotelefonico',
            'registrocovis.consultaprioritaria ', 'registrocovis.teleorientacion','registrocovis.otrasindicaciones',
            'registrocovis.motivoaislamiento AS MOTIVO DE AISLAMIENTO', 'registrocovis.tipo_vivienda AS TIPO DE VIVIENDA DE AISLAMIENTO', 'registrocovis.otravivienda AS CUAL (SI LA RESPUESTA EN LA CASILLA ANTERIOR ES OTRO)',
            'registrocovis.tipo_habitacion as AISLADO EN HABITACIÓN INDIVIDUAL', 'registrocovis.fecha_inicio_aislamiento as FECHA DE INGRESO AISLAMIENTO','registrocovis.created_at as FECHA DE INGRESO A LA RUTA', 'sc.created_at as FECHA DE EGRESO A LA RUTA',
            'registrocovis.cumplir_aislamiento as CUMPLIR CON EL AISLAMIENTO',
            'vacunado_influenza as VACUNACIÓN INFLUENZA MENOS DE 1 AÑO','utilizado_antibioticos as USO RECIENTE DE ANTIBIOTICOS','tipo_vacuna as VACUNA COVID','fecha_primera_dosis as 1 DOSIS','fecha_segunda_dosis as 2 DOSIS',
            'reportar_contactos as REPORTA CONTACTOS', 'documento_cuidador', 'nombre_cuidador', 'aseguradora_cuidador', 'mun.Nombre as MUNICIPIO', 'direccion_cuidador', 'telefono_cuidador',
            'parentesco_cuidador', 'correo_cuidador', 'presupuestocomun', 'contactado',
        ])
        ->leftjoin('cita_paciente', 'registrocovis.cita_paciente_id', 'cita_paciente.id')
        ->leftjoin('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
        ->leftjoin('municipios as m', 'registrocovis.municipio_procedencia_id', 'm.id')
        ->leftjoin('municipios as mun', 'registrocovis.municipio_cuidador_id', 'mun.id')
        ->leftjoin('ocupaciones as o', 'registrocovis.ocupacion_id', 'o.id')
        ->leftjoin('paises as pa', 'registrocovis.pais_viajo_id', 'pa.id')
            ->leftjoin('users as u', 'u.id','registrocovis.user_admisiona_id')
         ->leftjoin('seguimientocovids as sc','sc.id' , DB::raw('(select top 1 seguimientocovids.id from seguimientocovids where seguimientocovids.registrocovi_id=registrocovis.id order by seguimientocovids.id desc)'))
            ->orderby('registrocovis.id','asc')
            ->with([
                'seguimientosCovid' => function($query) {
                $query->select(['seguimientocovids.id as Seguimiento', 'registrocovi_id','contacto_fue as CONTACTO','actualizar_info_hospitalizaciones_paciente as ÁMBITO DE LA ATENCIÓN','tipo_hospitalizacion as Tipo de hospitalización','soporte_ventilatorio as SOPORTE VENTILATORIO',
                                'soporte_hemodinamico as SOPORTE HEMODINÁMICO', 'seguimientocovids.created_at as FECHA DE SEGUIMIENTO','paciente_sintomas as SINTOMAS' ,'ha_presentado_fiebre as FIEBRE CUANTIFICADA ≥ 38°C', 'ha_presentado_tos as TOS',
                                'ha_presentado_dificultad_respiratoria as DIFICULTAD RESPIRATORIA', 'ha_presentado_dolor_garganta as ODINOFAGIA', 'ha_presentado_fatiga as FATIGA/ ADINAMIA',
                                'ha_presentado_cefalea as CEFALEA', 'que_otros_sintomas as OTRO', 'causaseguimiento as CAUSA DEL SEGUIMIENTO', 'tiposeguimiento as TIPO SEGUIMIENTO',
                                'detalles_signos_alarmas as ALARMA DÍA SEGUIMIENTO', 'saturacion','pulso','temperatura','frecuenciarespiratoria','registrado_segcovid as SEGCOVID','pruebas_diagnostico_covid as PRUEBAS COVID',
                    'tipo_prueba_diagnostica_covid as TIPO','resultado_pruebas as RESULTADO','causaseguimiento as CAUSA DEL SEGUIMIENTO','tiposeguimiento as TIPO SEGUIMIENTO'
                    ,'escalanews2 as ESTADO DE AFECTACIÓN','callscore as CALL SCORE','laboratoriodomicilio as Se toman laboratorios en el domicilio','oxigenoterapia','flujo_oxigeno as Flujo de oxígeno indicado','tensionarterial',
                    'observaciones','control','destino_paciente','esperarencasa as Se recomienda esperar en casa ','asignarconsulta as se asigna consulta medica','seguimientotelefonico as seguimiento telefonico',
                    'consultaprioritaria as consulta prioritaria','teleorientacion as teleorientacion','concentracionoxigeno as concentrador de oxigeno','otrasindicaciones as otras incidaciones','deterioro_rapido_estado_general as ESTADO DE AFECTACIÓN', DB::raw("CONCAT(us.name,' ', us.apellido) as Por")
                ])
                    ->leftjoin('users as us', 'us.id','seguimientocovids.realizado_por');
            }
            ])->where('registrocovis.created_at', '>=', '2022-01-01 00:00:00.000')->get()->toArray();
        $data = [];
        foreach ($registrocovis as $key => $registro) {
            $data[$key] = $registro;
            unset($data[$key]['seguimientos_covid']);
            $count = 1;
             foreach ($registro["seguimientos_covid"] as $seguimiento) {
                 foreach ($seguimiento as $key2 => $value2) {
                    $data[$key][$key2." #".$count] = $value2;
                 }
                 unset($data[$key]['registrocovi_id'." #".$count]);
                $count++;
             }
        }
        return (new FastExcel($data))->download('file.xlsx');
    }
}
