<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePqrsfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqrsfs', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->bigInteger('Paciente_id')->unsigned()->index()->nullable();
            $table->foreign('Paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('Estado_id')->unsigned()->index();
            $table->string('Reabierta')->nullable();
            $table->string('Priorizacion')->nullable();
            $table->string('Atr_calidad')->nullable();
            $table->string('Prestador')->nullable();
            $table->string('Procedente')->nullable();                    
            $table->string('CCgenera')->nullable();
            $table->string('Nombregenera')->nullable();
            $table->string('Email');
            $table->string('Telefono', 20);
            $table->string('Tiposolicitud')->nullable();
            $table->string('Canal')->default("Web");
            $table->string('Descripcion', 2000);
            $table->string('Pqr_codigo')->nullable();
            $table->string('Fecha_creacion')->nullable();
            $table->string('Afec_tipodoc')->nullable();
            $table->string('Afec_numdoc')->nullable();
            $table->string('Afec_nombres')->nullable();
            $table->string('Afec_direccion')->nullable();
            $table->string('Afec_municipio')->nullable();
            $table->string('Afec_depto')->nullable();
            $table->string('Pqr_estado')->nullable();
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
        Schema::dropIfExists('pqrsfs');
    }
}
