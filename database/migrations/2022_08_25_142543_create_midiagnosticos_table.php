<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMidiagnosticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midiagnosticos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('azucar')->nullable();
            $table->string('cedula')->nullable();
            $table->string('ciclo_vida')->nullable();
            $table->string('clasificacion')->nullable();
            $table->string('colesterol_hdl')->nullable();
            $table->string('diabetes')->nullable();
            $table->string('ejercicio')->nullable();
            $table->string('eres_diabetico')->nullable();
            $table->string('examen_colesterol')->nullable();
            $table->string('fecha_nacimiento')->nullable();
            $table->string('frutas_verduras')->nullable();
            $table->string('fumador')->nullable();
            $table->string('hipertension')->nullable();
            $table->string('imc')->nullable();
            $table->string('perimetro_abdominal')->nullable();
            $table->string('peso')->nullable();
            $table->string('sexo')->nullable();
            $table->string('talla')->nullable();
            $table->string('total_colesterol')->nullable();
            $table->string('trigliceridos')->nullable();
            $table->string('email')->nullable();
            $table->string('presion_sistolica')->nullable();
            $table->string('presion_diastolica')->nullable();
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
        Schema::dropIfExists('midiagnosticos');
    }
}
