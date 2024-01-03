<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemperaturasToSeguimientocovids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientocovids', function (Blueprint $table) {
            $table->string('saturacion')->nullable();
            $table->string('pulso')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('tensionarterial')->nullable();
            $table->string('frecuenciarespiratoria')->nullable();
            $table->string('escalanews2')->nullable();
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
            $table->string('saturacion')->nullable();
            $table->string('pulso')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('tensionarterial')->nullable();
            $table->string('frecuenciarespiratoria')->nullable();
            $table->string('escalanews2')->nullable();
        });
    }
}
