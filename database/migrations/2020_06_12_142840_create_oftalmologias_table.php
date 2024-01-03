<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOftalmologiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oftalmologias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cita_paciente_id')->nullable()->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->string('nombre_responsable')->nullable();
            $table->string('documento_responsable')->nullable();
            $table->string('AVCC_correcion_derecho')->nullable();
            $table->string('AVCC_correcion_izquierdo')->nullable();
            $table->string('AVCC_sincorrecion_derecho')->nullable();
            $table->string('AVCC_sincorrecion_izquierdo')->nullable();
            $table->string('ph_derecho')->nullable();
            $table->string('ph_izquierdo')->nullable();
            $table->string('motilidad_derecho')->nullable();
            $table->string('motilidad_izquierdo')->nullable();
            $table->string('covertest_derecho')->nullable();
            $table->string('covertest_izquierdo')->nullable();
            $table->string('p_intraocular_derecho')->nullable();
            $table->string('p_intraocular_izquierdo')->nullable();
            $table->string('gonioscopia_derecho')->nullable();
            $table->string('gonioscopia_izquierdo')->nullable();
            $table->string('fondo_ojo_derecho')->nullable();
            $table->string('fondo_ojo_izquierdo')->nullable();
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
        Schema::dropIfExists('oftalmologias');
    }
}
