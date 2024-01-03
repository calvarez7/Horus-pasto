<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleSolicitudBodegaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_solicitud_bodega', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Usuarioresponde_id')->nullable()->unsigned()->index();
            $table->foreign('Usuarioresponde_id')->references('id')->on('Users');
            $table->bigInteger('Bodegarticulo_id')->nullable()->unsigned()->index();
            $table->foreign('Bodegarticulo_id')->references('id')->on('Bodegarticulos');
            $table->string('Lote')->nullable();
            $table->integer('Cantidad');
            $table->bigInteger('Codesumi_id')->nullable()->unsigned()->index();
            $table->foreign('Codesumi_id')->references('id')->on('Codesumis');
            $table->bigInteger('SolicitudBodega_id')->unsigned()->index();
            $table->foreign('SolicitudBodega_id')->references('id')->on('solicitudbodegas');
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->date('Fvence')->nullable();
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
        Schema::dropIfExists('detalle_solicitud_bodega');
    }
}
