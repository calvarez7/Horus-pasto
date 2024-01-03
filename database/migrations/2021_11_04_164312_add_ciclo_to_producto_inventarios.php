<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCicloToProductoInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_inventarios', function (Blueprint $table) {
            $table->bigInteger('ciclo_id')->nullable()->unsigned()->index();
            $table->foreign('ciclo_id')->references('id')->on('ciclos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_inventarios', function (Blueprint $table) {
            $table->bigInteger('ciclo_id')->nullable()->unsigned()->index();
            $table->foreign('ciclo_id')->references('id')->on('ciclos');
        });
    }
}
