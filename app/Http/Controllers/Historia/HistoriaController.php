<?php

namespace App\Http\Controllers\Historia;

use App\demandaInducida;
use App\User;
use App\Modelos\Cup;
use App\Modelos\Cita;
use App\Modelos\Cie10;
use App\Modelos\Marca;
use App\Modelos\Orden;
use App\Modelos\AcRips;
use App\Modelos\Agenda;
use App\Modelos\Codesumi;
use App\Modelos\Conducta;
use App\Modelos\Cuporden;
use App\Modelos\Paciente;
use App\Modelos\Auditoria;
use App\Modelos\Adherencia;
use App\Modelos\RedesApoyo;
use App\Modelos\Referencia;
use App\Modelos\TipoAgenda;
use App\Modelos\Detallecita;
use App\Modelos\PlanCuidado;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Examenfisico;
use App\Modelos\Familiograma;
use App\Modelos\ApgarFamiliar;
use App\Modelos\Cie10paciente;
use App\Modelos\Especialidade;
use Illuminate\Support\Carbon;
use App\Modelos\CrianzaCuidado;
use App\Modelos\HabitosToxicos;
use PhpParser\Builder\Function_;
use App\Modelos\Detaarticulorden;
use App\Modelos\InformacionSalud;
use PhpParser\Node\Expr\FuncCall;
use App\Modelos\AyudasDiagnostica;
use App\Modelos\Sedes_sumimedical;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\indiceBarthel;
use App\Modelos\AntecedenteVacunale;
use App\Modelos\OtrosFarmacologicas;
use App\Modelos\Pacienteantecedente;
use App\Modelos\RefraccionSubejtivo;
use Illuminate\Support\Facades\Auth;
use App\Modelos\AntecedenteFamiliare;
use App\Modelos\GestanteGinecologico;
use App\Modelos\AntecedentesPersonale;
use App\Modelos\CupsordenCitapaciente;
use App\Modelos\EvaluacionPlanCuidado;
use App\Modelos\Insumos_procedimiento;
use App\Modelos\ResultadosLaboratorio;
use App\Modelos\AntecedentesQuirurgico;
use App\Modelos\Especialidadtipoagenda;
use App\Modelos\SeguridadFarmacologica;
use Illuminate\Support\Facades\Storage;
use App\Modelos\AntedecentesTraumaticos;
use App\Modelos\NecesidadFarmacologicas;
use App\Modelos\ProcesoHistoriaIntegral;
use Illuminate\Support\Facades\Response;
use App\Modelos\AntecedenteFarmacologico;
use App\Modelos\AntecedentesGinecologico;
use App\Modelos\estratificacion_findrisk;
use App\Modelos\lensometriaAgudezavisual;
use Illuminate\Support\Facades\Validator;
use App\Modelos\AntecedenteBiopsicosocial;
use App\Modelos\AntecedentesTransfusiones;
use App\Modelos\EfectividadFarmacologicas;
use App\Modelos\EstratificacionFramingham;
use App\Modelos\AntecedenteHospitalizacion;
use App\Modelos\OtrosAntecedentesFamiliare;
use App\Modelos\BiomicroscopiaOftalmoscopia;
use App\Modelos\GestacionActualGinecologico;
use App\Modelos\AdherenciaFarmacoterapeutica;
use App\Modelos\AntedecentesFarmacoterapeutico;
use App\Modelos\Paraclinico;
use App\Modelos\RegistroLaboratorios;
use Illuminate\Http\Response as HttpResponse;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;
use Rap2hpoutre\FastExcel\FastExcel;

