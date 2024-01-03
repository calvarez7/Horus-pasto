<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvetariobodegaToConteoinventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conteoinventarios', function (Blueprint $table) {
            $table->bigInteger('inventario_bodega_id')->nullable()->unsigned()->index();
            $table->foreign('inventario_bodega_id')->references('id')->on('inventario_bodegas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conteoinventarios', function (Blueprint $table) {
            $table->bigInteger('inventario_bodega_id')->nullable()->unsigned()->index();
            $table->foreign('inventario_bodega_id')->references('id')->on('inventario_bodegas');
        });
    }
}
