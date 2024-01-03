<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCensosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('censos', function (Blueprint $table) {
            $table->id();
            $table->string('ips')->nullable();
            $table->string('atencion_admision')->nullable();
            $table->string('tipo_identficicacion')->nullable();
            $table->string('numero_identificacion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('diganostico_cie10')->nullable();
            $table->string('fecha_ingreso')->nullable();
            $table->string('nombres_apellidos')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('dias_estancia')->nullable();
            $table->string('via_ingreso')->nullable();
            $table->string('Asegurador')->nullable();
            $table->string('grupo_diagnostico')->nullable();
            $table->string('ips_basica')->nullable();
            $table->string('Alta')->nullable();
            $table->string('areas_reporte')->nullable();
            $table->string('pym')->nullable();
            $table->bigInteger('concurrencia_id')->nullable()->unsigned()->index();
            $table->foreign('concurrencia_id')->references('id')->on('registroconcurrencias');
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
        Schema::dropIfExists('censos');
    }
}
