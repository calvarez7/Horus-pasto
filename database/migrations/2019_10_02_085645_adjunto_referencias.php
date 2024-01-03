<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjuntoReferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjunto_referencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Ruta');
            $table->bigInteger('referencia_id')->unsigned()->index();
            $table->foreign('referencia_id')->references('id')->on('referencias');
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
        Schema::dropIfExists('adjunto_referencias');
    }
}
