<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstratificacionFindrisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estratificacion_findrisks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('edad_puntaje')->nullable();
            $table->integer('indice_corporal')->nullable();
            $table->integer('perimetro_abdominal')->nullable();
            $table->string('actividad_fisica')->nullable();
            $table->integer('puntaje_fisica')->nullable();
            $table->integer('frutas_verduras')->nullable();
            $table->string('hipertension')->nullable();
            $table->integer('resultado_hipertension')->nullable();
            $table->string('glucosa')->nullable();
            $table->integer('resultado_glucosa')->nullable();
            $table->string('diabetes')->nullable();
            $table->string('parentezco')->nullable();
            $table->integer('resultado_diabetes')->nullable();
            $table->integer('totales')->nullable();
            $table->bigInteger('usuario_id')->nullable()->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('estratificacion_findrisks');
    }
}
