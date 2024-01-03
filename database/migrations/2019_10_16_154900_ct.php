<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Id_Paquete')->unsigned()->index();
            $table->foreign('Id_Paquete')->references('id')->on('paq_rips')->OnDelete('cascade');
            $table->string('Fecha_Radicado');
            $table->string('Nombre_Archivo');
            $table->integer('Catidad_Registros');
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
        Schema::dropIfExists('cts');
    }
}
