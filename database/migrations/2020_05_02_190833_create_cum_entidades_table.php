<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCumEntidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cum_entidades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('pbs');
            $table->integer('nivel');
            $table->string('linea');
            $table->string('condicion');
            $table->string('auditoria');
            $table->bigInteger('codesumi_id')->unsigned()->index();
            $table->foreign('codesumi_id')->references('id')->on('Codesumis');
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
        Schema::dropIfExists('cum_entidades');
    }
}
