<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatitosToPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cargo_laboral')->nullable();
            $table->string('composicion_familiar')->nullable();
            $table->string('vivienda')->nullable();
            $table->string('personas_a_cargo')->nullable();
            $table->string('tipo_contratacion')->nullable();
            $table->string('antiguedad_en_empresa')->nullable();
            $table->string('antiguedad_cargo_actual')->nullable();
            $table->string('numero_cursos_a_cargo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('cargo_laboral')->nullable();
            $table->string('composicion_familiar')->nullable();
            $table->string('vivienda')->nullable();
            $table->string('personas _a_cargo')->nullable();
            $table->string('tipo_contratacion')->nullable();
            $table->string('antiguedad_en_empresa')->nullable();
            $table->string('antiguedad_cargo_actual')->nullable();
            $table->string('numero_cursos_a_cargo')->nullable();
        });
    }
}
