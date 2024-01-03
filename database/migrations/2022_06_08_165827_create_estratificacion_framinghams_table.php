<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstratificacionFraminghamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estratificacion_framinghams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tratamiento')->nullable();
            $table->integer('edad_puntaje')->nullable();
            $table->integer('colesterol_total')->nullable();
            $table->integer('colesterol_puntaje')->nullable();
            $table->integer('colesterol_hdl')->nullable();
            $table->integer('colesterol_puntajehdl')->nullable();
            $table->integer('fumador_puntaje')->nullable();
            $table->integer('arterial_puntaje')->nullable();
            $table->double('totales')->nullable();
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
        Schema::dropIfExists('estratificacion_framinghams');
    }
}
