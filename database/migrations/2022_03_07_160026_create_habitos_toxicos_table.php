<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitosToxicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habitos_toxicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('checkboxTabaquismo')->nullable();
            $table->string('presente_tabaquismo')->nullable();
            $table->boolean('checkboxAlcohol')->nullable();
            $table->string('presente_alcohol')->nullable();
            $table->boolean('checkboxPsicofarmacos')->nullable();
            $table->string('presente_psicofarmacos')->nullable();
            $table->string('tipo_tabaquismo')->nullable();
            $table->string('tipo_alcohol')->nullable();
            $table->string('tipo_psicofarmacos')->nullable();
            $table->string('n_cigarrillo')->nullable();
            $table->string('frecuencia_alcohol')->nullable();
            $table->string('frecuencia_psicofarmacos')->nullable();
            $table->string('paquetes_cigarrillo')->nullable();
            $table->string('fecha_alcohol')->nullable();
            $table->string('fecha_psicofarmacos')->nullable();
            $table->string('fecha_cigarrillo')->nullable();
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
        Schema::dropIfExists('habitos_toxicos');
    }
}
