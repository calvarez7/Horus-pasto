<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudbodegasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudbodegas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('Cantidad');
            $table->bigInteger('Codesumi_id')->unsigned()->index();
            $table->foreign('Codesumi_id')->references('id')->on('Codesumis');
            $table->bigInteger('Bodegaorigen_id')->unsigned()->index();
            $table->foreign('Bodegaorigen_id')->references('id')->on('Bodegas');
            $table->bigInteger('Bodegadestino_id')->unsigned()->index();
            $table->foreign('Bodegadestino_id')->references('id')->on('Bodegas');
            $table->bigInteger('Usuariosolicita_id')->unsigned()->index();
            $table->foreign('Usuariosolicita_id')->references('id')->on('Users');
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
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
        Schema::dropIfExists('solicitudbodegas');
    }
}
