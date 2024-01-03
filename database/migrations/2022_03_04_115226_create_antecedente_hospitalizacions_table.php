<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedenteHospitalizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedente_hospitalizacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hospitalizacion_neonatal', 2000)->nullable();
            $table->string('sino_neonatal', 2000)->nullable();
            $table->string('consulta_urgencias', 2000)->nullable();
            $table->string('sino_urgenicias', 2000)->nullable();
            $table->string('hospitalizacion_ultimo_anio', 2000)->nullable();
            $table->string('sino_hospiultimoanio', 2000)->nullable();
            $table->string('mas_tres_hospitalizaciones_anio', 2000)->nullable();
            $table->string('sino_masdetreshospitalizacion', 2000)->nullable();
            $table->string('hospitalizacion_mayor_dos_semanas_anio', 2000)->nullable();
            $table->string('sino_mayordossemanashospi', 2000)->nullable();
            $table->string('hospitalizacion_uci_anio', 2000)->nullable();
            $table->string('sino_ucianio', 2000)->nullable();
            $table->bigInteger('citapaciente_id')->unsigned()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('antecedente_hospitalizacions');
    }
}
