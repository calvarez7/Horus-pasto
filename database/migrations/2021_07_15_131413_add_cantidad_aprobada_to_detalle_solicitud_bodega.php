<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantidadAprobadaToDetalleSolicitudBodega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_solicitud_bodega', function (Blueprint $table) {
            $table->bigInteger('cantidad_aprobada')->nullable();
            $table->bigInteger('lote_referencia_id')->unsigned()->index()->nullable();
            $table->foreign('lote_referencia_id')->references('id')->on('lotes');
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
            $table->bigInteger('cantidad_aprobada')->nullable();
            $table->bigInteger('lote_referencia_id')->unsigned()->index()->nullable();
            $table->foreign('lote_referencia_id')->references('id')->on('lotes');
        });
    }
}
