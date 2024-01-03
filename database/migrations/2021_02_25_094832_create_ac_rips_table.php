<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcRipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_rips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->string('cod_habilitacion_sede')->nullable();
            $table->string('tipo_doc')->nullable();
            $table->string('numero_doc')->nullable();
            $table->timestamp('fecha_consulta')->nullable();
            $table->bigInteger('orden_id')->nullable()->unsigned()->index();
            $table->foreign('orden_id')->references('id')->on('ordens');
            $table->string('cod_cup')->nullable();
            $table->string('finalidad')->nullable();
            $table->string('causa_externa')->nullable();
            $table->string('dx_principal')->nullable();
            $table->string('dx_relacionado1')->nullable();
            $table->string('dx_relacionado2')->nullable();
            $table->string('dx_relacionado3')->nullable();
            $table->string('tipo_diagnostico')->nullable();
            $table->bigInteger('valor_consulta')->nullable();
            $table->bigInteger('valor_cuota_moderada')->nullable();
            $table->bigInteger('valor_neto_pagar')->nullable();
            $table->bigInteger('entidad_id')->nullable()->unsigned()->index();
            $table->foreign('entidad_id')->references('id')->on('entidades');
            $table->bigInteger('estado_id')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('ac_rips');
    }
}
