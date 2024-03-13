<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_procedimientos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rips_usuario_id')->nullable()->index();
            $table->foreign('rips_usuario_id')->references('id')->on('rips_usuarios');
            $table->string('codComplicacion')->nullable();
            $table->string('codDiagnosticoPrincipal')->nullable();
            $table->string('codDiagnosticoRelacionado')->nullable();
            $table->string('codPrestador')->nullable();
            $table->string('codProcedimiento')->nullable();
            $table->string('codServicio')->nullable();
            $table->string('conceptoRecaudo')->nullable();
            $table->string('consecutivo')->nullable();
            $table->string('fechaInicioAtencion')->nullable();
            $table->string('finalidadTecnologiaSalud')->nullable();
            $table->string('grupoServicios')->nullable();
            $table->string('idMIPRES')->nullable();
            $table->string('modalidadGrupoServicioTecSal')->nullable();
            $table->string('numAutorizacion')->nullable();
            $table->string('numDocumentoIdentificacion')->nullable();
            $table->string('numFEVPagoModerador')->nullable();
            $table->string('tipoDocumentoIdentificacion')->nullable();
            $table->string('valorPagoModerador')->nullable();
            $table->string('viaIngresoServicioSalud')->nullable();
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
        Schema::dropIfExists('rips_procedimientos');
    }
}
