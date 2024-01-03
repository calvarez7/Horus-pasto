<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsumosProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_procedimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cantidad')->nullable();
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
            $table->bigInteger('producto')->nullable()->unsigned()->index();
            $table->foreign('producto')->references('id')->on('codesumis');
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
        Schema::dropIfExists('insumos_procedimientos');
    }
}
