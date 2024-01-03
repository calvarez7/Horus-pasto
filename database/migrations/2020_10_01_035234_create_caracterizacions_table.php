<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaracterizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracterizacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id_registra')->nullable()->unsigned()->index();
            $table->foreign('user_id_registra')->references('id')->on('users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->string('alergicas')->nullable();
            $table->string('vacunado')->nullable();
            $table->string('vacunarse')->nullable();
            $table->string('zona_vivienda')->nullable();
            $table->string('correo')->nullable();
            $table->string('residencia')->nullable();
            $table->string('telefono1')->nullable();
            $table->string('telefono2')->nullable();
            $table->string('correocontacto')->nullable();
            $table->string('conforman_hogar')->nullable();
            $table->string('tipo_vivienda')->nullable();
            $table->string('hogar_con_agua')->nullable();
            $table->string('alimentos')->nullable();
            $table->string('energia')->nullable();
            $table->string('accesibilidad_vivienda')->nullable();
            $table->string('seguridad_orden')->nullable();
            $table->string('etnia')->nullable();
            $table->string('fisica')->nullable();
            $table->string('auditiva')->nullable();
            $table->string('visual')->nullable();
            $table->string('sordera')->nullable();
            $table->string('intelectual')->nullable();
            $table->string('mental')->nullable();
            $table->string('escolaridad')->nullable();
            $table->string('estrato')->nullable();
            $table->string('orientacion_sexual')->nullable();
            $table->string('oficio')->nullable();
            $table->string('planificando_metodos')->nullable();
            $table->string('planeado_embarazo')->nullable();
            $table->string('citologia_ultimo_ano')->nullable();
            $table->string('tamizaje_Mamografia')->nullable();
            $table->string('tamizaje_Prostata')->nullable();
            $table->string('autocuidado_salud')->nullable();
            $table->string('victima_violencia')->nullable();
            $table->string('victima_desplazamiento')->nullable();
            $table->string('exposicion_tabaco')->nullable();
            $table->string('tabacos_al_dia')->nullable();
            $table->string('expuestotabaco')->nullable();
            $table->string('consume_sustancias_psicoactivas')->nullable();
            $table->string('Frecuencia_consumo_psicoactivas')->nullable();
            $table->string('consume_alcohol')->nullable();
            $table->string('Frecuencia_consumo_alcohol')->nullable();
            $table->string('actividad_fisica')->nullable();
            $table->string('mama')->nullable();
            $table->string('prostata')->nullable();
            $table->string('pulmon')->nullable();
            $table->string('gastrointestinales')->nullable();
            $table->string('cervicouterino')->nullable();
            $table->string('piel')->nullable();
            $table->string('otros')->nullable();
            $table->string('obesidad')->nullable();
            $table->string('tiroides')->nullable();
            $table->string('mellitus')->nullable();
            $table->string('dislipidemia')->nullable();
            $table->string('ansiedad')->nullable();
            $table->string('depresion')->nullable();
            $table->string('esquizofrenia')->nullable();
            $table->string('consumopsicoativo')->nullable();
            $table->string('bipolar')->nullable();
            $table->string('hiperactividad')->nullable();
            $table->string('conductaalimentaria')->nullable();
            $table->string('antecedente_mama')->nullable();
            $table->string('antecedente_prostata')->nullable();
            $table->string('antecedente_pulmon')->nullable();
            $table->string('antecedente_gastrointestinales')->nullable();
            $table->string('antecedente_cervicouterino')->nullable();
            $table->string('antecedente_piel')->nullable();
            $table->string('antecedente_otros')->nullable();
            $table->string('antecedente_obesidad')->nullable();
            $table->string('antecedente_tiroides')->nullable();
            $table->string('antecedente_mellitus')->nullable();
            $table->string('antecedente_dislipidemia')->nullable();
            $table->string('antecedente_cardiopatia')->nullable();
            $table->string('antecedente_hipertension')->nullable();
            $table->string('antecedente_cerebrovascular')->nullable();
            $table->string('antecedente_arterial')->nullable();
            $table->string('antecedente_renal')->nullable();
            $table->string('antecedente_asma')->nullable();
            $table->string('antecedente_epoc')->nullable();
            $table->string('antecedente_bronquitis')->nullable();
            $table->string('antecedente_apnea')->nullable();
            $table->string('antecedenterespiratoria_otros')->nullable();
            $table->string('condiciones_vih')->nullable();
            $table->string('condiciones_autoinmunes')->nullable();
            $table->string('condiciones_biologicos')->nullable();
            $table->string('condiciones_quimioterapia')->nullable();
            $table->string('condiciones_otros')->nullable();
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('caracterizacions');
    }
}
