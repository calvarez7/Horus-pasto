<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanastaF4PacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canasta_f4_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->date('fecha_novedad');
            $table->date('fecha_diagnostico');
            $table->date('fecha_ingreso_programa');
            $table->date('fecha_inicio_tratamiento');
            $table->date('fecha_ultima_atencion');
            $table->string('cie10_primario');
            $table->string('cie10_secundario')->nullable();
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
        Schema::dropIfExists('canasta_f4_pacientes');
    }
}
