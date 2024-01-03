<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedenteBiopsicosocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedente_biopsicosocials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('checkboxOrientacionSex')->nullable();
            $table->boolean('checkboxIdentidadGenero')->nullable();
            $table->boolean('checkboxIncioSexual')->nullable();
            $table->boolean('checkboxNumeroRelaciones')->nullable();
            $table->boolean('checkboxUsoAnticonceptivo')->nullable();
            $table->boolean('checkboxEdadMenarquia')->nullable();
            $table->boolean('checkboxEdadEsperma')->nullable();
            $table->boolean('checkboxActivoSexual')->nullable();
            $table->boolean('checkboxDificultadesRelaciones')->nullable();
            $table->boolean('checkboxConocimientoIts')->nullable();
            $table->boolean('checkboxTransmisionSexual')->nullable();
            $table->boolean('checkboxDerechosSexuales')->nullable();
            $table->boolean('checkboxPreocupacionSalud')->nullable();
            $table->boolean('checkboxProblemasDesarroInfantil')->nullable();
            $table->boolean('checkboxVictimaGenero')->nullable();
            $table->boolean('checkboxVictimaIdentidadgenero')->nullable();
            $table->string('orientacion_sex')->nullable();
            $table->string('identidad_genero')->nullable();
            $table->string('inicio_sexual')->nullable();
            $table->string('numero_relaciones')->nullable();
            $table->string('fecha_inciosexual')->nullable();
            $table->string('activo_sexual')->nullable();
            $table->string('edad_esperma')->nullable();
            $table->string('edad_menarquia')->nullable();
            $table->string('uso_anticonceptivos')->nullable();
            $table->string('dificultades_relaciones')->nullable();
            $table->string('conocimiento_its')->nullable();
            $table->string('trasnmision_sexual')->nullable();
            $table->string('derechos_sexuales')->nullable();
            $table->string('victima_identidadgenero')->nullable();
            $table->string('victima_genero')->nullable();
            $table->string('problemas_desarrolloinfantil')->nullable();
            $table->string('preocupacion_salud')->nullable();
            $table->string('tipo_victimagenero')->nullable();
            $table->string('tipo_victima_violencia_genero')->nullable();
            $table->boolean('checkboxDuermeBien')->nullable();
            $table->boolean('checkboxCuantasHoras')->nullable();
            $table->boolean('checkboxTiempoJuego')->nullable();
            $table->boolean('checkboxActivadFisica')->nullable();
            $table->string('duerme_bien')->nullable();
            $table->string('cuantas_horas')->nullable();
            $table->string('tiempo_juego')->nullable();
            $table->string('actividad_fisica')->nullable();
            $table->boolean('checkboxBañoDia')->nullable();
            $table->string('cuantas_baño')->nullable();
            $table->boolean('checkboxCuidadoBucal')->nullable();
            $table->string('cuidado_bucal')->nullable();
            $table->boolean('checkboxControlVesical')->nullable();
            $table->string('cuidado_vesical')->nullable();
            $table->boolean('checkboxCaracteristicasOrina')->nullable();
            $table->string('caracteristicas_orina')->nullable();
            $table->boolean('checkboxControlRectal')->nullable();
            $table->string('cuidado_rectal')->nullable();
            $table->boolean('checkboxCaracteristicasdeposiciones')->nullable();
            $table->string('caracteristicas_deposiciones')->nullable();
            $table->boolean('checkboxExposicionTv')->nullable();
            $table->string('exposicion_tv')->nullable();
            $table->string('tipo_anticonceptivos')->nullable();
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
        Schema::dropIfExists('antecedente_biopsicosocials');
    }
}
