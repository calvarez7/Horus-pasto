<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class At extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->OnDelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->OnDelete('cascade');
            $table->bigInteger('Codigo_Servicio')->unsigned()->index();
            $table->foreign('Codigo_Servicio')->references('id')->on('cups')->OnDelete('cascade');
            $table->string('Numero_Autorizacion')->nullable();
            $table->string('Tipo_Servicio')->nullable();
            $table->string('Nombre_Servicio')->nullable();
            $table->string('Cantidad')->nullable();
            $table->string('Valor_Unitario');
            $table->string('Valor_Total');
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
        Schema::dropIfExists('ats');
    }
}
