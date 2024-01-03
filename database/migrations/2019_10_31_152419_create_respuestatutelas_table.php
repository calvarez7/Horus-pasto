<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRespuestatutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respuestatutelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Tutela_id')->unsigned()->index();
            $table->foreign('Tutela_id')->references('id')->on('Tutelas');
            $table->bigInteger('User_id')->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('Users');
            $table->text('Respuesta');
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
        Schema::dropIfExists('respuestatutelas');
    }
}
