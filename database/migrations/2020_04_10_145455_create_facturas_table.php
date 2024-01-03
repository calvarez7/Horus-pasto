<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('usuario_id')->nullable()->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('Pacientes');
            $table->bigInteger('tipofactura_id')->unsigned()->index();
            $table->foreign('tipofactura_id')->references('id')->on('tipofacturas');
            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->integer('valor_domicilio')->nullable();
            $table->integer('valor_total')->nullable();
            $table->integer('valor_empaque')->nullable();
            $table->integer('cantidad_empaques')->nullable();
            $table->date('fecha_cobro')->nullable();
            $table->string('observacion_final')->nullable();
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
        Schema::dropIfExists('facturas');
    }
}
