<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformacionSaludsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informacion_saluds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('info_salud')->nullable();
            $table->string('tipo_info_salud')->nullable();
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
        Schema::dropIfExists('informacion_saluds');
    }
}
