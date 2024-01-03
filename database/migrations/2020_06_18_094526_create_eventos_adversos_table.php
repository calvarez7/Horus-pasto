<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosAdversosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_adversos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('evento_id')->unsigned()->index();
            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->bigInteger('bodegaimagenologia_id')->nullable()->unsigned()->index();
            $table->foreign('bodegaimagenologia_id')->references('id')->on('bodega_imagenologias');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('fecha_venci_medicamento')->nullable();
            $table->string('lote_medicamento')->nullable();
            $table->string('invima_medicamento')->nullable();
            $table->string('sede_ocurrencia')->nullable();
            $table->string('sede_reportante')->nullable();
            $table->string('clasificacion')->nullable();
            $table->date('fecha_ocurrencia')->nullable();
            $table->string('relacion')->nullable();
            $table->string('dispositivo')->nullable();
            $table->string('referencia')->nullable();
            $table->string('modelo')->nullable();
            $table->string('serial')->nullable();
            $table->string('invima_dispositivo')->nullable();
            $table->text('descripcion_hechos')->nullable();
            $table->text('accion_tomada')->nullable();
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
        Schema::dropIfExists('eventos_adversos');
    }
}
