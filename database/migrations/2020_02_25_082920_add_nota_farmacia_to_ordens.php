<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotaFarmaciaToOrdens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detaarticulordens', function (Blueprint $table) {
            $table->float('cantidadConcentracionFormulada', 1)->nullable();
            $table->text('notaFarmacia')->nullable();
        });

        Schema::table('codesumis', function (Blueprint $table) {
            $table->integer('concentracion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detaarticulordens', function (Blueprint $table) {
            $table->float('cantidadConcentracionFormulada', 1)->nullable();
            $table->text('notaFarmacia')->nullable();
        });

        Schema::table('codesumis', function (Blueprint $table) {
            $table->integer('concentracion')->nullable();
        });
    }
}
