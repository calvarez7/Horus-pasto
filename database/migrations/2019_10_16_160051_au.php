<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Au extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->OnDelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->OnDelete('cascade');
            $table->bigInteger('Diagnostico_Salida')->unsigned()->index();
            $table->foreign('Diagnostico_Salida')->references('id')->on('cie10s')->OnDelete('cascade');
            $table->string('Fecha_Ingreso');
            $table->string('Hora_Ingreso');
            $table->string('Numero_Autorizacion')->nullable();
            $table->string('Causa_Externa');
            $table->string('DiagnosticoRelacion_Salida1')->nullable();
            $table->string('DiagnosticoRelacion_Salida2')->nullable();
            $table->string('DiagnosticoRelacion_Salida3')->nullable();
            $table->string('Destinousuario_salida')->nullable();
            $table->string('Estado_Salida');
            $table->string('Causabasica_Muerte')->nullable();
            $table->string('Fechasalida_Usuario');
            $table->string('Horasalida_Usuario');
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
        Schema::dropIfExists('aus');
    }
}
