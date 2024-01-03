<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionRadicaciononlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion_radicaciononlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('radicaciononline_id')->unsigned()->index();
            $table->foreign('radicaciononline_id')->references('id')->on('radicacion_onlines');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('tipo_id')->unsigned()->nullable()->index();
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->text('motivo');
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
        Schema::dropIfExists('gestion_radicaciononlines');
    }
}
