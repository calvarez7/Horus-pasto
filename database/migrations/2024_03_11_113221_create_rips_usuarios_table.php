<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRipsUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rips_usuarios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rips_transaccion_id')->nullable()->index();
            $table->foreign('rips_transaccion_id')->references('id')->on('rips_transacciones');
            $table->string('codMunicipioResidencia')->nullable();
            $table->string('codPaisOrigen')->nullable();
            $table->string('codPaisResidencia')->nullable();
            $table->string('codSexo')->nullable();
            $table->string('codZonaTerritorialResidencia')->nullable();
            $table->string('consecutivo')->nullable();
            $table->string('fechaNacimiento')->nullable();
            $table->string('incapacidad')->nullable();
            $table->string('numDocumentoIdentificacion')->nullable();
            $table->string('tipoDocumentoIdentificacion')->nullable();
            $table->string('tipoUsuario')->nullable();
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
        Schema::dropIfExists('rips_usuarios');
    }
}
