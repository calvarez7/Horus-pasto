<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntedecentesFarmacoterapeuticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antedecentes_farmacoterapeuticos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tratamientos_cronicos')->nullable();
            $table->string('descripcion_tratamientos_cronicos')->nullable();
            $table->string('tratamientos_biologicos')->nullable();
            $table->string('descripcion_tratamientos_biologicos')->nullable();
            $table->string('quimioterapia')->nullable();
            $table->string('descripcion_quimioterapia')->nullable();
            $table->bigInteger('usuario_id')->nullable()->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('antedecentes_farmacoterapeuticos');
    }
}
