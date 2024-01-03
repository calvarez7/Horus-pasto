<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBodegasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodegas', function (Blueprint $table) {
            $table->integer('stock_seguridad')->nullable();
            $table->integer('tiempo_reposicion')->nullable();
            $table->integer('cobertura')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bodegas', function (Blueprint $table) {
            $table->integer('stock_seguridad')->nullable();
            $table->integer('tiempo_reposicion')->nullable();
            $table->integer('cobertura')->nullable();
        });
    }
}
