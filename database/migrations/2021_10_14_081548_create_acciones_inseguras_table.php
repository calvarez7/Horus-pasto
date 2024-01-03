<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccionesInsegurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acciones_inseguras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('condiciones_paciente')->nullable();
            $table->string('metodos')->nullable();
            $table->string('colaborador')->nullable();
            $table->string('equipo_trabajo')->nullable();
            $table->string('ambiente')->nullable();
            $table->string('recursos')->nullable();
            $table->string('contexto')->nullable();
            $table->bigInteger('analisisevento_id')->unsigned()->index();
            $table->foreign('analisisevento_id')->references('id')->on('analisis_eventos');
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
        Schema::dropIfExists('acciones_inseguras');
    }
}
