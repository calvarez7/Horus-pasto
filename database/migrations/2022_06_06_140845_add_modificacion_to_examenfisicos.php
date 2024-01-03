<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModificacionToExamenfisicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examenfisicos', function (Blueprint $table) {
            $table->string('funciones')->nullable();
            $table->string('desempeñocomunicativo')->nullable();
            $table->string('violencia_mental')->nullable();
            $table->string('violencia_conflicto')->nullable();
            $table->string('violencia_sexual')->nullable();
            $table->string('rendimiento_escolar')->nullable();
            $table->string('test_figura_humana')->nullable();
            $table->string('columna_vertebral')->nullable();
            $table->string('examen_mental')->nullable();
            $table->string('circunferencia_brazo')->nullable();
            $table->string('circunferencia_pantorrilla')->nullable();
            $table->string('peso_talla')->nullable();
            $table->string('clasificacion_peso_talla')->nullable();
            $table->string('talla_edad')->nullable();
            $table->string('clasificacion_talla_edad')->nullable();
            $table->string('cefalico_edad')->nullable();
            $table->string('clasificacion_cefalico_edad')->nullable();
            $table->string('imc_edad')->nullable();
            $table->string('clasificacion_imc_edad')->nullable();
            $table->string('peso_edad')->nullable();
            $table->string('clasificacion_peso_edad')->nullable();

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
            $table->string('funciones')->nullable();
            $table->string('desempeñocomunicativo')->nullable();
            $table->string('violencia_mental')->nullable();
            $table->string('violencia_conflicto')->nullable();
            $table->string('violencia_sexual')->nullable();
            $table->string('rendimiento_escolar')->nullable();
            $table->string('test_figura_humana')->nullable();
            $table->string('columna_vertebral')->nullable();
            $table->string('examen_mental')->nullable();
            $table->string('circunferencia_brazo')->nullable();
            $table->string('circunferencia_pantorrilla')->nullable();
            $table->string('peso_talla')->nullable();
            $table->string('clasificacion_peso_talla')->nullable();
            $table->string('talla_edad')->nullable();
            $table->string('clasificacion_talla_edad')->nullable();
            $table->string('cefalico_edad')->nullable();
            $table->string('clasificacion_cefalico_edad')->nullable();
            $table->string('imc_edad')->nullable();
            $table->string('clasificacion_imc_edad')->nullable();
            $table->string('peso_edad')->nullable();
            $table->string('clasificacion_peso_edad')->nullable();
        });
    }
}
