<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModificacionToEstilovidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estilovidas', function (Blueprint $table) {
            $table->boolean('checkboxexposicionhumo')->nullable();
            $table->boolean('checkboxexposicionpsicoactivos')->nullable();
            $table->string('expuestohumo ')->nullable();
            $table->string('expuesttopsicoactivos')->nullable();
            $table->string('anosexpuesto')->nullable();
            $table->string('anosexpuesto_sustancias')->nullable();
            $table->string('cuantas_comidas')->nullable();
            $table->string('dieta_balanceada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estilovidas', function (Blueprint $table) {
            $table->boolean('checkboxexposicionhumo')->nullable();
            $table->boolean('checkboxexposicionpsicoactivos')->nullable();
            $table->string('expuestohumo ')->nullable();
            $table->string('expuesttopsicoactivos')->nullable();
            $table->string('anosexpuesto')->nullable();
            $table->string('anosexpuesto_sustancias')->nullable();
            $table->string('cuantas_comidas')->nullable();
            $table->string('dieta_balanceada')->nullable();
        });
    }
}
