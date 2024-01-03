<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHemofiliaFiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hemofilia_fias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Cie_10')->nullable();
            $table->string('patologia')->nullable();
            $table->string('fecha_de_diagnostico')->nullable();
            $table->string('tipo_de_tratamiento')->nullable();
            $table->string('fecha_de_ingreso_al_programa')->nullable();
            $table->string('fecha_inicio_tratamiento')->nullable();
            $table->string('fecha_ultimo_tratamiento')->nullable();
            $table->string('fecha_ultima_atencion')->nullable();
            $table->string('novedades')->nullable();
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
        Schema::dropIfExists('hemofilia_fias');
    }
}
