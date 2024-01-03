<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaenfermeriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notaenfermerias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cita_paciente_id')->nullable()->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->string('nota')->nullable();
            $table->string('signos_alarma')->nullable();
            $table->string('cuidados_casa')->nullable();
            $table->string('caso_urgencia')->nullable();
            $table->string('alimentacion')->nullable();
            $table->string('efectos_secundarios')->nullable();
            $table->string('habito_higiene')->nullable();
            $table->string('derechos_deberes')->nullable();
            $table->string('normas_sala_quimioterapia')->nullable();
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
        Schema::dropIfExists('notaenfermerias');
    }
}
