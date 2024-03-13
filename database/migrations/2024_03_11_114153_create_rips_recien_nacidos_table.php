<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsRecienNacidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_recien_nacidos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rips_usuario_id')->nullable()->index();
            $table->foreign('rips_usuario_id')->references('id')->on('rips_usuarios');
            $table->string('codDiagnosticoCausaMuerte')->nullable();
            $table->string('codDiagnosticoPrincipal')->nullable();
            $table->string('codPrestador')->nullable();
            $table->string('codSexoBiologico')->nullable();
            $table->string('condicionDestinoUsuarioEgreso')->nullable();
            $table->string('consecutivo')->nullable();
            $table->string('edadGestacional')->nullable();
            $table->string('fechaEgreso')->nullable();
            $table->string('fechaNacimiento')->nullable();
            $table->string('numConsultasCPrenatal')->nullable();
            $table->string('numDocumentoIdentificacion')->nullable();
            $table->string('peso')->nullable();
            $table->string('tipoDocumentoIdentificacion')->nullable();
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
        Schema::dropIfExists('rips_recien_nacidos');
    }
}
