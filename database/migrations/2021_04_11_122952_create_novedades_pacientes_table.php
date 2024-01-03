<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadesPacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novedades_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->unsigned()->nullable()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Tipo_id')->unsigned()->index();
            $table->foreign('Tipo_id')->references('id')->on('Tipos');
            $table->date('fecha_novedad')->nullable();
            $table->text('movtivo')->nullable();
            $table->date('fecha_creacion')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('novedades_pacientes');
    }
}
