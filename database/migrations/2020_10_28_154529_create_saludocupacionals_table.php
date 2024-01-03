<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaludocupacionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saludocupacionals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->text('proceso_cognitivo')->nullable();
            $table->text('memoria')->nullable();
            $table->text('atencion')->nullable();
            $table->text('lenguaje')->nullable();
            $table->text('ubicacion')->nullable();
            $table->text('estado_mental')->nullable();
            $table->text('presentacion_personal')->nullable();
            $table->text('pensamiento_logico')->nullable();
            $table->text('introspeccion')->nullable();
            $table->text('prospeccion')->nullable();
            $table->text('social')->nullable();
            $table->text('familiar')->nullable();
            $table->text('laboral')->nullable();
            $table->text('academica')->nullable();
            $table->text('analisis_diagnostico')->nullable();
            $table->text('recoleccion_datos')->nullable();
            $table->string('area_familiar')->nullable();
            $table->string('area_mental')->nullable();
            $table->string('antecedentes_mentales')->nullable();
            // otro
            $table->string('respiracion_modo')->nullable();
            $table->string('respiracion_tipo')->nullable();
            $table->string('respiracion_fonorespiratoria')->nullable();
            $table->string('perimetros_biaxilar')->nullable();
            $table->string('perimetros_xifoideo')->nullable();
            $table->string('perimetros_abdominal')->nullable();
            $table->string('respiracion_prueba_glatzer')->nullable();
            $table->string('tiempos_respiracion_inspiracion')->nullable();
            $table->string('tiempos_respiracion_espiracion')->nullable();
            $table->string('timbre')->nullable();
            $table->string('intensidad')->nullable();
            $table->string('tono')->nullable();
            $table->string('cierre_glotico')->nullable();
            $table->string('lugar_cabeza')->nullable();
            $table->string('lugar_frente')->nullable();
            $table->string('lugar_nasales')->nullable();
            $table->string('lugar_mejillas')->nullable();
            $table->string('lugar_cuello')->nullable();
            $table->string('voz_observaciones')->nullable();
            $table->string('musculatura_laringea_normal')->nullable();
            $table->string('musculatura_laringea_irritada')->nullable();
            $table->string('musculatura_laringea_inflamada')->nullable();
            $table->string('musculatura_laringea_placas')->nullable();
            $table->string('musculatura_extralaringea_dolor_palpar')->nullable();
            $table->string('musculatura_extralaringea_dolor_deglutir')->nullable();
            $table->string('musculatura_extralaringea_tono_normal')->nullable();
            $table->string('musculatura_extralaringea_tono_aumentado')->nullable();
            $table->string('remision_especialista')->nullable();
            $table->string('aptitud_laboral_psicologia')->nullable();
            $table->string('aptitud_laboral_voz')->nullable();
            $table->string('aptitud_laboral_visiomretria')->nullable();
            $table->string('aptitud_laboral_medico')->nullable();
            $table->string('riesgo_ocupacionales')->nullable();
            $table->string('origen_enfermedades')->nullable();
            $table->string('antecedente_accidente_trabajo')->nullable();
            $table->string('antecedente_enfermedad_laboral')->nullable();
            $table->string('patologia_osteomuscular')->nullable();
            $table->string('patologia_cardiovascular')->nullable();
            $table->string('patologia_metabolica')->nullable();
            $table->string('diagnosticos_infecciosos_parasitaria')->nullable();
            $table->string('diagnosticos_neoplasicos')->nullable();
            $table->string('patologia_sangre_hematopoyeticos')->nullable();
            $table->string('trastronos_mentales_comportamiento')->nullable();
            $table->string('patologia_sistema_nervioso')->nullable();
            $table->string('patologia_ojo_anexos')->nullable();
            $table->string('patologia_oido_apofisis_mastoides')->nullable();
            $table->string('patologia_sistema_respiratorio')->nullable();
            $table->string('patologia_aparato_digestivo')->nullable();
            $table->string('patologia_piel_tejido_subcutaneo')->nullable();
            $table->string('patologia_aparato_urinario')->nullable();
            $table->string('malformacion_congenitas')->nullable();
            // VISIOMETRIA
            $table->string('avcc_ojoderecho')->nullable();
            $table->string('avcc_ojoizquierdo')->nullable();
            $table->string('avsc_ojoderecho')->nullable();
            $table->string('avsc_ojoizquierdo')->nullable();
            $table->string('avscav_ojoizquierdo')->nullable();
            $table->string('avscav_ojoderecho')->nullable();
            $table->string('avcerca_ojoderecho')->nullable();
            $table->string('avcerca_ojoizquierdo')->nullable();
            $table->string('motilidad_ocular')->nullable();
            $table->string('estereopsis')->nullable();
            $table->string('vision_color')->nullable();
            $table->string('retinoscopia_ojoderecho')->nullable();
            $table->string('retinoscopia_ojoizquierdo')->nullable();
            $table->string('gionios_ojoderecho')->nullable();
            $table->string('gionios_ojoizquierdo')->nullable();
            $table->string('fondo_ojoderecho')->nullable();
            $table->string('fondo_ojoizquierdo')->nullable();
            // SIN CONDICIONAR
            $table->string('vacunas')->nullable();
            $table->string('hepatitis')->nullable();
            $table->string('dosis_v')->nullable();
            $table->string('ultima_dosis')->nullable();
            $table->string('t_t')->nullable();
            $table->string('t_d')->nullable();
            $table->string('dosi')->nullable();
            $table->string('otras')->nullable();
            $table->string('Fechas')->nullable();
            // ginecoobstetricos
            $table->string('ginecoobstetricos')->nullable();
            $table->string('menarquia')->nullable();
            $table->string('ciclos')->nullable();
            $table->string('fum')->nullable();
            $table->string('g')->nullable();
            $table->string('p')->nullable();
            $table->string('c')->nullable();
            $table->string('a')->nullable();
            $table->string('v')->nullable();
            $table->string('fup')->nullable();
            $table->string('planificacion')->nullable();
            $table->string('metodo')->nullable();
            $table->string('ultima_citologia')->nullable();
            $table->string('resultado')->nullable();
            $table->string('tratada')->nullable();
            // HABITOS
            $table->string('fuma')->nullable();
            $table->string('fumo')->nullable();
            $table->string('anos_fumador')->nullable();
            $table->string('cigarrillos_al_dia')->nullable();
            $table->string('hace_cuanto_no_fuma')->nullable();
            $table->string('alcohol')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('tipo')->nullable();
            $table->string('sustancias_psico_activas')->nullable();
            $table->string('cuales')->nullable();
            $table->string('practica_deporte')->nullable();
            $table->string('cual')->nullable();
            $table->string('regularidad')->nullable();
            // REVISION POR SISTEMAS
            $table->string('cabeza_y_orl')->nullable();
            $table->string('sistema_neurologico')->nullable();
            $table->string('sistema_cardiopulmonar')->nullable();
            $table->string('sistema_digestivo')->nullable();
            $table->string('sistema_musculo_esqueletico')->nullable();
            $table->string('sistema_genitourinario')->nullable();
            $table->string('piel_y_faneras')->nullable();
            $table->string('otros')->nullable();
            // OTROS NUEVOS

            $table->text('observacion_vacunas')->nullable();
            $table->text('espirometria')->nullable();
            $table->text('espirometria_si')->nullable();
            $table->text('espirometria_no')->nullable();
            // CONDICIONES DE SALUD
            $table->string('tipoExamen')->nullable();
            $table->string('factoresRiesgo')->nullable();
            $table->string('antecedentesenfermedad')->nullable();
            $table->string('origenEnfermedades')->nullable();
            $table->string('antecedentedetrabajo')->nullable();
            $table->string('antecedenteenfermedadlaboral')->nullable();
            $table->string('enfermedadComun')->nullable();
            $table->string('antecedentesfamiliares')->nullable();
            $table->string('patologiaAntecedente')->nullable();
            $table->string('masaCorporal')->nullable();
            $table->string('patologiaOsteomuscular')->nullable();
            $table->string('patologiaCardiovascular')->nullable();
            $table->string('patologiaMetabolica')->nullable();
            $table->string('infecciososParasitaria')->nullable();
            $table->string('patologiaSangre')->nullable();
            $table->string('trastronosMentales')->nullable();
            $table->string('patologiaNervioso')->nullable();
            $table->string('patologiaOjo')->nullable();
            $table->string('patologiaOido')->nullable();
            $table->string('patologiaRespiratorio')->nullable();
            $table->string('patologiaDigestivo')->nullable();
            $table->string('patologiaPiel')->nullable();
            $table->string('patologiaUrinario')->nullable();
            $table->string('malformacionCongenitas')->nullable();
            // EXAMEN FISICO
            $table->string('condiciones_generales')->nullable();
            $table->string('dominancia_motora')->nullable();
            $table->integer('talla')->nullable();
            $table->integer('peso')->nullable();
            $table->string('kg_p_a')->nullable();
            $table->string('f_c')->nullable();
            $table->string('f_r')->nullable();
            $table->string('imc')->nullable();
            $table->string('t')->nullable();
            $table->string('c_')->nullable();
            $table->string('sato2')->nullable();
            $table->string('cabeza')->nullable();
            $table->string('ojos')->nullable();
            $table->string('fondo_de_ojos')->nullable();
            $table->string('oidos')->nullable();
            $table->string('otoscopia')->nullable();
            $table->string('nariz')->nullable();
            $table->string('boca')->nullable();
            $table->string('dentadura')->nullable();
            $table->string('faringe')->nullable();
            $table->string('cuello')->nullable();
            $table->string('mamas')->nullable();
            $table->string('torax')->nullable();
            $table->string('corazon')->nullable();
            $table->string('pulmones')->nullable();
            $table->string('columna')->nullable();
            $table->string('abdomen')->nullable();
            $table->string('genitales_externos')->nullable();
            $table->string('miembros_sup')->nullable();
            $table->string('miembros_inf')->nullable();
            $table->string('reflejos')->nullable();
            $table->string('motilidad')->nullable();
            $table->string('fuerza_muscular')->nullable();
            $table->string('marcha')->nullable();
            $table->string('piel_faneras')->nullable();
            $table->string('ampliacion_hallazgos')->nullable();
            $table->string('vigilancia_epidemiologico')->nullable();
            $table->string('firma_medico_examinador')->nullable();
            $table->string('firma_trabajador')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saludocupacionals');
    }
}
