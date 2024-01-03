<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPacientenewcamposToPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('ciclo_vida')->nullable();
            $table->string('regional')->nullable();
            $table->string('tipo_vivienda')->nullable();
            $table->string('zona_vivienda')->nullable();
            $table->string('numero_habitaciones')->nullable();
            $table->string('numero_miembros')->nullable();
            $table->string('acceso_vivienda')->nullable();
            $table->string('seguridad_vivienda')->nullable();
            $table->string('hogar_con_agua')->nullable();
            $table->string('prepara_comida_con')->nullable();
            $table->string('vivienda_con_energia')->nullable();
            $table->string('mascota')->nullable();
            $table->string('religion')->nullable();
            $table->string('nombre_pareja')->nullable();
            $table->string('pareja_actual_padre')->nullable();
            $table->string('ocupacion_padre')->nullable();
            $table->string('grupo_sanguineo_padre')->nullable();
            $table->bigInteger('municipio_nacimiento')->unsigned()->index()->nullable();
            $table->foreign('municipio_nacimiento')->references('id')->on('municipios');
            $table->bigInteger('pais_nacimiento')->unsigned()->index()->nullable();
            $table->foreign('pais_nacimiento')->references('id')->on('paises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            //
        });
    }
}
