<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjuntoTeleconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjunto_teleconceptos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Ruta');
            $table->bigInteger('teleconcepto_id')->unsigned()->index();
            $table->foreign('teleconcepto_id')->references('id')->on('teleconceptos');
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
        Schema::dropIfExists('adjunto_teleconceptos');
    }
}
