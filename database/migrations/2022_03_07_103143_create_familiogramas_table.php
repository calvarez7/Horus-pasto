<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliogramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familiogramas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('vinculos')->nullable();
            $table->string('relacion_afectiva')->nullable();
            $table->string('problemas_salud')->nullable();
            $table->string('cual_familiograma', 2000)->nullable();
            $table->string('observaciones_familiograma', 2000)->nullable();
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
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
        Schema::dropIfExists('familiogramas');
    }
}
