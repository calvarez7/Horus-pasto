<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ac extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->OnDelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->OnDelete('cascade');
            $table->string('Fecha_Consulta');
            $table->string('Numero_Autorizacion')->nullable();
            $table->bigInteger('Codigo_Consulta')->unsigned()->index();
            $table->foreign('Codigo_Consulta')->references('id')->on('cups')->OnDelete('cascade');
            $table->string('Finalidad_Consulta');
            $table->string('Causa_Externa');
            $table->bigInteger('Diagnostico_Principal')->unsigned()->index();
            $table->foreign('Diagnostico_Principal')->references('id')->on('cie10s')->OnDelete('cascade');
            $table->string('Codigo_Relacionado1')->nullable();
            $table->string('Codigo_Relacionado2')->nullable();
            $table->string('Codigo_Relacionado3')->nullable();
            $table->string('Tipodiagnostico_Principal');
            $table->string('valor_Consulta');
            $table->string('valorcuota_Moderadora');
            $table->string('valorneto_Pagar');
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
        Schema::dropIfExists('acs');
    }
}
