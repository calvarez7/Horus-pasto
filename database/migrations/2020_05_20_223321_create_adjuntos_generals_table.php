<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjuntosGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuntos_generals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tipo_id')->unsigned()->index();
            $table->foreign('tipo_id')->references('id')->on('Tipos');
            $table->string('nombre');
            $table->string('ruta');
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('cita_id')->nullable()->unsigned()->index();
            $table->foreign('cita_id')->references('id')->on('citas');
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
        Schema::dropIfExists('adjuntos_generals');
    }
}
