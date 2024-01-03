<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtrosAntecedentesFamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otros_antecedentes_familiares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patologias')->nullable();
            $table->string('tipo_cancer')->nullable();
            $table->string('tipo_transmision_sexual')->nullable();
            $table->string('parentesco')->nullable();
            $table->string('fecha_diagnostico')->nullable();
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
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
        Schema::dropIfExists('otros_antecedentes_familiares');
    }
}
