<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjuntosHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuntos_help', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Gestion_id')->nullable()->unsigned()->index();
            $table->foreign('Gestion_id')->references('id')->on('gestions_help');
            $table->string('Nombre');
            $table->string('Ruta');
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
        Schema::dropIfExists('adjuntos_help');
    }
}
