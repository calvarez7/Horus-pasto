<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsUrgenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_urgencias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rips_usuario_id')->nullable()->index();
            $table->foreign('rips_usuario_id')->references('id')->on('rips_usuarios');
            $table->string('causaMotivoAtencion')->nullable();
            $table->string('codDiagnosticoCausaMuerte')->nullable();
            $table->string('codDiagnosticoPrincipal')->nullable();
            $table->string('codDiagnosticoPrincipalE')->nullable();
            $table->string('codDiagnosticoRelacionadoE1')->nullable();
            $table->string('codDiagnosticoRelacionadoE2')->nullable();
            $table->string('codDiagnosticoRelacionadoE3')->nullable();
            $table->string('codPrestador')->nullable();
            $table->string('condicionDestinoUsuarioEgreso')->nullable();
            $table->string('consecutivo')->nullable();
            $table->string('fechaEgreso')->nullable();
            $table->string('fechaInicioAtencion')->nullable();
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
        Schema::dropIfExists('rips_urgencias');
    }
}
