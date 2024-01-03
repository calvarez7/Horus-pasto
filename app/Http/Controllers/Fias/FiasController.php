<?php

namespace App\Http\Controllers\Fias;

use App\Exports\RutaFiasExport;
use App\Http\Controllers\Controller;
use App\Jobs\GenerarFiasJob;
use App\Modelos\ArtritisFias;
use App\Modelos\CancerFias;
use App\Modelos\Examenfisico;
use App\Modelos\HemofiliaFias;
use App\Modelos\HuerfanasFias;
use App\Modelos\Paciente;
use App\Modelos\RutaRcv;
use App\Modelos\Sedeproveedore;
use App\Modelos\TransplantesFias;
use App\Services\Fias\FiasService;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Facades\Excel;


class FiasController extends Controller
{
    use FileTrait;

    public function allCacRcv(Request $request)
    {
        $rutaRcv = RutaRcv::select(
            'p.Num_Doc', 'sp.Nombre',
            'ruta_rcvs.id','ruta_rcvs.programa_nefroporteccion','ruta_rcvs.dx_hta','ruta_rcvs.fecha_dx_hta','ruta_rcvs.costo_hta',
            'ruta_rcvs.dx_dm','ruta_rcvs.fecha_dx_dm','ruta_rcvs.costo_dm','ruta_rcvs.causa_erc','ruta_rcvs.peso',
            'ruta_rcvs.talla','ruta_rcvs.imc','ruta_rcvs.clasificacion_imc','ruta_rcvs.perimetro_abdominal',
            'ruta_rcvs.tension_arterial_sistolica','ruta_rcvs.tension_arterial_diastolica','ruta_rcvs.escala_framinghan',
            'ruta_rcvs.fecha_escala_framinghan','ruta_rcvs.resultado_diabetes_escala_findrisk',
            'ruta_rcvs.fecha_escala_findrisk','ruta_rcvs.resultado_rcv_control','ruta_rcvs.fecha_tamizaje_rcv',
            'ruta_rcvs.resultado_riesgo_diabetes','ruta_rcvs.fecha_tamizaje_riesgo_diabetes','ruta_rcvs.escala_kaiser',
            'ruta_rcvs.dislipidemia','ruta_rcvs.factores_riesgo','ruta_rcvs.falla_organo_blaco','ruta_rcvs.creatinina',
            'ruta_rcvs.fecha_creatinina','ruta_rcvs.hba1c','ruta_rcvs.fecha_hba1c','ruta_rcvs.albuminuria',
            'ruta_rcvs.fecha_albuminuria','ruta_rcvs.albuminuria_creatinuria','ruta_rcvs.fecha_albuminuria_creatinuria',
            'ruta_rcvs.colesterol','ruta_rcvs.fecha_colesterol','ruta_rcvs.hdl','ruta_rcvs.fecha_hdl','ruta_rcvs.ldl',
            'ruta_rcvs.fecha_ldl','ruta_rcvs.trigliceridos','ruta_rcvs.fecha_trigliceridos','ruta_rcvs.glicemias',
            'ruta_rcvs.fecha_glicemia','ruta_rcvs.pth','ruta_rcvs.fecha_pth','ruta_rcvs.tfg','ruta_rcvs.fecha_tfg',
            'ruta_rcvs.ekg','ruta_rcvs.fecha_ekg','ruta_rcvs.recibe_ieca','ruta_rcvs.recibe_arados','ruta_rcvs.recibe_hipoglicemiantes',
            'ruta_rcvs.actividad_fisica','ruta_rcvs.fecha_prescripcion_actividad_fisica','ruta_rcvs.atencion_recibida',
            'ruta_rcvs.fecha_educacion','ruta_rcvs.tema','ruta_rcvs.medicina_general_y_o_experto','ruta_rcvs.medicina_interna_familiar',
            'ruta_rcvs.medicina_geriatrica','ruta_rcvs.oftalmologia','ruta_rcvs.nutricion','ruta_rcvs.enfermeria',
            'ruta_rcvs.trabajo_social','ruta_rcvs.dx_erc','ruta_rcvs.estadio_erc','ruta_rcvs.fecha_dx_erc_5',
            'ruta_rcvs.programa_seguimiento_erc_hta_dm','ruta_rcvs.tfg_inicio_trr','ruta_rcvs.modo_inicio_trr',
            'ruta_rcvs.fecha_inicio_trr','ruta_rcvs.ingreso_unidad_renal','ruta_rcvs.hemodialisis','ruta_rcvs.dosis_dialisis_ktv',
            'ruta_rcvs.costo_hemodialisis','ruta_rcvs.dialisis_peritoneal','ruta_rcvs.dosis_dialisis_ktv_dpd',
            'ruta_rcvs.numero_horas_hemodialisis','ruta_rcvs.peritonitis','ruta_rcvs.costo_dp','ruta_rcvs.vacuna_hepatitisb',
            'ruta_rcvs.fecha_dx_hepatitisb','ruta_rcvs.fecha_dx_hepatitis_c','ruta_rcvs.rerapia_no_dialitica_erc_5',
            'ruta_rcvs.costo_terapia_erc_5','ruta_rcvs.hemoglobina','ruta_rcvs.albumina_servica','ruta_rcvs.fosforo',
            'ruta_rcvs.valoracio_nefrología','ruta_rcvs.cancer_activo_12_meses','ruta_rcvs.infeccion_cronica_activa_no_tratada',
            'ruta_rcvs.no_manifestado_deseo_trasplantarse','ruta_rcvs.contraindicacion_esperanza_vida_menor_igual_6_meses',
            'ruta_rcvs.autocuidado_adherencia_tratamiento_posttrasplante','ruta_rcvs.enfermedad_cardiaca_cerebrovascular_vascular',
            'ruta_rcvs.contraindicacion_infeccion_vih','ruta_rcvs.contraindicacion_infeccion_vhc','ruta_rcvs.contraindicacion_enfermedad_inmunologica_activa',
            'ruta_rcvs.contraindicacion_enfermedad_pulmonar_cronica','ruta_rcvs.contraindicacion_otras_enfermedades_cronicas',
            'ruta_rcvs.fecha_realizacion_trasplante','ruta_rcvs.ips_donde_espera','ruta_rcvs.recibido_trasplante_renal',
            'ruta_rcvs.eps_realizo_trasplante','ruta_rcvs.ips_realizo_trasplante','ruta_rcvs.tipo_donante',
            'ruta_rcvs.costo_trasplante','ruta_rcvs.presentado_complicacion_con_el_trasplante','ruta_rcvs.fecha_dx_citomegalovirus',
            'ruta_rcvs.fecha_dx_infeccion_hongos','ruta_rcvs.fecha_dx_presentado_infeccion_tuberculosis',
            'ruta_rcvs.fecha_dx_complicacion_cardiovascular','ruta_rcvs.fecha_dx_complicacion_urologica',
            'ruta_rcvs.fecha_dx_complicacion_herida_quirurgica','ruta_rcvs.fecha_primer_dx_cancer','ruta_rcvs.medicamentos_inmunosupresores',
            'ruta_rcvs.recibio_metilprednisolona','ruta_rcvs.recibio_azatriopina','ruta_rcvs.recibio_ciclosporina','ruta_rcvs.recibio_micofenalato',
            'ruta_rcvs.recibio_tracolimus','ruta_rcvs.recibio_prednisona','ruta_rcvs.recibio_mx_no_pos_1','ruta_rcvs.recibio_mx_no_pos_2',
            'ruta_rcvs.recibio_mx_no_pos_3','ruta_rcvs.episodios_rechazo','ruta_rcvs.fecha_primer_episodios_rechazo_agudo',
            'ruta_rcvs.fecha_retorno_dialisis','ruta_rcvs.numero_trasplantes_renales','ruta_rcvs.costo_terapia_postrasplante',
            'ruta_rcvs.meses_prestacion_servicios','ruta_rcvs.costo_total','ruta_rcvs.eps_origen','ruta_rcvs.novedad','ruta_rcvs.causa_muerte',
            'ruta_rcvs.fecha_muerte','ruta_rcvs.codigo_bdua','ruta_rcvs.fecha_corte','ruta_rcvs.fecha_inicio_tratamiento_enfermedad',
            'ruta_rcvs.Hospitalizacion','ruta_rcvs.fecha_hospitalizacion'
        )
        ->join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
        ->where('p.IPS', $request->sede_id)
        ->where('p.Ut', $request->entidad)
        ->get();
        return response()->json($rutaRcv, 200);
    }

