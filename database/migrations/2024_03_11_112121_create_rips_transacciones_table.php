<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_transacciones', function (Blueprint $table) {
            $table->id();
            $table->string('numDocumentoIdObligado')->nullable();
            $table->string('numFactura')->nullable();
            $table->string('tipoNota')->nullable();
            $table->string('numNota')->nullable();
            $table->bigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('entidad_id')->nullable()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
            $table->bigInteger('prestador_id')->nullable()->index();
            $table->foreign('prestador_id')->references('id')->on('prestadores');
            $table->bigInteger('estado_id')->nullable()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('rips_transacciones');
    }
}
