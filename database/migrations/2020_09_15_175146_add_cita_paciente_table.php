<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCitaPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cita_paciente', function (Blueprint $table) {
            $table->string('avcc_ojoderecho')->nullable();
            $table->string('avcc_ojoizquierdo')->nullable();
            $table->string('avsc_ojoderecho')->nullable();
            $table->string('avsc_ojoizquierdo')->nullable();
            $table->string('ph_ojoderecho')->nullable();
            $table->string('ph_ojoizquierdo')->nullable();
            $table->text('motilidad_ojoderecho')->nullable();
            $table->text('covert_ojoderecho')->nullable();
            $table->text('motilidad_ojoizquierdo')->nullable();
            $table->text('covert_ojoizquierdo')->nullable();
            $table->text('biomicros_ojoderecho')->nullable();
            $table->text('biomicros_ojoizquierdo')->nullable();
            $table->text('presionintra_ojoderecho')->nullable();
            $table->text('presionintra_ojoizquierdo')->nullable();
            $table->text('gionios_ojoderecho')->nullable();
            $table->text('gionios_ojoizquierdo')->nullable();
            $table->text('fondo_ojoderecho')->nullable();
            $table->text('fondo_ojoizquierdo')->nullable();
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
            $table->string('avcc_ojoderecho')->nullable();
            $table->string('avcc_ojoizquierdo')->nullable();
            $table->string('avsc_ojoderecho')->nullable();
            $table->string('avsc_ojoizquierdo')->nullable();
            $table->string('ph_ojoderecho')->nullable();
            $table->string('ph_ojoizquierdo')->nullable();
            $table->text('motilidad_ojoderecho')->nullable();
            $table->text('covert_ojoderecho')->nullable();
            $table->text('motilidad_ojoizquierdo')->nullable();
            $table->text('covert_ojoizquierdo')->nullable();
            $table->text('biomicros_ojoderecho')->nullable();
            $table->text('biomicros_ojoizquierdo')->nullable();
            $table->text('presionintra_ojoderecho')->nullable();
            $table->text('presionintra_ojoizquierdo')->nullable();
            $table->text('gionios_ojoderecho')->nullable();
            $table->text('gionios_ojoizquierdo')->nullable();
            $table->text('fondo_ojoderecho')->nullable();
            $table->text('fondo_ojoizquierdo')->nullable();
        });
    }
}
