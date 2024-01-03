<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditoriaNovedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria_novedades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('novedad_id')->unsigned()->nullable()->index();
            $table->foreign('novedad_id')->references('id')->on('novedades_pacientes');
            $table->bigInteger('paciente_id')->unsigned()->nullable()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Tipo_id')->unsigned()->index();
            $table->foreign('Tipo_id')->references('id')->on('Tipos');
            $table->date('fecha_novedad')->nullable();
            $table->date('fecha_creacion')->nullable();
            $table->text('movtivo')->nullable();
            $table->bigInteger('userActualiza_id')->unsigned()->nullable()->index();
            $table->foreign('userActualiza_id')->references('id')->on('users');
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('auditoria_novedades');
    }
}
