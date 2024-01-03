<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSarlaftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sarlafts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('tipo_solicitud')->nullable();
            $table->string('tipo_cliente')->nullable();
            $table->bigInteger('municipio_id')->unsigned()->nullable()->index();
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->string('clase')->nullable();
            $table->string('sector')->nullable();
            $table->string('cual_descripcion')->nullable();
            $table->string('tipo_bienservicio')->nullable();
            $table->string('direccion')->nullable();
            $table->string('nombre_diligencia')->nullable();
            $table->string('cedula_diligencia')->nullable();
            $table->string('cargo_diligencia')->nullable();
            $table->bigInteger('departamento_id')->unsigned()->nullable()->index();
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->string('email_empresa')->nullable();
            $table->string('telefono_empresa')->nullable();
            $table->string('fax')->nullable();
            $table->string('numero_contacto')->nullable();
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('sarlafts');
    }
}
