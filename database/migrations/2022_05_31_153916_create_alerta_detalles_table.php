<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerta_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('interaccion')->nullable();
            $table->bigInteger('tipo_id')->nullable()->index();
            $table->foreign('tipo_id')->references('id')->on('tipo_alertas');
            $table->bigInteger('mensaje_id')->nullable()->index();
            $table->foreign('mensaje_id')->references('id')->on('mensajes_alertas');
            $table->bigInteger('usuario_id')->nullable()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
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
        Schema::dropIfExists('alerta_detalles');
    }
}
