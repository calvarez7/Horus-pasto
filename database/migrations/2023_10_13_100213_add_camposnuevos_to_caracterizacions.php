<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposnuevosToCaracterizacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caracterizacions', function (Blueprint $table) {
            $table->string('parentesco_cuidador')->nullable();
            $table->string('habitaciones')->nullable();
            $table->string('enfermedades_cardiovasculares')->nullable();
            $table->string('zonóticas')->nullable();
            $table->string('alteraciones_nutricionales')->nullable();
            $table->string('violencias')->nullable();
            $table->string('grado_discapacidad_uno')->nullable();
            $table->string('grado_discapacidad_dos')->nullable();
            $table->string('grado_discapacidad_tres')->nullable();
            $table->string('grado_discapacidad_cuatro')->nullable();
            $table->string('grado_discapacidad_cinco')->nullable();
            $table->string('trastorno_consumo_sustancias_psicoactivas')->nullable();
            $table->string('cancer')->nullable();
            $table->string('trastornos_visuales')->nullable();
            $table->string('trastornos_auditivos')->nullable();
            $table->string('trastornos_salud_bucal')->nullable();
            $table->string('problemas_salud_mental')->nullable();
            $table->string('accidente_enfermedad_laboral')->nullable();
            $table->string('trastornos_degenarativos_neuropatias_autoinmune')->nullable();
            $table->string('enfermedades_infecciosas')->nullable();
            $table->string('estado_Paciente')->nullable();
            $table->string('antecedente_patologico')->nullable();
            $table->string('cicloVital')->nullable();
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
            $table->string('parentesco_cuidador')->nullable();
            $table->string('habitaciones')->nullable();
            $table->string('enfermedades_cardiovasculares')->nullable();
            $table->string('zonóticas')->nullable();
            $table->string('alteraciones_nutricionales')->nullable();
            $table->string('violencias')->nullable();
            $table->string('grado_discapacidad_uno')->nullable();
            $table->string('grado_discapacidad_dos')->nullable();
            $table->string('grado_discapacidad_tres')->nullable();
            $table->string('grado_discapacidad_cuatro')->nullable();
            $table->string('grado_discapacidad_cinco')->nullable();
            $table->string('trastorno_consumo_sustancias_psicoactivas')->nullable();
            $table->string('cancer')->nullable();
            $table->string('trastornos_visuales')->nullable();
            $table->string('trastornos_auditivos')->nullable();
            $table->string('trastornos_salud_bucal')->nullable();
            $table->string('problemas_salud_mental')->nullable();
            $table->string('accidente_enfermedad_laboral')->nullable();
            $table->string('trastornos_degenarativos_neuropatias_autoinmune')->nullable();
            $table->string('enfermedades_infecciosas')->nullable();
            $table->string('estado_Paciente')->nullable();
            $table->string('antecedente_patologico')->nullable();
            $table->string('cicloVital')->nullable();
        });
    }
}
