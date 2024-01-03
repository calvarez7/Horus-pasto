<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalisisEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analisis_eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('eventosadverso_id')->unsigned()->index();
            $table->foreign('eventosadverso_id')->references('id')->on('eventos_adversos');
            $table->date('fecha_analisis')->nullable();
            $table->text('cronologia')->nullable();
            $table->text('consecuencias')->nullable();
            $table->text('acciones_inseguras')->nullable();
            $table->string('clasificacion')->nullable();
            $table->text('paciente')->nullable();
            $table->text('tarea_tecnologia')->nullable();
            $table->text('individuo')->nullable();
            $table->text('equipo_trabajo')->nullable();
            $table->text('ambiente')->nullable();
            $table->text('organizacion')->nullable();
            $table->text('contexto')->nullable();
            $table->text('accion_mejora')->nullable();
            $table->string('responsable')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_finalizacion')->nullable();
            $table->integer('porcentaje_mejora')->nullable();
            $table->date('fecha_seguimiento')->nullable();
            $table->integer('porcentaje_cumplimiento')->nullable();
            $table->text('elemento_funcion')->nullable();
            $table->text('modo_fallo')->nullable();
            $table->text('efecto')->nullable();
            $table->integer('npr')->nullable();
            $table->text('acciones_propuestas')->nullable();
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
        Schema::dropIfExists('analisis_eventos');
    }
}
