<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallecomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallecompras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('factura_id')->unsigned()->index();
            $table->foreign('factura_id')->references('id')->on('Facturas');
            $table->bigInteger('producto_id')->unsigned()->index();
            $table->foreign('producto_id')->references('id')->on('Productos');
            $table->string('cantidad_producto')->nullable();
            $table->integer('valor_unitario')->nullable();
            $table->integer('descuento')->nullable();
            $table->text('nota')->nullable();
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
        Schema::dropIfExists('detallecompras');
    }
}