    public function allCancerFias(Request $request)
    {
        $cancerFias = CancerFias::select(
            'p.Num_Doc', 'sp.Nombre','base_oncologia.*',
        )
        ->join('pacientes as p', 'base_oncologia.paciente_id', 'p.id')
        ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
        ->where('p.IPS', $request->sede_id)
        ->where('p.Ut', $request->entidad)
        ->get();
        return response()->json($cancerFias, 200);
    }

    public function reportRcv()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(9000000);
        return (new FastExcel(RutaRcv::select(
            'p.Num_Doc', 'sp.Nombre',
            'ruta_rcvs.id','ruta_rcvs.programa_nefroporteccion','ruta_rcvs.dx_hta','ruta_rcvs.fecha_dx_hta','ruta_rcvs.costo_hta',
            'ruta_rcvs.dx_dm','ruta_rcvs.fecha_dx_dm','ruta_rcvs.costo_dm','ruta_rcvs.causa_erc','ruta_rcvs.peso',
            'ruta_rcvs.talla','ruta_rcvs.imc','ruta_rcvs.clasificacion_imc','ruta_rcvs.perimetro_abdominal',
            'ruta_rcvs.tension_arterial_sistolica','ruta_rcvs.tension_arterial_diastolica','ruta_rcvs.escala_framinghan',
            'ruta_rcvs.fecha_escala_framinghan','ruta_rcvs.resultado_diabetes_escala_findrisk',
            'ruta_rcvs.fecha_escala_findrisk','ruta_rcvs.resultado_rcv_control','ruta_rcvs.fecha_tamizaje_rcv',
            'ruta_rcvs.resultado_riesgo_diabetes','ruta_rcvs.fecha_tamizaje_riesgo_diabetes','ruta_rcvs.escala_kaiser',
            'ruta_rcvs.dislipidemia','ruta_rcvs.factores_riesgo','ruta_rcvs.falla_organo_blaco','ruta_rcvs.creatinina',
            'ruta_rcvs.fecha_creatinina','ruta_rcvs.hba1c','ruta_rcvs.fecha_hba1c','ruta_rcvs.albuminuria',
            'ruta_rcvs.fecha_albuminuria','ruta_rcvs.albuminuria_creatinuria','ruta_rcvs.fecha_albuminuria_creatinuria',
            'ruta_rcvs.colesterol','ruta_rcvs.fecha_colesterol','ruta_rcvs.hdl','ruta_rcvs.fecha_hdl','ruta_rcvs.ldl',
            'ruta_rcvs.fecha_ldl','ruta_rcvs.trigliceridos','ruta_rcvs.fecha_trigliceridos','ruta_rcvs.glicemias',
            'ruta_rcvs.fecha_glicemia','ruta_rcvs.pth','ruta_rcvs.fecha_pth','ruta_rcvs.tfg','ruta_rcvs.fecha_tfg',
            'ruta_rcvs.ekg','ruta_rcvs.fecha_ekg','ruta_rcvs.recibe_ieca','ruta_rcvs.recibe_arados','ruta_rcvs.recibe_hipoglicemiantes',
            'ruta_rcvs.actividad_fisica','ruta_rcvs.fecha_prescripcion_actividad_fisica','ruta_rcvs.atencion_recibida',
            'ruta_rcvs.fecha_educacion','ruta_rcvs.tema','ruta_rcvs.medicina_general_y_o_experto','ruta_rcvs.medicina_interna_familiar',
            'ruta_rcvs.medicina_geriatrica','ruta_rcvs.oftalmologia','ruta_rcvs.nutricion','ruta_rcvs.enfermeria',
            'ruta_rcvs.trabajo_social','ruta_rcvs.dx_erc','ruta_rcvs.estadio_erc','ruta_rcvs.fecha_dx_erc_5',
            'ruta_rcvs.programa_seguimiento_erc_hta_dm','ruta_rcvs.tfg_inicio_trr','ruta_rcvs.modo_inicio_trr',
            'ruta_rcvs.fecha_inicio_trr','ruta_rcvs.ingreso_unidad_renal','ruta_rcvs.hemodialisis','ruta_rcvs.dosis_dialisis_ktv',
            'ruta_rcvs.costo_hemodialisis','ruta_rcvs.dialisis_peritoneal','ruta_rcvs.dosis_dialisis_ktv_dpd',
            'ruta_rcvs.numero_horas_hemodialisis','ruta_rcvs.peritonitis','ruta_rcvs.costo_dp','ruta_rcvs.vacuna_hepatitisb',
            'ruta_rcvs.fecha_dx_hepatitisb','ruta_rcvs.fecha_dx_hepatitis_c','ruta_rcvs.rerapia_no_dialitica_erc_5',
            'ruta_rcvs.costo_terapia_erc_5','ruta_rcvs.hemoglobina','ruta_rcvs.albumina_servica','ruta_rcvs.fosforo',
            'ruta_rcvs.valoracio_nefrología','ruta_rcvs.cancer_activo_12_meses','ruta_rcvs.infeccion_cronica_activa_no_tratada',
            'ruta_rcvs.no_manifestado_deseo_trasplantarse','ruta_rcvs.contraindicacion_esperanza_vida_menor_igual_6_meses',
            'ruta_rcvs.autocuidado_adherencia_tratamiento_posttrasplante','ruta_rcvs.enfermedad_cardiaca_cerebrovascular_vascular',
            'ruta_rcvs.contraindicacion_infeccion_vih','ruta_rcvs.contraindicacion_infeccion_vhc','ruta_rcvs.contraindicacion_enfermedad_inmunologica_activa',
            'ruta_rcvs.contraindicacion_enfermedad_pulmonar_cronica','ruta_rcvs.contraindicacion_otras_enfermedades_cronicas',
            'ruta_rcvs.fecha_realizacion_trasplante','ruta_rcvs.ips_donde_espera','ruta_rcvs.recibido_trasplante_renal',
            'ruta_rcvs.eps_realizo_trasplante','ruta_rcvs.ips_realizo_trasplante','ruta_rcvs.tipo_donante',
            'ruta_rcvs.costo_trasplante','ruta_rcvs.presentado_complicacion_con_el_trasplante','ruta_rcvs.fecha_dx_citomegalovirus',
            'ruta_rcvs.fecha_dx_infeccion_hongos','ruta_rcvs.fecha_dx_presentado_infeccion_tuberculosis',
            'ruta_rcvs.fecha_dx_complicacion_cardiovascular','ruta_rcvs.fecha_dx_complicacion_urologica',
            'ruta_rcvs.fecha_dx_complicacion_herida_quirurgica','ruta_rcvs.fecha_primer_dx_cancer','ruta_rcvs.medicamentos_inmunosupresores',
            'ruta_rcvs.recibio_metilprednisolona','ruta_rcvs.recibio_azatriopina','ruta_rcvs.recibio_ciclosporina','ruta_rcvs.recibio_micofenalato',
            'ruta_rcvs.recibio_tracolimus','ruta_rcvs.recibio_prednisona','ruta_rcvs.recibio_mx_no_pos_1','ruta_rcvs.recibio_mx_no_pos_2',
            'ruta_rcvs.recibio_mx_no_pos_3','ruta_rcvs.episodios_rechazo','ruta_rcvs.fecha_primer_episodios_rechazo_agudo',
            'ruta_rcvs.fecha_retorno_dialisis','ruta_rcvs.numero_trasplantes_renales','ruta_rcvs.costo_terapia_postrasplante',
            'ruta_rcvs.meses_prestacion_servicios','ruta_rcvs.costo_total','ruta_rcvs.eps_origen','ruta_rcvs.novedad','ruta_rcvs.causa_muerte',
            'ruta_rcvs.fecha_muerte','ruta_rcvs.codigo_bdua','ruta_rcvs.fecha_corte','ruta_rcvs.fecha_inicio_tratamiento_enfermedad',
            'ruta_rcvs.Hospitalizacion','ruta_rcvs.fecha_hospitalizacion'
        )
        ->join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
        ->get()))->download('file.xlsx');
    }

    public function counterRCV()
    {
        $creatinina   = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', '=', 'p.id')
        ->where('creatinina', '<>', '99')
        ->where('p.Estado_Afiliado', 1)
        ->count();
        $sistolica = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', '=', 'p.id')
        ->where('tension_arterial_sistolica', '<>', '999')
        ->where('p.Estado_Afiliado', 1)
        ->count();
        $diastolica = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', '=', 'p.id')
        ->where('tension_arterial_diastolica', '<>', '999')
        ->where('p.Estado_Afiliado', 1)
        ->count();
        $hipertension = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', '=', 'p.id')
        ->where('ruta_rcvs.dx_hta', 1)
        ->where('p.Estado_Afiliado', 1)
        ->count();
        $diabetes = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', '=', 'p.id')
        ->whereIn('ruta_rcvs.dx_dm', [1,3])
        ->where('p.Estado_Afiliado', 1)
        ->count();

        // $total = $pendientes + $solucionadas;

        return response()->json(
            [
                'creatinina'   => $creatinina,
                'diastolica'   => $diastolica,
                'sistolica' => $sistolica,
                'hipertension' => $hipertension,
                'diabetes' => $diabetes,
            ], 200);
    }

    public function consulta(Request $request)
    {
        // dd('oe', $request['f_inicio']);
        $creatinina   = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->where('creatinina', '<>', '99')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_inicio'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_final'])
        ->count();

        $sistolica = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->where('tension_arterial_sistolica', '<>', '999')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_inicio'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_final'])
        ->count();

        $diastolica = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->where('tension_arterial_diastolica', '<>', '999')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_inicio'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_final'])
        ->count();

        $hipertension = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->where('ruta_rcvs.dx_hta', 1)
        ->where('ruta_rcvs.dx_dm', 2)
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_inicio'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_final'])
        ->count();

        $htaDm = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->where('ruta_rcvs.dx_hta', 1)
        ->where('ruta_rcvs.dx_dm', '!=', 2)
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_inicio'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_final'])
        ->count();

        $diabetes = RutaRcv::join('pacientes as p', 'ruta_rcvs.paciente_id', 'p.id')
        ->whereIn('ruta_rcvs.dx_dm', [1,3])
        ->where('ruta_rcvs.dx_hta', 2)
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_inicio'])
        // ->where('ruta_rcvs.fecha_corte', $request['f_final'])
        ->count();

        return response()->json([
            'creatinina'   => $creatinina,
            'sistolica'    => $sistolica,
            'diastolica'   => $diastolica,
            'hipertension' => $hipertension,
            'diabetes'     => $diabetes,
            'htaDm'        => $htaDm
        ]);
    }

    public function consultaCancer(Request $request)
    {
        $mama   = CancerFias::join('pacientes as p', 'base_oncologia.paciente_id', 'p.id')
        ->whereIn('Codigo_CIE10_de_la_neoplasia_maligna_reportada', ['C500','C501','C502','C503','C504','C505','C506','C508','C509','D057','D059'
        ])
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        ->count();

        $cervix = CancerFias::join('pacientes as p', 'base_oncologia.paciente_id', 'p.id')
        ->whereIn('Codigo_CIE10_de_la_neoplasia_maligna_reportada', ['C530','C531','D060','D061','D069'])
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('base_oncologia.fecha_corte', $request['f_inicio'])
        // ->where('base_oncologia.fecha_corte', $request['f_final'])
        ->count();

        $prostata = CancerFias::join('pacientes as p', 'base_oncologia.paciente_id', 'p.id')
        ->whereIn('Codigo_CIE10_de_la_neoplasia_maligna_reportada', ['C61X','D075'])
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('base_oncologia.fecha_corte', $request['f_inicio'])
        // ->where('base_oncologia.fecha_corte', $request['f_final'])
        ->count();

        $pulmon = CancerFias::join('pacientes as p', 'base_oncologia.paciente_id', 'p.id')
        ->whereIn('Codigo_CIE10_de_la_neoplasia_maligna_reportada', ['C341','C342','C343','C348','C349','D022','C780'])
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('base_oncologia.fecha_corte', $request['f_inicio'])
        // ->where('base_oncologia.fecha_corte', $request['f_final'])
        ->count();

        $colon = CancerFias::join('pacientes as p', 'base_oncologia.paciente_id', 'p.id')
        ->whereIn('Codigo_CIE10_de_la_neoplasia_maligna_reportada', ['C182','C184','C186','C187','C188','C189','D010'])
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        // ->where('base_oncologia.fecha_corte', $request['f_inicio'])
        // ->where('base_oncologia.fecha_corte', $request['f_final'])
        ->count();



        return response()->json([
            'mama'      => $mama,
            'cervix'    => $cervix,
            'prostata'  => $prostata,
            'pulmon'    => $pulmon,
            'colon'     => $colon,
        ]);
    }

    public function consultaHemofilia(Request $request)
    {
        $hemofilia   = HemofiliaFias::join('pacientes as p', 'base_hemofilia.Paciente_id', 'p.id')
        ->whereIn('CODIGO_DE_LA_PATOLOGIA', ['D680','D66X','D66X','D680','D66X','D682','D689','D67X','D680'])
        // ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        ->count();

        return response()->json([
            'hemofilia' => $hemofilia
        ]);
    }

    public function allHemofiliaFias(Request $request)
    {
        $hemofilia = HemofiliaFias::select(
            'p.Num_Doc', 'sp.Nombre','base_hemofilia.*',
        )
        ->join('pacientes as p', 'base_hemofilia.Paciente_id', 'p.id')
        ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
        ->where('p.IPS', $request->sede_id)
        ->where('p.Ut', $request->entidad)
        ->get();
        return response()->json($hemofilia, 200);
    }

    public function sedes(Request $request)
    {
        $sedes = Sedeproveedore::all();
        return response()->json($sedes, 200);
    }

    public function historicoSignos(Request $request)
    {
        $paciente = Paciente::where('Num_Doc', $request->cedula)->first();
        if ($paciente) {
            $examenFisicos = Examenfisico::select('Peso', 'Talla', 'Imc', 'Clasificacion', 'Presionsistolica', 'Presiondiastolica', 'Presionarterialmedia')
            ->where('paciente_id', $paciente->id)
            ->get();
            return response()->json($examenFisicos, 200);
        }

    }

    public function histoPacienteVital(Request $request)
    {
        $paciente = Paciente::where('Num_Doc', $request->cedula)->first();
        return response()->json($paciente, 200);
    }

    public function allArtritis(Request $request)
    {
        try {
            $hemofilia = ArtritisFias::select(
                'p.Num_Doc', 'sp.Nombre','base_artritis.*',
            )
            ->join('pacientes as p', 'base_artritis.Paciente_id', 'p.id')
            ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
            ->where('p.Estado_Afiliado', 1)
            ->where('p.IPS', $request->sede_id)
            ->where('p.Ut', $request->entidad)
            ->get();
            return response()->json($hemofilia, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }

    }


    public function consultartritis(Request $request)
    {
        $artritis   = ArtritisFias::join('pacientes as p', 'base_artritis.Paciente_id', 'p.id')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        ->count();

        return response()->json([
            'artritis' => $artritis
        ]);
    }

    public function allHuerfanas(Request $request)
    {
        $hemofilia = HuerfanasFias::select(
            'p.Num_Doc', 'sp.Nombre','base_vih.*',
        )
        ->join('pacientes as p', 'base_vih.paciente_id', 'p.id')
        ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request->sede_id)
        ->where('p.Ut', $request->entidad)
        ->get();
        return response()->json($hemofilia, 200);
    }


    public function consulthuerfanas(Request $request)
    {
        $huerfanas   = HuerfanasFias::join('pacientes as p', 'base_vih.paciente_id', 'p.id')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        ->count();

        return response()->json([
            'huerfanas' => $huerfanas
        ]);
    }

    public function allTransplantes(Request $request)
    {
        $hemofilia = TransplantesFias::select(
            'p.Num_Doc', 'sp.Nombre','transplantados_fias.*',
        )
        ->join('pacientes as p', 'transplantados_fias.paciente_id', 'p.id')
        ->join('sedeproveedores as sp', 'p.IPS', 'sp.id')
        ->where('p.IPS', $request->sede_id)
        ->where('p.Ut', $request->entidad)
        ->get();
        return response()->json($hemofilia, 200);
    }


    public function consultTransplantes(Request $request)
    {
        $transplantes   = TransplantesFias::join('pacientes as p', 'transplantados_fias.paciente_id', 'p.id')
        ->where('p.Estado_Afiliado', 1)
        ->where('p.IPS', $request['sede_id'])
        ->where('p.Ut', $request['entidad'])
        ->count();

        return response()->json([
            'transplantes' => $transplantes
        ]);
    }

    /**
     * Descarga un archivo txt con la informacion del fias especificado
     * @param Request $request
     * @return Response
     * @author David Peláez
     */
    public function descargar(Request $request){
        try{
            /** se setean variables para evitar el uso de memoria y el tiempo estimado de la construccion de los fias */
            $service = new FiasService();
            $instancia = $service->determinarFias($request->tipo_fias);
            GenerarFiasJob::dispatch($instancia, $request->all(), auth()->user());
            return response()->json(true);

        }catch(\Throwable $th){
            return response()->json($th->getMessage(), 400);
        }
    }

    public function descargarCac(Request $request) {
        $procedure_uno = Collect(DB::select("SET NOCOUNT ON; exec SP_ProgramasCAC ?,?,?", [$request->programa,$request->mes,$request->anio]));
        $result = json_decode($procedure_uno, true);
        return (new FastExcel($result))->download('file.xlsx');
    }
}
