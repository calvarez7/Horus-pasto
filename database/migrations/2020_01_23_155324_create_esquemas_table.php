<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEsquemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esquemas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ciclos')->nullable();
            $table->string('frecuenciaRepeat')->nullable();
            $table->string('frecuenciaDuracion')->nullable();
            $table->string('biografia')->nullable();
            $table->bigInteger('estadoId')->default(1)->unsigned()->index();
            $table->foreign('estadoId')->references('id')->on('Estados');
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
        Schema::dropIfExists('esquemas');
    }
}
