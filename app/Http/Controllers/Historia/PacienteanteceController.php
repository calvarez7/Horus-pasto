<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\AntecedenteFarmacologico;
use App\Modelos\AntecedentesPersonale;
use App\Modelos\AntecedenteToxicologico;
use App\Modelos\citapaciente;
use App\Modelos\OtrosAntecedentesPersonale;
use App\Modelos\Pacienteantecedente;
use App\Modelos\Parentescoantecedente;
use App\User;
use Illuminate\Http\Request;

class PacienteanteceController extends Controller
{
    public function saveAntecedentes(Request $request)
    {
        $antecedentes = Pacienteantecedente::where('citapaciente_id', $request->citapaciente_id)->first();
        if (!$antecedentes) {
            $request['usuario_id'] = auth()->user()->id;
            Pacienteantecedente::create($request->all());
            return response()->json([
                'message' => 'Antecedentes patologicos guardados con exito!'
            ]);
        }else {
            $request['usuario_id'] = auth()->user()->id;
            $antecedentes->update($request->except(['citapaciente_id']));
            return response()->json([
                'message' => 'Antecedentes patologicos actualizados con exito!'
            ]);
        }
    }

    public function fetchAnt(Request $request)
    {
        $fechAntecedentes = Pacienteantecedente::select(
            'checkboxOtras_enfermedades',
            'checkboxasma',
            'asmaPresente',
            'asmaTipo',
            'asmaTratamiento',
            'asmaFecha',
            'checkboxepoc',
            'epocPresente',
            'epocTipo',
            'epocTratamiento',
            'epocFecha',
            'checkboxdiabetes',
            'diabetesPresente',
            'diabetesTipo',
            'diabetesTratamiento',
            'diabetesFecha',
            'checkboxtuberculosis',
            'tuberculosisPresente',
            'tuberculosisTipo',
            'tuberculosisTratamiento',
            'tuberculosisFecha',
            'checkboxCancer',
            'checkboxCancerPresente',
            'checkboxCancerTipo',
            'checkboxCancerTratamiento',
            'checkboxCancerFecha',
            'checkboxhipertension_arterial',
            'hipertension_arterialPresente',
            'hipertension_arterialTipo',
            'hipertension_arterialTratamiento',
            'hipertension_arterialFecha',
            'checkboxenfermedad_renal_cronica',
            'enfermedad_renal_cronicaPresente',
            'enfermedad_renal_cronicaTipo',
            'enfermedad_renal_cronicaTratamiento',
            'enfermedad_renal_cronicaFecha',
            'checkboxenfermedad_cerebrovascular',
            'enfermedad_cerebrovascularPresente',
            'enfermedad_cerebrovascularTipo',
            'enfermedad_cerebrovascularTratamiento',
            'enfermedad_cerebrovascularFecha',
            'checkboxenfermedad_genetica_congenita_multiple',
            'enfermedad_genetica_congenita_multiplePresente',
            'enfermedad_genetica_congenita_multipleTipo',
            'enfermedad_genetica_congenita_multipleTratamiento',
            'enfermedad_genetica_congenita_multipleFecha',
            'checkboxenfermedad_anemica',
            'enfermedad_anemicaPresente',
            'enfermedad_anemicaTipo',
            'enfermedad_anemicaTratamiento',
            'enfermedad_anemicaFecha',
            'checkboxenfermedades_trasmision_sexual',
            'enfermedades_trasmision_sexualPresente',
            'enfermedades_trasmision_sexualTipo',
            'enfermedades_trasmision_sexualTratamiento',
            'enfermedades_trasmision_sexualFecha',
            'checkboxsindrome_convulsivo',
            'sindrome_convulsivoPresente',
            'sindrome_convulsivoTipo',
            'sindrome_convulsivoTratamiento',
            'sindrome_convulsivoFecha',
            'checkboxcovid',
            'covidPresente',
            'covidTipo',
            'covidTratamiento',
            'covidFecha',
            'checkboxenfermedad_arterial_oclusiva_cronica',
            'enfermedad_arterial_oclusiva_cronicaPresente',
            'enfermedad_arterial_oclusiva_cronicaTipo',
            'enfermedad_arterial_oclusiva_cronicaTratamiento',
            'enfermedad_arterial_oclusiva_cronicaFecha',
            'checkboxtromboflevitis',
            'tromboflevitisPresente',
            'tromboflevitisTipo',
            'tromboflevitisTratamiento',
            'tromboflevitisFecha',
            'checkboxotras_enfermedades_neurologicas',
            'otras_enfermedades_neurologicasPresente',
            'otras_enfermedades_neurologicasTipo',
            'otras_enfermedades_neurologicasTratamiento',
            'otras_enfermedades_neurologicasFecha',
            'checkboxenfermedad_de_hansen',
            'enfermedad_de_hansenPresente',
            'enfermedad_de_hansenTipo',
            'enfermedad_de_hansenTratamiento',
            'enfermedad_de_hansenFecha',
            'checkboxvih',
            'vihPresente',
            'vihTipo',
            'vihTratamiento',
            'vihFecha',
            'checkboxenfermedad_cardiopatia_isquemica',
            'enfermedad_cardiopatia_isquemicaPresente',
            'enfermedad_cardiopatia_isquemicaTipo',
            'enfermedad_cardiopatia_isquemicaTratamiento',
            'enfermedad_cardiopatia_isquemicaFecha',
            'checkboxmalnutricion',
            'malnutricionPresente',
            'malnutricionTipo',
            'malnutricionTratamiento',
            'malnutricionFecha',
            'checkboxdislipidemia',
            'dislipidemiaPresente',
            'dislipidemiaTipo',
            'dislipidemiaTratamiento',
            'dislipidemiaFecha',
            'checkboxhipotirodismo_congenito',
            'hipotirodismo_congenitoPresente',
            'hipotirodismo_congenitoTipo',
            'hipotirodismo_congenitoTratamiento',
            'hipotirodismo_congenitoFecha',
            'checkboxenfermedad_artritis_reumatoide',
            'enfermedad_artritis_reumatoidePresente',
            'enfermedad_artritis_reumatoideTipo',
            'enfermedad_artritis_reumatoideTratamiento',
            'enfermedad_artritis_reumatoideFecha',
            'checkboxenfermedad_cardiovascular',
            'enfermedad_cardiovascularPresente',
            'enfermedad_cardiovascularTipo',
            'enfermedad_cardiovascularTratamiento',
            'enfermedad_cardiovascularFecha',
            'checkboxenfermedades_autoinmunes',
            'enfermedades_autoinmunesPresente',
            'enfermedades_autoinmunesTipo',
            'enfermedades_autoinmunesTratamiento',
            'enfermedades_autoinmunesFecha',
            'checkboxenfermedad_acido_peptica',
            'enfermedad_acido_pepticaPresente',
            'enfermedad_acido_pepticaTipo',
            'enfermedad_acido_pepticaTratamiento',
            'enfermedad_acido_pepticaFecha',
            'checkboxsindrome_de_apnea_hipoapnea_del_sueno',
            'sindrome_de_apnea_hipoapnea_del_suenoPresente',
            'sindrome_de_apnea_hipoapnea_del_suenoTipo',
            'sindrome_de_apnea_hipoapnea_del_suenoTratamiento',
            'sindrome_de_apnea_hipoapnea_del_suenoFecha',
            'checkboxpatologia_perinatal_neonatal_significativa',
            'patologia_perinatal_neonatal_significativaPresente',
            'patologia_perinatal_neonatal_significativaTipo',
            'patologia_perinatal_neonatal_significativaTratamiento',
            'patologia_perinatal_neonatal_significativaFecha',
            'checkboxhijo_de_madre_infeccion_gestacional_alto_riesgo',
            'hijo_de_madre_infeccion_gestacional_alto_riesgoPresente',
            'hijo_de_madre_infeccion_gestacional_alto_riesgoTipo',
            'hijo_de_madre_infeccion_gestacional_alto_riesgoTratamiento',
            'hijo_de_madre_infeccion_gestacional_alto_riesgoFecha',
            'checkboxdermatitis_atopica',
            'dermatitis_atopicaPresente',
            'dermatitis_atopicaTipo',
            'dermatitis_atopicaTratamiento',
            'dermatitis_atopicaFecha',
            'checkboxenfermedad_musculo_esqueleticas',
            'enfermedad_musculo_esqueleticasPresente',
            'enfermedad_musculo_esqueleticasTipo',
            'enfermedad_musculo_esqueleticasTratamiento',
            'enfermedad_musculo_esqueleticasFecha',
            'checkboxhijo_de_madre_sospecha_depresion_pos_parto',
            'hijo_de_madre_sospecha_depresion_pos_partoPresente',
            'hijo_de_madre_sospecha_depresion_pos_partoTipo',
            'hijo_de_madre_sospecha_depresion_pos_partoTratamiento',
            'hijo_de_madre_sospecha_depresion_pos_partoFecha',
            'checkboxhijo_de_madre_sospecha_consumo_de_spa',
            'hijo_de_madre_sospecha_consumo_de_spaPresente',
            'hijo_de_madre_sospecha_consumo_de_spaTipo',
            'hijo_de_madre_sospecha_consumo_de_spaTratamiento',
            'hijo_de_madre_sospecha_consumo_de_spaFecha',
            'checkboxhijo_de_padre_enfermedad_mental',
            'hijo_de_padre_enfermedad_mentalPresente',
            'hijo_de_padre_enfermedad_mentalTipo',
            'hijo_de_padre_enfermedad_mentalTratamiento',
            'hijo_de_padre_enfermedad_mentalFecha',
            'checkboxnino_acompanante_madre_privacion_libertad',
            'nino_acompanante_madre_privacion_libertadPresente',
            'nino_acompanante_madre_privacion_libertadTipo',
            'nino_acompanante_madre_privacion_libertadTratamiento',
            'nino_acompanante_madre_privacion_libertadFecha',
        )->where('pacienteantecedentes.citapaciente_id', $request->citaPaciente_id)
        ->first();
        return response()->json($fechAntecedentes, 200);
    }

