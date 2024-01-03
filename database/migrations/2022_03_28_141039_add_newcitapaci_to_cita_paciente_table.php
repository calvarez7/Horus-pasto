<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewcitapaciToCitaPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->string('ciclo_vida')->nullable();
            $table->bigInteger('especialidad_id')->nullable()->unsigned()->index();
            $table->foreign('especialidad_id')->references('id')->on('especialidades');
            $table->bigInteger('actividad_id')->nullable()->unsigned()->index();
            $table->foreign('actividad_id')->references('id')->on('tipo_agendas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            //
        });
    }
}
