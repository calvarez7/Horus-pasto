<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductoInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_inventarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ingrediente_id')->unsigned()->nullable()->index();
            $table->foreign('ingrediente_id')->references('id')->on('inventarios');
            $table->bigInteger('producto_id')->unsigned()->nullable()->index();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->float('cantidad')->nullable();
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
        Schema::dropIfExists('producto_inventarios');
    }
}
