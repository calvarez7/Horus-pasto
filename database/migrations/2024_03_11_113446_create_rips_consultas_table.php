<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_consultas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rips_usuario_id')->nullable()->index();
            $table->foreign('rips_usuario_id')->references('id')->on('rips_usuarios');
            $table->string('causaMotivoAtencion')->nullable();
            $table->string('codConsulta')->nullable();
            $table->string('codDiagnosticoPrincipal')->nullable();
            $table->string('codDiagnosticoRelacionado1')->nullable();
            $table->string('codDiagnosticoRelacionado2')->nullable();
            $table->string('codDiagnosticoRelacionado3')->nullable();
            $table->string('codPrestador')->nullable();
            $table->string('codServicio')->nullable();
            $table->string('conceptoRecaudo')->nullable();
            $table->string('consecutivo')->nullable();
            $table->string('fechaInicioAtencion')->nullable();
            $table->string('finalidadTecnologiaSalud')->nullable();
            $table->string('grupoServicios')->nullable();
            $table->string('modalidadGrupoServicioTecSal')->nullable();
            $table->string('numAutorizacion')->nullable();
            $table->string('numDocumentoIdentificacion')->nullable();
            $table->string('numFEVPagoModerador')->nullable();
            $table->string('tipoDiagnosticoPrincipal')->nullable();
            $table->string('tipoDocumentoIdentificacion')->nullable();
            $table->string('valorPagoModerador')->nullable();
            $table->string('vrServicio')->nullable();
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
        Schema::dropIfExists('rips_consultas');
    }
}
