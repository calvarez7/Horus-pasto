<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdherenciaFarmacoterapeuticasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adherencia_farmacoterapeuticas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->string('criterio_quimico');
            $table->string('dejado_medicamento');
            $table->string('dias_notomomedicamento');
            $table->string('finsemana_notomomedicamento');
            $table->string('finsemana_olvidomedicamento');
            $table->string('hora_indicada');
            $table->string('olvido_medicamento');
            $table->string('porcentaje');
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
        Schema::dropIfExists('adherencia_farmacoterapeuticas');
    }
}
