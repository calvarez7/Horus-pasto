<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeguimientoconcurrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientoconcurrencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('registroConcurrencia_id')->unsigned()->index();
            $table->foreign('registroConcurrencia_id')->references('id')->on('referencias');
            $table->bigInteger('user_responsable_seguimiento')->unsigned()->index();
            $table->foreign('user_responsable_seguimiento')->references('id')->on('users');
            $table->text('seguimientoConcurrencia');
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
        Schema::dropIfExists('seguimientoconcurrencias');
    }
}
