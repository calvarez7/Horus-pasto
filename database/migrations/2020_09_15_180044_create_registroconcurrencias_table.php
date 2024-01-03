<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroconcurrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registroconcurrencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('Pacientes');
            $table->bigInteger('auditor_id')->unsigned()->index();
            $table->foreign('auditor_id')->references('id')->on('users');
            $table->bigInteger('cie10_id')->nullable()->unsigned()->index();
            $table->foreign('cie10_id')->references('id')->on('cie10s');
            $table->bigInteger('referencia_id')->unsigned()->index();
            $table->foreign('referencia_id')->references('id')->on('referencias');
            $table->string('ips_atencion')->nullable();
            $table->string('costo_atencion')->nullable();
            $table->string('via_ingreso')->nullable();
            $table->string('reingreso_hospitalización15días')->nullable();
            $table->string('unidad_funcional')->nullable();
            $table->string('fecha_egreso')->nullable();
            $table->string('destino_egreso')->nullable();
            $table->string('dias_estancia_observacion')->nullable();
            $table->string('dias_estancia_intensivo')->nullable();
            $table->string('dias_estancia_intermedio')->nullable();
            $table->string('dias_estancia_basicos')->nullable();
            $table->string('estancia_total_dias')->nullable();
            $table->string('especialidad_tratante')->nullable();
            $table->string('peso_rn')->nullable();
            $table->string('edad_gestacional')->nullable();
            $table->string('hospitalizacion_evitable')->nullable();
            $table->string('reporte_patologia_diagnostica')->nullable();
            $table->string('eventos_seguridad')->nullable();
            $table->string('eventos_centinelas')->nullable();
            $table->string('eventos_notificacion_inmediata')->nullable();
            $table->string('descripicion_gestion_realizada')->nullable();
            $table->string('costo_evitado')->nullable();
            $table->string('descripcion_costo_evitado')->nullable();
            $table->string('valor_costo_evitado')->nullable();
            $table->string('costo_evitable')->nullable();
            $table->string('descripción_costo_evitable')->nullable();
            $table->string('valor_costo_evitable')->nullable();
            $table->string('alto_costo')->nullable();
            $table->string('incumplimiento_habilitación')->nullable();
            $table->string('descripcion_habilitación')->nullable();
            $table->string('asesoria_especialista')->nullable();
            $table->string('numero_factura')->nullable();
            $table->string('lider_concurrencia')->nullable();
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
        Schema::dropIfExists('registroconcurrencias');
    }
}
