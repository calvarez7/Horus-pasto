<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaInsatisfechasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanda_insatisfechas', function (Blueprint $table) {
            $table->id();
            $table->string('observacion');
            $table->bigInteger('cita_asignada_id')->nullable()->unsigned()->index();
            $table->foreign('cita_asignada_id')->references('id')->on('citas');
            $table->bigInteger('especialidad_id')->unsigned()->index();
            $table->foreign('especialidad_id')->references('id')->on('especialidades');
            $table->bigInteger('tipo_agenda_id')->unsigned()->index();
            $table->foreign('tipo_agenda_id')->references('id')->on('tipo_agendas');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('estado_id')->default(1)->unsigned()->index();
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
        Schema::dropIfExists('demanda_insatisfechas');
    }
}
