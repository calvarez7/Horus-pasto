<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablasRips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paq_rips', function (Blueprint $table) {
            $table->string('nombre_rep')->nullable();
            $table->string('codigo')->nullable();
            $table->string('entidad')->nullable();
            $table->string('ac_size')->nullable();
            $table->string('af_size')->nullable();
            $table->string('ah_size')->nullable();
            $table->string('am_size')->nullable();
            $table->string('ap_size')->nullable();
            $table->string('at_size')->nullable();
            $table->string('au_size')->nullable();
            $table->string('ct_size')->nullable();
            $table->string('us_size')->nullable();
            $table->string('mes')->nullable();
            $table->string('anio')->nullable();
            $table->string('ruta')->nullable();
        });

        Schema::table('cts', function (Blueprint $table) {
            $table->string('codigo_prestador')->nullable();
        });

        Schema::table('us', function (Blueprint $table) {

            $table->string('numero_documento')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->string('primer_nombre')->nullable();
            $table->string('segundo_nombre')->nullable();
            $table->string('sexo')->nullable();
            $table->string('codigo_departamento_residencia')->nullable();
            $table->string('codigo_municipio_residencia')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('afs', function (Blueprint $table) {
            $table->string('codigo_prestador')->nullable();
            $table->string('nombre_prestador')->nullable();
        });

        Schema::table('acs', function (Blueprint $table) {
            $table->string('consulta')->nullable();
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('codigo_diagnostico_principal')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('aps', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('procedimiento')->nullable();
            $table->string('diagnostico_primario')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('ans', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('diagnostico_recien_nacido')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('ahs', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('diagnostico_principal_ingreso')->nullable();
            $table->string('diagnostico_principal_egreso')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('aus', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('diagnostico_principal_salida')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });
        Schema::table('ats', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('ams', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('codigo_medicamento')->nullable();
            $table->string('nombre_generico')->nullable();
            $table->string('forma_farmaceutica')->nullable();
            $table->string('concentracion_medicamento')->nullable();
            $table->string('unidad_medida')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paq_rips', function (Blueprint $table) {
            $table->string('nombre_rep')->nullable();
            $table->string('codigo')->nullable();
            $table->string('entidad')->nullable();
            $table->string('ac_size')->nullable();
            $table->string('af_size')->nullable();
            $table->string('ah_size')->nullable();
            $table->string('am_size')->nullable();
            $table->string('ap_size')->nullable();
            $table->string('at_size')->nullable();
            $table->string('au_size')->nullable();
            $table->string('ct_size')->nullable();
            $table->string('us_size')->nullable();
            $table->string('mes')->nullable();
            $table->string('anio')->nullable();
            $table->string('ruta')->nullable();
        });

        Schema::table('cts', function (Blueprint $table) {
            $table->string('codigo_prestador')->nullable();
        });

        Schema::table('us', function (Blueprint $table) {
            $table->string('tipo_documento')->nullable();
            $table->string('primer_apellido')->nullable();
            $table->string('segundo_apellido')->nullable();
            $table->string('primer_nombre')->nullable();
            $table->string('segundo_nombre')->nullable();
            $table->string('sexo')->nullable();
            $table->string('codigo_departamento_residencia')->nullable();
            $table->string('codigo_municipio_residencia')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('afs', function (Blueprint $table) {
            $table->string('codigo_prestador')->nullable();
            $table->string('nombre_prestador')->nullable();
        });

        Schema::table('acs', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('codigo_diagnostico_principal')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('aps', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('procedimiento')->nullable();
            $table->string('diagnostico_primario')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('ans', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('diagnostico_recien_nacido')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('ahs', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('diagnostico_principal_ingreso')->nullable();
            $table->string('diagnostico_principal_egreso')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('aus', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('diagnostico_principal_salida')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });
        Schema::table('ats', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });

        Schema::table('ams', function (Blueprint $table) {
            $table->string('numero_factura')->nullable();
            $table->string('codigo_prestador')->nullable();
            $table->string('codigo_medicamento')->nullable();
            $table->string('nombre_generico')->nullable();
            $table->string('forma_farmaceutica')->nullable();
            $table->string('concentracion_medicamento')->nullable();
            $table->string('unidad_medida')->nullable();
            $table->bigInteger('paq_rip_id')->nullable()->unsigned()->index();
            $table->foreign('paq_rip_id')->references('id')->on('paq_rips');
        });
    }
}
