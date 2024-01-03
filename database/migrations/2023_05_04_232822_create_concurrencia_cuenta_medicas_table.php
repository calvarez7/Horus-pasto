<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcurrenciaCuentaMedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concurrencia_cuenta_medicas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('concurrenciaRegistro_id')->nullable()->unsigned()->index();
            $table->foreign('concurrenciaRegistro_id')->references('id')->on('registroconcurrencias');
            $table->string('numero_factura')->nullable();
            $table->string('valor_facturado')->nullable();
            $table->string('valor_objetado')->nullable();
            $table->string('valor_objetado_concurrencia')->nullable();
            $table->string('factura_final')->nullable();
            $table->string('porcentaje_objecion')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('concurrencia_cuenta_medicas');
    }
}