    public function otrosAntecedentes(Request $request)
    {
        $request['usuario_id'] = auth()->user()->id;
        OtrosAntecedentesPersonale::create($request->all());
        return response()->json([
            'message' => 'Antecedente guardado con exito'
        ]);

    }

    public function fetchOtrosAntecedentes(Request $request)
    {
        $pacienteantecedente = OtrosAntecedentesPersonale::select('otros_antecedentes_personales.id','c.Descripcion',
        'otros_antecedentes_personales.created_at','otros_antecedentes_personales.checkboxOtras_enfermedades',
        'otros_antecedentes_personales.Descripcion', 'u.name', 'c.Descripcion as cie')
            ->leftjoin('cie10s as c', 'otros_antecedentes_personales.cie10_id', 'c.id')
            ->join('users as u', 'otros_antecedentes_personales.Usuario_id', 'u.id')
            ->where('otros_antecedentes_personales.Paciente_id', $request->paciente_id)
            ->orderBy('otros_antecedentes_personales.created_at', 'desc')
            ->get();
        return response()->json($pacienteantecedente, 200);
    }

    public function antecedentesFarmacologia(Request $request)
    {
        $anteFarmacologia = AntecedenteFarmacologico::select(['antecedente_farmacologicos.id','antecedente_farmacologicos.created_at','antecedente_farmacologicos.utiempo','antecedente_farmacologicos.frecuencia',
        'antecedente_farmacologicos.alergia','antecedente_farmacologicos.observacionAlergia', 'd.Producto'])
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antecedente_farmacologicos.Citapaciente_id')
        ->leftjoin('detallearticulos as d', 'antecedente_farmacologicos.detallearticulo_id', 'd.id')
        ->where('cp.Paciente_id', $request->paciente_id)
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->get();
        return response()->json($anteFarmacologia, 200);

    }

