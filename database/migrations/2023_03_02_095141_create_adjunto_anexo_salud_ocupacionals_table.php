<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntoAnexoSaludOcupacionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjunto_anexo_salud_ocupacionals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ruta');
            $table->integer('cedula_paciente');
            $table->date('fecha_proceso');
            $table->bigInteger('tipo_anexo_id')->unsigned()->index();
            $table->foreign('tipo_anexo_id')->references('id')->on('tipo_anexo_salud_ocupacionals');
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
        Schema::dropIfExists('adjunto_anexo_salud_ocupacionals');
    }
}
