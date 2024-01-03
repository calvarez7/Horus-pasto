<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultadosLaboratoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultados_laboratorios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('laboratorio')->nullable();
            $table->string('resultado_lab')->nullable();
            $table->string('factor_rh')->nullable();
            $table->date('fecha_laboratorio')->nullable();
            $table->string('adjunto')->nullable();
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('citapaciente_id')->unsigned()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('resultados_laboratorios');
    }
}