    public function getToxicologicos(citapaciente $citapaciente)
    {
        $anttoxicologicos = AntecedenteToxicologico::where('Citapaciente_id', $citapaciente->id)->first();
        return response()->json($anttoxicologicos, 200);
    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }
    public function antecedentesOcupacional($cita_paciente_id)
    {
        $antedentesOcupacionales = Pacienteantecedente::select('pacienteantecedentes.Descripcion',
            'pacienteantecedentes.created_at', 'pacienteantecedentes.Descripcion', 'u.name', 'c.Descripcion as cie')
            ->join('cita_paciente', 'pacienteantecedentes.citapaciente_id', 'cita_paciente.id')
            ->leftjoin('cie10s as c', 'pacienteantecedentes.cie10_id', 'c.id')
            ->join('users as u', 'pacienteantecedentes.Usuario_id', 'u.id')
            ->where('citapaciente_id', $cita_paciente_id)
            ->orderBy('pacienteantecedentes.created_at', 'desc')
            ->get();
        return response()->json($antedentesOcupacionales, 200);

    }
    public function saveAntecedentesOcupacional(request $request){
        $antecedentes = Pacienteantecedente::where('citapaciente_id', $request->citapaciente_id)->first();
            $request['usuario_id'] = auth()->user()->id;
            Pacienteantecedente::create($request->all());
            return response()->json([
                'message' => 'Antecedentes patologicos guardados con exito!'
            ]);
        }

}
