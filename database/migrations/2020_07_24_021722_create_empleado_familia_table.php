<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadoFamiliaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_familia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('familiar_id')->unsigned()->index();
            $table->foreign('familiar_id')->references('id')->on('familiares');
            $table->bigInteger('empleado_id')->unsigned()->index();
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->string('tipo_documento');
            $table->integer('num_documento');
            $table->string('nombre');
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->string('genero');
            $table->string('escolaridad');
            $table->string('profesion');
            $table->string('depende_empleado');
            $table->string('vivecon_el_empleado');
            $table->string('nombre_documento');
            $table->string('ruta_documento');
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
        Schema::dropIfExists('empleado_familia');
    }
}
