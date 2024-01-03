<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeguimientoAtencionContingenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimiento_atencion_contingencia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('Pacientes');
            $table->bigInteger('municipio_ocurrencia_id')->unsigned()->index();
            $table->foreign('municipio_ocurrencia_id')->references('id')->on('Municipios');
            $table->bigInteger('user_crea_id')->unsigned()->index();
            $table->foreign('user_crea_id')->references('id')->on('Users');
            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('Estados');
            $table->string('nacionalidad')->nullable();
            $table->boolean('afirmacion_viaje_internacional')->nullable();
            $table->string("descripcion_viaje_internacional")->nullable();
            $table->boolean('afirmacion_viaje_nacional')->nullable();
            $table->string("descripcion_viaje_nacional")->nullable();
            $table->json('tipo_contatos')->nullable();
            $table->string("tipo_vivienda")->nullable();
            $table->boolean('habitacion_individual')->nullable();
            $table->string("clasificacion_caso")->nullable();
            $table->string("observaciones")->nullable();
            $table->boolean('docente_clase');
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
        Schema::dropIfExists('seguimiento_atencion_contingencia');
    }
}
