<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntecedentesGinecologicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antecedentes_ginecologicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('checkboxMenarquia')->nullable();
            $table->string('presente_menarquia')->nullable();
            $table->string('fecha_menarquia')->nullable();
            $table->boolean('checkboxCiclos')->nullable();
            $table->string('clasificacion_ciclos')->nullable();
            $table->string('frecuencia_ciclos')->nullable();
            $table->string('duracion_ciclos')->nullable();
            $table->boolean('checkboxFlujovaginal')->nullable();
            $table->string('presente_flujovaginal')->nullable();
            $table->string('descripcion_flujovaginal')->nullable();
            $table->string('tratamiento_flujovaginal')->nullable();
            $table->string('fecha_flujovaginal')->nullable();
            $table->boolean('checkboxTransmisionsexual')->nullable();
            $table->string('presente_transmisionsexual')->nullable();
            $table->string('descripcion_transmisionsexual')->nullable();
            $table->string('tratamiento_transmisionsexual')->nullable();
            $table->string('fecha_transmisionsexual')->nullable();
            $table->boolean('checkboxMetodoanticonceptivo')->nullable();
            $table->string('presente_metodoanticonceptivo')->nullable();
            $table->string('descripcion_metodoanticonceptivo')->nullable();
            $table->string('tipo_metodoanticonceptivo')->nullable();
            $table->string('tratamiento_metodoanticonceptivo')->nullable();
            $table->string('fecha_metodoanticonceptivo')->nullable();
            $table->boolean('checkboxTratamientoinfertilidad')->nullable();
            $table->string('presente_tratamientoinfertilidad')->nullable();
            $table->string('tratamiento_tratamientoinfertilidad')->nullable();
            $table->string('fecha_tratamientoinfertilidad')->nullable();
            $table->boolean('checkboxAutoexamensenos')->nullable();
            $table->string('presente_autoexamensenos')->nullable();
            $table->string('frecuencia_autoexamensenos')->nullable();
            $table->boolean('checkboxPatologiacervical')->nullable();
            $table->string('presente_patologiacervical')->nullable();
            $table->boolean('checkboxProcedimientocuellouterino')->nullable();
            $table->string('presente_procedimientocuellouterino')->nullable();
            $table->string('tratamiento_procedimientocuellouterino')->nullable();
            $table->string('fecha_procedimientocuellouterino')->nullable();
            $table->boolean('checkboxOtrotipotratamiento')->nullable();
            $table->string('tratamiento_otrotipotratamiento')->nullable();
            $table->boolean('checkbox_embarazo')->nullable();
            $table->string('gesta')->nullable();
            $table->string('parto')->nullable();
            $table->string('aborto')->nullable();
            $table->string('vivos')->nullable();
            $table->string('cesarea')->nullable();
            $table->string('mortinato')->nullable();
            $table->string('ectopicos')->nullable();
            $table->string('molas')->nullable();
            $table->string('gemelos')->nullable();
            $table->string('gestacionalfum')->nullable();
            $table->string('embarazodeseado')->nullable();
            $table->string('gestacionaleco1')->nullable();
            $table->string('embarazoplaneado')->nullable();
            $table->string('gestacionaleco2')->nullable();
            $table->string('embarazoaceptado')->nullable();
            $table->string('gestacionaleco3')->nullable();
            $table->string('ultimamestruacion')->nullable();
            $table->string('probableparto')->nullable();
            $table->string('intergenesico')->nullable();
            $table->string('gestacionalcaptacion')->nullable();
            $table->string('semananacimineto')->nullable();
            $table->string('inconvenienteslactancia')->nullable();
            $table->string('planlactanciaretorno')->nullable();
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
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
        Schema::dropIfExists('antecedentes_ginecologicos');
    }
}
