<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleAtencionContingenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_atencion_contingencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('seguimiento_atencion_contingencia_id')->unsigned()->index();
            $table->foreign('seguimiento_atencion_contingencia_id')->references('id')->on('seguimiento_atencion_contingencia');
            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->string('clasificacion_caso')->nullable();
            $table->string('caso')->nullable();
            $table->boolean('toma_muestra')->nullable();
            $table->date('fecha_realizacion')->nullable();
            $table->date('fecha_resultado')->nullable();
            $table->string('resultado')->nullable();
            $table->string('quien_toma_muestra')->nullable();
            $table->string('quien_procesa_muestra')->nullable();
            $table->text('resultado_muestra')->nullable();
            $table->boolean('contacto_estrecho')->nullable();
            $table->boolean('vacunacion_estacional_vigente')->nullable();
            $table->boolean('antibioticos_ultima_semana')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->text('nombre_institucion')->nullable();
            $table->string('tipo_hospitalizacion')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->string('estado_alta')->nullable();
            $table->boolean('gestante')->nullable();
            $table->json('poblacion_riesgo')->nullable();
            $table->boolean('tratamiento_biologico')->nullable();
            $table->boolean('quimioterapia')->nullable();
            $table->date('fecha_inicio_sintomas')->nullable();
            $table->boolean('requiere_seguimiento')->nullable();
            $table->string('tipoMuestra')->nullable();
            $table->boolean('requiere_hospitalizacion')->nullable();
            $table->boolean('contacto_fallido')->nullable();
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
        Schema::dropIfExists('detalle_atencion_contingencia');
    }
}
