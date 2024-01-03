<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAyudasDiagnosticasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ayudas_diagnosticas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Ayuda_Diagnostica')->nullable();
            $table->string('resultado_dx')->nullable();
            $table->string('calidad_ccv')->nullable();
            $table->string('microorganismos_ccv')->nullable();
            $table->string('otros_microorganismosccv')->nullable();
            $table->string('otros_neoplasicos')->nullable();
            $table->string('anormalidades_celulasescamosas')->nullable();
            $table->string('anormalidades_celulasgalndulares')->nullable();
            $table->string('fecha_ayudadx')->nullable();
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
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
        Schema::dropIfExists('ayudas_diagnosticas');
    }
}
