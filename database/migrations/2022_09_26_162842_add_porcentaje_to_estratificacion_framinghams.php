<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPorcentajeToEstratificacionFraminghams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estratificacion_framinghams', function (Blueprint $table) {
            $table->string('porcentaje')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estratificacion_framinghams', function (Blueprint $table) {
            $table->string('porcentaje')->nullable();
        });
    }
}
