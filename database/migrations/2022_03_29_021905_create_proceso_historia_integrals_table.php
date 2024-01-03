<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcesoHistoriaIntegralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proceso_historia_integrals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('porcentaje');
            $table->boolean('PacienteComponent')->nullable();
            $table->boolean('MotivoComponent')->nullable();
            $table->boolean('RevisionSistemasComponent')->nullable();
            $table->boolean('AntecedentesTransfusiones')->nullable();
            $table->boolean('AntecedentesQuirurgicosComponent')->nullable();
            $table->boolean('AntecedentesPersonalesComponent')->nullable();
            $table->boolean('AntecedentesGinecologicosComponent')->nullable();
            $table->boolean('EsquemaVacunacionComponent')->nullable();
            $table->boolean('AntecedentesFamiliaresComponent')->nullable();
            $table->boolean('AntecedentesHospitalizacionComponent')->nullable();
            $table->boolean('RedesApoyoComponent')->nullable();
            $table->boolean('ApgarFamiliarComponent')->nullable();
            $table->boolean('FamiliogramaComponent')->nullable();
            $table->boolean('AntecedentesBiopsicosocialesComponent')->nullable();
            $table->boolean('HabitosToxicosComponent')->nullable();
            $table->boolean('ResultadosLaboratorioComponent')->nullable();
            $table->boolean('ResultadosAyudasDxComponent')->nullable();
            $table->boolean('AntecedentesFarmacologicosComponent')->nullable();
            $table->boolean('ExamenFisicoComponent')->nullable();
            $table->boolean('AntecedenteQuimicoComponent')->nullable();
            $table->boolean('ImpresionDxComponent')->nullable();
            $table->boolean('PlanCuidadoComponent')->nullable();
            $table->boolean('PlanManejoComponent')->nullable();
            $table->boolean('AnamnesisPsicologiaOcupacionalComponent')->nullable();
            $table->boolean('AntecedentesPersonalesOcupacionalesComponent')->nullable();
            $table->boolean('AntecedentesFamiliaresOcupacionalesComponent')->nullable();
            $table->boolean('AreasPsicologiaOcupacionalComponent')->nullable();
            $table->boolean('ConductaOcupacionalComponent')->nullable();
            $table->boolean('AnamnesisVozOcupacionalComponent')->nullable();
            $table->boolean('RespiracionVozComponent')->nullable();
            $table->boolean('CualidadesVozComponent')->nullable();
            $table->boolean('ManejoPersonalVozComponent')->nullable();
            $table->boolean('ExamenFisicoVozComponent')->nullable();
            $table->boolean('AnamnesisVisiometriaComponent')->nullable();
            $table->boolean('ExamenVisiometriaComponent')->nullable();
            $table->boolean('AnamensisMedicaComponent')->nullable();
            $table->boolean('AntecedentesOcupacionalesMedicosComponent')->nullable();
            $table->boolean('VacunasMedicasOcupacionalesComponent')->nullable();
            $table->boolean('GinecoobstetricosMedicaComponent')->nullable();
            $table->boolean('HabitosMedicaComponent')->nullable();
            $table->boolean('RevisionSistemasMedicaComponent')->nullable();
            $table->boolean('CondicionesSaludMedicaComponent')->nullable();
            $table->boolean('ExamenFisicoMedicaComponent')->nullable();
            $table->boolean('EstiloVidaComponent')->nullable();
            $table->boolean('OftalmologiaComponent')->nullable();
            $table->boolean('DescripcionPatologicaComponent')->nullable();
            $table->boolean('OptometriaComponent')->nullable();
            $table->boolean('BiomiOftalmoscopiaComponent')->nullable();
            $table->boolean('RefraSubjetivoComponent')->nullable();
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
        Schema::dropIfExists('proceso_historia_integrals');
    }
}
