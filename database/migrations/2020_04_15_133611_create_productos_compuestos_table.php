<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosCompuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_compuestos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('producto_id')->unsigned()->index();
            $table->foreign('producto_id')->references('id')->on('Productos');
            $table->bigInteger('producto_detalle_id')->unsigned()->index();
            $table->foreign('producto_detalle_id')->references('id')->on('Productos');
            $table->string('nombre')->nullable();
            $table->integer('descripcion')->nullable();
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
        Schema::dropIfExists('productos_compuestos');
    }
}
