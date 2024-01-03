<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVagoutBodegaIdToInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->bigInteger('vagout_bodega_id')->nullable()->unsigned()->index();
            $table->foreign('vagout_bodega_id')->references('id')->on('vagout_bodegas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->bigInteger('vagout_bodega_id')->nullable()->unsigned()->index();
            $table->foreign('vagout_bodega_id')->references('id')->on('vagout_bodegas');
        });
    }
}
