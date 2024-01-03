<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGastosImagenologiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gastos_imagenologia', function (Blueprint $table) {
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gastos_imagenologia', function (Blueprint $table) {
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
        });
    }
}
