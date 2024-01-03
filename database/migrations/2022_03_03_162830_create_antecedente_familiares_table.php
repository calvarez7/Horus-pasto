<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedenteFamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedente_familiares', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patologias')->nullable();
            $table->string('tipo_cancer')->nullable();
            $table->string('tipo_trastorno_mental')->nullable();
            $table->string('tipo_transmision_sexual')->nullable();
            $table->string('tipo_enfermedad_autoinmune')->nullable();
            $table->string('otro_patologia_cancer', 2000)->nullable();
            $table->string('otro_enfermedad_autoinmune', 2000)->nullable();
            $table->string('otro_patologia_sexual', 2000)->nullable();
            $table->string('otro_patologia', 2000)->nullable();
            $table->string('parentesco')->nullable();
            $table->date('fecha_diagnostico')->nullable();
            $table->bigInteger('citapaciente_id')->unsigned()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('antecedente_familiares');
    }
}
