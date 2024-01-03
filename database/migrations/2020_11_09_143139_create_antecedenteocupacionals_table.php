<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedenteocupacionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedenteocupacionals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->string('antecedente_empresa')->nullable();
            $table->string('antecedente_cargo')->nullable();
            $table->string('f')->nullable();
            $table->string('q')->nullable();
            $table->string('b')->nullable();
            $table->string('erg')->nullable();
            $table->string('psic')->nullable();
            $table->string('mec')->nullable();
            $table->string('elec')->nullable();
            $table->string('otro')->nullable();
            $table->string('tiempo')->nullable();
            $table->string('uso_e_p_p')->nullable();
            $table->string('accidentes_trabajo')->nullable();
            $table->string('fecha_accidentes')->nullable();
            $table->string('empresa_accidentes')->nullable();
            $table->string('causa')->nullable();
            $table->string('tipo_lesion')->nullable();
            $table->string('parte_afectada')->nullable();
            $table->string('dias_incap')->nullable();
            $table->string('secuelas')->nullable();
            $table->string('enfermedades_profesionales')->nullable();
            $table->string('fecha')->nullable();
            $table->string('empresa')->nullable();
            $table->string('diagnostico')->nullable();
            $table->string('reubicacion')->nullable();
            $table->string('indemnizacion')->nullable();
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
        Schema::dropIfExists('antecedenteocupacionals');
    }
}
