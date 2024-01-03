<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjuntosTutelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuntos_tutelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Tutela_id')->nullable()->unsigned()->index();
            $table->foreign('Tutela_id')->references('id')->on('Tutelas');            
            $table->bigInteger('Respuesta_id')->nullable()->unsigned()->index();
            $table->foreign('Respuesta_id')->references('id')->on('Respuestatutelas');
            $table->string('Nombre');
            $table->string('Ruta');
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
        Schema::dropIfExists('adjuntos_tutelas');
    }
}
