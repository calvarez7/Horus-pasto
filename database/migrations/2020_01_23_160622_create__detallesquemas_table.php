<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesquemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Detallesquemas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('dosis')->nullable();
            $table->string('unidadMedida')->nullable();
            $table->string('indiceDosis')->nullable();
            $table->string('nivelOrdenamiento')->nullable();
            $table->string('via')->nullable();
            $table->string('dosisFormulada')->nullable();
            $table->string('descripcionDosis')->nullable();
            $table->bigInteger('cantidadAplicaciones')->nullable();
            $table->string('diasAplicacion')->nullable();
            $table->string('frecuencia')->nullable();
            $table->string('frecuenciaDuracion')->nullable();
            $table->string('observaciones')->nullable();
            $table->bigInteger('codesumisId')->unsigned()->index();
            $table->foreign('codesumisId')->references('id')->on('Codesumis');
            $table->bigInteger('esquemaId')->unsigned()->index();
            $table->foreign('esquemaId')->references('id')->on('Esquemas');   
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
        Schema::dropIfExists('Detallesquemas');
    }
}
