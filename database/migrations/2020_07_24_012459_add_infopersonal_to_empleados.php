<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfopersonalToEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->bigInteger('eps_id')->unsigned()->nullable()->index();
            $table->foreign('eps_id')->references('id')->on('eps');
            $table->bigInteger('area_id')->unsigned()->nullable()->index();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->string('tipo_documento')->nullable();
            $table->string('primer_nombre')->nullable();
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('lugar_nacimineto')->nullable();
            $table->integer('edad')->nullable();
            $table->string('lugar_exp_documento')->nullable();
            $table->string('fecha_exp_documento')->nullable();
            $table->string('nombre_documento')->nullable();
            $table->string('ruta_documento')->nullable();
            $table->string('genero')->nullable();
            $table->string('grupo_sanguineo')->nullable();
            $table->string('etnia')->nullable();
            $table->string('discapacidad')->nullable();
            $table->string('tipo_discapacidad')->nullable();
            $table->string('cabeza_familia')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('estatura')->nullable();
            $table->string('peso')->nullable();
            $table->integer('telefono')->nullable();
            $table->string('celular2')->nullable();
            $table->string('correo_corporativo')->nullable();
            $table->string('direccion_residencia')->nullable();
            $table->string('barrio')->nullable();
            $table->string('tipo_vivienda')->nullable();
            $table->integer('estrato')->nullable();
            $table->bigInteger('municipio_id')->unsigned()->nullable()->index();
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->string('contacto_emergencia')->nullable();
            $table->string('tel_contacto_emergencia')->nullable();
            $table->string('parentesco_contacto')->nullable();
            $table->string('nombre_foto')->nullable();
            $table->string('ruta_foto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->bigInteger('eps_id')->unsigned()->nullable()->index();
            $table->foreign('eps_id')->references('id')->on('eps');
            $table->bigInteger('area_id')->unsigned()->nullable()->index();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->string('tipo_documento')->nullable();
            $table->string('primer_nombre')->nullable();
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('lugar_nacimineto')->nullable();
            $table->integer('edad')->nullable();
            $table->string('lugar_exp_documento')->nullable();
            $table->string('fecha_exp_documento')->nullable();
            $table->string('nombre_documento')->nullable();
            $table->string('ruta_documento')->nullable();
            $table->string('genero')->nullable();
            $table->string('grupo_sanguineo')->nullable();
            $table->string('etnia')->nullable();
            $table->string('discapacidad')->nullable();
            $table->string('tipo_discapacidad')->nullable();
            $table->string('cabeza_familia')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('estatura')->nullable();
            $table->string('peso')->nullable();
            $table->integer('telefono')->nullable();
            $table->string('celular2')->nullable();
            $table->string('correo_corporativo')->nullable();
            $table->string('direccion_residencia')->nullable();
            $table->string('barrio')->nullable();
            $table->string('tipo_vivienda')->nullable();
            $table->integer('estrato')->nullable();
            $table->bigInteger('municipio_id')->unsigned()->nullable()->index();
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->string('contacto_emergencia')->nullable();
            $table->string('tel_contacto_emergencia')->nullable();
            $table->string('parentesco_contacto')->nullable();
            $table->string('nombre_foto')->nullable();
            $table->string('ruta_foto')->nullable();
        });
    }
}
