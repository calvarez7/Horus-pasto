<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguimientocovidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientocovids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('registrocovi_id')->unsigned()->index();
            $table->foreign('registrocovi_id')->references('id')->on('registrocovis');
            $table->string('contacto_fue')->nullable();
            $table->string('actualizar_info_hospitalizaciones_paciente')->nullable();
            $table->string('institucion_realiza_hospitalizacion')->nullable();
            $table->string('tipo_hospitalizacion')->nullable();
            $table->string('soporte_ventilatorio')->nullable();
            $table->string('soporte_hemodinamico')->nullable();
            $table->date('fecha_ingreso_hospitalizacion')->nullable();
            $table->string('se_encuentra_hospitalizado')->nullable();
            $table->date('fecha_egreso_hospitalizacion')->nullable();
            $table->string('estado_alta')->nullable();
            $table->string('paciente_sintomas')->nullable();
            $table->string('ha_presentado_fiebre')->nullable();
            $table->string('ha_presentado_tos')->nullable();
            $table->string('ha_presentado_dificultad_respiratoria')->nullable();
            $table->string('ha_presentado_dolor_garganta')->nullable();
            $table->string('ha_presentado_fatiga')->nullable();
            $table->string('ha_presentado_cefalea')->nullable();
            $table->string('que_otros_sintomas')->nullable();
            $table->string('ha_presentado_sintomas_alarma')->nullable();
            $table->string('detalles_signos_alarmas')->nullable();
            $table->string('respiracion_rapida_taquipnea')->nullable();
            $table->string('fiebre_mas_2_dias')->nullable();
            $table->string('pecho_suena_sibilancias')->nullable();
            $table->string('somnolencia_letargia')->nullable();
            $table->string('convulsiones')->nullable();
            $table->string('deterioro_rapido_estado_general')->nullable();
            $table->string('registrado_segcovid')->nullable();
            $table->string('pruebas_diagnostico_covid')->nullable();
            $table->string('tipo_prueba_diagnostica_covid')->nullable();
            $table->date('fecha_realizacion_prueba')->nullable();
            $table->date('fecha_recepcion_prueba')->nullable();
            $table->string('ips_toma_muestra')->nullable();
            $table->string('resultado_pruebas')->nullable();
            $table->date('fecha_reporte_resultado_covid')->nullable();
            $table->string('ips_procesa_muestra')->nullable();
            $table->string('otra_ips')->nullable();
            $table->string('numero_nit')->nullable();
            $table->string('observaciones')->nullable();
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
        Schema::dropIfExists('seguimientocovids');
    }
}
