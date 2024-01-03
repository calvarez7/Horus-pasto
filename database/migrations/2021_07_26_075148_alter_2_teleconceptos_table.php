<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alter2TeleconceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teleconceptos', function (Blueprint $table) {
            $table->boolean('girs')->nullable();
            $table->bigInteger('cita_paciente_id')->nullable()->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->text('observacion_reasignacion_girs')->nullable();
            $table->boolean('pertinente_teleconcepto')->nullable();
            $table->text('observacion_pertinente_teleconcepto')->nullable();
            $table->boolean('pertinente_ordenamiento')->nullable();
            $table->text('observacion_pertinente_ordenamiento')->nullable();
            $table->string('institucion_prestadora')->nullable();
            $table->string('eapb')->nullable();
            $table->text('evaluacion_junta')->nullable();
            $table->string('junta_aprueba')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teleconceptos', function (Blueprint $table) {
            $table->boolean('girs')->nullable();
            $table->bigInteger('cita_paciente_id')->nullable()->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->text('observacion_reasignacion_girs')->nullable();
            $table->boolean('no_pertinente_teleconcepto')->nullable();
            $table->text('observacion_pertinente_teleconcepto')->nullable();
            $table->boolean('no_pertinente_ordenamiento')->nullable();
            $table->text('observacion_pertinente_ordenamiento')->nullable();
            $table->string('institucion_prestadora')->nullable();
            $table->string('eapb')->nullable();
            $table->text('evaluacion_junta')->nullable();
            $table->string('junta_aprueba')->nullable();
        });
    }
}
