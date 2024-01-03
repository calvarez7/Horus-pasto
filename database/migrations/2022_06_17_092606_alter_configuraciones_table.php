<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->date('fecha_inicio_habilitacion_validador_202')->nullable();
            $table->date('fecha_fin_habilitacion_validador_202')->nullable();
            $table->boolean('excepcion_habilitacion_validador_202')->nullable();
            $table->integer('dia_inicio_habilitacion_validador_rips')->nullable();
            $table->integer('dia_final_habilitacion_validador_rips')->nullable();
            $table->boolean('excepcion_habilitacion_validador_rips')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->date('fecha_inicio_habilitacion_validador_202')->nullable();
            $table->date('fecha_fin_habilitacion_validador_202')->nullable();
            $table->boolean('excepcion_habilitacion_validador_202')->nullable();
            $table->integer('dia_inicio_habilitacion_validador_rips')->nullable();
            $table->integer('dia_final_habilitacion_validador_rips')->nullable();
            $table->boolean('excepcion_habilitacion_validador_rips')->nullable();
        });
    }
}
