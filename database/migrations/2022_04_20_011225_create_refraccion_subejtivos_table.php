<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefraccionSubejtivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refraccion_subejtivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queratometria_od')->nullable();
            $table->string('queratometria_oi')->nullable();
            $table->string('refraccion_od')->nullable();
            $table->string('refraccionAv_od')->nullable();
            $table->string('refraccion_oi')->nullable();
            $table->string('refraccionAv_oi')->nullable();
            $table->string('subjetivo_od')->nullable();
            $table->string('subjetivoAv_od')->nullable();
            $table->string('subjetivo_oi')->nullable();
            $table->string('subjetivoAv_oi')->nullable();
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
        Schema::dropIfExists('refraccion_subejtivos');
    }
}
