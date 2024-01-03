<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCupEntidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cup_entidades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('tarifa');
            $table->integer('nivel');
            $table->string('auditoria');
            $table->string('periodicidad');
            $table->bigInteger('cup_id')->unsigned()->index();
            $table->foreign('cup_id')->references('id')->on('Cups');
            $table->bigInteger('entidad_id')->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
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
        Schema::dropIfExists('cup_entidades');
    }
}
