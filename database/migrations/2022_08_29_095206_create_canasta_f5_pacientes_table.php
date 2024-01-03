<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanastaF5PacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canasta_f5_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->date('fecha_novedad');
            $table->date('fecha_diagnostico');
            $table->string('cie10_neoplasia');
            $table->date('fecha_inicio_tratamiento');
            $table->date('fecha_ultima_atencion');
            $table->date('fecha_ultimo_tratamiento');
            $table->date('fecha_remision_diagnostico_presuntivo');
            $table->integer('tratamiento_actual');
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
        Schema::dropIfExists('canasta_f5_pacientes_');
    }
}
