<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtritisFiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artritis_fias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sexo')->nullable();
            $table->string('grupo_lgtbiq')->nullable();
            $table->string('pertenencia_etnica')->nullable();
            $table->string('grupo_poblacional')->nullable();
            $table->string('fecha_de_diagnóstico')->nullable();
            $table->string('Cie_10')->nullable();
            $table->string('descripcion_Cie_10')->nullable();
            $table->string('fecha_de_ingreso_al_programa')->nullable();
            $table->string('fecha_inicio_tratamiento')->nullable();
            $table->string('fecha_ultima_atencion')->nullable();
            $table->string('peso_ultima_atencion')->nullable();
            $table->string('talla_ultima_atencion')->nullable();
            $table->string('imc')->nullable();
            $table->string('fecha_ultimo_tratamiento')->nullable();
            $table->string('tratamiento_actual')->nullable();
            $table->string('biologico')->nullable();
            $table->string('novedad')->nullable();
            $table->string('fecha_desafiliacion')->nullable();
            $table->string('fecha_muerte')->nullable();
            $table->string('causa_muerte')->nullable();
            $table->string('paciente_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artritis_fias');
    }
}
