<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeguimientocovisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientocovis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_responsable_seguimiento')->unsigned()->index();
            $table->foreign('user_responsable_seguimiento')->references('id')->on('users');
            $table->bigInteger('detalle_atencion_contingencia_id')->unsigned()->index();
            $table->foreign('detalle_atencion_contingencia_id')->references('id')->on('detalle_atencion_contingencia');
            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->boolean('contacto_fallido');
            $table->string('clasificacion_caso')->nullable();
            $table->string('caso')->nullable();
            $table->boolean('toma_muestra')->nullable();
            $table->date('fecha_realizacion')->nullable();
            $table->date('fecha_resultado')->nullable();
            $table->date('fecha_inicio_sintomas')->nullable();
            $table->string('resultado')->nullable();
            $table->string('quien_toma_muestra')->nullable();
            $table->string('quien_procesa_muestra')->nullable();
            $table->text('resultado_muestra')->nullable();
            $table->boolean('requiere_hospitalizacion')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->text('nombre_institucion')->nullable();
            $table->string('tipo_hospitalizacion')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->string('estado_alta')->nullable();
            $table->boolean('asintomatico')->nullable();
            $table->boolean('fiebre_mayor38')->nullable();
            $table->boolean('tos')->nullable();
            $table->boolean('odinofagia')->nullable();
            $table->boolean('alteracion_olfato')->nullable();
            $table->boolean('alteracion_gusto')->nullable();
            $table->boolean('anorexia')->nullable();
            $table->boolean('cefalea')->nullable();
            $table->boolean('fatiga_adinamia')->nullable();
            $table->boolean('dificultad_espiratoria')->nullable();
            $table->boolean('somnolencia')->nullable();
            $table->boolean('expectoracion')->nullable();
            $table->boolean('vomito_diarrea_intratable ')->nullable();
            $table->boolean('paciente_adomicilio ')->nullable();
            $table->string('frecuencia_respiratoria')->nullable();
            $table->string('saturacion_oxigeno')->nullable();
            $table->string('presion_sistolica')->nullable();
            $table->string('pulso_minuto')->nullable();
            $table->string('temperatura')->nullable();
            $table->boolean('requiere_oxigenoterapia')->nullable();
            $table->string('oxigeno_indicado')->nullable();
            $table->string('destino_paciente')->nullable();
            $table->boolean('suguimiento')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('tipoMuestra')->nullable();
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
        Schema::dropIfExists('seguimientocovis');
    }
}
