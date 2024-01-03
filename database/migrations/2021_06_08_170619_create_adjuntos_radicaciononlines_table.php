<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntosRadicaciononlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuntos_radicaciononlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('radicaciononline_id')->unsigned()->nullable()->index();
            $table->foreign('radicaciononline_id')->references('id')->on('radicacion_onlines');
            $table->bigInteger('gestionradicaciononline_id')->unsigned()->nullable()->index();
            $table->foreign('gestionradicaciononline_id')->references('id')->on('gestion_radicaciononlines');
            $table->string('nombre');
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
        Schema::dropIfExists('adjuntos_radicaciononlines');
    }
}
