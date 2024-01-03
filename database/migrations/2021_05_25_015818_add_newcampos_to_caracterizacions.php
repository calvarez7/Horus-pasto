<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewcamposToCaracterizacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caracterizacions', function (Blueprint $table) {
            $table->string('medicamentos')->nullable();
            $table->string('antecedente_hospitalizacion')->nullable();
            $table->string('riesgo_individualizado')->nullable();
            $table->string('Maternoperinatal')->nullable();
            $table->string('salud_mental')->nullable();
            $table->string('riesgo_cardiovascular')->nullable();
            $table->string('Oncologicos')->nullable();
            $table->string('nefroproteccion')->nullable();
            $table->string('respiratorios_cronicos')->nullable();
            $table->string('reumatologicos')->nullable();
            $table->string('trasmisibles_cronicos')->nullable();
            $table->string('rehabilitación_integral')->nullable();
            $table->string('cuidados_paliativos')->nullable();
            $table->string('enfermedades_huerfanas')->nullable();
            $table->string('Economia_articular')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caracterizacions', function (Blueprint $table) {
            $table->string('medicamentos')->nullable();
            $table->string('antecedente_hospitalizacion')->nullable();
            $table->string('riesgo_individualizado')->nullable();
            $table->string('Maternoperinatal')->nullable();
            $table->string('salud_mental')->nullable();
            $table->string('riesgo_cardiovascular')->nullable();
            $table->string('Oncologicos')->nullable();
            $table->string('nefroproteccion')->nullable();
            $table->string('respiratorios_cronicos')->nullable();
            $table->string('reumatologicos')->nullable();
            $table->string('trasmisibles_cronicos')->nullable();
            $table->string('rehabilitación_integral')->nullable();
            $table->string('cuidados_paliativos')->nullable();
            $table->string('enfermedades_huerfanas')->nullable();
            $table->string('Economia_articular')->nullable();
        });
    }
}
