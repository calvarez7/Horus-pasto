<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatologiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patologias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('CitaPaciente_id')->unsigned()->index();
            $table->foreign('CitaPaciente_id')->references('id')->on('cita_paciente');
            $table->string('Patologiacancelactual')->nullable();
            $table->string('fdxcanceractual')->nullable();
            $table->string('flaboratoriopatologia')->nullable();
            $table->string('tumorsegunbiopsia')->nullable();
            $table->string('localizacioncancer')->nullable();
            $table->string('Dukes')->nullable();
            $table->string('gleason')->nullable();
            $table->string('Her2')->nullable();
            $table->string('Estadificaciónclinica')->nullable();
            $table->string('estadificacioninicial')->nullable();
            $table->string('fechaestadificacion')->nullable();
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
        Schema::dropIfExists('patologias');
    }
}
