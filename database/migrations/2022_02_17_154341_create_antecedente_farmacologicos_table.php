<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedenteFarmacologicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedente_farmacologicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('utiempo')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('alergia')->nullable();
            $table->string('observacionAlergia')->nullable();
            $table->bigInteger('detallearticulo_id')->nullable()->unsigned()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
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
        Schema::dropIfExists('antecedente_farmacologicos');
    }
}
