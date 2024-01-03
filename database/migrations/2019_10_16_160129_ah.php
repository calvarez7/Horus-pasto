<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ahs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->OnDelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->OnDelete('cascade');
            $table->string('Dia_Ingreso');
            $table->string('Fecha_Ingreso');
            $table->string('Hora_Ingreso');
            $table->string('Numero_Autorizacion');
            $table->string('Causa_Externa');
            $table->bigInteger('Diagnostico_ingreso')->unsigned()->index();
            $table->foreign('Diagnostico_ingreso')->references('id')->on('cie10s')->OnDelete('cascade');
            $table->bigInteger('Diagnostico_egreso')->unsigned()->index();
            $table->foreign('Diagnostico_egreso')->references('id')->on('cie10s')->OnDelete('cascade');
            $table->string('Diagnaosticorelacionado_1');
            $table->string('Diagnaosticorelacionado_2');
            $table->string('Diagnaosticorelacionado_3');
            $table->string('Diagnostico_Complicacion');
            $table->string('Estado_Salida');
            $table->string('Diagnosticocausa_Muerte')->nullable();
            $table->string('Fecha_Egreso');
            $table->string('Hora_Egreso');
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
        Schema::dropIfExists('ahs');
    }
}
