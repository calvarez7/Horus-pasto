<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallscoreToSeguimientocovids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('callscore')->nullable();
            $table->string('concentracionoxigeno')->nullable();
            $table->string('laboratoriodomicilio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('callscore')->nullable();
            $table->string('concentracionoxigeno')->nullable();
            $table->string('laboratoriodomicilio')->nullable();
        });
    }
}
