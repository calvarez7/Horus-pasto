<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCamposToEstilovidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estilovidas', function (Blueprint $table) {
            $table->string('tabaquico')->nullable();
            $table->string('riesgoEpoc')->nullable();
            $table->string('DietaFrecuencia')->nullable();
            $table->string('DuracionSueno')->nullable();
            $table->string('frecuenciaActividad')->nullable();
            $table->string('TipoSueno')->nullable();
            $table->string('juego')->nullable();
            $table->boolean('checkboxtv')->nullable();
            $table->boolean('checkboxjuego')->nullable();
            $table->boolean('checkboxbano')->nullable();
            $table->boolean('checkboxbucal')->nullable();
            $table->boolean('checkboxPosiciones')->nullable();
            $table->boolean('checkboxesfinter')->nullable();
            $table->boolean('checkboxOrina')->nullable();
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
            $table->string('tabaquico')->nullable();
            $table->string('riesgoEpoc')->nullable();
            $table->boolean('checkboxtv')->nullable();
            $table->boolean('checkboxjuego')->nullable();
            $table->boolean('checkboxbano')->nullable();
            $table->boolean('checkboxbucal')->nullable();
            $table->boolean('checkboxPosiciones')->nullable();
            $table->boolean('checkboxesfinter')->nullable();
            $table->boolean('checkboxOrina')->nullable();
        });
    }
}
