<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaInducidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanda_inducidas', function (Blueprint $table) {
            $table->id();
            $table->string('tipoDemandaInducida')->nullable();
            $table->string('programaremitido')->nullable();
            $table->date('fecha_dx_riesgocardiovascular')->nullable();
            $table->string('descripcion_evento_saludpublica')->nullable();
            $table->string('descripcion_otro_programa')->nullable();
            $table->boolean('demanda_inducida_efectiva')->nullable();
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('usuario_genera_id')->unsigned()->index();
            $table->foreign('usuario_genera_id')->references('id')->on('users');
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
        Schema::dropIfExists('demanda_inducidas');
    }
}
