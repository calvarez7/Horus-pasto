<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOcupacionesToRegistrocovis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->bigInteger('ocupacion_id')->nullable()->unsigned()->index();
            $table->foreign('ocupacion_id')->references('id')->on('ocupaciones');
            $table->string('clasificacion_final_caso')->nullable();
            $table->string('Viajo_circulacion_virus')->nullable();
            $table->string('viajo_territorio_nacional')->nullable();
            $table->bigInteger('municipio_viajo_id')->nullable()->unsigned()->index();
            $table->foreign('municipio_viajo_id')->references('id')->on('municipios');
            $table->string('viajo_internacional')->nullable();
            $table->bigInteger('pais_viajo_id')->nullable()->unsigned()->index();
            $table->foreign('pais_viajo_id')->references('id')->on('paises');
            $table->string('tuvo_contacto_estrecho')->nullable();
            $table->string('tos')->nullable();
            $table->string('fiebre')->nullable();
            $table->string('dolor_garganta')->nullable();
            $table->string('dificultad_respiratoria')->nullable();
            $table->string('fatiga_adinamia')->nullable();
            $table->string('rinorrea')->nullable();
            $table->string('conjuntivitis')->nullable();
            $table->string('cefalea')->nullable();
            $table->string('diarrea')->nullable();
            $table->string('perdida_de_olfato')->nullable();
            $table->string('otros_sintomas')->nullable();
            $table->string('cuales_otros')->nullable();
            $table->string('asma')->nullable();
            $table->string('epoc')->nullable();
            $table->string('diabetes')->nullable();
            $table->string('vih')->nullable();
            $table->string('enfermedad_cardiaca')->nullable();
            $table->string('cancer')->nullable();
            $table->string('malnutricion')->nullable();
            $table->string('obesidad')->nullable();
            $table->string('insuficiencia_renal')->nullable();
            $table->string('medicamentos_inmunosupresores')->nullable();
            $table->string('fumador')->nullable();
            $table->string('hipertensión')->nullable();
            $table->string('tuberculosis')->nullable();
            $table->string('otros')->nullable();
            $table->string('otros_antecedentes')->nullable();
            $table->string('infiltrado_alveolar_neumonia')->nullable();
            $table->string('infiltrados_intesticiales')->nullable();
            $table->string('infiltrados_basales_vidrio_esmerilado')->nullable();
            $table->string('ninguno')->nullable();
            $table->string('servicio_hospitalizo')->nullable();
            $table->date('fecha_ingreso_uci')->nullable();
            $table->string('derrame_pleural')->nullable();
            $table->string('derrame_pericardico')->nullable();
            $table->string('miocarditis')->nullable();
            $table->string('septicemia')->nullable();
            $table->string('falla_respiratoria')->nullable();
            $table->string('otro')->nullable();
            $table->string('otra_complicacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->bigInteger('ocupacion_id')->nullable()->unsigned()->index();
            $table->foreign('ocupacion_id')->references('id')->on('ocupaciones');
        });
    }
}
