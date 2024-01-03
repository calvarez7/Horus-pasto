<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCupsordenCitapacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupsorden_citapacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cup_id')->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('cups');
            $table->bigInteger('orden_id')->unsigned()->index();
            $table->foreign('orden_id')->references('id')->on('ordens');
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
        Schema::dropIfExists('cupsorden_citapacientes');
    }
}
