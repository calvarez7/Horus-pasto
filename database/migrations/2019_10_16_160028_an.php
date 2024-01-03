<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class An extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Af_id')->unsigned()->index();
            $table->foreign('Af_id')->references('id')->on('afs')->OnDelete('cascade');
            $table->bigInteger('Us_id')->unsigned()->index();
            $table->foreign('Us_id')->references('id')->on('us')->OnDelete('cascade');
            $table->bigInteger('Cie10_id')->unsigned()->index();
            $table->foreign('Cie10_id')->references('id')->on('cie10s')->OnDelete('cascade');
            $table->string('Fecha_Nacimiento');
            $table->string('Edad_Gestional');
            $table->string('Hora_Nacimiento');
            $table->string('Gestion_Prenatal');
            $table->string('Sexo');
            $table->string('Peso');
            $table->string('Causa_Muerte')->nullable();
            $table->string('Fecha_Muerte')->nullable();
            $table->string('Hora_Muerte')->nullable();
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
        Schema::dropIfExists('ans');
    }
}
