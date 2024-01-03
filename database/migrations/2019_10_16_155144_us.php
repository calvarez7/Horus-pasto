<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Us extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Paciente_id')->unsigned()->index();
            $table->foreign('Paciente_id')->references('id')->on('pacientes')->OnDelete('cascade');
            $table->bigInteger('Municipio_id')->unsigned()->index();
            $table->foreign('Municipio_id')->references('id')->on('Municipios')->OnDelete('cascade');
            $table->string('Codigo_Admin')->default('RES004');            
            $table->string('Tipo_Usuario')->default('5');            
            $table->string('Edad');            
            $table->string('Unidad_Medida');             
            $table->string('Zona_Residencia'); 
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
        Schema::dropIfExists('us');
    }
}
