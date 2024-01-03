<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjuntoEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjunto_eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('eventoadverso_id')->unsigned()->nullable()->index();
            $table->foreign('eventoadverso_id')->references('id')->on('eventos_adversos');
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
        Schema::dropIfExists('adjunto_eventos');
    }
}
