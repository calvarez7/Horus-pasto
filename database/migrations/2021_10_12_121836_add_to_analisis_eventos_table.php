<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToAnalisisEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->string('metodologia')->nullable();
            $table->text('que_fallo')->nullable();
            $table->text('porque_fallo')->nullable();
            $table->text('que_causo')->nullable();
            $table->text('accion_implemento')->nullable();
            $table->string('despues_adminmedicamento')->nullable();
            $table->string('factores_explicarelevento')->nullable();
            $table->string('desaparecio_suspendermedicamento')->nullable();
            $table->string('reaccion_medicamentosospechoso')->nullable();
            $table->string('ampliar_informacionpaciente')->nullable();
            $table->string('evaluacion_causalidad')->nullable();
            $table->string('clasificacion_invima')->nullable(); 
            $table->string('seriedad')->nullable();
            $table->string('fecha_muerte')->nullable();
            $table->string('desenlace_evento')->nullable();
            $table->string('causas_esavi')->nullable();
            $table->string('asociacion_esavi')->nullable();
            $table->string('ventana_mayoriesgo')->nullable();
            $table->string('evidencia_asociacioncausal')->nullable();
            $table->string('factores_esavi')->nullable();
            $table->string('farmaco_cinetica')->nullable();
            $table->string('condiciones_farmacocinetica')->nullable();
            $table->string('prescribio_manerainadecuada')->nullable();
            $table->string('medicamento_entrenamiento')->nullable();
            $table->string('potenciales_interacciones')->nullable();
            $table->string('notificacion_refieremedicamento')->nullable();
            $table->string('problema_biofarmaceutico')->nullable();
            $table->string('deficiencias_sistemas')->nullable();
            $table->string('factores_asociados')->nullable();
            $table->string('medicamento_manerainadecuada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analisis_eventos', function (Blueprint $table) {
            $table->string('metodologia')->nullable();
            $table->text('que_fallo')->nullable();
            $table->text('porque_fallo')->nullable();
            $table->text('que_causo')->nullable();
            $table->text('accion_implemento')->nullable();
            $table->string('despues_adminmedicamento')->nullable();
            $table->string('factores_explicarelevento')->nullable();
            $table->string('desaparecio_suspendermedicamento')->nullable();
            $table->string('reaccion_medicamentosospechoso')->nullable();
            $table->string('ampliar_informacionpaciente')->nullable();
            $table->string('evaluacion_causalidad')->nullable();
            $table->string('clasificacion_invima')->nullable();
            $table->string('seriedad')->nullable();
            $table->string('fecha_muerte')->nullable();
            $table->string('desenlace_evento')->nullable();
            $table->string('causas_esavi')->nullable();
            $table->string('asociacion_esavi')->nullable();
            $table->string('ventana_mayoriesgo')->nullable();
            $table->string('evidencia_asociacioncausal')->nullable();
            $table->string('factores_esavi')->nullable();
            $table->string('farmaco_cinetica')->nullable();
            $table->string('condiciones_farmacocinetica')->nullable();
            $table->string('prescribio_manerainadecuada')->nullable();
            $table->string('medicamento_entrenamiento')->nullable();
            $table->string('potenciales_interacciones')->nullable();
            $table->string('notificacion_refieremedicamento')->nullable();
            $table->string('problema_biofarmaceutico')->nullable();
            $table->string('deficiencias_sistemas')->nullable();
            $table->string('factores_asociados')->nullable();
            $table->string('medicamento_manerainadecuada')->nullable();
        });
    }
}
