<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetallearticuloIdToDetalleSolicitudBodega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_solicitud_bodega', function (Blueprint $table) {
            $table->bigInteger('detallearticulo_id')->unsigned()->nullable()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_solicitud_bodega', function (Blueprint $table) {
            $table->bigInteger('detallearticulo_id')->unsigned()->nullable()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
        });
    }
}
