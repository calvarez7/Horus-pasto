<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntoNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjunto_novedades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('novedad_id')->unsigned()->nullable()->index();
            $table->foreign('novedad_id')->references('id')->on('novedades_pacientes');
            $table->string('nombre')->nullable();
            $table->string('ruta');
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
        Schema::dropIfExists('adjunto_novedades');
    }
}
