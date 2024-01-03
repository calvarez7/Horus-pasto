<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOportunidadCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oportunidad_citas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dpto');
            $table->string('mpio');
            $table->string('Tipo_Doc');
            $table->string('Num_Doc');
            $table->date('fecha_deseada');
            $table->date('fecha_asignacion');
            $table->string('tipo_consulta')->nullable();
            $table->integer('entidad_id');
            $table->string('entidad');
            $table->integer('IPS_id');
            $table->string('IPS');
            $table->integer('estado_id');
            $table->string('estado_cita');
            $table->integer('medico_id')->nullable();
            $table->string('nombre_medico')->nullable();
            $table->string('especialidad_medico')->nullable();
            $table->string('Mes');
            $table->string('Año');
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
        Schema::dropIfExists('oportunidad_citas');
    }
}
