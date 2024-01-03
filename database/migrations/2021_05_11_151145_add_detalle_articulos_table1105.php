<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetalleArticulosTable1105 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detallearticulos', function (Blueprint $table) {
            $table->string('codigo')->nullable();
            $table->string('clasificacion_riesgo')->nullable();
            $table->string('marca_dispositivo')->nullable();
            $table->bigInteger('presentacioncomercial_id')->nullable()->unsigned()->index();
            $table->foreign('presentacioncomercial_id')->references('id')->on('presentacion_comercials');
            $table->string('ium_primernivel')->nullable();
            $table->string('ium_segundonivel')->nullable();
            $table->boolean('oncologico')->nullable();
            $table->string('origen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detallearticulos', function (Blueprint $table) {
            $table->string('codigo')->nullable();
        });
    }
}
