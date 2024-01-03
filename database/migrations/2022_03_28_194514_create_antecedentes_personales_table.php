<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedentesPersonalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_personales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('checkboxOtras_enfermedades')->nullable();
            $table->string('patologias')->nullable();
            $table->string('tipo_cancer')->nullable();
            $table->string('tipo_asma')->nullable();
            $table->string('tipo_epoc')->nullable();
            $table->string('tipo_bronquitis_cronica')->nullable();
            $table->string('tipo_tuberculosis')->nullable();
            $table->string('tipo_diabetes')->nullable();
            $table->string('tipo_enfermedad_renal_cronica')->nullable();
            $table->string('tipo_trastorno_mental')->nullable();
            $table->string('tipo_malnutricion')->nullable();
            $table->string('tipo_enfermedad_tiroidea')->nullable();
            $table->string('tipo_enfermedades_trasmision_sexual')->nullable();
            $table->string('tipo_enfermedades_autoinmunes')->nullable();
            $table->string('otro_patologia_cancer', 2000)->nullable();
            $table->string('cual_discapacidad', 2000)->nullable();
            $table->string('otras_enfermedades_autoinmunes', 2000)->nullable();
            $table->string('otra_enfermedades_trasmision_sexual', 2000)->nullable();
            $table->string('otro_patologia', 2000)->nullable();
            $table->string('cual_enfermedad_genetica', 2000)->nullable();
            $table->string('descripcion_otras_enfermedades_neurologicas', 2000)->nullable();
            $table->string('descripcion_enfermedad_o_accidente_laboral', 2000)->nullable();
            $table->string('fecha_diagnostico')->nullable();
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
        Schema::dropIfExists('antecedentes_personales');
    }
}
