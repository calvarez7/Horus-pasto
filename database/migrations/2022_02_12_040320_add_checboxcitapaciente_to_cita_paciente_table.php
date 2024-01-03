<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChecboxcitapacienteToCitaPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->boolean('checkboxOftalmologico')->nullable();
            $table->boolean('checkboxGenitourinario')->nullable();
            $table->boolean('checkboxOtorrinoralingologico')->nullable();
            $table->boolean('checkboxLinfatico')->nullable();
            $table->boolean('checkboxOsteomioarticular')->nullable();
            $table->boolean('checkboxNeurologico')->nullable();
            $table->boolean('checkboxCardiovascular')->nullable();
            $table->boolean('checkboxTegumentario')->nullable();
            $table->boolean('checkboxRespiratorio')->nullable();
            $table->boolean('checkboxEndocrinologico')->nullable();
            $table->boolean('checkboxGastrointestinal')->nullable();
            $table->boolean('checkboxNorefiere')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->boolean('checkboxOftalmologico')->nullable();
            $table->boolean('checkboxGenitourinario')->nullable();
            $table->boolean('checkboxOtorrinoralingologico')->nullable();
            $table->boolean('checkboxLinfatico')->nullable();
            $table->boolean('checkboxOsteomioarticular')->nullable();
            $table->boolean('checkboxNeurologico')->nullable();
            $table->boolean('checkboxCardiovascular')->nullable();
            $table->boolean('checkboxTegumentario')->nullable();
            $table->boolean('checkboxRespiratorio')->nullable();
            $table->boolean('checkboxEndocrinologico')->nullable();
            $table->boolean('checkboxGastrointestinal')->nullable();
            $table->boolean('checkboxNorefiere')->nullable();
        });
    }
}
