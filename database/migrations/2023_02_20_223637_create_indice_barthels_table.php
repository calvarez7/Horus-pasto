<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndiceBarthelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indice_barthels', function (Blueprint $table) {
            $table->id();
            $table->string('barthelComer')->nullable();
            $table->string('barthelLavarse')->nullable();
            $table->string('barthelVestirse')->nullable();
            $table->string('barthelArreglarse')->nullable();
            $table->string('barthelDeposiciones')->nullable();
            $table->string('barthelMiccion')->nullable();
            $table->string('barthelRetrete')->nullable();
            $table->string('barthelTrasladarse')->nullable();
            $table->string('barthelDeambular')->nullable();
            $table->string('barthelEscalones')->nullable();
            $table->bigInteger('citapaciente_id')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('indice_barthels');
    }
}
