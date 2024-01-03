<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposciclosToExamenfisicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examenfisicos', function (Blueprint $table) {
            $table->string('motricidadGruesa')->nullable();
            $table->string('motricidadFina')->nullable();
            $table->string('audicionLenguaje')->nullable();
            $table->string('personalSocial')->nullable();
            $table->string('cuidado')->nullable();
            $table->string('escalaDesarrollo')->nullable();
            $table->string('autismo')->nullable();
            $table->string('resultadoVale')->nullable();
            $table->string('actividades')->nullable();
            $table->string('comportamientos')->nullable();
            $table->string('autoeficacia')->nullable();
            $table->string('independencia')->nullable();
            $table->string('actividadesProposito')->nullable();
            $table->string('controlInterno')->nullable();
            $table->string('funcionesEjecutivas')->nullable();
            $table->string('Identidad')->nullable();
            $table->string('valoracionIdentidad')->nullable();
            $table->string('Autonomia')->nullable();
            $table->string('valoracionAutonomia')->nullable();
            $table->string('visualnino')->nullable();
            $table->string('problemaOido')->nullable();
            $table->string('escuchaBien')->nullable();
            $table->string('factoresOido')->nullable();
            $table->string('riesgosMentalesNinos')->nullable();
            $table->string('lesionesAutoinflingidas')->nullable();
            $table->string('alteracionesGenitales')->nullable();
            $table->string('tannerMasculino')->nullable();
            $table->string('alteracionesGenitalesExternos')->nullable();
            $table->string('tannerFemenino')->nullable();
            $table->string('tannerVello')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examenfisicos', function (Blueprint $table) {
            $table->string('motricidadGruesa')->nullable();
            $table->string('motricidadFina')->nullable();
            $table->string('audicionLenguaje')->nullable();
            $table->string('personalSocial')->nullable();
            $table->string('cuidado')->nullable();
            $table->string('escalaDesarrollo')->nullable();
            $table->string('autismo')->nullable();
            $table->string('resultadoVale')->nullable();
            $table->string('actividades')->nullable();
            $table->string('comportamientos')->nullable();
            $table->string('autoeficacia')->nullable();
            $table->string('independencia')->nullable();
            $table->string('actividadesProposito')->nullable();
            $table->string('controlInterno')->nullable();
            $table->string('funcionesEjecutivas')->nullable();
            $table->string('Identidad')->nullable();
            $table->string('valoracionIdentidad')->nullable();
            $table->string('Autonomia')->nullable();
            $table->string('valoracionAutonomia')->nullable();
            $table->string('visualnino')->nullable();
            $table->string('problemaOido')->nullable();
            $table->string('escuchaBien')->nullable();
            $table->string('factoresOido')->nullable();
            $table->string('riesgosMentalesNinos')->nullable();
            $table->string('lesionesAutoinflingidas')->nullable();
            $table->string('alteracionesGenitales')->nullable();
            $table->string('tannerMasculino')->nullable();
            $table->string('alteracionesGenitalesExternos')->nullable();
            $table->string('tannerFemenino')->nullable();
            $table->string('tannerVello')->nullable();

        });
    }
}
