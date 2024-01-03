<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadpacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividadpacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('actividad_id')->default(1)->unsigned()->index();
            $table->foreign('actividad_id')->references('id')->on('actividadesbasicas');
            $table->bigInteger('citaPaciente_id')->unsigned()->index();
            $table->foreign('citaPaciente_id')->references('id')->on('cita_paciente');
            $table->string('descripcion')->nullable();
            $table->integer('puntage')->nullable();
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
        Schema::dropIfExists('actividadpacientes');
    }
}
