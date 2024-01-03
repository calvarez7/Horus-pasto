<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewEfectividadToEfectividadFarmacologicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('efectividad_farmacologicas', function (Blueprint $table) {
            $table->bigInteger('medico_id')->nullable()->unsigned()->index();
            $table->foreign('medico_id')->references('id')->on('Users');
            $table->bigInteger('detaarticulordens_id')->nullable()->unsigned()->index();
            $table->foreign('detaarticulordens_id')->references('id')->on('detaarticulordens');
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('efectividad_farmacologicas', function (Blueprint $table) {
            $table->bigInteger('medico_id')->nullable()->unsigned()->index();
            $table->foreign('medico_id')->references('id')->on('Users');
            $table->bigInteger('detaarticulordens_id')->nullable()->unsigned()->index();
            $table->foreign('detaarticulordens_id')->references('id')->on('detaarticulordens');
            $table->bigInteger('Citapaciente_id')->nullable()->unsigned()->index();
            $table->foreign('Citapaciente_id')->references('id')->on('cita_paciente');
        });
    }
}
