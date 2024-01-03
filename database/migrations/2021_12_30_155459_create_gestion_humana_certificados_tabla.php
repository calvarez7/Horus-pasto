<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionHumanaCertificadosTabla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestion_humana_certificados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('documento');
            $table->string('nombre_del_completo');
            $table->string('cargo_empleado');
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro')->nullable();
            $table->date('fecha_de_aumento');
            $table->integer('salario');
            $table->string('grupo');
            $table->string('estado_empleado');
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
        Schema::dropIfExists('gestion_humana_certificados');
    }
}
