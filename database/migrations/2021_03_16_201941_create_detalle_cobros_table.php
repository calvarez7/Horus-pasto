<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleCobrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_cobros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cobro_id')->nullable()->unsigned()->index();
            $table->foreign('cobro_id')->references('id')->on('cobros');
            $table->bigInteger('medicamento_orden_id')->nullable()->unsigned()->index();
            $table->foreign('medicamento_orden_id')->references('id')->on('detaarticulordens');
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
        Schema::dropIfExists('detalle_cobros');
    }
}
