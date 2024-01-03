<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampos465ToPacientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('ut_saliente')->nullable();
            $table->string('estado_civil')->nullable();
            $table->json('dx')->nullable();
            $table->json('cups')->nullable();
            $table->json('cums')->nullable();
            $table->json('propios')->nullable();
            $table->string('f_solicitud')->nullable();
            $table->string('anexo')->nullable();
            $table->string('ruta')->nullable();
            $table->string('represa')->nullable();
            $table->string('justifica_represa')->nullable();
            $table->string('observacion_contratista')->nullable();
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
           $table->string('estado_civil')->nullable();
           $table->json('dx')->nullable();
           $table->json('cups')->nullable();
           $table->json('cums')->nullable();
           $table->json('propios')->nullable();
           $table->string('f_solicitud')->nullable();
           $table->string('anexo')->nullable();
           $table->string('ruta')->nullable();
           $table->string('represa')->nullable();
           $table->string('justifica_represa')->nullable();
           $table->string('observacion_contratista')->nullable();

        });
    }
}