class HistoriaController extends Controller
{
    public function atenderPaciente(Request $request)
    {
        $atender = citapaciente::where('id', $request->cita_paciente_id)->first();
        if ($request->Especialidad == 'Optometria') {
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                    $atender->update([
                        'Datetimeingreso' => now(),
                        'Estado_id' => 8,
                        'Tipocita_id' => 17
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Oftalmologia') {
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 12
            ]);
            break;
        }
        }else if ($request->Especialidad == 'Oncologia') {
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 7
            ]);
        }else if ($request->Especialidad == 'Quimica Farmacologica') {
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 18
            ]);
        }else if ($request->salud_ocupacional == 'Psicologia') {
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 13
            ]);
        }else if ($request->salud_ocupacional == 'Voz') {
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 14
            ]);
        }else if ($request->salud_ocupacional == 'Visiometria') {
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 15
            ]);
        }else if ($request->salud_ocupacional == 'Consulta Medica') {
            $atender->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 16
            ]);
        }else if ($request->Especialidad == 'Enfermeria Sede' || $request->Especialidad == 'Enfermeria'){
            switch ($request->tipoAgenda){
            case "5":
                $atender->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 3 // Procedimientos Menores
                ]);
                break;
            case "27":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "28":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "39":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "42":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "53":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "133":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "429":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "648":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
            case "443":
                $atender->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $atender->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 62
                ]);
                break;
            }
        }else if ($request->Especialidad == 'Auxiliar De Enfermeria'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
            default:
                $atender->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 67
                ]);
                break;
            }
        }else if ($request->Especialidad == 'Medicina General'){
            switch ($request->tipoAgenda){
                case "3":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 84 //Recien Nacido
                    ]);
                    break;
                case "23":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 81 //Control Prenatal
                    ]);
                    break;
                case "536":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 74 //Grupales RCV
                    ]);
                    break;
                case "63":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 75 //Primera Infancia
                    ]);
                    break;
                case "26":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 76 //Consulta Infancia
                    ]);
                    break;
                case "13":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 77 //Consulta Adolencencia
                    ]);
                    break;
                case "35":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 78 //Consulta Joven
                    ]);
                    break;
                case "501":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 79 //Consulta Adultes
                    ]);
                    break;
                case "9":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 79 //Consulta Adultes
                    ]);
                    break;
                case "16":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 80 //Consulta Vejez
                    ]);
                    break;
                    case "526":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 80 //Consulta Vejez
                        ]);
                        break;
                case "20":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 82 //Planificacion Familiar
                    ]);
                    break;
                case "4":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 74 //grupales Rcv
                    ]);
                    break;
                    case "181":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 74 //grupales Rcv
                        ]);
                        break;
                default:
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 8
                    ]);
                    break;
            }
        }else if ($request->Especialidad == 'Medico Experto Salud Mental'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 19
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medico Experto Reumatologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 20
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medico Experto Anticoagulados'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 21
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medico Experto Nefroproteccion'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 22
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medico Experto Respiratorios'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 23
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medico Experto Trasmisibles Cronicas'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 24
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Psiquiatria'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 25
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Neurologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 26
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cardiologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 27
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Ginecologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 28
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Obstetricia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 29
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medicina Interna'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 30
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Anestesiologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 31
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medicina Familiar'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 32
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Hematologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 33
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Nefrologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 34
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Otorrinolaringologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 35
                    ]);
                    break;
                }
        }else if ($request->Especialidad === 'Ortopedia y Traumatologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 36
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Endocrinologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 37
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugia Coloproctologica'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 38
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugia General'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 39
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Pediatria'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 40
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Dermatologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 41
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medicina Del Deporte'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 42
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Alergologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 43
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Mastologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 44
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Neumologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 45
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medicina Del Dolor'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 46
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Toxicologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 47
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Fisiatria'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 48
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Urologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 49
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medicina Alternativa'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 50
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Neurocirugia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 51
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Infectologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 52
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Reumatologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 53
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Electrofisiologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 54
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Fonoaudiologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 55
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Terapia Respiratoria'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 56
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Fisioterapia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 57
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Trabajo Social'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 58
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Psicologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 59
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Nutricion'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 60
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Odontologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 61
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Audiologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 63
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugía Cardiovascular'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 64
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugía Bariátrica'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 65
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugia Plastica'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 66
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Medico Experto RCV'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 68
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Neuropsicologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 69
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Radiologia'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 70
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugia Hepatobiliar'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 71
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugia Columna Vertebral'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 72
                    ]);
                    break;
                }
        }else if ($request->Especialidad == 'Cirugia Cabeza Y Cuello'){
            switch ($request->tipoAgenda){
                case "5":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores
                    ]);
                    break;
                case "27":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "28":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "39":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "42":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "53":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "133":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "429":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "648":
                        $atender->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 3 // Procedimientos Menores
                        ]);
                        break;
                case "443":
                    $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                    ]);
                    break;
                default:
                $atender->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 73
                    ]);
                    break;
                }
        }else {
                $atender->update([
                    'Datetimeingreso' => now(),
                    'Estado_id' => 8,
                    'Tipocita_id' => 8
                ]);
        }
        $cupOrden = CupsordenCitapaciente::where('citapaciente_id', $request->cita_paciente_id)->first();
        if($cupOrden){
            Cuporden::where('id', $cupOrden->cupordens_id)
            ->update([
                'atendida' => 1
            ]);
        }
        return response()->json([
            'message' => 'Inicia la atención del paciente'
        ]);

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

    public function UserLogin()
    {
        $userLogin = auth()->user()->name . " " . auth()->user()->apellido;

        return response()->json($userLogin, 200);
    }

    public function saveMotivoConsulta(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Motivoconsulta'    => 'required|min:10',
            'Enfermedadactual'  => 'required|min:10|max:5000',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $motivo = citapaciente::find($request->cita_paciente_id);
        if ($motivo) {
            $motivo->update($request->except(['cita_paciente_id']));
            return response()->json([
                'message' => 'Motivo Guardado con exito!'
            ], 200);
        }
    }

    public function fetchMotivoConsulta($cita_paciente_id)
    {
        $motivo = citapaciente::find($cita_paciente_id, ['Motivoconsulta', 'Enfermedadactual', 'Resultayudadiagnostica', 'intencion',
        'tratamientoncologico','ncirujias','iniciocirujia','fincirujia',
        'inicioradioterapia','finradioterapia','nsesiones','cirujiaoncologica','recibioradioterapia']);
        return response()->json($motivo, 200);
    }

    public function saveRevisionSistemas(Request $request)
    {

        $revision = citapaciente::find($request->cita_paciente_id);
        if ($revision) {
            $revision->update($request->except(['cita_paciente_id']));
            return response()->json([
                'message' => 'Revision por sistema Guardado con exito!'
            ], 200);
        }
    }

    public function fetchRevisionSistemas($cita_paciente_id)
    {
        $revision = citapaciente::find($cita_paciente_id, [
            'checkboxOftalmologico',
            'Oftalmologico',
            'checkboxGenitourinario',
            'Genitourinario',
            'checkboxOtorrinoralingologico',
            'Otorrinoralingologico',
            'checkboxLinfatico',
            'Linfatico',
            'checkboxOsteomioarticular',
            'Osteomioarticular',
            'checkboxNeurologico',
            'Neurologico',
            'checkboxCardiovascular',
            'Cardiovascular',
            'checkboxTegumentario',
            'Tegumentario',
            'checkboxRespiratorio',
            'Respiratorio',
            'checkboxEndocrinologico',
            'Endocrinologico',
            'checkboxGastrointestinal',
            'Gastrointestinal',
            'checkboxNorefiere',
            'Norefiere',
            'observaciones_generales',
            'TegumentarioSiNo',
            'FlujoVaginal',
            'SintomaticoRespiratorio',
        ]);
        return response()->json($revision, 200);
    }

    public function saveGinecologico(Request $request)
    {
        $Ginecologicos = AntecedentesGinecologico::where('citapaciente_id',$request->citapaciente_id)->first();
        if (!$Ginecologicos) {
            $request['usuario_id'] = auth()->user()->id;
            AntecedentesGinecologico::create($request->all());
            return response()->json([
                'message' => 'Ginecologicos guardados con exito!'
            ], 200);
        }else {
            $Ginecologicos->update($request->except(['cita_paciente_id']));
            return response()->json([
                'message' => 'Ginecologicos actualizados con exito!'
            ], 200);
        }
    }

    public function fetchGinecologico($cita_paciente_id)
    {
        $gineco = AntecedentesGinecologico::where('citapaciente_id', $cita_paciente_id)->first();
        return response()->json($gineco, 200);
    }

    public function saveGestanteGinecologico(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
       GestanteGinecologico::create($request->all());
       return response()->json([
            'message' => 'Gestante guardado con exito!'
       ]);
    }

    public function fetchGestanteGinecologico($paciente_id)
    {
        $gestantes = GestanteGinecologico::select(['gestante_ginecologicos.id','gestante_ginecologicos.patologias',
                                                'gestante_ginecologicos.presente','gestante_ginecologicos.fecha_patologia',
                                                'u.name'])
        ->join('users as u', 'gestante_ginecologicos.usuario_id', 'u.id')
        ->where('gestante_ginecologicos.paciente_id', $paciente_id)
        ->orderBy('gestante_ginecologicos.id', 'desc')
        ->get();
        return response()->json($gestantes, 200);
    }

    public function saveGestacionGinecologico(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
       GestacionActualGinecologico::create($request->all());
       return response()->json([
            'message' => 'Gestante guardado con exito!'
       ]);
    }

    public function fetchGestacionGinecologico($paciente_id)
    {
        $gestantes = GestacionActualGinecologico::select(['gestacion_actual_ginecologicos.id','gestacion_actual_ginecologicos.patologias',
                                                'gestacion_actual_ginecologicos.presente','gestacion_actual_ginecologicos.created_at',
                                                'u.name'])
        ->join('users as u', 'gestacion_actual_ginecologicos.usuario_id', 'u.id')
        ->where('gestacion_actual_ginecologicos.paciente_id', $paciente_id)
        ->orderBy('gestacion_actual_ginecologicos.id', 'desc')
        ->get();
        return response()->json($gestantes, 200);
    }

    public function saveAntecedentesVacunales(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        AntecedenteVacunale::create($request->all());
       return response()->json([
            'message' => 'Antecedentes vacunales guardado con exito!'
       ]);
    }

    public function fetchEsquemaVacunacion($paciente_id)
    {
        $vacunas = AntecedenteVacunale::select([
            'antecedente_vacunales.id','antecedente_vacunales.vacunas',
            'antecedente_vacunales.dosis','antecedente_vacunales.fecha_dosis',
            'antecedente_vacunales.laboratorio',
            'antecedente_vacunales.created_at','u.name'])
        ->join('users as u', 'antecedente_vacunales.usuario_id', 'u.id')
        ->where('antecedente_vacunales.paciente_id', $paciente_id)
        ->orderBy('antecedente_vacunales.id', 'desc')
        ->get();
        return response()->json($vacunas, 200);
    }

    public function saveAntecedentesFamiliares(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        $antecedenteFamiliares = AntecedenteFamiliare::create($request->all());
        return response()->json([
            'message' => 'Antecedente familiar guardado con exito!'
        ]);

    }

    public function fetcAntecedentesFamiliares($paciente_id)
    {
        $familiares = AntecedenteFamiliare::select([
            'antecedente_familiares.id','antecedente_familiares.patologias','antecedente_familiares.tipo_cancer',
            'antecedente_familiares.tipo_trastorno_mental','antecedente_familiares.tipo_transmision_sexual',
            'tipo_enfermedad_autoinmune','antecedente_familiares.otro_patologia_cancer','antecedente_familiares.otro_enfermedad_autoinmune',
            'otro_patologia_sexual','antecedente_familiares.parentesco','antecedente_familiares.fecha_diagnostico','antecedente_familiares.Fallecio',
            'antecedente_familiares.created_at', 'u.name'
        ])
        ->join('users as u', 'antecedente_familiares.usuario_id', 'u.id')
        ->where('paciente_id', $paciente_id)
        ->orderBy('antecedente_familiares.created_at', 'desc')
        ->get();
        return response()->json($familiares, 200);
    }

    public function saveOtrosAntecedentesFamiliares(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        OtrosAntecedentesFamiliare::create($request->all());
        return response()->json([
            'Message' => 'Se agrego otro antecedente familiar con exito!'
        ]);
    }

    public function saveAnteHospitalizacion(Request $request)
    {
        $antHospitalizacion = AntecedenteHospitalizacion::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$antHospitalizacion) {
            AntecedenteHospitalizacion::create($request->all());
            return response()->json([
                'message' => 'Antecedente hospitalización guardo con exito!'
            ]);
        } else {
            $antHospitalizacion->update($request->except(['citapaciente_id']));
            return response()->json([
                'message' => 'Antecedente hospitalizacion actulizado con exito!'
            ]);
        }
    }

    public function fetchAnteHospitalizacion($cita_paciente_id)
    {
        $antHospitalizacion = AntecedenteHospitalizacion::where('citapaciente_id', $cita_paciente_id)->first();
        return response()->json($antHospitalizacion, 200);
    }

    public function saveRedesApoyo(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        RedesApoyo::create($request->all());
        return response()->json([
            'message' => 'Redes de apoyo guardado con exito!'
        ]);

    }

    public function fetchRedesApoyo($cita_paciente_id)
    {
        $redesapoyo = RedesApoyo::select([
                    'redes_apoyos.id','redes_apoyos.redes_apoyo','redes_apoyos.sino',
                    'redes_apoyos.cual_club','redes_apoyos.observacion_club', 'u.name'])
            ->join('users as u', 'redes_apoyos.usuario_id', 'u.id')
            ->where('redes_apoyos.citapaciente_id', $cita_paciente_id)
            ->orderBy('redes_apoyos.id', 'desc')
            ->get();
        return response()->json($redesapoyo, 200);
    }

    public function saveApgarFamiliar(Request $request)
    {
        $apgar =  ApgarFamiliar::where('citapaciente_id', $request->citapaciente_id)->first();
        $request['usuario_id'] = auth()->user()->id;
        $request['valor_ayuda_familia'] = $request->ayuda_familia;
        $request['valor_familia_habla_con_usted'] = $request->familia_habla_con_usted;
        $request['valor_cosas_nuevas'] = $request->cosas_nuevas;
        $request['valor_le_gusta_familia_hace'] = $request->le_gusta_familia_hace;
        $request['valor_le_gusta_familia_comparte'] = $request->le_gusta_familia_comparte;
        if (!$apgar) {
            ApgarFamiliar::create($request->all());
            return response()->json([
                'message' => 'Apgar familiar guardado con exito!'
            ]);
        } else {
            $apgar->update($request->all());
            return response()->json([
                'message' => 'Apgar familiar actualizado con exito!'
            ]);
        }
    }

    public function fetchApgarFamiliar($paciente_id)
    {
        $apgarFamiliar = ApgarFamiliar::select([
                    'apgar_familiars.id',
                    'apgar_familiars.ayuda_familia',
                    'apgar_familiars.familia_habla_con_usted',
                    'apgar_familiars.cosas_nuevas',
                    'apgar_familiars.le_gusta_familia_hace',
                    'apgar_familiars.le_gusta_familia_comparte',
                    'apgar_familiars.valor_ayuda_familia',
                    'apgar_familiars.valor_familia_habla_con_usted',
                    'apgar_familiars.valor_cosas_nuevas',
                    'apgar_familiars.valor_le_gusta_familia_hace',
                    'apgar_familiars.valor_le_gusta_familia_comparte',
                    'apgar_familiars.resultado',
                     'u.name'])
            ->join('users as u', 'apgar_familiars.usuario_id', 'u.id')
            ->where('apgar_familiars.paciente_id', $paciente_id)
            ->orderBy('apgar_familiars.id', 'desc')
            ->get();
        return response()->json($apgarFamiliar, 200);
    }

    public function fetchlistaApgar($citapaciente_id)
    {
        $apgarFamiliar = ApgarFamiliar::select([
                    'apgar_familiars.valor_ayuda_familia','apgar_familiars.valor_familia_habla_con_usted',
                    'apgar_familiars.valor_cosas_nuevas','apgar_familiars.valor_le_gusta_familia_hace',
                    'apgar_familiars.valor_le_gusta_familia_comparte'])
            ->join('users as u', 'apgar_familiars.usuario_id', 'u.id')
            ->where('apgar_familiars.citapaciente_id', $citapaciente_id)
            ->first();
        return response()->json($apgarFamiliar, 200);
    }

    public function saveFamiliograma(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        Familiograma::create($request->all());
        return response()->json([
            'message' => 'Familiograma guardado con exito!'
        ]);

    }

    public function fetchFamiliograma($paciente_id)
    {
        $familiograma = Familiograma::select(['familiogramas.id','familiogramas.vinculos','familiogramas.relacion_afectiva','familiogramas.problemas_salud',
                                            'familiogramas.cual_familiograma','familiogramas.observaciones_familiograma', 'u.name'])
        ->join('users as u', 'familiogramas.usuario_id', 'u.id')
        ->where('paciente_id', $paciente_id)->orderBy('familiogramas.id','desc')->get();
        return response()->json($familiograma, 200);
    }

    public function saveBiopsicosocial(Request $request)
    {
        $biosicosocial = AntecedenteBiopsicosocial::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$biosicosocial) {
            $request['usuario_id'] = auth()->user()->id;
            AntecedenteBiopsicosocial::create($request->all());
            return response()->json([
                'message' => 'Antecedentes Biopsicosicial guardado con exito!'
            ]);
        } else {
            $biosicosocial->update($request->except(['citapaciente_id', 'usuario_id']));
            return response()->json([
                'message' => 'Antecedente Biopsicosicial actulizado con exito!'
            ]);
        }


    }

    public function fetchBiopsicosocial($paciente_id)
    {
        $biosicosocial = AntecedenteBiopsicosocial::select(['antecedente_biopsicosocials.checkboxOrientacionSex','antecedente_biopsicosocials.checkboxIdentidadGenero','antecedente_biopsicosocials.checkboxIncioSexual',
                    'antecedente_biopsicosocials.checkboxNumeroRelaciones','antecedente_biopsicosocials.checkboxUsoAnticonceptivo','antecedente_biopsicosocials.checkboxEdadMenarquia','antecedente_biopsicosocials.checkboxEdadEsperma','antecedente_biopsicosocials.checkboxActivoSexual',
                    'antecedente_biopsicosocials.checkboxDificultadesRelaciones','antecedente_biopsicosocials.checkboxConocimientoIts','antecedente_biopsicosocials.checkboxTransmisionSexual','antecedente_biopsicosocials.checkboxDerechosSexuales',
                    'antecedente_biopsicosocials.checkboxPreocupacionSalud','antecedente_biopsicosocials.checkboxProblemasDesarroInfantil','antecedente_biopsicosocials.checkboxVictimaGenero','antecedente_biopsicosocials.checkboxVictimaIdentidadgenero',
                    'antecedente_biopsicosocials.orientacion_sex','antecedente_biopsicosocials.identidad_genero','antecedente_biopsicosocials.inicio_sexual','antecedente_biopsicosocials.numero_relaciones','antecedente_biopsicosocials.fecha_inciosexual','antecedente_biopsicosocials.activo_sexual',
                    'antecedente_biopsicosocials.edad_esperma','antecedente_biopsicosocials.edad_menarquia','antecedente_biopsicosocials.uso_anticonceptivos','antecedente_biopsicosocials.dificultades_relaciones','antecedente_biopsicosocials.conocimiento_its',
                    'antecedente_biopsicosocials.trasnmision_sexual','antecedente_biopsicosocials.derechos_sexuales','antecedente_biopsicosocials.victima_identidadgenero','antecedente_biopsicosocials.victima_genero','antecedente_biopsicosocials.problemas_desarrolloinfantil',
                    'antecedente_biopsicosocials.preocupacion_salud','antecedente_biopsicosocials.tipo_victimagenero','antecedente_biopsicosocials.tipo_victima_violencia_genero','antecedente_biopsicosocials.checkboxDuermeBien','antecedente_biopsicosocials.checkboxCuantasHoras',
                    'antecedente_biopsicosocials.checkboxTiempoJuego','antecedente_biopsicosocials.checkboxActivadFisica','antecedente_biopsicosocials.duerme_bien','antecedente_biopsicosocials.cuantas_horas','antecedente_biopsicosocials.tiempo_juego','antecedente_biopsicosocials.actividad_fisica',
                    'antecedente_biopsicosocials.checkboxBañoDia','antecedente_biopsicosocials.cuantas_baño','antecedente_biopsicosocials.checkboxCuidadoBucal','antecedente_biopsicosocials.cuidado_bucal','antecedente_biopsicosocials.checkboxControlVesical','antecedente_biopsicosocials.cuidado_vesical',
                    'antecedente_biopsicosocials.checkboxCaracteristicasOrina','antecedente_biopsicosocials.caracteristicas_orina','antecedente_biopsicosocials.checkboxControlRectal','antecedente_biopsicosocials.cuidado_rectal',
                    'antecedente_biopsicosocials.checkboxCaracteristicasdeposiciones','antecedente_biopsicosocials.caracteristicas_deposiciones','antecedente_biopsicosocials.checkboxExposicionTv',
                    'antecedente_biopsicosocials.exposicion_tv','antecedente_biopsicosocials.tipo_anticonceptivos',
                    'antecedente_biopsicosocials.violencia','antecedente_biopsicosocials.checkboxSignosViolencia',
                    'antecedente_biopsicosocials.checkboxPresenciaMutilacion', 'antecedente_biopsicosocials.PresenciaMutilacion',
                    'antecedente_biopsicosocials.checkboxMatrimonioInfantil','antecedente_biopsicosocials.MatrimonioInfantil',
                    'antecedente_biopsicosocials.identidad_generoTransgenero','antecedente_biopsicosocials.Espermarquia',
                    'antecedente_biopsicosocials.Menarquia','antecedente_biopsicosocials.checkboxSufridoIts',
                    'antecedente_biopsicosocials.CualIts', 'antecedente_biopsicosocials.TratamientoIts',
                    'antecedente_biopsicosocials.fecha_enfermedadIts','antecedente_biopsicosocials.Descripciondificultad',
                    'antecedente_biopsicosocials.checkboxCicloMenstrual','antecedente_biopsicosocials.Ciclos','antecedente_biopsicosocials.trabaja',
                    'antecedente_biopsicosocials.iglesia','antecedente_biopsicosocials.ClubDeportivo','antecedente_biopsicosocials.Amigos',
                    'antecedente_biopsicosocials.Asiste_colegio','antecedente_biopsicosocials.Comparte_Vecinos',
                    'antecedente_biopsicosocials.Club_Social', 'antecedente_biopsicosocials.Cual_club','antecedente_biopsicosocials.Observacion_club',
                     'antecedente_biopsicosocials.Ayuda_Familia','antecedente_biopsicosocials.Habla_Familia','antecedente_biopsicosocials.Cosas_nuevas',
                    'antecedente_biopsicosocials.Familia_Cuando', 'antecedente_biopsicosocials.Familia_Tiempo',
                    'antecedente_biopsicosocials.CiclosMnestruales','antecedente_biopsicosocials.Resultado','antecedente_biopsicosocials.Vinculos',
                    'antecedente_biopsicosocials.Relacion','antecedente_biopsicosocials.problemasSalud','antecedente_biopsicosocials.cualsalud',
                    'antecedente_biopsicosocials.Observacion_Salud','antecedente_biopsicosocials.TipoFamilia','antecedente_biopsicosocials.cuantas_familia',
                    'antecedente_biopsicosocials.hijos_conforman','antecedente_biopsicosocials.actividad_laboral','antecedente_biopsicosocials.alteraciones',
                    'antecedente_biopsicosocials.historia_repro','antecedente_biopsicosocials.Paridad', 'antecedente_biopsicosocials.cesarea',
                    'antecedente_biopsicosocials.preeclampsia','antecedente_biopsicosocials.Abortos_Recurrentes', 'antecedente_biopsicosocials.Hemorragia_Pos',
                    'antecedente_biopsicosocials.Peso_recien','antecedente_biopsicosocials.Mortalidad_fetal','antecedente_biopsicosocials.Trabajo_parto',
                    'antecedente_biopsicosocials.Cirugia_Gineco','antecedente_biopsicosocials.Renal','antecedente_biopsicosocials.Diabetes_Gestacional',
                    'antecedente_biopsicosocials.Hemorragia','antecedente_biopsicosocials.semanas_hemorragia','antecedente_biopsicosocials.Anemia',
                    'antecedente_biopsicosocials.valor_anemia','antecedente_biopsicosocials.Embarazo_prolongado','antecedente_biopsicosocials.semanas_prolongado',
                    'antecedente_biopsicosocials.Hiper_arterial','antecedente_biopsicosocials.Polihidramnios','antecedente_biopsicosocials.Embarazo_multiple',
                    'antecedente_biopsicosocials.Presente_frente','antecedente_biopsicosocials.Isoinmunización','antecedente_biopsicosocials.Ansiedad_severa',
                    'antecedente_biopsicosocials.familiar_inadecuado','antecedente_biopsicosocials.Aviso',
                    'antecedente_biopsicosocials.Resultadopre','antecedente_biopsicosocials.tipodecisionesSexRep','antecedente_biopsicosocials.sufrido_its',
                    'antecedente_biopsicosocials.checkboxDecisionesSexRep','antecedente_biopsicosocials.decisionesSexRep','antecedente_biopsicosocials.descripcion_actividad','antecedente_biopsicosocials.Diabetes_Preconcepcional',
                    'antecedente_biopsicosocials.rendimiento','antecedente_biopsicosocials.aprendizaje','antecedente_biopsicosocials.actitudAula','antecedente_biopsicosocials.relacionamiento',
                    'antecedente_biopsicosocials.testAlteraciones'
                    ])
        ->where('paciente_id', $paciente_id)->orderBy('antecedente_biopsicosocials.id','desc')->first();
        return response()->json($biosicosocial, 200);
    }

    public function saveHabitosToxicos(Request $request)
    {
        $habitosToxicos = HabitosToxicos::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$habitosToxicos) {
            $request['usuario_id'] = auth()->user()->id;
            HabitosToxicos::create($request->all());
            return response()->json([
                'message' => 'Habito toxicos guardado con exito!'
            ]);
        } else {
            $habitosToxicos->update($request->except(['citapaciente_id', 'usuario_id']));
            return response()->json([
                'message' => 'Habito toxicos actulizado con exito!'
            ]);
        }
    }

    public function fetchHabitosToxicos($paciente_id)
    {
        $habitosToxicos = HabitosToxicos::select([
            'habitos_toxicos.checkboxTabaquismo','habitos_toxicos.presente_tabaquismo','habitos_toxicos.checkboxAlcohol',
            'habitos_toxicos.presente_alcohol','habitos_toxicos.checkboxPsicofarmacos','habitos_toxicos.presente_psicofarmacos',
            'habitos_toxicos.tipo_tabaquismo','habitos_toxicos.tipo_alcohol','habitos_toxicos.tipo_psicofarmacos',
            'habitos_toxicos.n_cigarrillo','habitos_toxicos.frecuencia_alcohol','habitos_toxicos.frecuencia_psicofarmacos',
            'habitos_toxicos.paquetes_cigarrillo','habitos_toxicos.fecha_alcohol','habitos_toxicos.fecha_psicofarmacos',
            'habitos_toxicos.fecha_cigarrillo'
        ])
        ->where('habitos_toxicos.paciente_id', $paciente_id)->orderBy('habitos_toxicos.id','desc')->first();
        return response()->json($habitosToxicos, 200);
    }

    public function saveExamenFisico(Request $request)
    {
        if($request->especialidad != 'Quimica Farmacologica'){
            $validate = Validator::make($request->all(), [
                'Condiciongeneral' => 'required|min:5',
            ]);
            if ($validate->fails()) {
                $errores = $validate->errors();
                return response()->json($errores, 422);
            }
        }
        $examenFisico = Examenfisico::where('Citapaciente_id', $request->Citapaciente_id)->first();
        // if($examenFisico != null || $request->especialidad !='Quimica Farmacologica'){
        //     if($examenFisico->Peso == null){
        //             return response()->json([
        //             'error' =>'No a guardado el PESO y la TALLA!'
        //         ],422);
        //     }
        // }
        // else{
        //     if($request->especialidad =='Quimica Farmacologica'){
        //         return response()->json([
        //             'message' => 'Examen fisico visitado con exito!'
        //         ]);
        //     }
            if (!$examenFisico) {
                Examenfisico::create($request->all());
                return response()->json([
                    'message' => 'Examen fisico creado con exito!'
                ]);
            } else {
                $examenFisico->update($request->except('Citapaciente_id'));
                return response()->json([
                    'message' => 'Examen fisico actualizado con exito'
                ]);
            }
        // }

    }

    public function saveAntropometricas(Request $request)
    {
        $examenFisico = Examenfisico::where('Citapaciente_id', $request->Citapaciente_id)->first();
        if (!$examenFisico) {
            Examenfisico::create($request->all());
            return response()->json([
                'message' => 'Medida antropometrica creada con exito!'
            ]);
        } else {
            $examenFisico->update($request->except('Citapaciente_id'));
            return response()->json([
                'message' => 'Examen fisico actualizado con exito'
            ]);
        }
    }

    public function saveSignosVitales(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Presionsistolica'    => 'required',
            'Presiondiastolica'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $examenFisico = Examenfisico::where('Citapaciente_id', $request->Citapaciente_id)->first();
        if (!$examenFisico) {
            Examenfisico::create($request->all());
            return response()->json([
                'message' => 'Signos vitales creados con exito!'
            ]);
        } else {
            $examenFisico->update($request->except('Citapaciente_id', 'Peso', 'Talla', 'Imc', 'Clasificacion', 'Perimetroabdominal', 'Perimetrocefalico'));
            return response()->json([
                'message' => 'Signos vitales actualizados con exito'
            ]);
        }
    }

    public function fetchAntropoSignos($paciente_id)
    {
        $antropo = Examenfisico::select([
            'examenfisicos.id','examenfisicos.Peso', 'examenfisicos.Talla', 'examenfisicos.Imc',
            'examenfisicos.Clasificacion', 'examenfisicos.Perimetroabdominal', 'examenfisicos.Perimetrocefalico',
            'examenfisicos.Posicion', 'examenfisicos.Lateralidad', 'examenfisicos.Presionsistolica',
            'examenfisicos.Presiondiastolica','examenfisicos.Presionarterialmedia', 'examenfisicos.ISC',
            'examenfisicos.Frecucardiaca','examenfisicos.Pulsos','examenfisicos.Frecurespiratoria', 'examenfisicos.Temperatura',
            'examenfisicos.Saturacionoxigeno','FiO','p.Primer_Nom','examenfisicos.created_at',
            'examenfisicos.gananciaesperada','examenfisicos.circunferencia_brazo',
            'examenfisicos.circunferencia_pantorrilla','examenfisicos.peso_talla', 'examenfisicos.clasificacion_peso_talla', 'examenfisicos.talla_edad',
            'examenfisicos.clasificacion_talla_edad', 'examenfisicos.cefalico_edad',
            'examenfisicos.clasificacion_cefalico_edad', 'examenfisicos.imc_edad','examenfisicos.clasificacion_imc_edad',
            'examenfisicos.peso_edad','examenfisicos.clasificacion_peso_edad'
        ])
        ->leftjoin('Pacientes as p', 'examenfisicos.paciente_id', 'p.id')
        ->where('examenfisicos.paciente_id', $paciente_id)
        ->orderBy('examenfisicos.id','desc')
        ->get();

        return response()->json($antropo, 200);
    }

    public function fetchExamenFisicos($citapaciente_id)
    {
        $fisicos = Examenfisico::select(['Condiciongeneral','Cabezacuello','Ojosfondojos',
        'Agudezavisual','Cardiopulmonar','Abdomen','Osteomuscular','Extremidades',
        'Pulsosperifericos','Neurologico','Reflejososteotendinos','Pielfraneras',
        'Genitourinario','Examenmama','Tactoretal','Examenmental','checkboxCcc','cabeza','Cara','Ojos','Selccc',
        'AgudezavisualDer','AgudezavisualIzq','Conjuntiva','Esclera','OjosfondojosAnt', 'OjosfondojosPost','Nariz',
        'Tabique', 'Cornetes','Oidos','AuricularDer','AuricularIzq','ConductoDer','MembranaTim','integra','perforacion',
        'TubosVen','Maxilar','LabiosComisura','MejillaCarrillo','CavidadOral','ArticulaciónTemporo', 'EstructurasDentales',
        'checkboxTorax','Torax','checkboxDesTorax','Mamas','Pectorales','RejaCostal','checkboxDesToraxPos','RejaCostalPos',
        'DesvCol','checkboxCardioPulmonar','Pulmones','Cardiacos','checkboxAbdomen','AlturaUterina','ActividadUterina',
        'FrecuenciaCardiacaFetal','movimientosFetales','RuidosPlacentarios','checkboxManiobra','PresentacionFetal',
        'NumFetos', 'PosUtero','Tacto','checkboxGenitoUrinario','Maculino','Testiculos','Escroto','Prepucio','Cordon',
        'TactoRectalHom','Femenino','Especuloscopia','TactoVag','Involucion','SangradoUter','ExpulTapon','Dilatacioncuello',
        'Borramiento','Estacion','loquios','Tactorecfem','TemTono','checkboxPerine','DesgarroPerine', 'Episiorragia',
        'checkboxExtremidades','checkboxSistemaNervioso','SistemaNervioso','ParesCraneales','EvaluacionMarcha',
        'EvaluacionTonoMuscular','EvaluacionFuerza','EvaluacionEsfera','checkboxPielFaneras', 'PielFaneras',
        'checkboxSistemaOsteo','SistemaOsteo','Cuello','cuidado','escalaDesarrollo', 'autismo','DorsoFetal',
        'resultadoVale', 'actividades', 'comportamientos',  'autoeficacia','independencia',
        'actividadesProposito', 'controlInterno', 'funcionesEjecutivas','Identidad','valoracionIdentidad',
        'Autonomia','valoracionAutonomia','visualnino','problemaOido','escuchaBien','factoresOido',
        'riesgosMentalesNinos','lesionesAutoinflingidas', 'alteracionesGenitales',
        'tannerMasculino','alteracionesGenitalesExternos','tannerFemenino', 'tannerVello','motricidadGruesa',
        'motricidadFina','audicionLenguaje', 'personalSocial','funciones','desempeñocomunicativo', 'violencia_mental',
        'violencia_conflicto', 'violencia_sexual', 'rendimiento_escolar','test_figura_humana','columna_vertebral','examen_mental'
        ])
        ->where('examenfisicos.Citapaciente_id', $citapaciente_id)
        ->first();

        return response()->json($fisicos, 200);
    }

    public function saveAntFarmaco(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'observacionAlergia'    => 'required',

        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntecedenteFarmacologico::create([
            'utiempo' => $request['utiempo'],
            'frecuencia' => $request['frecuencia'],
            'alergia' => $request['alergia'],
            'observacionAlergia' => $request['observacionAlergia'],
            'detallearticulo_id' => $request['data']['id'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente Farmacologico guardado con exito!'
        ], 200);
    }

    public function savecronicos(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tratamientos_cronicos'   => 'required',
            'descripcion_tratamientos_cronicos'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntedecentesFarmacoterapeutico::create([
            'tratamientos_cronicos' => $request['tratamientos_cronicos'],
            'descripcion_tratamientos_cronicos' => $request['descripcion_tratamientos_cronicos'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente guardado con exito!'
        ], 200);
    }

    public function getCronicos(Request $request)
    {
        $cronico = AntedecentesFarmacoterapeutico::select(['antedecentes_farmacoterapeuticos.id','antedecentes_farmacoterapeuticos.created_at',
        'antedecentes_farmacoterapeuticos.tratamientos_cronicos','antedecentes_farmacoterapeuticos.descripcion_tratamientos_cronicos','u.name as medico'])
        ->join('users as u', 'antedecentes_farmacoterapeuticos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antedecentes_farmacoterapeuticos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antedecentes_farmacoterapeuticos.tratamientos_cronicos')
        ->where('antedecentes_farmacoterapeuticos.estado_id', 1)
        ->get();
        return response()->json($cronico, 200);
    }

    public function cronico(AntedecentesFarmacoterapeutico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'tratamiento eliminado con exito!',
        ], 200);
    }

    public function saveBiologicos(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tratamientos_biologicos'   => 'required',
            'descripcion_tratamientos_biologicos'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntedecentesFarmacoterapeutico::create([
            'tratamientos_biologicos' => $request['tratamientos_biologicos'],
            'descripcion_tratamientos_biologicos' => $request['descripcion_tratamientos_biologicos'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente guardado con exito!'
        ], 200);
    }

    public function getBiologicos(Request $request)
    {
        $cronico = AntedecentesFarmacoterapeutico::select(['antedecentes_farmacoterapeuticos.id','antedecentes_farmacoterapeuticos.created_at',
        'antedecentes_farmacoterapeuticos.tratamientos_biologicos','antedecentes_farmacoterapeuticos.descripcion_tratamientos_biologicos','u.name as medico'])
        ->join('users as u', 'antedecentes_farmacoterapeuticos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antedecentes_farmacoterapeuticos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antedecentes_farmacoterapeuticos.tratamientos_biologicos')
        ->where('antedecentes_farmacoterapeuticos.estado_id', 1)
        ->get();
        return response()->json($cronico, 200);
    }

    public function biologico(AntedecentesFarmacoterapeutico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'tratamiento eliminado con exito!',
        ], 200);
    }

    public function saveQuimio(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'quimioterapia'   => 'required',
            'descripcion_quimioterapia'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntedecentesFarmacoterapeutico::create([
            'quimioterapia' => $request['quimioterapia'],
            'descripcion_quimioterapia' => $request['descripcion_quimioterapia'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente guardado con exito!'
        ], 200);
    }

    public function getQuimio(Request $request)
    {
        $cronico = AntedecentesFarmacoterapeutico::select(['antedecentes_farmacoterapeuticos.id','antedecentes_farmacoterapeuticos.created_at',
        'antedecentes_farmacoterapeuticos.quimioterapia','antedecentes_farmacoterapeuticos.descripcion_quimioterapia','u.name as medico'])
        ->join('users as u', 'antedecentes_farmacoterapeuticos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antedecentes_farmacoterapeuticos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antedecentes_farmacoterapeuticos.quimioterapia')
        ->where('antedecentes_farmacoterapeuticos.estado_id', 1)
        ->get();
        return response()->json($cronico, 200);
    }

    public function quimio(AntedecentesFarmacoterapeutico $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'tratamiento eliminado con exito!',
        ], 200);
    }

    public function saveTraumaticos(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'traumaticos'   => 'required',
            'descripcion_traumaticos'  => 'required',
            'accidentes'   => 'required',
            'descripcion_accidentes'   => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteTraumatico = AntedecentesTraumaticos::create([
            'traumaticos' => $request['traumaticos'],
            'descripcion_traumaticos' => $request['descripcion_traumaticos'],
            'accidentes' => $request['accidentes'],
            'descripcion_accidentes' => $request['descripcion_accidentes'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente traumatico guardado con exito!'
        ], 200);
    }

    public function getTraumaticos(Request $request)
    {
        $traumatico = AntedecentesTraumaticos::select(['antedecentes_traumaticos.id','antedecentes_traumaticos.created_at',
        'antedecentes_traumaticos.traumaticos','antedecentes_traumaticos.descripcion_traumaticos','u.name as medico',
        'antedecentes_traumaticos.accidentes','antedecentes_traumaticos.descripcion_accidentes',
        ])
        ->join('users as u', 'antedecentes_traumaticos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antedecentes_traumaticos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->where('antedecentes_traumaticos.estado_id', 1)
        ->get();
        return response()->json($traumatico, 200);
    }

    public function traumatico(AntedecentesTraumaticos $id)
    {
        $id->update([
            'estado_id' => 2
        ]);
        return response()->json([
            'message' => 'antecedente eliminado con exito!',
        ], 200);
    }

    public function saveAntAlergicosAlimentos(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Alimneto'    => 'required',
            'observacionalimneto'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntecedenteFarmacologico::create([
            'Alimneto' => $request['Alimneto'],
            'observacionalimneto' => $request['observacionalimneto'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente alergico alimentario guardado con exito!'
        ], 200);
    }

    public function saveAntAlergicosAmbientales(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Ambientales'    => 'required',
            'observacionambiental'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntecedenteFarmacologico::create([
            'Ambientales' => $request['Ambientales'],
            'observacionambiental' => $request['observacionambiental'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente alergico alimentario guardado con exito!'
        ], 200);
    }

    public function saveAntAlergicosOtros(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'OtrasAlergias'    => 'required',
            'observacionotras'  => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $anteFarmacologico = AntecedenteFarmacologico::create([
            'OtrasAlergias' => $request['OtrasAlergias'],
            'observacionotras' => $request['observacionotras'],
            'Citapaciente_id' => $request['citaPaciente_id'],
            'paciente_id' => $request['paciente_id'],
            'usuario_id' => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Antecedente alergico alimentario guardado con exito!'
        ], 200);
    }

    public function getAntecedentesFarmacologia(Request $request)
    {
        $anteFarmacologia = AntecedenteFarmacologico::select(['antecedente_farmacologicos.id','antecedente_farmacologicos.created_at','antecedente_farmacologicos.utiempo','antecedente_farmacologicos.frecuencia',
        'antecedente_farmacologicos.alergia','antecedente_farmacologicos.observacionAlergia', 'd.Producto','u.name as medico'])
        ->join('users as u', 'antecedente_farmacologicos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antecedente_farmacologicos.Citapaciente_id')
        ->leftjoin('detallearticulos as d', 'antecedente_farmacologicos.detallearticulo_id', 'd.id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antecedente_farmacologicos.detallearticulo_id')
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->get();
        return response()->json($anteFarmacologia, 200);
    }

    public function getAntecedenteAlimentos(Request $request)
    {
        $anteAlimento = AntecedenteFarmacologico::select(['antecedente_farmacologicos.id','antecedente_farmacologicos.created_at',
        'antecedente_farmacologicos.Alimneto','antecedente_farmacologicos.observacionalimneto','u.name as medico'])
        ->join('users as u', 'antecedente_farmacologicos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antecedente_farmacologicos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antecedente_farmacologicos.Alimneto')
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->get();
        return response()->json($anteAlimento, 200);
    }

    public function  getAntecedenteOtros(Request $request)
    {
        $anteOtros = AntecedenteFarmacologico::select(['antecedente_farmacologicos.id','antecedente_farmacologicos.created_at',
        'antecedente_farmacologicos.OtrasAlergias','antecedente_farmacologicos.observacionotras','u.name as medico'])
        ->join('users as u', 'antecedente_farmacologicos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antecedente_farmacologicos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antecedente_farmacologicos.OtrasAlergias')
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->get();
        return response()->json($anteOtros, 200);
    }


    public function getAntecedenteAmbientales(Request $request)
    {
        $anteAbiental = AntecedenteFarmacologico::select(['antecedente_farmacologicos.id','antecedente_farmacologicos.created_at',
        'antecedente_farmacologicos.Ambientales','antecedente_farmacologicos.observacionambiental','u.name as medico'])
        ->join('users as u', 'antecedente_farmacologicos.usuario_id', 'u.id')
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antecedente_farmacologicos.Citapaciente_id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->whereNotNULL('antecedente_farmacologicos.Ambientales')
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->get();
        return response()->json($anteAbiental, 200);
    }

    public function getAntecedentesQuimico(Request $request)
    {
        $anteFarmacologia = Codesumi::select('codesumis.Nombre as Medicamento',
        DB::RAW("min(convert(date,o.Fechaorden)) as Fecha_orden"),'codesumis.unidadMedida','codesumis.Frecuencia',
        'd.Via','af.observacionAlergia','af.alergia','u.name as medico','u.apellido')
        ->join('detaarticulordens as d','d.codesumi_id','codesumis.id')
        ->join('ordens as o','o.id','d.Orden_id')
        ->join('cita_paciente as cp','cp.id','o.Cita_id')
        ->join('detallearticulos as de','de.Codesumi_id','codesumis.id')
        ->leftjoin('antecedente_farmacologicos as af','af.detallearticulo_id','de.id')
        ->leftjoin('users as u','u.id','af.usuario_id')
        ->where('cp.Paciente_id',$request->paciente_id)
        ->groupBy('codesumis.Nombre','codesumis.unidadMedida','codesumis.Frecuencia','d.Via',
        'af.observacionAlergia','af.alergia','u.name','u.apellido')
        ->get();
        return response()->json($anteFarmacologia, 200);
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

    public function savePorcentaje($cita_paciente_id, Request $request){

        $proceso = ProcesoHistoriaIntegral::where('citapaciente_id', $cita_paciente_id)->first();

        if($request->component){

            $porcentajeRequest = 100 / count($request->components);
            $porcentajeGlobal = $porcentajeRequest;

            if($proceso){

                if($proceso->PacienteComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->MotivoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->RevisionSistemasComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesPersonalesComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesTransfusiones == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesQuirurgicosComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesGinecologicosComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->EsquemaVacunacionComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesFamiliaresComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesHospitalizacionComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->RedesApoyoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ApgarFamiliarComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->FamiliogramaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesBiopsicosocialesComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->HabitosToxicosComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ResultadosLaboratorioComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ResultadosAyudasDxComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesFarmacologicosComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ExamenFisicoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedenteQuimicoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ImpresionDxComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->PlanCuidadoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->PlanManejoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AnamnesisPsicologiaOcupacionalComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesPersonalesOcupacionalesComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesFamiliaresOcupacionalesComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AreasPsicologiaOcupacionalComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ConductaOcupacionalComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AnamnesisVozOcupacionalComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->RespiracionVozComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->CualidadesVozComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ManejoPersonalVozComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ExamenFisicoVozComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AnamnesisVisiometriaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ExamenVisiometriaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AnamensisMedicaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesOcupacionalesMedicosComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->VacunasMedicasOcupacionalesComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->GinecoobstetricosMedicaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->HabitosMedicaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->RevisionSistemasMedicaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->CondicionesSaludMedicaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->ExamenFisicoMedicaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->EstiloVidaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->OftalmologiaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->OptometriaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->BiomiOftalmoscopiaComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->RefraSubjetivoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

                if($proceso->AntecedentesQuimicoFarmacologicoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->AntecedentesTraumaticosComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->AntecedentesFarmacologicoComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->EstadificacionRCVComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->PacientesSstComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->KarnofskiComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->EcogComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->EsasComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }
                if($proceso->IndiceBarthelComponent == 1){
                    $porcentajeGlobal += $porcentajeRequest;
                }

            }

            if( $porcentajeGlobal > 100){
                $porcentajeGlobal = 100;
            }
            $procesoHistoria = ProcesoHistoriaIntegral::updateOrCreate(
                ['citapaciente_id' => $cita_paciente_id],
                ['porcentaje' => $porcentajeGlobal,
                $request->component => 1]
            );

        }else{
            $procesoHistoria = $proceso;
        }

        return response()->json($procesoHistoria, 200);
    }

    public function saveAntecedentesPersonal(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        AntecedentesPersonale::create($request->all());
        return response()->json([
            'message' => 'Antecedente del paciente guardado con exito!'
        ]);
    }

    public function citaNoProgramada(Request $request, $paciente){
        $fechaHoy = Carbon::now();
        $fechaHoyFormato = $fechaHoy->format('Y-m-d');
        $cita_paciente = citapaciente::create([
            "user_medico_atiende"  => auth()->id(),
            "Cup_id"        => $request->Cup_id,
            "Paciente_id"   => $paciente,
            "Usuario_id"    => auth()->id(),
            "Ambito"        => 'Ambulatorio',
            'lugar_atencion'=> $request->lugar_atencion,
            'actividad_id' => $request->actividad,
            'especialidad_id' => $request->especialidad,
            'Tipocita_id' => 2,
            'Fecha_solicita' => $fechaHoyFormato,
            'Estado_id' => 8,
            'Datetimeingreso' => $fechaHoy,
        ]);
        $especialidad = Especialidade::where('id', '=', $request->especialidad)->first();
        return response()->json([$cita_paciente,$especialidad], 200);
    }

    public function fetchAntecedentePersonal(Request $request)
    {
        $getAntecedentesPersonales = AntecedentesPersonale::select('antecedentes_personales.id','antecedentes_personales.patologias','antecedentes_personales.tipo_cancer',
        'antecedentes_personales.tipo_asma','antecedentes_personales.tipo_epoc','antecedentes_personales.tipo_bronquitis_cronica',
        'antecedentes_personales.tipo_tuberculosis','antecedentes_personales.tipo_diabetes','antecedentes_personales.tipo_enfermedad_renal_cronica',
        'antecedentes_personales.tipo_trastorno_mental','antecedentes_personales.tipo_malnutricion','antecedentes_personales.tipo_enfermedad_tiroidea',
        'antecedentes_personales.tipo_enfermedades_trasmision_sexual','antecedentes_personales.tipo_enfermedades_autoinmunes',
        'antecedentes_personales.otro_patologia_cancer','antecedentes_personales.cual_discapacidad',
        'antecedentes_personales.otras_enfermedades_autoinmunes','antecedentes_personales.otra_enfermedades_trasmision_sexual',
        'antecedentes_personales.otro_patologia','antecedentes_personales.cual_enfermedad_genetica','antecedentes_personales.descripcion_otras_enfermedades_neurologicas',
        'antecedentes_personales.descripcion_enfermedad_o_accidente_laboral','antecedentes_personales.fecha_diagnostico', 'u.name as medico',
        'antecedentes_personales.created_at', 'antecedentes_personales.checkboxOtras_enfermedades')
        ->join('users as u', 'antecedentes_personales.usuario_id', 'u.id')
        ->where('antecedentes_personales.paciente_id', $request->paciente_id)
        ->get();
        return response()->json($getAntecedentesPersonales, 200);
    }

    public function saveLaboratorios(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        $resultado = ResultadosLaboratorio::create($request->except(["adjunto"]));

        if(isset($request['adjunto'])){
            $file = $request->file('adjunto');
            list ($nombre,$extension) = explode('.',$file->getClientOriginalName());
            $path = '/adjuntosResultadosLaboratorios/'.$request["Citapaciente_id"].'/'.$request["laboratorio"].$resultado->id.'.'.$extension;
            Storage::disk(config('filesystems.disksUse'))->put($path, fopen($file, 'r+'));
            $resultado->adjunto = $path;
            $resultado->save();
        }
        return response()->json([
            'message' => 'Resultado guardado con exito!'
        ], 200);
    }

    public function fetchLaboratorios($paciente_id)
    {
        $laboratorios = ResultadosLaboratorio::select(
            'resultados_laboratorios.laboratorio',
            'resultados_laboratorios.resultado_lab',
            'resultados_laboratorios.factor_rh',
            'resultados_laboratorios.fecha_laboratorio',
            'resultados_laboratorios.adjunto',
            'resultados_laboratorios.created_at as fecha_registro',
            'u.name as medico'
        )
        ->join('users as u', 'resultados_laboratorios.usuario_id', 'u.id')
        ->where('resultados_laboratorios.paciente_id', $paciente_id)
        ->get();
        return response()->json($laboratorios);
    }

    public function saveAyudasDx(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        AyudasDiagnostica::create($request->all());
        return response()->json([
            'message' => 'Ayuda diagnostica creada con exito!'
        ]);
    }

    public function fetchAyudasDx($paciente_id)
    {
        $ayudaDx = AyudasDiagnostica::select(
            'ayudas_diagnosticas.Ayuda_Diagnostica',
            'ayudas_diagnosticas.resultado_dx',
            'ayudas_diagnosticas.calidad_ccv',
            'ayudas_diagnosticas.microorganismos_ccv',
            'ayudas_diagnosticas.otros_microorganismosccv',
            'ayudas_diagnosticas.otros_neoplasicos',
            'ayudas_diagnosticas.anormalidades_celulasescamosas',
            'ayudas_diagnosticas.anormalidades_celulasgalndulares',
            'ayudas_diagnosticas.fecha_ayudadx',
            'ayudas_diagnosticas.created_at',
            'u.name'

        )
        ->where('ayudas_diagnosticas.paciente_id', $paciente_id)
        ->join('users as u', 'ayudas_diagnosticas.usuario_id', 'u.id')
        ->get();
        return response()->json($ayudaDx, 200);
    }

    public function saveImpresionDx(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Cie10_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($validate, 422);
        }
        $impresionDx = Cie10paciente::where('Citapaciente_id', $request->Citapaciente_id)->where('Esprimario', 1)->first();
        if (!$impresionDx) {
            Cie10paciente::create([
                'Cie10_id' => $request->Cie10_id,
                'Citapaciente_id' => $request->Citapaciente_id,
                'Esprimario' => 1,
                'Tipodiagnostico' => $request->Tipodiagnostico,
                'usuario_id' => auth()->id(),
                'Marca' => $request->marca,
                'paciente_id' => $request->paciente_id
            ]);
            return response()->json([
                'message' => 'Diagnostico guardado con exito'
            ]);
        }
        Cie10paciente::create([
            'Cie10_id' => $request->Cie10_id,
            'Citapaciente_id' => $request->Citapaciente_id,
            'Esprimario' => 0,
            'Tipodiagnostico' => $request->Tipodiagnostico,
            'usuario_id' => auth()->id(),
            'Marca' => $request->marca,
            'paciente_id' => $request->paciente_id
        ]);
        return response()->json([
            'message' => 'Diagnostico guardado con exito'
        ]);
    }

    public function fetchImpresionDx($citapaciente_id)
    {
        $impresionDx = Cie10paciente::select([
            'cie10pacientes.id', 'cie10pacientes.Esprimario',
            'cie10pacientes.Tipodiagnostico', 'cie10pacientes.created_at', 'cie10pacientes.marca',
            'c.id as CIE10_id','c.Codigo_CIE10','c.ficha_epidemiologica','c.Descripcion', 'u.name'
        ])
        ->join('cie10s as c', 'cie10pacientes.Cie10_id', 'c.id')
        ->join('users as u', 'cie10pacientes.usuario_id', 'u.id')
        ->where('cie10pacientes.Citapaciente_id', $citapaciente_id)
        ->get();
        return response()->json($impresionDx);
    }
    public function dxExistente($citapaciente_id)
    {
        $impresionDx = Cie10paciente::where('Citapaciente_id', $citapaciente_id)
        ->first();
        if($impresionDx == null){
            $impresionDx = false;
        }
        return response()->json($impresionDx);
    }

    public function deleteDX($id)
    {
        $dx = Cie10paciente::find($id);
        $dx->delete();
        return response()->json([
            'message' => 'Diagnostico eliminado con exito'
        ]);
    }

    public function savePlanCuidado(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        PlanCuidado::create($request->all());
        return response()->json([
            'message' => 'Plan y cuidado guardado con exito!'
        ],200);
    }

    public function saveProximoControl(Request $request)
    {
        $revision = citapaciente::where('id',$request->Citapaciente_id)
        ->update([
            'proximoControl'=>$request->proximoControl

        ]);
        return response()->json($revision,200);
    }

    public function fetchProximoControl($Citapaciente_id)
    {
        $revision = citapaciente::select(
            'proximoControl',
            'u.name as medico'
        )
        ->join('users as u', 'cita_paciente.Usuario_id', 'u.id')
        ->where('cita_paciente.id', $Citapaciente_id)
        ->first();
        return response()->json($revision, 200);
    }

    public function fetchPlancuidado($paciente_id)
    {
        $laboratorios = PlanCuidado::select(
            'plan_cuidados.id',
            'plan_cuidados.plan_cuidado',
            'plan_cuidados.tipo_plan_cuidado',
            'u.name as medico', 'plan_cuidados.created_at'
        )
        ->join('users as u', 'plan_cuidados.usuario_id', 'u.id')
        ->where('plan_cuidados.paciente_id', $paciente_id)
        ->orderBy('plan_cuidados.id', 'desc')
        ->get();
        return response()->json($laboratorios);
    }

    public function deletePlan($id)
    {
        $plan = PlanCuidado::find($id);
        $plan->delete();
        return response()->json([
            'message' => 'Elemento eliminado con exito'
        ]);
    }

    public function saveEvaluacionPlan(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        EvaluacionPlanCuidado::create($request->all());
        return response()->json([
            'message' => 'Evaluacion guardada con exito!'
        ],200);
    }

    public function fetchPlanevaluacion($paciente_id)
    {
        $evaluacion = EvaluacionPlanCuidado::select(
            'evaluacion_plan_cuidados.id',
            'evaluacion_plan_cuidados.evaluacion',
            'evaluacion_plan_cuidados.tipo_evaluacion',
            'u.name as medico', 'evaluacion_plan_cuidados.created_at'
        )
        ->join('users as u', 'evaluacion_plan_cuidados.usuario_id', 'u.id')
        ->where('evaluacion_plan_cuidados.paciente_id', $paciente_id)
        ->orderBy('evaluacion_plan_cuidados.id', 'desc')
        ->get();
        return response()->json($evaluacion);
    }

    public function deleteEvaluacion($id)
    {
        $evaluacion = EvaluacionPlanCuidado::find($id);
        $evaluacion->delete();
        return response()->json([
            'message' => 'Elemento eliminado con exito'
        ]);
    }

    public function saveinforSalud(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        InformacionSalud::create($request->all());
        return response()->json([
            'message' => 'Informacion de salud guardada con exito!'
        ],200);
    }

    public function fetchInforSalud($paciente_id)
    {
        $infosalud = InformacionSalud::select(
            'informacion_saluds.id',
            'informacion_saluds.info_salud',
            'informacion_saluds.tipo_info_salud',
            'u.name as medico', 'informacion_saluds.created_at'
        )
        ->join('users as u', 'informacion_saluds.usuario_id', 'u.id')
        ->where('informacion_saluds.paciente_id', $paciente_id)
        ->orderBy('informacion_saluds.id', 'desc')
        ->get();
        return response()->json($infosalud);
    }

    public function deleteinforSalud($id)
    {
        $info = InformacionSalud::find($id);
        $info->delete();
        return response()->json([
            'message' => 'Elemento eliminado con exito'
        ]);
    }

    public function saveCrianzaCuidado(Request $request)
    {
        $request['usuario_id'] = auth()->id();
        CrianzaCuidado::create($request->all());
        return response()->json([
            'message' => 'Practicas y Crianza guardada con exito!'
        ],200);
    }

    public function fetchCrianzaCuidado($paciente_id)
    {
        $infosalud = CrianzaCuidado::select(
            'crianza_cuidados.id',
            'crianza_cuidados.practica_crianza',
            'crianza_cuidados.tipo_practica_crianza',
            'u.name as medico', 'crianza_cuidados.created_at'
        )
        ->join('users as u', 'crianza_cuidados.usuario_id', 'u.id')
        ->where('crianza_cuidados.paciente_id', $paciente_id)
        ->orderBy('crianza_cuidados.id', 'desc')
        ->get();
        return response()->json($infosalud);
    }

    public function deleteCrianzaCuidado($id)
    {
        $info = CrianzaCuidado::find($id);
        $info->delete();
        return response()->json([
            'message' => 'Elemento eliminado con exito'
        ]);
    }

    public function savePlanManejo(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'Planmanejo' => 'required',
            'Recomendaciones' => 'required',
            'Destinopaciente' => 'required',
            'Finalidad' => 'required',
            'consultaExterna' => 'required',

        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $conducta = Conducta::where('Citapaciente_id', $request->Citapaciente_id)->first();
        if (!$conducta) {
            $request['usuario_id'] = auth()->id();
            Conducta::create($request->all());
            return response()->json([
                'message' => 'Conducta creada con exito!'
            ]);
        }
        $conducta->update($request->except('Citapaciente_id'));
        return response()->json([
            'message' => 'Conducta actualizada con exito!'
        ]);
    }

    public function fetchConducta($citapaciente_id)
    {
        $conducta = Conducta::select([
            'id', 'Planmanejo',
            'Recomendaciones', 'created_at', 'Destinopaciente', 'consultaExterna',
            'Finalidad', 'Especialidad', 'cursoprofilactico', 'asesoriaive'
        ])
        ->where('conductas.Citapaciente_id', $citapaciente_id)
        ->first();
        return response()->json($conducta);
    }

    public function finalizarConsulta(citapaciente $citapaciente)
    {
        $historiaCie10 = Cie10paciente::where('Citapaciente_id', $citapaciente->id)->first();

        if (!$historiaCie10) {
            return response()->json([
                'message' => 'La historia no cuenta con un Diagnostico de consulta!',
            ], 200);
        }

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
            'Datetimesalida' => now(),
            'user_medico_atiende' =>auth()->id(),
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
            'message' => 'Consulta finalizada con exito!',
        ], 200);
    }

    public function saveAntQuirurgico(Request $request)
    {
        $quirurgico = AntecedentesQuirurgico::where('Citapaciente_id', $request->Citapaciente_id)->first();
        if (!$quirurgico) {
            $request['usuario_id'] = auth()->id();
            AntecedentesQuirurgico::create($request->all());
            return response()->json([
                'message' => 'Antecedente Quirurgico guardado con exito!'
            ]);
        }
        $quirurgico->update($request->except('Citapaciente_id'));
        return response()->json([
            'message' => 'Antecedente Quirurgico actualizado con exito!'
        ]);
    }

    public function fetchAntQuirurgico($paciente_id)
    {
        $quirurgico = AntecedentesQuirurgico::select([
            'antecedentes_quirurgicos.id', 'antecedentes_quirurgicos.antecedentes_operaciones',
            'antecedentes_quirurgicos.edad_quirurgicos', 'antecedentes_quirurgicos.created_at', 'antecedentes_quirurgicos.a_cual',
             'u.name as medico'
        ])
        ->join('users as u', 'antecedentes_quirurgicos.usuario_id', 'u.id')
        ->where('antecedentes_quirurgicos.paciente_id', $paciente_id)
        ->get();
        return response()->json($quirurgico);
    }

    public function saveAntTransfusiones(Request $request)
    {
        $trasfusion = AntecedentesTransfusiones::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$trasfusion) {
            $request['usuario_id'] = auth()->id();
            AntecedentesTransfusiones::create($request->all());
            return response()->json([
                'message' => 'Antecedente Transfusion guardado con exito!'
            ]);
        }
        $trasfusion->update($request->except('Citapaciente_id'));
        return response()->json([
            'message' => 'Antecedente Transfusion actualizado con exito!'
        ]);
    }

    public function fetchAntTransfusiones($paciente_id)
    {
        $trasfusion = AntecedentesTransfusiones::select([
            'antecedentes_transfusiones.id', 'antecedentes_transfusiones.fecha_transfusiones',
            'antecedentes_transfusiones.transfusiones', 'antecedentes_transfusiones.created_at', 'antecedentes_transfusiones.observacion',
             'u.name as medico'
        ])
        ->join('users as u', 'antecedentes_transfusiones.usuario_id', 'u.id')
        ->where('antecedentes_transfusiones.paciente_id', $paciente_id)
        ->get();
        return response()->json($trasfusion);
    }

    public function pacienteInasiste(request $request)
    {
        $validate = Validator::make($request->all(), [
            'Observacionesinasistido'  => 'required|min:10|max:150',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $cita_paciente = citapaciente::where('id', $request->cita_paciente_id)
            ->first();

        Detallecita::create([
            'Citapaciente_id'   => $request->cita_paciente_id,
            'Motivo'            => $request->Observacionesinasistido,
            'Estado_id'         => 12,
            'Usuario_id'        => auth()->id(),
        ]);

        $cita_paciente->update([
            'Estado_id' => 12,
            'Observacionesinasistido' => $request->Observacionesinasistido,
        ]);

        return response()->json([
            'message' => 'Cita cancelada por inasistencia del paciente!',
        ], 200);
    }

    public function allCodesumis(request $request)
    {
        $niveles = auth()->user()->getRepository()->getQueryLevelItemsOrder();
        $articulos = Codesumi::select(
            'Codesumis.id', 'dta.Producto as Nombre', 'dta.Forma_Farmaceutica', 'dta.Via_Administracion',
            'Codesumis.Frecuencia as frec', 'Codesumis.Cantidadmaxordenar', 'Codesumis.Requiere_autorizacion',
            'Codesumis.Nivel_Ordenamiento', 'Codesumis.Estado_id', 'Codesumis.Genero',
        )
        ->join('detallearticulos as dta', 'Codesumis.id', 'dta.Codesumi_id')
        ->whereIn('Codesumis.Nivel_Ordenamiento', $niveles)
        ->whereIn('Codesumis.Tipocodesumi_id', [1, 2, 7, 8])
        ->where('Codesumis.Estado_id', 1)
        ->get();
        return response()->json($articulos, 200);
    }

    public function alertasPaciente($paciente_id)
    {
        $alertas = AntecedenteFarmacologico::select('antecedente_farmacologicos.id', 'antecedente_farmacologicos.utiempo','antecedente_farmacologicos.frecuencia',
        'antecedente_farmacologicos.frecuencia','antecedente_farmacologicos.alergia','antecedente_farmacologicos.observacionAlergia',
        'dta.Producto')
        ->leftjoin('detallearticulos as dta', 'antecedente_farmacologicos.detallearticulo_id', 'dta.id')
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->where('antecedente_farmacologicos.alergia', 'SI')
        ->where('antecedente_farmacologicos.paciente_id', $paciente_id)
        ->get();

        return response()->json($alertas, 200);
    }

    public function datosPaciente(Request $request)
    {
        $cie10 = Cie10paciente::where('Citapaciente_id', $request->cita_paciente_id)->where('Esprimario', 1)->first();
        $datosPaci = Paciente::where('id', $request->paciente_id)
        ->first();
            if($cie10 != null){
                $datosPaci['Cie10_id'] = $cie10->Cie10_id;
            }
        return response()->json($datosPaci, 200);
    }

    public function citasGrupales(Request $request)
    {
        $user = User::find(auth()->id());
        $rol =  $user->hasRole('Grupales RCV');
        $rolOncologia =  $user->hasRole('Enfermeria Oncologia');
        if($rol == "Grupales RCV"){
            if($request->paciente != null){
                $citaIndividual = Agenda::select('agendas.*',
                'e.Nombre as Especialidad',
                's.Nombre as sede',
                'co.Nombre as consultorio',
                'ta.name as Tipo_agenda',
                )
                ->join('especialidade_tipoagenda as et','agendas.Especialidad_id', 'et.id')
                ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                ->join('consultorios as co','agendas.Consultorio_id','co.id')
                ->join('sedes as s','co.sede_id','s.id')
                ->join('estados as es', 'agendas.Estado_id', 'es.id')
                ->join('citas','citas.Agenda_id','agendas.id')
                ->join('cita_paciente as cp','cp.Cita_id','citas.id')
                ->leftjoin('pacientes as p','cp.Paciente_id', 'p.id')
                ->where('p.Num_Doc',$request->paciente)
                ->where('agendas.Fecha',now())
                ->whereIn('ta.id',[536,596,612,613])
                ->where('es.id',3)
                ->with([
                    'citas'=>function($query) use ($request){
                        $query->select('cp.id as cita_paciente_id','e.Nombre as Especialidad',
                        'ta.name as Tipo_agenda','ta.id as tipoAgenda','cp.actividad_id','cp.especialidad_id',
                        'citas.Agenda_id','citas.Hora_Inicio','p.id as paciente_id',DB::RAW("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Nombre_Paciente"),
                        'p.Primer_Nom', 'p.Correo1' , 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Sexo','et.marcacion',
                        'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado','cp.Tipocita_id as Tipocita_id','cp.estado_id as CP_Estado_id')
                        ->join('cita_paciente as cp','cp.Cita_id','citas.id')
                        ->join('agendas as a','a.id','citas.Agenda_id')
                        ->join('especialidade_tipoagenda as et','a.Especialidad_id', 'et.id')
                        ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                        ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                        ->leftjoin('pacientes as p','cp.Paciente_id', 'p.id')
                        ->join('estados as es', 'cp.Estado_id', 'es.id')
                        ->where('p.Num_Doc',$request->paciente)
                        ->whereIn('es.id',[1,4,12,9,8]);
                    }
                ])
                ->get();
                return response()->json($citaIndividual, 200);

            }
        }else if($rolOncologia == 'Enfermeria Oncologia'){
                if($request->paciente != null){
                    $citaIndividual = Agenda::select('agendas.*',
                    'e.Nombre as Especialidad',
                    's.Nombre as sede',
                    'co.Nombre as consultorio',
                    'ta.name as Tipo_agenda',
                    )
                    ->join('especialidade_tipoagenda as et','agendas.Especialidad_id', 'et.id')
                    ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                    ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                    ->join('consultorios as co','agendas.Consultorio_id','co.id')
                    ->join('sedes as s','co.sede_id','s.id')
                    ->join('estados as es', 'agendas.Estado_id', 'es.id')
                    ->join('citas','citas.Agenda_id','agendas.id')
                    ->join('cita_paciente as cp','cp.Cita_id','citas.id')
                    ->leftjoin('pacientes as p','cp.Paciente_id', 'p.id')
                    ->where('p.Num_Doc',$request->paciente)
                    ->where('agendas.Fecha',now())
                    ->whereIn('ta.id',[652])
                    ->where('es.id',3)
                    ->with([
                        'citas'=>function($query) use ($request){
                            $query->select('cp.id as cita_paciente_id','e.Nombre as Especialidad',
                            'ta.name as Tipo_agenda','ta.id as tipoAgenda','cp.actividad_id','cp.especialidad_id',
                            'citas.Agenda_id','citas.Hora_Inicio','p.id as paciente_id',DB::RAW("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Nombre_Paciente"),
                            'p.Primer_Nom', 'p.Correo1' , 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Sexo','et.marcacion',
                            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado','cp.Tipocita_id as Tipocita_id','cp.estado_id as CP_Estado_id')
                            ->join('cita_paciente as cp','cp.Cita_id','citas.id')
                            ->join('agendas as a','a.id','citas.Agenda_id')
                            ->join('especialidade_tipoagenda as et','a.Especialidad_id', 'et.id')
                            ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                            ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                            ->leftjoin('pacientes as p','cp.Paciente_id', 'p.id')
                            ->join('estados as es', 'cp.Estado_id', 'es.id')
                            ->where('p.Num_Doc',$request->paciente)
                            ->whereIn('es.id',[1,4,12,9,8]);
                        }
                    ])
                    ->get();
                    return response()->json($citaIndividual, 200);

            }else{
                $citaIndividual = Agenda::select('agendas.*','e.Nombre as Especialidad','s.Nombre as sede','co.Nombre as consultorio','ta.name as Tipo_agenda')
                ->join('especialidade_tipoagenda as et','agendas.Especialidad_id', 'et.id')
                ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                ->join('consultorios as co','agendas.Consultorio_id','co.id')
                ->join('sedes as s','co.sede_id','s.id')
                ->join('estados as es', 'agendas.Estado_id', 'es.id')
                ->where('agendas.Fecha',now())
                ->whereIn('ta.id',[652])
                ->where('es.id',3)
                ->where('s.id',$request->sede)
                ->with([
                    'citas'=>function($query){
                        $query->select('cp.id as cita_paciente_id','e.Nombre as Especialidad',
                        'ta.name as Tipo_agenda','ta.id as tipoAgenda','cp.actividad_id','cp.especialidad_id',
                        'citas.Agenda_id','citas.Hora_Inicio','p.id as paciente_id',DB::RAW("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Nombre_Paciente"),
                        'p.Primer_Nom', 'p.Correo1', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Sexo','et.marcacion',
                        'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado','cp.Tipocita_id as Tipocita_id','cp.estado_id as CP_Estado_id')
                        ->join('cita_paciente as cp','cp.Cita_id','citas.id')
                        ->join('agendas as a','a.id','citas.Agenda_id')
                        ->join('especialidade_tipoagenda as et','a.Especialidad_id', 'et.id')
                        ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                        ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                        ->leftjoin('pacientes as p','cp.Paciente_id', 'p.id')
                        ->join('estados as es', 'cp.Estado_id', 'es.id')
                        ->whereIn('es.id',[1,4,12,9,8]);
                    }
                ])->get();
                return response()->json($citaIndividual, 200);

            }
        }else{
                $citaIndividual = Agenda::select('agendas.*','e.Nombre as Especialidad','s.Nombre as sede','co.Nombre as consultorio','ta.name as Tipo_agenda')
                ->join('especialidade_tipoagenda as et','agendas.Especialidad_id', 'et.id')
                ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                ->join('consultorios as co','agendas.Consultorio_id','co.id')
                ->join('sedes as s','co.sede_id','s.id')
                ->join('estados as es', 'agendas.Estado_id', 'es.id')
                ->where('agendas.Fecha',now())
                ->whereIn('ta.id',[536,596,612,613])
                ->where('es.id',3)
                ->where('s.id',$request->sede)
                ->with([
                    'citas'=>function($query){
                        $query->select('cp.id as cita_paciente_id','e.Nombre as Especialidad',
                        'ta.name as Tipo_agenda','ta.id as tipoAgenda','cp.actividad_id','cp.especialidad_id',
                        'citas.Agenda_id','citas.Hora_Inicio','p.id as paciente_id',DB::RAW("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Nombre_Paciente"),
                        'p.Primer_Nom', 'p.Correo1', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Sexo','et.marcacion',
                        'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado','cp.Tipocita_id as Tipocita_id','cp.estado_id as CP_Estado_id')
                        ->join('cita_paciente as cp','cp.Cita_id','citas.id')
                        ->join('agendas as a','a.id','citas.Agenda_id')
                        ->join('especialidade_tipoagenda as et','a.Especialidad_id', 'et.id')
                        ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
                        ->join('especialidades as e' , 'et.Especialidad_id' ,'e.id')
                        ->leftjoin('pacientes as p','cp.Paciente_id', 'p.id')
                        ->join('estados as es', 'cp.Estado_id', 'es.id')
                        ->whereIn('es.id',[1,4,12,9,8]);
                    }
                ])->get();
                return response()->json($citaIndividual, 200);

            }
    }

    public function citasOcupacionales(Request $request){
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
            ->where('cp.salud_ocupacional', 'Psicologia')
            ->orderBy('citas.Hora_Inicio', 'Asc');
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
            ->where('cp.salud_ocupacional', 'Voz')
            ->orderBy('citas.Hora_Inicio', 'Asc');;
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
            ->where('cp.salud_ocupacional', 'Visiometria')
            ->orderBy('citas.Hora_Inicio', 'Asc');
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
            ->whereIn('cp.salud_ocupacional', ['Psicologia','Voz','Visiometria','consulta Medica'])
            ->orderBy('citas.Hora_Inicio', 'Asc');
            if(isset($request->fecha_inicio)){
                $citaspendientes->where('a.Fecha','>=',$request->fecha_inicio);
                $citaspendientes->where('a.Fecha','<=',date('Y-m-d'));
            }else{
                $citaspendientes->where('a.Fecha', date('Y-m-d'));
            }
            $citas = $citaspendientes->get();
            return response()->json($citas, 200);
        }

    }

    public function citasIndividales(Request $request){

        $citaspendientes = Cita::select(['citas.id as id', 'p.id as paciente_id',
            'cp.id as cita_paciente_id', 'cp.Estado_id as CP_Estado_id',DB::RAW("CONCAT(S.Nombre,' (',c.Nombre,')') as consultorio"),
            'citas.Hora_Inicio', 'e.Nombre as Especialidad','e.id as idEspecialidad',
            'ta.name as Tipo_agenda','ta.id as tipoAgenda','cp.actividad_id','cp.especialidad_id', 'a.Fecha', 'citas.Estado_id',
            'p.Primer_Nom', 'p.Correo1', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape',DB::RAW("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Nombre_Paciente"), 'p.Sexo','et.marcacion',
            'p.Tipo_Doc', 'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado','cp.Tipocita_id as Tipocita_id','p.victima_conficto_armado'])
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
            ->whereIn('cp.Estado_id', [3, 4, 5, 7, 8, 9, 12])
            ->where('ta.id','<>',536)
            ->where('cp.salud_ocupacional',null)
            // ['Psicologia','Voz','Visiometria','consulta Medica'])
            ->orderBy('citas.Hora_Inicio', 'Asc');
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

        return response()->json($citas, 200);

    }

    public function citasNoProgramadas()
    {
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $noProgramada = citapaciente::select(['p.id as paciente_id', 'cita_paciente.id as cita_paciente_id',
        'cita_paciente.created_at as Fecha', 'cita_paciente.Estado_id as CP_Estado_id','cita_paciente.actividad_id as tipoAgenda',
        'p.Primer_Nom', 'p.Correo1', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape', 'p.Tipo_Doc',DB::RAW("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Nombre_Paciente"),
        'p.Num_Doc', 'p.Edad_Cumplida', 'es.Nombre as Estado','p.Sexo','e.Nombre as Especialidad','cita_paciente.created_at','ti.name as Tipo_agenda','p.victima_conficto_armado'])
            ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('estados as es', 'cita_paciente.Estado_id', 'es.id')
            ->join('users as u', 'cita_paciente.Usuario_id', 'u.id')
            ->join('especialidades as e','e.id','cita_paciente.especialidad_id')
            ->join('tipo_agendas as ti','ti.id','cita_paciente.actividad_id')
            ->whereIn('cita_paciente.Estado_id', [8, 9, 4])
            ->where('cita_paciente.created_at','>', $fechaHoy)
            ->where('cita_paciente.cita_no_programada', 1)
            ->where('u.id', auth()->id())
            ->orderBy('cita_paciente.id','desc')
            ->get();

        return response()->json($noProgramada, 200);
    }
    public function saveOptometria(Request $request){
        $lensometria = lensometriaAgudezavisual::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$lensometria) {
            lensometriaAgudezavisual::create($request->all());
            return response()->json([
                'message' => 'guardado con exito!'
            ]);
        }
        $lensometria->update($request->except('Citapaciente_id'));
        return response()->json([
            'message' => 'actualizado con exito!'
        ]);
    }

    public function fetchOptometria($citapaciente_id)
    {
        $lensometria = lensometriaAgudezavisual::select([
        'lateralidad_od','esf_od','cyl_od','eje_od','add_od','lateralidad_oi','esf_oi','cyl_oi','eje_oi',
        'add_oi','checkboxSC','checkboxCC','agudeza_od','agudezavp_od','agudeza_oi','agudezavp_oi','ocular_vl',
        'ocular_vp','ocular_ppc','citapaciente_id'
        ])
        ->where('lensometria_agudezavisuals.Citapaciente_id', $citapaciente_id)
        ->first();

        return response()->json($lensometria, 200);
    }

    public function savebiooftalmoscopia(Request $request){
        $optometria = BiomicroscopiaOftalmoscopia::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$optometria) {
            BiomicroscopiaOftalmoscopia::create($request->all());
            return response()->json([
                'message' => 'guardado con exito!'
            ]);
        }
        $optometria->update($request->except('Citapaciente_id'));
        return response()->json([
            'message' => 'actualizado con exito!'
        ]);
    }

    public function fetchsbiooftalmoscopia($citapaciente_id)
    {
        $optometria = BiomicroscopiaOftalmoscopia::select([
            'biomicroscopiaOd',
            'biomicroscopiaOi',
            'pioOd',
            'pioOi',
            'oftalmoscopiaOd',
            'oftalmoscopiaOi',
        ])
        ->where('biomicroscopia_oftalmoscopias.Citapaciente_id', $citapaciente_id)
        ->first();

        return response()->json($optometria, 200);
    }

    public function saveRefraccion(Request $request){
        $optometria = RefraccionSubejtivo::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$optometria) {
            RefraccionSubejtivo::create($request->all());
            return response()->json([
                'message' => 'guardado con exito!'
            ]);
        }
        $optometria->update($request->except('Citapaciente_id'));
        return response()->json([
            'message' => 'actualizado con exito!'
        ]);
    }

    public function fetchsRefraccion($citapaciente_id)
    {
        $optometria = RefraccionSubejtivo::select([
            'queratometria_od',
            'queratometria_oi',
            'refraccion_od',
            'refraccionAv_od',
            'refraccion_oi',
            'refraccionAv_oi',
            'subjetivo_od',
            'subjetivoAv_od',
            'subjetivo_oi',
            'subjetivoAv_oi',
        ])
        ->where('refraccion_subejtivos.Citapaciente_id', $citapaciente_id)
        ->first();

        return response()->json($optometria, 200);
    }

    public function CitasnoProgramada(Request $request, $paciente)
    {

        $fechaHoy = Carbon::now();
        $fechaHoyFormato = $fechaHoy->format('Y-m-d');

        $cita_paciente = citapaciente::create([
            "user_medico_atiende"  => auth()->id(),
            "Cup_id"        => $request->Cup_id,
            "Paciente_id"   => $paciente,
            "Usuario_id"    => auth()->id(),
            "Ambito"        => 'Ambulatorio',
            'lugar_atencion'=> $request->lugar_atencion,
            'actividad_id' => $request->actividad,
            'especialidad_id' => $request->especialidad,
            'Fecha_solicita' => $fechaHoyFormato,
            'cita_no_programada' => 1
        ]);
        $especialidad = Especialidade::where('id', $request->especialidad)->first();
        $tipoagenda = TipoAgenda::where('id', $request->actividad)->first();

        if ($especialidad->Nombre == 'Optometria') {
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
            $cita_paciente->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 17
            ]);
            break;
        }
        }else if ($especialidad->Nombre == 'Oftalmologia') {
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
            $cita_paciente->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 12
            ]);
            break;
        }
        }else if ($especialidad->Nombre == 'Oncologia') {
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
            $cita_paciente->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 7
            ]);
            break;
        }
        }else if ($especialidad->Nombre == 'Quimica Farmacologica') {
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
            $cita_paciente->update([
                'Datetimeingreso' => now(),
                'Estado_id' => 8,
                'Tipocita_id' => 18
            ]);
            break;
        }
        }else if ($especialidad->Nombre == 'Enfermeria Sede' || $especialidad->Nombre == 'Enfermeria'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 62
                ]);
                break;
            }
        }else if ($especialidad->Nombre == 'Auxiliar De Enfermeria'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            default:
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 67
                ]);
                break;
            }
        }else if ($especialidad->Nombre == 'Medicina General'){
            switch ($tipoagenda->id){
                case "3":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 84 //Recien Nacido
                    ]);
                    break;
                case "23":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 81 //Control Prenatal
                    ]);
                    break;
                case "536":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 74 //Grupales RCV
                    ]);
                    break;
                case "63":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 75 //Primera Infancia
                    ]);
                    break;
                case "26":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 76 //Consulta Infancia
                    ]);
                    break;
                case "13":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 77 //Consulta Adolencencia
                    ]);
                    break;
                case "35":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 78 //Consulta Joven
                    ]);
                    break;
                case "501":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 79 //Consulta Adultes
                    ]);
                    break;
                case "9":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 79 //Consulta Adultes
                    ]);
                    break;
                case "16":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 80 //Consulta Vejez
                    ]);
                    break;
                case "20":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 82 //Planificacion Familiar
                    ]);
                    break;
                    case "4":
                        $cita_paciente->update([
                            'Datetimeingreso'   => now(),
                            'Estado_id'         => 8,
                            'Tipocita_id'       => 74 //Grupales RCV
                        ]);
                        break;
                        case "181":
                            $cita_paciente->update([
                                'Datetimeingreso'   => now(),
                                'Estado_id'         => 8,
                                'Tipocita_id'       => 74 //Grupales RCV
                            ]);
                            break;
                            case "5":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                                ]);
                            break;
                            case "27":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                                ]);
                            break;
                            case "28":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                                ]);
                            break;
                            case "39":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                                ]);
                            break;
                            case "42":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                                ]);
                            break;
                            case "53":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                                ]);
                            break;
                            case "133":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                                ]);
                            break;
                            case "429":
                                $cita_paciente->update([
                                    'Datetimeingreso'   => now(),
                                    'Estado_id'         => 8,
                                    'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                                ]);
                            break;
                default:
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 8
                    ]);
                    break;
            }
        }else if ($especialidad->Nombre == 'Medico Experto Salud Mental'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 19
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medico Experto Reumatologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 20
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medico Experto Anticoagulados'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 21
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medico Experto Nefroproteccion'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 22
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medico Experto Respiratorios'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 23
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medico Experto Trasmisibles Cronicas'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 24
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Psiquiatria'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 25
                    ]);
                    break;
                }               
        }else if ($especialidad->Nombre == 'Neurologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 26
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cardiologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 27
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Ginecologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 28
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Obstetricia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 29
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medicina Interna'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 30
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Anestesiologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 31
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medicina Familiar'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 32
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Hematologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 33
                ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Nefrologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 34
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Otorrinolaringologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 35
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Ortopedia y Traumatologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 36
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Endocrinologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 37
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugia Coloproctologica'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 38
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugia General'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 39
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Pediatria'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 40
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Dermatologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 41
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medicina Del Deporte'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 42
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Alergologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 43
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Mastologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 44
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Neumologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 45
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medicina Del Dolor'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 46
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Toxicologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 47
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Fisiatria'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 48
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Urologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 49
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medicina Alternativa'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 50
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Neurocirugia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 51
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Infectologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 52
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Reumatologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 53
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Electrofisiologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 54
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Fonoaudiologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 55
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Terapia Respiratoria'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 56
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Fisioterapia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 57
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Trabajo Social'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 58
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Psicologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 59
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Nutricion'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 60
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Odontologia'){
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 61
                    ]);
        }else if ($especialidad->Nombre == 'Audiologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 63
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugía Cardiovascular'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 64
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugía Bariátrica'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 65
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugia Plastica'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 66
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Medico Experto RCV'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 68
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Neuropsicologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 69
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Radiologia'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 70
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugia Hepatobiliar'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 71
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugia Columna Vertebral'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 72
                    ]);
                    break;
                }
        }else if ($especialidad->Nombre == 'Cirugia Cabeza Y Cuello'){
            switch ($tipoagenda->id){
                case "5":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (20 Min)
                    ]);
                break;
                case "27":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (30 Min)
                    ]);
                break;
                case "28":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (15 Min)
                    ]);
                break;
                case "39":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (5 Min)
                    ]);
                break;
                case "42":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (10 Min)
                    ]);
                break;
                case "53":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (7 Min)
                    ]);
                break;
                case "133":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (4 Min)
                    ]);
                break;
                case "429":
                    $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 3 // Procedimientos Menores (8 Min)
                    ]);
                break;
            case "443":
                $cita_paciente->update([
                    'Datetimeingreso'   => now(),
                    'Estado_id'         => 8,
                    'Tipocita_id'       => 10 // Procedimientos Menores oncologico
                ]);
                break;
            default:
                $cita_paciente->update([
                        'Datetimeingreso'   => now(),
                        'Estado_id'         => 8,
                        'Tipocita_id'       => 73
                    ]);
                    break;
                }
        }else{
            $cita_paciente->update([
                'Datetimeingreso'   => now(),
                'Estado_id'         => 8,
                'Tipocita_id'       => 2
            ]);
        }

        return response()->json([$cita_paciente,$especialidad,$tipoagenda], 200);
    }

    public function descargaFias($fia)
    {
        $file = public_path()."/$fia.txt";
        $header = array(
            'Content-type' => 'text/plain',
        );
        return response()->download($file, 'fias', $header);
    }

    public function getActividad(request $request){
            $especialidad = Especialidade::where('Nombre', $request->Especialidad)->first();
            $especialidadTipoAgenda = Especialidadtipoagenda::select('t.id as tipoAgenda','t.name as Tipo_agenda')
            ->join('tipo_agendas as t','t.id','especialidade_tipoagenda.Tipoagenda_id')
            ->where('t.Estado_id',1)
            ->where('Especialidad_id',$especialidad->id)->get();

        return response()->json($especialidadTipoAgenda, 200);


    }

    public function cambioActividad(request $request){
        $fechaHoy = Carbon::now();
        $fechaHoyFormato = $fechaHoy->format('Y-m-d');

        $cita_paciente = citapaciente::where('id',$request->cita_paciente_id)->update([
            "user_medico_atiende"  => auth()->id(),
            "Ambito"        => 'Ambulatorio',
            'actividad_id' => $request->tipoAgenda,
            'especialidad_id' => $request->idEspecialidad,
            'Fecha_solicita' => $fechaHoyFormato,
            'Estado_id' => 8,
            'Datetimeingreso' => $fechaHoy,
        ]);
        return response()->json("Actualizado Con Exito", 200);

    }

    public function historicoOrdenes(request $request)
    {
        $sumarMeses = strtotime('+6 months', strtotime(date('Y-m')));
        $fecha6Despues = date('Y-m-d',$sumarMeses);
        $restaMeses = strtotime('-6 months', strtotime(date('Y-m')));
        $fechaHace6 = date('Y-m-t',$restaMeses);
        $medicamentos = Orden::select(
            'ordens.id as orden','de.id as detaarticulordens_id','c.Nombre as medicamentos','ordens.paginacion','cp.id as cita_paciente','c.id as id','cp.Tipocita_id',
            'de.Cantidadosis','de.Via','de.Unidadmedida','de.Frecuencia','de.Unidadtiempo','de.Fechaorden','cp.Paciente_id','cp.Datetimeingreso',
            'de.Duracion','de.Observacion','de.Cantidadpormedico','e.Nombre as Estado','e.id as EstadosOrden','c.*','us.id as UserId',
            DB::RAW("concat(us.cedula,' - ',concat(us.name,' ',us.apellido),' (',us.especialidad_medico,')') as medico")
        )
            ->join('detaarticulordens as de','de.Orden_id','ordens.id')
            ->join('codesumis as c', 'de.codesumi_id', 'c.id')
            ->join('estados as e','e.id','de.Estado_id')
            ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->join('users as us','us.id','ordens.Usuario_id')
            ->where('ordens.Tiporden_id',3)
            ->where('c.id',$request->id)
            ->where('cp.Paciente_id',$request->Paciente_id)
            ->whereBetween('ordens.Fechaorden',[$fechaHace6,$fecha6Despues])
            ->orderBy('ordens.Fechaorden','asc')
            ->get();

            return response()->json($medicamentos);
    }

    public function historicoMedicamentos(request $request)
    {
        $sumarMeses = strtotime('+6 months', strtotime(date('Y-m')));
        $fecha6Despues = date('Y-m-d',$sumarMeses);
        $restaMeses = strtotime('-6 months', strtotime(date('Y-m')));
        $fechaHace6 = date('Y-m-t',$restaMeses);

        $medicamentos = Orden::select(
            'c.Nombre as medicamentos','c.id as id','cp.Paciente_id'
        )
            ->distinct()
            ->join('detaarticulordens as de','de.Orden_id','ordens.id')
            ->join('codesumis as c', 'de.codesumi_id', 'c.id')
            ->join('estados as e','e.id','de.Estado_id')
            ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->where('ordens.Tiporden_id',3)
            ->where('cp.Paciente_id',$request->paciente_id)
            ->whereBetween('ordens.Fechaorden',[$fechaHace6,$fecha6Despues])
            ->get();

            return response()->json($medicamentos);
    }

    public function FechthistoricoMedicamentos($cedula)
    {
        $fechaHoy = date('Y-m').'-01';
        $restaMeses = strtotime('-6 months', strtotime(date('Y-m')));
        $fechaHace6 = date('Y-m-t',$restaMeses);
        $medicamentos = Orden::select(
            'c.Nombre as medicamentos','c.id as id','cp.Paciente_id'
        )
            ->distinct()
            ->join('detaarticulordens as de','de.Orden_id','ordens.id')
            ->join('codesumis as c', 'de.codesumi_id', 'c.id')
            ->join('estados as e','e.id','de.Estado_id')
            ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->where('ordens.Tiporden_id',3)
            ->where('p.Num_Doc',$cedula)
            ->whereBetween('ordens.Fechaorden',[$fechaHace6,$fechaHoy])
            ->get();

            return response()->json($medicamentos);
    }

    public function signosvitales(request $request){
        $examenFisico = Examenfisico::where('paciente_id', $request->paciente_id)->orderBy('id','desc')->get();
        return response()->json($examenFisico, 200);
    }

    public function suspenderMedicamento(request $request)
    {
        $validate = Validator::make($request->all(), [
            'suspencion'    => 'required|min:5',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        else{
        $ordenes = Orden::select('de.id as medicamento_id')
        ->join('detaarticulordens as de','de.Orden_id','ordens.id')
        ->join('codesumis as c', 'de.codesumi_id', 'c.id')
        ->join('estados as e','e.id','de.Estado_id')
        ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
        ->where('de.Fechaorden','>=',$request->Fechaorden)
        ->where('cp.id',$request->cita_paciente)
        ->where('c.id',$request->id)
        ->whereIn('e.id',[1,7,18,19])
        ->get();

        foreach ($ordenes as $orden) {
            Detaarticulorden::where('id', $orden->medicamento_id)->update(['Estado_id' => 50]);
            Auditoria::create([
                'Descripcion' => 'Se suspende el medicamento ' . $request->Medicamento .' del la orden ' . $request->orden,
                'Tabla' => 'Detaarticulorden',
                'Usuariomodifica_id' => auth()->id(),
                'Model_id' => $orden->medicamento_id,
                'Motivo' => $request->suspencion
            ]);
            }
        }
        return response()->json('Se Actualizo Correctamente',200);
    }

    public function motivoNegacion($detaarticulordens_id)
    {
        $motivoNegacion = Auditoria::select(DB::RAW("select top 1 Motivo as negacion, concat(u.name,'',u.apellido) as Fecha_orden
        from auditorias inner join users as u on auditorias.Usuariomodifica_id=u.id'"))
        ->where('auditorias.Model_id',$detaarticulordens_id)
        ->orderBy('auditorias.id','desc')
        ->get();

        return response()->json($motivoNegacion,200);
    }
    public function fechtMedicamentoInsumos()
    {
        $medicamentos =  Codesumi::select(
            'Codesumis.id', 'dta.Producto as Nombre',
        )
        ->join('detallearticulos as dta', 'Codesumis.id', 'dta.Codesumi_id')
        ->where('Codesumis.Estado_id', 1)
        ->get();

        return response()->json($medicamentos,200);
    }

    public function saveProcedimientoMenor(request $request)
    {
        try {
            $saveProcedimiento = citapaciente::where('id',$request->cita_paciente)
            ->update([
                'ProcedimientosMenores' => $request->ProcedimientosMenores,
                'Cup_id'                => $request->Cup_id
            ]);

            return response()->json([
                'message'   => 'Procedimiento Menor Guardado Con Exito',
                'data'      => $saveProcedimiento],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }

    }

    public function fethProcedimientoMenor($cita_paciente_id)
    {
        $fethProcedimientoMenor = citapaciente::select('cups.id as Cup_id', DB::RAW("CONCAT(cups.Codigo, '-',cups.Nombre) AS Nombre"),'ProcedimientosMenores')
        ->join('cups','cups.id','cita_paciente.Cup_id')
        ->where('cita_paciente.id',$cita_paciente_id)->first();

        return response()->json($fethProcedimientoMenor,200);
    }

    public function saveInsumoProcedimientos(request $request)
    {
            $request['usuario_id'] = auth()->user()->id;
            Insumos_procedimiento::create($request->all());
            return response()->json([
                'message' => 'Producto agregado con exito!'
            ]);
    }

    public function fethInsumoProcedimientos($cita_paciente_id)
    {
        $medicamento = Insumos_procedimiento::select('insumos_procedimientos.id','co.Nombre', 'insumos_procedimientos.cantidad')
        ->join('codesumis as co','insumos_procedimientos.producto', '=', 'co.id')
        ->orderBy('insumos_procedimientos.created_at', 'desc')
        ->where('insumos_procedimientos.estado_id', 1)
        ->where('insumos_procedimientos.Citapaciente_id',$cita_paciente_id)
        ->get();
         return response()->json($medicamento, 200);
    }

    public function medicamento(Insumos_procedimiento $id)
    {
        $id->update([
            'estado_id'  => 2,
        ]);
        return response()->json([
            'message' => 'Antecedente eliminado con exito!',
        ], 200);
    }
    public function fetchValoraciones($paciente_id)
    {
        $fetchOptometria = citapaciente::select('c2.Nombre','c2.Codigo','o.Fechaorden','cita_paciente.Paciente_id',
        DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),)
        ->join('ordens as o','cita_paciente.id','o.Cita_id')
        ->join('users as u','u.id','o.Usuario_id')
        ->join('cupordens as c','o.id','c.Orden_id')
        ->join('cups as c2','c.Cup_id','c2.id')
        ->whereIn('c2.id' , [2019,2344])
        ->where('c.Estado_id',1)
        ->where('cita_paciente.Paciente_id',$paciente_id)
        ->orderby('o.Fechaorden','desc')->take(3);

        $fetchNutricion = citapaciente::select('c2.Nombre','c2.Codigo','o.Fechaorden','cita_paciente.Paciente_id',
        DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),)
        ->join('ordens as o','cita_paciente.id','o.Cita_id')
        ->join('users as u','u.id','o.Usuario_id')
        ->join('cupordens as c','o.id','c.Orden_id')
        ->join('cups as c2','c.Cup_id','c2.id')
        ->whereIn('c2.id' , [2012,2018,2278,2290])
        ->where('c.Estado_id',1)
        ->where('cita_paciente.Paciente_id',$paciente_id)
        ->orderby('o.Fechaorden','desc')->take(3)->union($fetchOptometria);

        $fetchoftalmologia = citapaciente::select('c2.Nombre','c2.Codigo','o.Fechaorden','cita_paciente.Paciente_id',
        DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),)
        ->join('ordens as o','cita_paciente.id','o.Cita_id')
        ->join('users as u','u.id','o.Usuario_id')
        ->join('cupordens as c','o.id','c.Orden_id')
        ->join('cups as c2','c.Cup_id','c2.id')
        ->whereIn('c2.id' , [203,204])
        ->where('c.Estado_id',1)
        ->where('cita_paciente.Paciente_id',$paciente_id)
        ->orderby('o.Fechaorden','desc')->take(3)->union($fetchNutricion);

        $fetchPsicologia = citapaciente::select('c2.Nombre','c2.Codigo','o.Fechaorden','cita_paciente.Paciente_id',
        DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),)
        ->join('ordens as o','cita_paciente.id','o.Cita_id')
        ->join('users as u','u.id','o.Usuario_id')
        ->join('cupordens as c','o.id','c.Orden_id')
        ->join('cups as c2','c.Cup_id','c2.id')
        ->whereIn('c2.id' , [2013,2015,2020,2025])
        ->where('c.Estado_id',1)
        ->where('cita_paciente.Paciente_id',$paciente_id)
        ->orderby('o.Fechaorden','desc')->take(3)->union($fetchoftalmologia);

        $fetchTrabajoSocial = citapaciente::select('c2.Nombre','c2.Codigo','o.Fechaorden','cita_paciente.Paciente_id',
        DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),)
        ->join('ordens as o','cita_paciente.id','o.Cita_id')
        ->join('users as u','u.id','o.Usuario_id')
        ->join('cupordens as c','o.id','c.Orden_id')
        ->join('cups as c2','c.Cup_id','c2.id')
        ->whereIn('c2.id' , [2014,2021])
        ->where('c.Estado_id',1)
        ->where('cita_paciente.Paciente_id',$paciente_id)
        ->orderby('o.Fechaorden','desc')->take(3)->union($fetchPsicologia)->get();



        return response()->json($fetchTrabajoSocial,200);
    }

    public function motivoSuspencion($detaarticulordens_id)
    {
        $motivoSuspencion = Auditoria::select('Motivo as suspencion', DB::RAW("concat(u.name,'',u.apellido) as Fecha_orden"))
        ->join('users as u', 'auditorias.Usuariomodifica_id', 'u.id')
        ->where('auditorias.Model_id',$detaarticulordens_id)
        ->orderBy('auditorias.id','desc')->take(1)
        ->first();

        return response()->json($motivoSuspencion,200);
    }

    public function fechtMedicamentosOrdenados(request $request)
    {
        $fechaHoy = date('Y-m').'-01';
        $fechaHace6 = date('Y-m-t');
        $medicamentos = Orden::select(
            'c.Nombre as medicamentos','c.id as id','cp.Paciente_id'
        )
            ->distinct()
            ->join('detaarticulordens as de','de.Orden_id','ordens.id')
            ->join('codesumis as c', 'de.codesumi_id', 'c.id')
            ->join('estados as e','e.id','de.Estado_id')
            ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->where('ordens.Tiporden_id',3)
            ->where('p.id',$request->paciente_id)
            ->whereBetween('ordens.Fechaorden',[$fechaHace6,$fechaHoy])
            ->get();

            return response()->json($medicamentos);
    }

    public function saveAdherenciaFarmacologica(request $request)
    {
        $adherencia = AdherenciaFarmacoterapeutica::where('cita_paciente_id',$request->cita_paciente_id)->first();
        if (!$adherencia) {
            $request['usuario_id'] = auth()->user()->id;
            AdherenciaFarmacoterapeutica::create($request->all());
            return response()->json([
                'message' => 'Adherencia Farmacoterapeutica guardados con exito!'
            ], 200);
        }else {
            $adherencia->update($request->except(['cita_paciente_id']));
            return response()->json([
                'message' => 'Adherencia Farmacoterapeutica actualizados con exito!'
            ], 200);
        }
    }

    public function saveAdherenciaFarmacologicaNoHistoria(request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        $users = User::select(DB::RAW("concat(cedula,' - ',concat(name,' ',apellido),' (',especialidad_medico,')') as medico"),'id')->where('id',$request->intervencion_dirigida)->first();
        $request['intervencion_dirigida'] = $users->medico;
        $request['medico_id'] = $users->id;
        Adherencia::create($request->all());
        return response()->json([
            'message' => 'Adherencia guardados con exito!'
        ], 200);
    }


    public function fetchAdherenciaFarmacologica($cita_paciente_id)
    {
        $adherencia = AdherenciaFarmacoterapeutica::where('cita_paciente_id',$cita_paciente_id)->first();
        return response()->json($adherencia);
    }

    public function saveAdherencia(request $request)
    {
            $request['usuario_id'] = auth()->user()->id;
            Adherencia::create($request->all());
            return response()->json([
                'message' => 'Adherencia guardados con exito!'
            ], 200);
    }

    public function fetchAdherencia(request $request)
    {
        $adherencia = Adherencia::select(
            DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),
            'adherente',
            'causal',
            'intervencion_dirigida',
            'intervencion_principal',
            'adherencias.id',
            'detaarticulordens.Orden_id'
        )
        ->join('users as u', 'adherencias.usuario_id', 'u.id')
        ->leftjoin('detaarticulordens','detaarticulordens.id','adherencias.detaarticulordens_id')
        ->where('adherencias.paciente_id',$request->paciente_id)
        ->where('adherencias.codesumi_id',$request->codesumi_id)
        ->get();

        return response()->json($adherencia);
    }

    public function saveSeguridad(request $request)
    {
            $request['usuario_id'] = auth()->user()->id;
            $users = User::select(DB::RAW("concat(cedula,' - ',concat(name,' ',apellido),' (',especialidad_medico,')') as medico"),'id')->where('id',$request->intervencion_dirigida)->first();
            $request['intervencion_dirigida'] = $users->medico;
            $request['medico_id'] = $users->id;
            SeguridadFarmacologica::create($request->all());
            return response()->json([
                'message' => 'Seguridad guardados con exito!'
            ], 200);
    }

    public function fetchSeguridad(request $request)
    {
        $seguridad = SeguridadFarmacologica::select(
            DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),
            'intervencion_dirigida',
            'intervencion_principal',
            'prmevidenciado',
            'reaccion',
            'reaccion_adversa',
            'seguridad_farmacologicas.id',
            'detaarticulordens.Orden_id'
        )
        ->join('users as u', 'seguridad_farmacologicas.usuario_id', 'u.id')
        ->leftjoin('detaarticulordens','detaarticulordens.id','seguridad_farmacologicas.detaarticulordens_id')
        ->where('seguridad_farmacologicas.paciente_id',$request->paciente_id)
        ->where('seguridad_farmacologicas.codesumi_id',$request->codesumi_id)
        ->get();

        return response()->json($seguridad);
    }

    public function saveEfectividad(request $request)
    {
            $request['usuario_id'] = auth()->user()->id;
            $users = User::select(DB::RAW("concat(cedula,' - ',concat(name,' ',apellido),' (',especialidad_medico,')') as medico"),'id')->where('id',$request->intervencion_dirigida)->first();
            $request['intervencion_dirigida'] = $users->medico;
            $request['medico_id'] = $users->id;
            EfectividadFarmacologicas::create($request->all());
            return response()->json([
                'message' => 'Efectividad guardados con exito!'
            ], 200);
    }

    public function fetchEfectividad(request $request)
    {
        $efectividad = EfectividadFarmacologicas::select(
            DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),
            'intervencion_dirigida',
            'intervencion_principal',
            'prmevidenciado',
            'reaccion',
            'efectividad_farmacologicas.id',
            'detaarticulordens.Orden_id'
        )
        ->join('users as u', 'efectividad_farmacologicas.usuario_id', 'u.id')
        ->leftjoin('detaarticulordens','detaarticulordens.id','efectividad_farmacologicas.detaarticulordens_id')
        ->where('efectividad_farmacologicas.paciente_id',$request->paciente_id)
        ->where('efectividad_farmacologicas.codesumi_id',$request->codesumi_id)
        ->get();

        return response()->json($efectividad);
    }

    public function saveNecesidad(request $request)
    {
            $request['usuario_id'] = auth()->user()->id;
            $users = User::select(DB::RAW("concat(cedula,' - ',concat(name,' ',apellido),' (',especialidad_medico,')') as medico"),'id')->where('id',$request->intervencion_dirigida)->first();
            $request['intervencion_dirigida'] = $users->medico;
            $request['medico_id'] = $users->id;
            NecesidadFarmacologicas::create($request->all());
            return response()->json([
                'message' => 'Necesidad guardados con exito!'
            ], 200);
    }

    public function fetchNecesidad(request $request)
    {
        $efectividad = NecesidadFarmacologicas::select(
            DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),
            'intervencion_dirigida',
            'intervencion_principal',
            'prmevidenciado',
            'reaccion',
            'problemas_salud',
            'medicamento_inecesario',
            'necesidad_farmacologicas.id',
            'detaarticulordens.Orden_id'
        )
        ->join('users as u', 'necesidad_farmacologicas.usuario_id', 'u.id')
        ->leftjoin('detaarticulordens','detaarticulordens.id','necesidad_farmacologicas.detaarticulordens_id')
        ->where('necesidad_farmacologicas.paciente_id',$request->paciente_id)
        ->where('necesidad_farmacologicas.codesumi_id',$request->codesumi_id)
        ->get();

        return response()->json($efectividad);
    }

    public function saveOtro(request $request)
    {
            $request['usuario_id'] = auth()->user()->id;
            $users = User::select(DB::RAW("concat(cedula,' - ',concat(name,' ',apellido),' (',especialidad_medico,')') as medico"),'id')->where('id',$request->intervencion_dirigida)->first();
            $request['intervencion_dirigida'] = $users->medico;
            $request['medico_id'] = $users->id;
            OtrosFarmacologicas::create($request->all());
            return response()->json([
                'message' => 'Necesidad guardados con exito!'
            ], 200);
    }

    public function fetchOtros(request $request)
    {
        $efectividad = OtrosFarmacologicas::select(
            DB::RAW("CONCAT(u.name, ' ', u.apellido) AS Medico"),
            'intervencion_dirigida',
            'intervencion_principal',
            'prmevidenciado',
            'reaccion',
            'otros_farmacologicas.id',
            'detaarticulordens.Orden_id'
        )
        ->join('users as u', 'otros_farmacologicas.usuario_id', 'u.id')
        ->leftjoin('detaarticulordens','detaarticulordens.id','otros_farmacologicas.detaarticulordens_id')
        ->where('otros_farmacologicas.paciente_id',$request->paciente_id)
        ->where('otros_farmacologicas.codesumi_id',$request->codesumi_id)
        ->get();

        return response()->json($efectividad);
    }

    public function fechtCups()
    {
        $cups = cup::select('cups.id', DB::RAW("CONCAT(cups.Codigo, '-',cups.Nombre) AS Nombre"))
            ->whereIn('id',[43,44,45,46,47,48,49,65,66,293,294,296,297,298,2010,2011,2148,2149,2155,2156,2157,2158,2159,2160,2161,2162,2163,
            2164,2166,2177,2357,2677,2678,2724,2725,2726,2727,2728,2736,2737,2738,2739,2744,2745,2746,3373,3374,3469,3470,3852,4323,4330,4331,4332,4333,
            4334,4335,4336,4338,4339,4340,4341,4342,4384,4385,4386,4387,4388,4421,4422,4423,4424,4425,4426,4427,4428,4429,4430,4524,4578,4755,4756,4757,
            5165,5166,5167,5168,5169,5170,5202,5205,5206,5207,5208,5209,5210,5211,5212,5213,5214,5215,5216,5217,5218,5219,5220,5221,5222,5223,5224,5225,
            5226,5233,5234,5235,5236,5239,5241,5242,5243,5244,5245,5249,5733,5826,5827,5828,5829,5830,5831,7722,7839,7851,7853,7854,8163,8191,8192,8193,
            8194,8198,8199,8200,8201,8212,8213,8214,8215,8218,8219,8259,8388,8435,8462,8464,8465,8466,8467,8468,8470,8471,8472,8473,8474,8475,8477,8478,
            8479,8480,8481,8482,8483,8485,8486,8487,8489,8490,8495,8496,8497,8498,8499,8500,8501,8502,8503,8504,8505,8506,8507,8508,8509,8510,8511,8512,
            8513,8620,8621,8622,8623,8624,8625,8626,8627,8629,8630,8645,8646,8647,8648,8649,8650,8651,8652,8688,8690,8691,8692,8693,8931,8932,8933,8934,
            8935,8936,8937,8938,8939,8940,8941,8942,8943,8944,8945,8946,8947,8948,8949,8950,8952,9793,9794,9795,9796,9797,9798,9799,9800,9801,9802,9803,
            9804,9805,9806,9807,9808,9809,9810,9811,9812,9813,9814,9926,10239,10240,10246,10247,10248,10249,10283,10284,10366,10367,10368,10369,10551,10621,
            10622,10623,10624,10649,10654,10709,10710,10902,11078,11079,11080,11081,11291,11350,11449,11529,11694,11931,11966,12020,12040,12184,12220,12238,
            12707,12708,12725,12733,12775,12789,12799,12801,12802,12813,12926,12933,13004,4072,4329])->get();

            return response()->json($cups,200);
    }

    public function perfilFarmacologico($cedula_paciente)
    {
        $fechaHoy = Carbon::now();
        $fecha6mes = $fechaHoy->subMonth(6)->format('Y-m');
        $medicamentos = Orden::select(
            'de.id as detaarticulordens_id','c.Nombre as Medicamento','c.id as codesumi',
            'de.Cantidadosis','de.Via','de.Frecuencia',
            'de.Duracion',DB::RAW("CAST(de.Observacion AS VARCHAR(MAX)) as Observacion")
        )
            ->distinct()
            ->join('detaarticulordens as de','de.Orden_id','ordens.id')
            ->join('codesumis as c', 'de.codesumi_id', 'c.id')
            ->join('estados as e','e.id','de.Estado_id')
            ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->where('ordens.Tiporden_id',3)
            ->where('p.Num_Doc',$cedula_paciente)
            ->where('e.id','<>',26)
            ->where('e.id','<>',24)
            ->where('e.id','<>',50)
            ->where('ordens.Fechaorden','>',[$fecha6mes.-1])
            ->orderBy('c.id')
            ->get();

            return response()->json($medicamentos);
    }

    public function datosPacientes($cita_paciente_id)
    {
        $datosPacientes = citapaciente::select('estilovidas.checkboxFuma as fumador','examenfisicos.Presionsistolica as sistolica',
        'examenfisicos.Imc as imc','examenfisicos.Perimetroabdominal as abdominal','estilovidas.DietaFrecuencia as frecuencia','examenfisicos.Peso as peso')
        ->join('estilovidas','estilovidas.Citapaciente_id','cita_paciente.id')
        ->join('examenfisicos','examenfisicos.Citapaciente_id','cita_paciente.id')
        ->where('cita_paciente.id',$cita_paciente_id)->first();

        return response()->json($datosPacientes, 200);
    }

    public function saveFramingham(Request $request){
        $framighan = EstratificacionFramingham::where('citapaciente_id', $request->Citapaciente_id)->first();
        if($framighan == null){
           $request['usuario_id'] = auth()->user()->id;
            EstratificacionFramingham::create($request->all());
            return response()->json([
                'message' => 'Estratificacion Framingham guardado con exito!'
           ]);
        }else{
           $framighan->update([
            'tratamiento' => $request->tratamiento,
            'edad_puntaje' => $request->edad_puntaje,
            'colesterol_total' => $request->colesterol_total,
            'colesterol_puntaje' => $request->colesterol_puntaje,
            'colesterol_hdl' => $request->colesterol_hdl,
            'colesterol_puntajehdl' => $request->colesterol_puntajehdl,
            'fumador_puntaje' => $request->fumador_puntaje,
            'arterial_puntaje' => $request->arterial_puntaje,
            'diabetes_puntaje' => $request->diabetes_puntaje,
            'porcentaje' => $request->porcentaje,
            'totales' => $request->totales
        ]);
        return response()->json([
            'message' => 'Estratificacion Framingham guardado con exito!'
       ]);
        }
    }

    public function savefindrisk(Request $request){
        $framighan = estratificacion_findrisk::where('citapaciente_id', $request->Citapaciente_id)->first();
        if($framighan == null){
           $request['usuario_id'] = auth()->user()->id;
           estratificacion_findrisk::create($request->all());
            return response()->json([
                'message' => 'Estratificacion Frindisk guardado con exito!'
           ]);
        }else{
           $framighan->update($request->all());
        return response()->json([
            'message' => 'Estratificacion Frindisk guardado con exito!'
       ]);
        }
    }

    public function fetchFramingham($citapaciente_id){
        $framighan = EstratificacionFramingham::select('edad_puntaje', 'colesterol_total', 'colesterol_puntaje',  'colesterol_hdl',  'colesterol_puntajehdl',
        'fumador_puntaje', 'arterial_puntaje', 'totales' , 'tratamiento','porcentaje'
        )
        ->where('estratificacion_framinghams.Citapaciente_id',$citapaciente_id)
        ->first();
        return response()->json($framighan, 200);
    }

    public function fetchFindrisk($citapaciente_id){
        $findrisk = estratificacion_findrisk::select(
        'edad_puntaje',
        'indice_corporal',
        'perimetro_abdominal',
        'actividad_fisica',
        'puntaje_fisica',
        'frutas_verduras',
        'hipertension',
        'resultado_hipertension',
        'glucosa',
        'resultado_glucosa',
        'diabetes',
        'parentezco',
        'resultado_diabetes',
        'totales'
        )
        ->where('estratificacion_findrisks.Citapaciente_id',$citapaciente_id)
        ->first();
        return response()->json($findrisk, 200);
    }

    public function fetchUser(){
        $user = User::all(DB::RAW("concat(cedula,' - ',concat(name,' ',apellido),' (',especialidad_medico,')') as nombre"), 'id');

        return response()->json($user, 200);
    }

    public function datosParaclinicos($paciente_id){

        $creatinina = RegistroLaboratorios::select('registro_laboratorios.id as idCreatina','registro_laboratorios.resultado as resultadoCreatinina','registro_laboratorios.fecha_validacion as ultimaCreatinina')
        ->where('registro_laboratorios.paciente_id',$paciente_id)
        ->whereIn('registro_laboratorios.cup_id',[1178])
       ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

       $hdl = RegistroLaboratorios::select('registro_laboratorios.id as idHdl','registro_laboratorios.resultado as resultadoHdl','registro_laboratorios.fecha_validacion as fechaHdl')
       ->where('registro_laboratorios.paciente_id',$paciente_id)
       ->whereIn('registro_laboratorios.cup_id',[1099])
      ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

      $albuminuria = RegistroLaboratorios::select('registro_laboratorios.id as idAlbuminuria','registro_laboratorios.resultado as resultadoAlbuminuria','registro_laboratorios.fecha_validacion as fechaAlbuminuria')
       ->where('registro_laboratorios.paciente_id',$paciente_id)
       ->whereIn('registro_laboratorios.cup_id',[957])
      ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

       $hemoglobina = RegistroLaboratorios::select('registro_laboratorios.id as idHemoglobina','registro_laboratorios.resultado as resultaGlicosidada','registro_laboratorios.fecha_validacion as fechaGlicosidada')
       ->where('registro_laboratorios.paciente_id',$paciente_id)
       ->whereIn('registro_laboratorios.cup_id',[1043])
      ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

      $colesterolTotal = RegistroLaboratorios::select('registro_laboratorios.id as idRegistoColesterol','registro_laboratorios.resultado as resultadoColesterol','registro_laboratorios.fecha_validacion as fechaColesterol')
       ->where('registro_laboratorios.paciente_id',$paciente_id)
       ->whereIn('registro_laboratorios.cup_id',[1102])
      ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

      $trigliceridos = RegistroLaboratorios::select('registro_laboratorios.id as idTrigliceridos','registro_laboratorios.resultado as resultadoTrigliceridos','registro_laboratorios.fecha_validacion as fechaTrigliceridos')
      ->where('registro_laboratorios.paciente_id',$paciente_id)
      ->whereIn('registro_laboratorios.cup_id',[1151])
     ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

     $glicemia = RegistroLaboratorios::select('registro_laboratorios.id as idGlicemia','registro_laboratorios.resultado as resultadoGlicemia','registro_laboratorios.fecha_validacion as fechaGlicemia')
      ->where('registro_laboratorios.paciente_id',$paciente_id)
      ->whereIn('registro_laboratorios.cup_id',[1124])
     ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

     $pht = RegistroLaboratorios::select('registro_laboratorios.id as idPht','registro_laboratorios.resultado as resultadoPht','registro_laboratorios.fecha_validacion as fechaPht')
      ->where('registro_laboratorios.paciente_id',$paciente_id)
      ->whereIn('registro_laboratorios.cup_id',[1273])
     ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

     $albumina = RegistroLaboratorios::select('registro_laboratorios.id as idAlbumina','registro_laboratorios.resultado as albumina','registro_laboratorios.fecha_validacion as fechaAlbumina')
      ->where('registro_laboratorios.paciente_id',$paciente_id)
      ->whereIn('registro_laboratorios.cup_id',[1087])
     ->orderBy('registro_laboratorios.id','asc')->take(1)->first();

     $fosforo = RegistroLaboratorios::select('registro_laboratorios.id as idFosforo','registro_laboratorios.resultado as fosforo','registro_laboratorios.fecha_validacion as fechaFosforo')
     ->where('registro_laboratorios.paciente_id',$paciente_id)
     ->whereIn('registro_laboratorios.cup_id',[1118])
        ->orderBy('registro_laboratorios.id','asc')->take(1)->first();


        $array = [
            'creatinina' => $creatinina,
            'hdl' => $hdl,
            'albuminuria' => $albuminuria,
            'hemoglobina' => $hemoglobina,
            'colesterolTotal' => $colesterolTotal,
            'trigliceridos' => $trigliceridos,
            'glicemia'  => $glicemia,
            'pht' => $pht,
            'albumina' => $albumina,
            'fosforo' => $fosforo,
        ];

      return response()->json($array,200);
    }

    public function saveParaclinicos(Request $request){
           $request['usuario_id'] = auth()->user()->id;
           Paraclinico::create($request->all());
           RegistroLaboratorios::where('id',$request['idRegistoColesterol'])
           ->update([
            'resultado' => $request->resultadoColesterol,
            'fecha_validacion' => $request->fechaColesterol
           ]);
           RegistroLaboratorios::where('id',$request['idHdl'])
           ->update([
            'resultado' => $request->resultadoHdl,
            'fecha_validacion' => $request->fechaHdl
           ]);
           RegistroLaboratorios::where('id',$request['idTrigliceridos'])
           ->update([
            'resultado' => $request->resultadoTrigliceridos,
            'fecha_validacion' => $request->fechaTrigliceridos
           ]);
           RegistroLaboratorios::where('id',$request['idCreatina'])
           ->update([
            'resultado' => $request->resultadoCreatinina,
            'fecha_validacion' => $request->ultimaCreatinina
           ]);
           RegistroLaboratorios::where('id',$request['idAlbuminuria'])
           ->update([
            'resultado' => $request->resultadoAlbuminuria,
            'fecha_validacion' => $request->fechaAlbuminuria
           ]);
           RegistroLaboratorios::where('id',$request['idHemoglobina'])
           ->update([
            'resultado' => $request->resultaGlicosidada,
            'fecha_validacion' => $request->fechaGlicosidada
           ]);
           RegistroLaboratorios::where('id',$request['idGlicemia'])
           ->update([
            'resultado' => $request->resultadoGlicemia,
            'fecha_validacion' => $request->fechaGlicemia
           ]);
           RegistroLaboratorios::where('id',$request['idPht'])
           ->update([
            'resultado' => $request->resultadoPht,
            'fecha_validacion' => $request->fechaPht
           ]);
           RegistroLaboratorios::where('id',$request['idAlbumina'])
           ->update([
            'resultado' => $request->albumina,
            'fecha_validacion' => $request->fechaAlbumina
           ]);
           RegistroLaboratorios::where('id',$request['idFosforo'])
           ->update([
            'resultado' => $request->fosforo,
            'fecha_validacion' => $request->fechaFosforo
           ]);
            return response()->json([
                'message' => 'Paraclinicos guardado con exito!'
           ],200);

    }

    public function fetchDataParaclinicos($citaPaciente){
        $paraclinico = Paraclinico::select('resultadoCreatinina','resultadoColesterol','resultadoHdl','ultimaCreatinina','resultaGlicosidada',
        )
        ->where('paraclinicos.Citapaciente_id',$citaPaciente)
        ->orderBy('paraclinicos.id','desc')->first();
        return response()->json($paraclinico, 200);
    }

    public function saveKarnofski(Request $request)
    {
        $citaPaciente = citapaciente::where('id', $request->citapaciente_id)->first();
        $citaPaciente->update([
            'valor_scala_karnofski' => $request->valor_scala_karnofski,
        ]);
        return response()->json([
            $citaPaciente,
            'message' => 'Escala de karnofski guardada con exito!'
        ]);
    }

    public function saveEcog(Request $request)
    {
        $citaPaciente = citapaciente::where('id', $request->citapaciente_id)->first();
        $citaPaciente->update([
            'valor_scala_ecog' => $request->valor_scala_ecog,
        ]);
        return response()->json([
            $citaPaciente,
            'message' => 'Escala de Ecog guardada con exito!'
        ]);
    }

    public function saveEsas(Request $request)
    {
        $citaPaciente = citapaciente::where('id', $request->citapaciente_id)->first();
        $citaPaciente->update([
            'sin_dolor' => $request->sin_dolor,
            'sin_cansancio' => $request->sin_cansancio,
            'sin_nauseas' => $request->sin_nauseas,
            'sin_tristeza' => $request->sin_tristeza,
            'sin_ansiedad' => $request->sin_ansiedad,
            'sin_somnolencia' => $request->sin_somnolencia,
            'sin_disnea' => $request->sin_disnea,
            'buen_apetito' => $request->buen_apetito,
            'maximo_bienestar' => $request->maximo_bienestar,
            'sin_dificulta_para_dormir' => $request->sin_dificulta_para_dormir,
        ]);
        return response()->json([
            $citaPaciente,
            'message' => 'Escala de Esas guardada con exito!'
        ]);
    }

    public function fetchKarnofski($citapaciente_id)
    {
        $citaPaciente = citapaciente::find($citapaciente_id, ['id', 'valor_scala_karnofski']);
        return response()->json($citaPaciente, 200);
    }

    public function fetchEcog($citapaciente_id)
    {
        $citaPaciente = citapaciente::find($citapaciente_id, ['id', 'valor_scala_ecog']);
        return response()->json($citaPaciente, 200);
    }

    public function fetchEsas($citapaciente_id)
    {
        $citaPaciente = citapaciente::find($citapaciente_id, ['sin_dolor','sin_cansancio','sin_nauseas','sin_tristeza',
            'sin_ansiedad','sin_somnolencia','sin_disnea','buen_apetito','maximo_bienestar','sin_dificulta_para_dormir',
        ]);
        return response()->json($citaPaciente, 200);
    }

    public function demandaInducida($cedulaPaciente)
    {
        $pacienteDemandaInducida = Paciente::select(
            'Pacientes.Aseguradora','Pacientes.Celular1','Pacientes.Celular2','Pacientes.Correo1','Pacientes.Correo2','Pacientes.Dane_Dpto','Pacientes.Dane_Mpio','Pacientes.Departamento','Pacientes.Direccion_Residencia',
            'Pacientes.Discapacidad','Pacientes.Doc_Cotizante','Pacientes.Dpto_Atencion','Pacientes.Edad_Cumplida','Pacientes.Estado_Afiliado','Pacientes.Estrato','Pacientes.Etnia','Pacientes.Fecha_Afiliado',
            'Pacientes.Fecha_Emision','Pacientes.Fecha_Naci','Pacientes.Grado_Discapacidad','s.Nombre as Punto_Atencion','Pacientes.Laboraen','Pacientes.Medicofamilia','Pacientes.Mpio_Afiliado','m.Nombre as Municipio_Atencion',
            'Pacientes.Mpio_Labora','Pacientes.Mpio_Residencia','Pacientes.Nivel_Sisben','Pacientes.Nivelestudio','Pacientes.Nombreacompanante','Pacientes.Nombreresponsable','Pacientes.Num_Doc',
            'Pacientes.Num_Folio','Pacientes.Num_Hijos','Pacientes.Ocupacion','Pacientes.Orden_Judicial','Pacientes.Otradiscapacidad','Pacientes.Parentesco','Pacientes.Primer_Ape','Pacientes.Primer_Nom',
            'Pacientes.RH','Pacientes.Region','Pacientes.Sede_Odontologica','Pacientes.SegundoNom','Pacientes.Segundo_Ape','Pacientes.Sexo','Pacientes.Sexo_Biologico','Pacientes.Subregion','Pacientes.Telefono',
            'Pacientes.Telefonoacompanante','Pacientes.Telefonoresponsable','Pacientes.Tienetutela','Pacientes.TipoDoc_Cotizante','Pacientes.Tipo_Afiliado','Pacientes.Tipo_Cotizante',
            'Pacientes.Tipo_Doc','Pacientes.Tipo_Regimen','Pacientes.Tipovinculacion','Pacientes.Ut','Pacientes.Vivecon','Pacientes.acceso_vivienda','Pacientes.anexo','Pacientes.antiguedad_cargo_actual',
            'Pacientes.antiguedad_en_empresa','Pacientes.cargo_laboral','Pacientes.ciclo_vida','Pacientes.composicion_familiar','Pacientes.created_at','Pacientes.cums','Pacientes.cups',
            'Pacientes.domiciliaria','Pacientes.dx','Pacientes.edad_horus','Pacientes.entidad_id','Pacientes.estado_civil','Pacientes.f_solicitud','Pacientes.fecha_fin_cont','Pacientes.fecha_ini_cont',
            'Pacientes.fecha_nacimiento_horus','Pacientes.grupo_sanguineo_padre','Pacientes.hogar_con_agua','Pacientes.id','Pacientes.justifica_represa','Pacientes.mascota',
            'Pacientes.medicofamilia2','Pacientes.municipio_nacimiento','Pacientes.nivel','Pacientes.nombre_pareja','Pacientes.numero_cursos_a_cargo','Pacientes.numero_habitaciones',
            'Pacientes.numero_miembros','Pacientes.observacion_contratista','Pacientes.ocupacion_padre','Pacientes.pais_nacimiento','Pacientes.pareja_actual_padre','Pacientes.id as paciente_id',
            'Pacientes.personas_a_cargo','Pacientes.portabilidad','Pacientes.prepara_comida_con','Pacientes.propios','Pacientes.regional','Pacientes.religion','Pacientes.represa','Pacientes.ruta',
            'Pacientes.seguridad_vivienda','Pacientes.sem_cot','Pacientes.tipo_categoria','Pacientes.tipo_contratacion','Pacientes.tipo_vivienda','Pacientes.updated_at','Pacientes.ut_saliente',
            'Pacientes.valor_cont_cap','Pacientes.valot_cont_pyp','Pacientes.victima_conficto_armado','Pacientes.vivienda','Pacientes.vivienda_con_energia','Pacientes.vlr_upc','Pacientes.zona_vivienda',
        )
        ->leftJoin('sedeproveedores as s', 'Pacientes.IPS', DB::raw("CONVERT(VARCHAR, s.id)"))
        ->leftJoin('Municipios as m', 'Pacientes.Mpio_Atencion', 'm.id')
        ->where('Pacientes.Num_Doc', $cedulaPaciente)
        ->where('Pacientes.Estado_Afiliado', 1)->first();
        return response()->json($pacienteDemandaInducida, 200);

    }

    public function saveDemandaInducida(Request $request)
    {
        if (!$request->hasFile('adjuntos')) {
            $demandaInducida = demandaInducida::create([
            'tipoDemandaInducida' => $request->tipoDemandaInducida,
            'programaremitido' => $request->programaremitido,
            'fecha_dx_riesgocardiovascular' => $request->fecha_dx_riesgocardiovascular,
            'descripcion_evento_saludpublica' => $request->descripcion_evento_saludpublica,
            'descripcion_otro_programa' => $request->descripcion_otro_programa,
            'demanda_inducida_efectiva' => $request->demanda_inducida_efectiva,
            'paciente_id' => $request->paciente_id,
            'usuario_genera_id' => auth()->id(),
                'observaciones' => $request->observaciones
            ]);
            return response()->json([
                $demandaInducida,
                'mensaje' => 'Se genero demanda inducida con exito!',
            ], HttpResponse::HTTP_OK);
        }elseif ($request->hasFile('adjuntos')) {
            $ruta = 'historia_demanda_inducida';
            $files = $request->file('adjuntos');
            $lugar = Storage::disk('ftp')->put($ruta, $files);

            $demandaInducida = demandaInducida::create([
            'nombre' => $files->getClientOriginalName(),
            'ruta' => $lugar,
            'tipoDemandaInducida' => $request->tipoDemandaInducida,
            'programaremitido' => $request->programaremitido,
            'fecha_dx_riesgocardiovascular' => $request->fecha_dx_riesgocardiovascular,
            'descripcion_evento_saludpublica' => $request->descripcion_evento_saludpublica,
            'descripcion_otro_programa' => $request->descripcion_otro_programa,
            'demanda_inducida_efectiva' => $request->demanda_inducida_efectiva,
            'paciente_id' => $request->paciente_id,
            'usuario_genera_id' => auth()->id(),
            ]);
            return response()->json([
                $demandaInducida,
                'mensaje' => 'Se genero demanda inducida con exito!',
            ], HttpResponse::HTTP_OK);
        }
    }

    public function consultarDemandaInducida()
    {
        $demandaInducida = demandaInducida::select(
            'demanda_inducidas.id', 'demanda_inducidas.tipoDemandaInducida', 'demanda_inducidas.programaremitido', 'demanda_inducidas.fecha_dx_riesgocardiovascular',
            'demanda_inducidas.descripcion_evento_saludpublica', 'demanda_inducidas.descripcion_otro_programa', 'demanda_inducidas.demanda_inducida_efectiva',
            DB::RAW("concat(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Paciente"), 's.Nombre as IPS', 'm.Nombre as Municipio', 'p.Num_Doc',
            'p.Tipo_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'demanda_inducidas.paciente_id',
            'demanda_inducidas.Cita_Paciente_1_id',
            'cp1.Estado_id as estado1',
            'demanda_inducidas.Cita_Paciente_2_id',
            'cp2.Estado_id as estado2',
            'demanda_inducidas.Cita_Paciente_3_id',
            'cp3.Estado_id as estado3'
        )
            ->join('Pacientes as p', 'demanda_inducidas.paciente_id', 'p.id')
            ->leftJoin('sedeproveedores as s', 'p.IPS', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->leftJoin('Municipios as m', 'p.Mpio_Atencion', 'm.id')
            ->leftJoin('cita_paciente as cp1','cp1.id','demanda_inducidas.Cita_Paciente_1_id')
            ->leftJoin('cita_paciente as cp2','cp2.id','demanda_inducidas.Cita_Paciente_2_id')
            ->leftJoin('cita_paciente as cp3','cp3.id','demanda_inducidas.Cita_Paciente_3_id')
            ->orderBy('demanda_inducidas.id', 'DESC')
            ->get();
        return response()->json($demandaInducida, 200);
    }


    public function saveIndiceBerthel(Request $request)
    {
        $barthel = indiceBarthel::where('citapaciente_id', $request->citapaciente_id)->first();

        if ($barthel) {
            $barthel->update([
                'barthelComer' => $request->barthelComer,
                'barthelLavarse' => $request->barthelLavarse,
                'barthelVestirse' => $request->barthelVestirse,
                'barthelArreglarse' => $request->barthelArreglarse,
                'barthelDeposiciones' => $request->barthelDeposiciones,
                'barthelMiccion' => $request->barthelMiccion,
                'barthelRetrete' => $request->barthelRetrete,
                'barthelTrasladarse' => $request->barthelTrasladarse,
                'barthelDeambular' => $request->barthelDeambular,
                'barthelEscalones' => $request->barthelEscalones,
                'citapaciente_id' => $request->citapaciente_id
            ]);
            return response()->json([
                'mensaje' => 'Se actualizo indice de barthel con exito!',
            ], HttpResponse::HTTP_OK);
        }
        $demandaInducida = indiceBarthel::create([
            'barthelComer' => $request->barthelComer,
            'barthelLavarse' => $request->barthelLavarse,
            'barthelVestirse' => $request->barthelVestirse,
            'barthelArreglarse' => $request->barthelArreglarse,
            'barthelDeposiciones' => $request->barthelDeposiciones,
            'barthelMiccion' => $request->barthelMiccion,
            'barthelRetrete' => $request->barthelRetrete,
            'barthelTrasladarse' => $request->barthelTrasladarse,
            'barthelDeambular' => $request->barthelDeambular,
            'barthelEscalones' => $request->barthelEscalones,
            'citapaciente_id' => $request->citapaciente_id
        ]);
        return response()->json([
            $demandaInducida,
            'mensaje' => 'Se guardo indice de barthel con exito!',
        ], HttpResponse::HTTP_OK);
    }

    public function fetchIndiceBarthel($citapaciente_id)
    {
        $indiceBarthel = indiceBarthel::where('citapaciente_id', $citapaciente_id)->first([
            'barthelComer','barthelLavarse','barthelVestirse','barthelArreglarse',
            'barthelDeposiciones','barthelMiccion','barthelRetrete','barthelTrasladarse',
            'barthelDeambular','barthelEscalones'
        ]);
        return response()->json($indiceBarthel, HttpResponse::HTTP_OK);
    }

    public function asignarCitaDemandaInducida($codigoDemanda,$citaId){
        $demandaInducida = demandaInducida::find($codigoDemanda);
        if(!$demandaInducida->Cita_Paciente_1_id){
            $demandaInducida->Cita_Paciente_1_id = $citaId;
        }elseif (!$demandaInducida->Cita_Paciente_2_id){
            $demandaInducida->Cita_Paciente_2_id = $citaId;
        }elseif (!$demandaInducida->Cita_Paciente_3_id){
            $demandaInducida->Cita_Paciente_3_id = $citaId;
        }
        $demandaInducida->save();
        return response()->json(['mensaje' => 'Cita Registrada!']);
    }
    public function misdemandasinducidas()
    {
        $demandaInducida = demandaInducida::select(
            'demanda_inducidas.id', 'demanda_inducidas.tipoDemandaInducida', 'demanda_inducidas.programaremitido', 'demanda_inducidas.fecha_dx_riesgocardiovascular',
            'demanda_inducidas.descripcion_evento_saludpublica', 'demanda_inducidas.descripcion_otro_programa', 'demanda_inducidas.demanda_inducida_efectiva',
            DB::RAW("concat(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) as Paciente"), 's.Nombre as IPS', 'm.Nombre as Municipio', 'p.Num_Doc',
            'p.Tipo_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'demanda_inducidas.paciente_id',
        )
            ->join('Pacientes as p', 'demanda_inducidas.paciente_id', 'p.id')
            ->leftJoin('sedeproveedores as s', 'p.IPS', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->leftJoin('Municipios as m', 'p.Mpio_Atencion', 'm.id')
            ->where('demanda_inducidas.usuario_genera_id', auth()->id())
            ->get();
        return response()->json($demandaInducida, 200);
    }
    public function reporteDemandaInducida(Request $request)
    {
        $reporteInducida = collect(DB::select("exec dbo.SP_Demanda_Inducida_Citas ?,?", [$request['f_inicial'],$request['f_final']]));

        $array = json_decode($reporteInducida, true);
       return (new FastExcel($array))->download('file.xls');
    }
}
