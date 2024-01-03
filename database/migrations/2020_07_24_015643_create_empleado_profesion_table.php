<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadoProfesionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_profesion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('empleado_id')->unsigned()->index();
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->bigInteger('profesion_id')->unsigned()->index();
            $table->foreign('profesion_id')->references('id')->on('profesions');
            $table->string('numero_acta');
            $table->date('fecha_grado');
            $table->string('institucion');
            $table->bigInteger('ciudad')->unsigned()->nullable()->index();
            $table->foreign('ciudad')->references('id')->on('municipios');
            $table->string('nivel_estudio');
            $table->date('fecha_titulo')->nullable();
            $table->string('user_verifica_titulo')->nullable();
            $table->date('fecha_respuesta_inst')->nullable();
            $table->bigInteger('estado_titulo')->nullable()->unsigned()->index();
            $table->foreign('estado_titulo')->references('id')->on('estados');
            $table->bigInteger('estado_verificado')->default('18')->unsigned()->index();
            $table->foreign('estado_verificado')->references('id')->on('estados');
            $table->string('nombre_certificado')->nullable();
            $table->string('ruta_certificado')->nullable();
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
        Schema::dropIfExists('empleado_profesion');
    }
}
