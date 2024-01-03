<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortabilidadToNovedadesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novedades_pacientes', function (Blueprint $table) {
            $table->string('ipsorigen_portabilidad')->nullable();
            $table->string('telefonoorigen_portabilidad')->nullable();
            $table->string('correoorigen_portabilidad')->nullable();
            $table->string('fechaInicio_portabilidad')->nullable();
            $table->string('fechaFinal_portabilidad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('novedades_pacientes', function (Blueprint $table) {
            //
        });
    }
}
