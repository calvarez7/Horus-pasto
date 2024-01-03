<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDemandaInducidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demanda_inducidas', function (Blueprint $table) {
            $table->bigInteger('Cita_Paciente_1_id')->nullable()->unsigned()->index();
            $table->foreign('Cita_Paciente_1_id')->references('id')->on('cita_paciente');
            $table->bigInteger('Cita_Paciente_2_id')->nullable()->unsigned()->index();
            $table->foreign('Cita_Paciente_2_id')->references('id')->on('cita_paciente');
            $table->bigInteger('Cita_Paciente_3_id')->nullable()->unsigned()->index();
            $table->foreign('Cita_Paciente_3_id')->references('id')->on('cita_paciente');
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demanda_inducidas', function (Blueprint $table) {
            $table->foreign('Cita_Paciente_1_id')->references('id')->on('cita_paciente');
            $table->bigInteger('Cita_Paciente_2_id')->nullable()->unsigned()->index();
            $table->foreign('Cita_Paciente_2_id')->references('id')->on('cita_paciente');
            $table->bigInteger('Cita_Paciente_3_id')->nullable()->unsigned()->index();
            $table->foreign('Cita_Paciente_3_id')->references('id')->on('cita_paciente');
            $table->text('observaciones')->nullable();
        });
    }
}
