<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnasssToCitaPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->integer('valor_scala_karnofski')->nullable();
            $table->integer('valor_scala_ecog')->nullable();
            $table->integer('sin_dolor')->nullable();
            $table->integer('sin_cansancio')->nullable();
            $table->integer('sin_nauseas')->nullable();
            $table->integer('sin_tristeza')->nullable();
            $table->integer('sin_ansiedad')->nullable();
            $table->integer('sin_somnolencia')->nullable();
            $table->integer('sin_disnea')->nullable();
            $table->integer('buen_apetito')->nullable();
            $table->integer('maximo_bienestar')->nullable();
            $table->integer('sin_dificulta_para_dormir')->nullable();
            $table->integer('puntaje_esas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->integer('valor_scala_karnofski')->nullable();
            $table->string('valor_scala_ecog')->nullable();
            $table->integer('sin_dolor')->nullable();
            $table->integer('sin_cansancio')->nullable();
            $table->integer('sin_nauseas')->nullable();
            $table->integer('sin_tristeza')->nullable();
            $table->integer('sin_ansiedad')->nullable();
            $table->integer('sin_somnolencia')->nullable();
            $table->integer('sin_disnea')->nullable();
            $table->integer('buen_apetito')->nullable();
            $table->integer('maximo_bienestar')->nullable();
            $table->integer('sin_dificultad_para_dormir')->nullable();
            $table->integer('puntaje_esas')->nullable();
        });
    }
}
