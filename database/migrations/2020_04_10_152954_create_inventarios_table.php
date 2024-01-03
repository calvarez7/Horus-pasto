<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('producto_id')->unsigned()->index();
            $table->foreign('producto_id')->references('id')->on('Productos');
            $table->string('precio_compra')->nullable();
            $table->text('precio_venta')->nullable();
            $table->text('stock_minimo')->nullable();
            $table->text('stock_actual')->nullable();
            $table->date('fecha_produccion')->nullable();
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
        Schema::dropIfExists('inventarios');
    }
}
