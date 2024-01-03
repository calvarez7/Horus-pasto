<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventoAdversoTable1712 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->string('nombre_equipo')->nullable();
            $table->string('marca_equipo')->nullable();
            $table->string('modelo_equipo')->nullable();
            $table->string('serie_equipo')->nullable();
            $table->string('placa_equipo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->string('nombre_equipo')->nullable();
            $table->string('marca_equipo')->nullable();
            $table->string('modelo_equipo')->nullable();
            $table->string('serie_equipo')->nullable();
            $table->string('placa_equipo')->nullable();
        });
    }
}
