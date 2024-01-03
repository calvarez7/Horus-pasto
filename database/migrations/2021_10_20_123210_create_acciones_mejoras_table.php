<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccionesMejorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acciones_mejoras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->text('responsables');
            $table->string('fecha_cumplimiento');
            $table->string('fecha_seguimiento');
            $table->string('estado');
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
        Schema::dropIfExists('acciones_mejoras');
    }
}
